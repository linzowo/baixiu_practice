<?php 
// var_dump($_FILES['avatar']);
/* 
array(5) {
  ["name"]=>
  string(10) "hots_4.jpg"
  ["type"]=>
  string(10) "image/jpeg"
  ["tmp_name"]=>
  string(27) "C:\Windows\Temp\php220D.tmp"
  ["error"]=>
  int(0)
  ["size"]=>
  int(12509)
*/
// 接收==效验==存储图片
// ======================================
// 图片不存在
if(empty($_FILES['avatar'])){
    exit('头像修改失败');
}
// 图片类型
$allowed_img_type = array("image/jpeg","image/png","image/gif");
if(!in_array($_FILES['avatar']['type'],$allowed_img_type)){
    exit('头像格式不支持');
}
// 图片大小<10M
if($_FILES['avatar']['size'] > 10 * 1024 * 1024){
    exit('图片过大');
}
// 存储图片
$target = '../../static/uploads/'.uniqid().'.'.pathinfo($_FILES['avatar']['name'],PATHINFO_EXTENSION);
if(!move_uploaded_file($_FILES['avatar']['tmp_name'],$target)){
    exit('上传头像失败');
}
// 返回已上传头像的存储地址
echo substr($target,5);