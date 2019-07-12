<?php 
/**
 * 删除分类页面
 * id==>delete
 */
require_once('../../function.php');
if(empty($_GET['id'])){
    exit('删除失败');
}
// echo '删除页面';
$sql = "DELETE FROM categories WHERE id={$_GET['id']};";
if(!bx_delete_data_to_db($sql)){
    exit('删除失败');
}
header('Location: /admin/categories.php');

