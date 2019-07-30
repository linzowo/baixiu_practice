<?php 
// 获取用户评论信息
// 引入封装的函数
require_once '../../function.php';

// 获取数据库数据
$comments = bx_get_db_data("SELECT * FROM comments;");

// 声明返回的是json格式的数据
header('Content-Type: application/json');

// 输出数据
echo json_encode($comments);