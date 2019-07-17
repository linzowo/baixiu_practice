<!-- 检测用户是否登录开始 -->
<?php
require_once '../function.php';
bx_check_login_status();
?>
<!-- 检测用户是否登录结束 -->

<!-- 获取文章数据开始 -->
<?php
$get_posts_sql = "SELECT * FROM posts;";
$posts = bx_get_db_data($get_posts_sql);
// 转换获取到的数据为想要的数据
foreach ($posts as $key => $value) {
  $posts[$key]['user_name'] = bx_get_db_data("SELECT nickname FROM users WHERE id = '{$value['user_id']}';")[0]['nickname'];
  $posts[$key]['category_name'] = bx_get_db_data("SELECT `name` FROM categories WHERE id = '{$value['category_id']}';")[0]['name'];
  $posts[$key]['created'] = date("Y年-m月-d日\<br\>H:i:s",strtotime($value['created']));
  $posts[$key]['status'] = $value['status'] === 'drafted' ? '草稿' : $value['status'] === 'published' ? '已发布' : '回收站';
  switch ($posts[$key]['status']) {
    case 'drafted':
      $posts[$key]['status'] = '草稿';
      break;
    case 'published':
      $posts[$key]['status'] = '已发布';
      break;
    case 'trashed':
      $posts[$key]['status'] = '已发布';
      break;
  };
}
?>
<!-- 获取文章数据结束 -->

<!DOCTYPE html>
<html lang="zh-CN">

<head>
  <meta charset="utf-8">
  <title>Posts &laquo; Admin</title>
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
        <h1>所有文章</h1>
        <a href="post-add.html" class="btn btn-primary btn-xs">写文章</a>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <div class="page-action">
        <!-- show when multiple checked -->
        <a class="btn btn-danger btn-sm" href="javascript:;" style="display: none">批量删除</a>
        <form class="form-inline">
          <select name="" class="form-control input-sm">
            <option value="">所有分类</option>
            <option value="">未分类</option>
          </select>
          <select name="" class="form-control input-sm">
            <option value="">所有状态</option>
            <option value="">草稿</option>
            <option value="">已发布</option>
          </select>
          <button class="btn btn-default btn-sm">筛选</button>
        </form>
        <ul class="pagination pagination-sm pull-right">
          <li><a href="#">上一页</a></li>
          <li><a href="#">1</a></li>
          <li><a href="#">2</a></li>
          <li><a href="#">3</a></li>
          <li><a href="#">下一页</a></li>
        </ul>
      </div>
      <table class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <th class="text-center" width="40"><input type="checkbox"></th>
            <th>标题</th>
            <th>作者</th>
            <th>分类</th>
            <th class="text-center">发表时间</th>
            <th class="text-center">状态</th>
            <th class="text-center" width="100">操作</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($posts as $value) : ?>
            <tr>
              <td class="text-center"><input type="checkbox"></td>
              <td><?php echo $value['title']; ?></td>
              <td><?php echo $value['user_name']; ?></td>
              <td><?php echo $value['category_name']; ?></td>
              <td class="text-center"><?php echo $value['created']; ?></td>
              <td class="text-center"><?php echo $value['status']; ?></td>
              <td class="text-center">
                <a href="javascript:;" class="btn btn-default btn-xs">编辑</a>
                <a href="javascript:;" class="btn btn-danger btn-xs">删除</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <?php $current_page = 'posts'; ?>
  <?php include 'inc/slider.php'; ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>
    NProgress.done()
  </script>
</body>

</html>