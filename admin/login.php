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
include_once '../function.php';
?>
<?php
// 启动session
session_start();
// 引入依赖的配置文件
include_once '../config.php';

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
  // 建立查询语句
  $user_email = $_POST['email'];
  $query = "SELECT * FROM users WHERE email = '{$user_email}' LIMIT 1;";
  // 连接数据库
  $current_user = bx_get_db_data($query);
  // 判断邮箱是否在数据库中
  if (!$current_user) {
    // 用户名不存在
    $GLOBALS['error_msg'] = '邮箱与密码不匹配！';
    return;
  }
  // 判断密码是否匹配数据库信息
  if ($current_user['password'] !== $_POST['password']) {
    // 密码不正确
    $GLOBALS['error_msg'] = '邮箱与密码不匹配！';
    return;
  }
  // 持久化

  // 响应
  // 执行到此说明用户输入的信息正确，跳转至主页
  $_SESSION['user'] = $current_user;
  $locationUrl = "Location: " . (empty($_SESSION['source']) ? '/admin/' : $_SESSION['source']);
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
    $(function(){
      // 通过js在用户输入完邮箱后就获取用户在数据库中存放的头像
      // 获取元素
      var email = $('#email');
    // 为邮箱输入框注册失去焦点事件
    email.blur(function() {
        // 不合法就不发起ajax请求
      if(!(/^[0-9a-zA-Z._-]+[@][0-9a-zA-Z._-]+[.][a-zA-Z]+$/.test(email.val()))) return;
        
      // 合法就发起ajax请求
      $.get('/admin/api/avatar.php',{'email': email.val()},function(res){// 请求成功且返回数据正确
        if(res === ' '){// 如果数据为空就说明没有这个用户
          $('.avatar')[0].src = "/static/assets/img/default.png";
          return;
        }
        // 输出用户头像到页面
        var avatar = $('.avatar');
        avatar.fadeOut(function(){
          $(this).on('load',function(){
            // 等图片加载完成后再使其进入页面
            $(this).fadeIn();
          }).attr('src',res);
        })
      });
    });
    })
  </script>
</body>

</html>