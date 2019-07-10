<?php
// <!--
// * @Author: linzwo
// * @Date: 2019-07-09 22:51:24
// * @LastEditors: linzwo
// * @LastEditTime: 2019-07-10 10:31:31
// * @Description: file content
// -->
// 引入依赖的配置文件
include_once '../config.php';
?>
<?php
// 启动session
session_start();

// 如果已经登录成功就不再显示登录页
if (!empty($_SESSION['user'])) {
  header('Location: /admin/');
  exit;
}

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
  $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
  if (!$conn) { // 数据库连接失败
    exit("<h1>连接数据库失败，请重试！</h1>");
  }
  // 建立查询
  $user_email = $_POST['email'];
  $query = mysqli_query($conn, "SELECT * FROM users WHERE email = '{$user_email}' LIMIT 1;");
  if (!$query) {
    $GLOBALS['error_msg'] = '获取数据失败，请重试！';
    return;
  }
  // 获取用户信息
  $user = mysqli_fetch_assoc($query);
  // 判断邮箱是否在数据库中
  if (!$user) {
    // 用户名不存在
    $GLOBALS['error_msg'] = '邮箱与密码不匹配！';
    return;
  }
  // 判断密码是否匹配数据库信息
  if ($user['password'] !== $_POST['password']) {
    // 密码不正确
    $GLOBALS['error_msg'] = '邮箱与密码不匹配！';
    return;
  }
  // 持久化

  // 响应
  // 执行到此说明用户输入的信息正确，跳转至主页
  $_SESSION['user'] = $user;
  echo $_SESSION['source'];
  $locationUrl = "Location: " . (empty($_SESSION['source']) ? '/admin/' : $_SESSION['source']);
  echo $locationUrl;
  header($locationUrl);
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
    <form class="login-wrap<?php echo empty($error_msg) ? '' : ' shake animated'; ?>" action="<?php echo $_SERVER['PHP_SELF']; ?>" method='post' novalidate autocomplete='off'>
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
        <input id="email" name="email" type="email" class="form-control" placeholder="邮箱" autofocus value="<?php echo empty($_SESSION['user']) ? '' : $_SESSION['user']['email']; ?>">
      </div>
      <div class="form-group">
        <label for="password" class="sr-only">密码</label>
        <input id="password" name="password" type="password" class="form-control" placeholder="密码">
      </div>
      <button class="btn btn-primary btn-block" href="index.php">登 录</button>
    </form>
  </div>

  <!-- 引入jquery -->
  <script src="/static/assets/vendors/jquery/jquery.min.js"></script>
  <script>
    // 通过js在用户输入完邮箱后就获取用户在数据库中存放的头像
    // 为邮箱输入框注册失去焦点事件
    $('#email').blur(function() {
      // 判断是否存在报错的span
      if($('#email').siblings('span')){
        // 将其删除
        $('#email').siblings('span').remove();
      }
      // 判断用户输入的邮箱是否合法
      if(!(/[0-9a-zA-Z._-]+[@][0-9a-zA-Z._-]+[.][a-zA-Z]/.test($('#email').val()))){
        // 不合法就提示用户
        $('#email').parent('div').append($('<span>邮箱格式不正确请重新输入</span>'));
        return;
      }

      // 合法就发起ajax请求
      $.ajax({
        url: '/admin/api/avatar.php', // 请求处理地址
        type: 'get', //请求方法
        dataType: 'json', // 声明返回的文件类型
        data: {
          email: $(this).val()
        }, // 传递过去的参数==》获取时根据请求方法获取
        success: function(data) { // 请求成功且返回数据正确
          if(!data){// 如果没有拿到数据就说明没有这个用户
            $('#email').parent('div').append($('<span>该用户不存在，请注册！</span>'));
            $('.avatar')[0].src = "/static/assets/img/default.png";
            return;
          }
          // 输出用户头像到页面
          $('.avatar')[0].src = data['avatar'];
        },
        error: function() {
          console.log('请求错误');
        }
      })
    });
  </script>
</body>

</html>