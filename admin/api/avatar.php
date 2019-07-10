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
        $query = mysqli_query($conn, "SELECT * FROM users WHERE email = '{$user_email}' LIMIT 1;");
        if (!$query) {
            $GLOBALS['error_msg'] = '获取数据失败，请重试！';
            return;
        }
        // 获取用户信息
        $user = mysqli_fetch_assoc($query);
        // 输出用户信息
        echo json_encode($user);
        /* 
         array(8) {
            ["id"]=>
            string(1) "1"
            ["slug"]=>
            string(5) "admin"
            ["email"]=>
            string(16) "admin@linzowo.me"
            ["password"]=>
            string(6) "111111"
            ["nickname"]=>
            string(9) "管理员"
            ["avatar"]=>
            string(26) "/static/uploads/avatar.jpg"
            ["bio"]=>
            NULL
            ["status"]=>
            string(9) "activated"
            }
        */
        exit;
    }
    ?>