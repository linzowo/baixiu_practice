<?php

// var_dump($_POST);
// var_dump($_FILES);
/* 
array(5) {
  ["id"]=>
  string(1) "1"
  ["email"]=>
  string(16) "admin@linzowo.me"
  ["slug"]=>
  string(5) "admin"
  ["nickname"]=>
  string(9) "管理员"
  ["bio"]=>
  string(0) ""
}
array(1) {
  ["avatar"]=>
  array(5) {
    ["name"]=>
    string(12) "widget_3.jpg"
    ["type"]=>
    string(10) "image/jpeg"
    ["tmp_name"]=>
    string(27) "C:\Windows\Temp\php1FB3.tmp"
    ["error"]=>
    int(0)
    ["size"]=>
    int(7107)
  }
}
*/
// 引入依赖函数
require_once('../../function.php');

// 是否有id
if (empty($_POST['id'])) {
  exit('缺少必要参数。');
}

// 初始化变量
$msg = array();
$edit_sql = array();

// 接收数据
$id = $_POST['id'];
$slug = $_POST['slug'];
$nickname = $_POST['nickname'];
$bio = addslashes($_POST['bio']);

// 校验用户数据
// slug为空就不修改这一项
if (!empty($_POST['slug'])) {
  // slug必须唯一
  $has_slug = bx_get_db_data("SELECT slug FROM users WHERE slug = '{$slug}' AND id != '{$id}';");
  if ($has_slug) {
    $msg[] = '此slug已经存在';
  } else {
    $edit_sql[] = "slug = '{$slug}'";
  }
}

// 是否存在nickname
if ((!empty($_POST['nickname']))) {
  if (!preg_match("/^[^\s]{2,16}$/", $nickname)) {
    $msg[] = '昵称不符合规范';
  } else {
    $edit_sql[] = "nickname = '{$nickname}'";
  }
}

// 是否存在bio
$edit_sql[] = "bio = '{$bio}'";

// 校验图片
if (!empty($_FILES['avatar']) && empty($_FILES['avatar']['error'])) {
  $avatar = bx_check_upload_img('avatar', '../../static/uploads');
  if (!$avatar) {
    $msg[] = '图片上传失败';
  } else {
    $edit_sql[] = "avatar = '{$avatar}'";
  }
}

// 如果存在错误信息说明数据有问题直接返回错误信息
if (!empty($msg)) {
  $msg = join(',', $msg);
  exit($msg);
}

// 将$edit_sql转为字符串
$edit_sql = join(',', $edit_sql);

// 存储用户数据
$result = bx_edit_data_to_db("UPDATE users SET {$edit_sql} WHERE id = {$id};");

// 如果存储成功更新user信息
if ($result) {
  session_start();
  $_SESSION['user'] = bx_get_db_data("SELECT * FROM users WHERE id = '{$id}' LIMIT 1;")[0];
  echo 'success';
}else{
  exit("你可能什么都没有修改");
}
