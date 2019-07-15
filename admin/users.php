<!-- 检测用户登录状态开始 -->
<?php
  require_once '../function.php';
  bx_check_login_status();
  ?>
<!-- 检测用户登录状态开始 -->

<!-- 新增用户开始 -->
<?php
  function add_user()
  {
    global $msg, $email, $slug, $nickname, $password, $success;
    // 表单为空
    if (empty($_POST['email']) || empty($_POST['slug']) || empty($_POST['nickname']) || empty($_POST['password'])) {
      $msg = '请填写完整表单';
      return;
    }

    // 接收用户输入
    $email = trim($_POST['email']);
    $slug = trim($_POST['slug']);
    $nickname = trim($_POST['nickname']);
    $password = trim($_POST['password']);
    // 校验邮箱
    if (!preg_match("/^[0-9a-zA-Z]+[@][0-9a-zA-Z]+\.[a-zA-Z]+$/", $email)) {
      $msg = '邮箱格式错误，请重新输入';
      return;
    }
    // 校验slug
    if (strlen($slug) > 12 || preg_match("/[\x7f-\xff]|\W/", $slug)) {
      $msg = '别名不符合规范';
      return;
    }
    // slug是否唯一
    $check_slug_sql = "SELECT slug FROM users WHERE slug = '{$slug}';";
    $check_slug = bx_get_db_data($check_slug_sql);
    if ($check_slug) {
      $msg = '别名已经存在，请重新输入';
      return;
    }
    // 检查用户名（昵称）
    if (strlen($nickname) > 40) {
      $msg = '昵称过长，请重新输入';
      return;
    }
    // 检查密码
    if (strlen($password) > 12 || preg_match("/[\x7f-\xff]/", $password)) {
      $msg = '密码不符合规范，请重新输入';
      return;
    }
    // 存入数据库
    $save_new_user_sql = "INSERT INTO users (slug,email,`password`,nickname,avatar,`status`) VALUES ('{$slug}','{$email}','{$password}','{$nickname}','/static/uploads/avatar.jpg','activated');";
    $affected_rows = bx_add_data_to_db($save_new_user_sql);
    if ($affected_rows <= 0) {
      $msg = '添加失败，请重试。';
      return;
    }
    $msg = $affected_rows <= 0 ? '添加失败，请重试。' : '添加成功';
    $success = $affected_rows > 0;
  }
?>
<!-- 新增用户结束 -->

<!-- 判断用户操作开始 -->
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // 新增用户
  add_user();
}
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  // 删除结果
  if(isset($_GET['del_success'])){
    $success = trim($_GET['del_success']) === 'true';
    $msg = $success?'删除成功':'删除失败';
  }
}
?>
<!-- 判断用户操作结束 -->

<!-- // 获取用户信息面开始 -->
<?php
$get_users_data_sql = "SELECT * FROM users;";
$get_users_data = bx_get_db_data($get_users_data_sql);
?>
<!-- // 获取用户信息面结束 -->
<!DOCTYPE html>
<html lang="zh-CN">

<head>
  <meta charset="utf-8">
  <title>Users &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="/static/assets/vendors/nprogress/nprogress.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
  <script src="/static/assets/vendors/nprogress/nprogress.js"></script>
</head>

<body>
  <script>
    NProgress.start()
  </script>

  <div class="main">
    <?php include 'inc/navbar.php'; ?>
    <div class="container-fluid">
      <div class="page-title">
        <h1>用户</h1>
      </div>
      <!-- 输出提示信息结束 -->
      <?php if (!empty($msg)) : ?>
        <?php if ($success) : ?>
          <div class="alert alert-success">
            <strong>成功！</strong><?php echo $msg; ?>
          </div>
        <?php else : ?>
          <!-- 有错误信息时展示 -->
          <div class="alert alert-danger">
            <strong>错误！</strong><?php echo $msg; ?>
          </div>
        <?php endif; ?>
      <?php endif; ?>
      <!-- 输出提示信息结束 -->

      <div class="row">
        <!-- 新增用户开始 -->
        <div class="col-md-4">
          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" novalidate>
            <h2>添加新用户</h2>
            <div class="form-group">
              <label for="email">邮箱</label>
              <input id="email" class="form-control" name="email" type="email" placeholder="邮箱" value="<?php echo empty($email) || $success ? '' : $email; ?>">
            </div>
            <div class="form-group">
              <label for="slug">别名</label>
              <input id="slug" class="form-control" name="slug" type="text" placeholder="slug" value="<?php echo empty($slug) || $success ? '' : $slug; ?>">
              <p class="help-block">https://zce.me/author/<strong>slug</strong></p>
            </div>
            <div class="form-group">
              <label for="nickname">昵称</label>
              <input id="nickname" class="form-control" name="nickname" type="text" placeholder="昵称" value="<?php echo empty($nickname) || $success ? '' : $nickname; ?>">
            </div>
            <div class="form-group">
              <label for="password">密码</label>
              <input id="password" class="form-control" name="password" type="text" placeholder="密码" value="<?php echo empty($password) || $success ? '' : $password; ?>">
            </div>
            <div class="form-group">
              <button class="btn btn-primary" type="submit">添加</button>
            </div>
          </form>
        </div>
        <!-- 新增用户结束 -->

        <!-- 用户信息展示开始 -->
        <div class="col-md-8">
          <div class="page-action">
            <!-- show when multiple checked -->
            <a class="btn btn-danger btn-sm" href="javascript:;" style="display: none">批量删除</a>
          </div>
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th class="text-center" width="40"><input type="checkbox"></th>
                <th class="text-center" width="80">头像</th>
                <th>邮箱</th>
                <th>别名</th>
                <th>昵称</th>
                <th>状态</th>
                <th class="text-center" width="100">操作</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($get_users_data as $value) : ?>
                <tr>
                  <td class="text-center"><input type="checkbox"></td>
                  <td class="text-center"><img class="avatar" src="<?php echo $value['avatar']; ?>"></td>
                  <td><?php echo $value['email']; ?></td>
                  <td><?php echo $value['slug']; ?></td>
                  <td><?php echo $value['nickname']; ?></td>
                  <td><?php echo $value['status'] === 'activated' ? '激活' : '未激活'; ?></td>
                  <td class="text-center">
                    <a href="post-add.php" class="btn btn-default btn-xs">编辑</a>
                    <a href="/admin/api/users-delete.php?del_id=<?php echo $value['id']; ?>" class="btn btn-danger btn-xs">删除</a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <!-- 用户信息展示结束 -->

      </div>
    </div>
  </div>

  <?php $current_page = 'users'; ?>
  <?php include 'inc/slider.php'; ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>
    NProgress.done()
  </script>
</body>

</html>