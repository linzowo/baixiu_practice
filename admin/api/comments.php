<?php 
// 获取用户评论信息
// 引入封装的函数
require_once '../../function.php';
$page = empty($_GET['p'])?1:$_GET['p'];
$size = empty($_GET['s'])?1:$_GET['s'];;
$offset = ($page - 1)*$size;
// 获取数据库数据
$comments = bx_get_db_data("SELECT 
comments.* ,
posts.title as post_title
FROM comments 
INNER JOIN posts on comments.post_id = posts.id 
ORDER BY comments.created DESC 
LIMIT {$offset},{$size};");

// 评论总条数
$total_comments = bx_get_db_data("SELECT 
count(1) as num
FROM comments 
INNER JOIN posts on comments.post_id = posts.id;")[0]['num'];
$total_page = ceil($total_comments / $size);
// 将返回数据包装成一个对象
$result = array(
    'total_page' => $total_page,
    'comments' => $comments
);

// 声明返回的是json格式的数据
header('Content-Type: application/json');

// 输出数据
echo json_encode($result);