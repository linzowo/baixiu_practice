<!-- 检测用户是否登录开始 -->
<?php
require_once '../function.php';
bx_check_login_status();
?>
<!-- 检测用户是否登录结束 -->

<!-- 获取文章数据开始 -->
<!-- 数据转换函数 -->
<?php 
function bx_convert_user_name($user_id){
  return bx_get_db_data("SELECT nickname FROM users WHERE id = '{$user_id}';")[0]['nickname'];
}

function bx_convert_category_name($category_id){
  return bx_get_db_data("SELECT `name` FROM categories WHERE id = '{$category_id}';")[0]['name'];
}

function bx_convert_created($created){
  return date("Y年-m月-d日",strtotime($created)).'<br/>'.date("H:i:s",strtotime($created));
}

function bx_convert_status(){
  return array(
    'drafted' => '草稿',
    'published' => '已发布',
    'trashed' => '回收站'
  );
}
?>
<?php
$get_posts_sql = "SELECT * FROM posts;";
$posts = bx_get_db_data($get_posts_sql);
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
              <td><?php echo bx_convert_user_name($value['user_id']); ?></td>
              <td><?php echo bx_convert_category_name($value['category_id']); ?></td>
              <td class="text-center"><?php echo bx_convert_created($value['created']); ?></td>
              <td class="text-center"><?php echo bx_convert_status()[$value['status']]; ?></td>
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