<?php
// 引入依赖的配置文件
include_once '../../config.php';
?>
 <?php
    // <!-- 获取用户头像的php代码 -->
    if (($_SERVER['REQUEST_METHOD'] === 'GET') && !empty($_REQUEST)) {
        // var_dump($_SERVER['REQUEST_METHOD']);
        // var_dump($_GET);
        // 判断用户输入的邮箱是否存在于数据库中
        // 连接数据库
        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if (!$conn) { // 数据库连接失败
            exit("<h1>连接数据库失败，请重试！</h1>");
        }
        // 建立查询
        $user_email = $_GET['email'];
        $query = mysqli_query($conn, "SELECT avatar FROM users WHERE email = '{$user_email}' LIMIT 1;");
        if (!$query) {
            exit("建立查询失败");
        }
        // 获取用户信息
        $user_avatar = mysqli_fetch_assoc($query);

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