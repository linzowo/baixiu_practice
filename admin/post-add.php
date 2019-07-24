<!-- 引入依赖文件 -->
<!-- 检查用户是否登录 -->
<?php
require_once '../function.php';
bx_check_login_status();
?>
<!-- ===================================== -->
<!-- 动态获取数据库数据渲染页面 -->
<?php
// 分类信息
$categories_arr = bx_get_db_data("SELECT * FROM categories;");
?>
<!-- ===================================== -->
<!-- 声明数据处理函数 -->
<?php
/* array(6) {
  ["title"]=&gt;
  string(0) ""
  ["content"]=&gt;
  string(0) ""
  ["slug"]=&gt;
  string(0) ""
  ["category"]=&gt;
  string(1) "1"
  ["created"]=&gt;
  string(0) ""
  ["status"]=&gt;
  string(7) "drafted"
}
 */
function post_add()
{
  global $msg;
  $have_data = empty($_GET['title']) || empty($_GET['content']) || empty($_GET['slug']) || empty($_GET['category']) || empty($_GET['created']) || empty($_GET['status']);
  if ($have_data) {
    $msg = '请填写完整表单';
    return;
  }
}
?>
<!-- ===================================== -->
<!-- 调用数据处理函数 -->
<?php
post_add();
?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
  <meta charset="utf-8">
  <title>Add new post &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="/static/assets/vendors/nprogress/nprogress.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
  <link rel="stylesheet" href="/static/assets/vendors/simplemde/simplemde.min.css">
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
        <h1>写文章</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <?php if (!empty($msg)) : ?>
        <div class="alert alert-danger">
          <strong>错误！</strong>发生XXX错误
        </div>
      <?php endif; ?>
      <form class="row" action="<?php $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
        <div class="col-md-9">
          <div class="form-group">
            <label for="title">标题</label>
            <input id="title" class="form-control input-lg" name="title" type="text" placeholder="文章标题">
          </div>
          <div class="form-group">
            <label for="content">标题</label>
            <textarea id="content" class="form-control input-lg" name="content" cols="30" rows="10" placeholder="内容"></textarea>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="slug">别名</label>
            <input id="slug" class="form-control" name="slug" type="text" placeholder="slug">
            <p class="help-block">https://zce.me/post/<strong>slug</strong></p>
          </div>
          <div class="form-group">
            <label for="feature">特色图像</label>
            <!-- show when image chose -->
            <img class="help-block thumbnail" style="display: none">
            <input id="feature" class="form-control" name="feature" type="file">
          </div>
          <div class="form-group">
            <label for="category">所属分类</label>
            <select id="category" class="form-control" name="category">
              <?php foreach ($categories_arr as $key => $value) : ?>
                <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label for="created">发布时间</label>
            <input id="created" class="form-control" name="created" type="datetime-local">
          </div>
          <div class="form-group">
            <label for="status">状态</label>
            <select id="status" class="form-control" name="status">
              <option value="drafted">草稿</option>
              <option value="published">已发布</option>
            </select>
          </div>
          <div class="form-group">
            <button class="btn btn-primary" type="submit">保存</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <?php $current_page = 'post-add'; ?>
  <?php include 'inc/slider.php'; ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script src="/static/assets/vendors/simplemde/simplemde.min.js"></script>
  <script>
    var simplemde = new SimpleMDE({
      element: $('#content')[0]
    });
  </script>
  <script>
    NProgress.done()
  </script>
</body>

</html>