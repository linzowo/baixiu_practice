<?php 
/**
 * 删除分类页面
 * id==>delete
 */
// 引入依赖函数
require_once('../../function.php');
if(empty($_GET['id'])){
    exit('删除失败');
}

// 获取用户的操作需求
$status = 'trashed';
if(!empty($_GET['status'])){
    $status = $_GET['status'];
}

// 防止sql注入
$inputArr = explode(',',$_GET['id']);
foreach ($inputArr as $key => $value) {
    $inputArr[$key] = (int)$value;
}
$idStr = implode(',',$inputArr);

// 编辑数据==可单条==可批量
$sql = "UPDATE comments SET `status` = '{$status}' WHERE id IN ({$idStr});";

$result = bx_edit_data_to_db($sql);
header('Content-Type: application/json');
echo json_encode($result > 0);

