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
// 防止sql注入
$inputArr = explode(',',$_GET['id']);
foreach ($inputArr as $key => $value) {
    $inputArr[$key] = (int)$value;
}
$idStr = implode(',',$inputArr);
// 删除数据库数据==》可批量和单条删除
$sql = "DELETE FROM categories WHERE id IN ({$idStr});";
var_dump($sql);
if(!bx_delete_data_to_db($sql)){
    exit('删除失败');
}
header('Location: /admin/categories.php');

