<?php
// 引入公共函数
require_once '../../function.php';
?>
 <?php
    // <!-- 获取用户头像的php代码 -->
    if (($_SERVER['REQUEST_METHOD'] === 'GET') && !empty($_REQUEST)) {
        // 判断用户输入的邮箱是否存在于数据库中
        // 获取用户输入信息
        $user_email = $_GET['email'];
        // 建立查询语句
        $query = "SELECT avatar FROM users WHERE email = '{$user_email}' LIMIT 1;";
        // 获取用户信息
        $user_avatar = bx_get_db_data($query)[0];
        if(!$user_avatar){ // 查询结果为空说明没有这个用户
            exit;
        }
        // 输出用户信息
        echo $user_avatar['avatar'];
        /* 
         array(1) { 
            ["avatar"]=>
            string(26) "/static/uploads/avatar.jpg"
            }
        */
        exit;
    }
    ?>