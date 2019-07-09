<?php
// 引入依赖的配置文件
include_once '../config.php';

// 启动session
session_start();

// 登录处理函数
function login()
{
  // 接收并效验
  // 效验邮箱
  // 信息是否输入完整
  if (empty($_POST['email'])) {
    $GLOBALS['error_msg'] = '请输入邮箱';
    return;
  }
  if (empty($_POST['password'])) {
    $GLOBALS['error_msg'] = '请输入密码';
    return;
  }
  // 判断用户输入的邮箱是否存在于数据库中
  // 连接数据库
  $conn = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);
  if(!$conn){// 数据库连接失败
    exit("<h1>连接数据库失败，请重试！</h1>");
  }
  // 建立查询
  $user_email = $_POST['email'];
  $query = mysqli_query($conn,"SELECT * FROM users WHERE email = '{$user_email}' LIMIT 1;");
  if(!$query){
    $GLOBALS['error_msg'] = '获取数据失败，请重试！';
    return;
  }
  // 获取用户信息
  $user = mysqli_fetch_assoc($query);
  // 判断邮箱是否在数据库中
  if(!$user){
    // 用户名不存在
    $GLOBALS['error_msg'] = '邮箱与密码不匹配！';
    return;
  }
  // 判断密码是否匹配数据库信息
  if($user['password'] !== $_POST['password']){
    // 密码不正确
    $GLOBALS['error_msg'] = '邮箱与密码不匹配！';
    return;
  }
  // 持久化

  // 响应
  // 执行到此说明用户输入的信息正确，跳转至主页
  $_SESSION['user'] = $user;
  header('Location: /admin/');
}

if (!empty($_POST)) {
  login();
}
?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
  <meta charset="utf-8">
  <title>Sign in &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
  <link rel="stylesheet" href="/static/assets/vendors/animate/animate.css">
</head>

<body>
  <div class="login">
    <form class="login-wrap<?php echo empty($error_msg)?'':' shake animated'; ?>" action="<?php echo $_SERVER['PHP_SELF']; ?>" method='post' novalidate autocomplete='off'>
      <img class="avatar" src="/static/assets/img/default.png">
      <!-- 有错误信息时展示 -->
      <?php if (isset($error_msg)) :; ?>
        <div class="alert alert-danger">
          <strong>错误！</strong> 用户名或密码错误！
          <span><?php echo $error_msg; ?></span>
        </div>
      <?php endif; ?>
      <div class="form-group">
        <label for="email" class="sr-only">邮箱</label>
        <input id="email" name="email" type="email" class="form-control" placeholder="邮箱" autofocus value = "<?php echo empty($_SESSION['user'])?'':$_SESSION['user']['email']; ?>">
      </div>
      <div class="form-group">
        <label for="password" class="sr-only">密码</label>
        <input id="password" name="password" type="password" class="form-control" placeholder="密码">
      </div>
      <button class="btn btn-primary btn-block" href="index.php">登 录</button>
    </form>
  </div>
</body>

</html>