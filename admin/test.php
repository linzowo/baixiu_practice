<?php 
require_once '../function.php';
// $numstr = '你好';
// echo (int)$numstr;


$edit_sql = "SELECT * FROM categories WHERE `id`= '1';";
$edit_res = bx_get_db_data($edit_sql);
var_dump($edit_res);

if(!$edit_res) {
    echo '查询失败';
};