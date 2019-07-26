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
  global $msg, $categories_arr, $slug, $title, $feature, $created, $content, $status, $user_id, $category_id;
  // 存储用户数据
  $slug = $_POST['slug'];
  $title = addslashes($_POST['title']);
  $feature = ''; // 现在不处理，后面再接收一次
  $created = $_POST['created'];
  $content = addslashes($_POST['content']);
  $status = $_POST['status'];
  $user_id = $_SESSION['user']['id'];
  $category_id = $_POST['category'];
  // 校验用户是否填写完整表单
  $have_data = empty($_POST['title']) || empty($_POST['content']) || empty($_POST['slug']) || empty($_POST['category']) || empty($_POST['created']) || empty($_POST['status']);
  if ($have_data) {
    $msg = '请填写完整表单';
    return;
  }

  // 校验文章标题===字数不能超过60==也就是20个字
  if (strlen($_POST['title']) > 60) {
    $msg = '标题过长';
    return;
  }
  // slug===》必须是唯一的
  $have_slug = bx_get_db_data("SELECT slug FROM posts WHERE slug = '{$_POST['slug']}';")[0];
  if ($have_slug) {
    $msg = 'slug已经存在，请重新输入。';
    return;
  }
  // 分类==必须包含在已有的分类中==防止有人传递恶意数据
  foreach ($categories_arr as $key => $value) {
    if (in_array($_POST['category'], $value)) {
      $have_category = true;
      break;
    }
  }
  if (empty($have_category)) {
    $msg = '请使用有效参数。';
    return;
  }
  // 状态
  $status_arr = ['drafted', 'published'];
  if (!in_array($_POST['status'], $status_arr)) {
    $msg = '请使用有效参数。';
    return;
  }
  // 图片==类型==大小
  var_dump($_FILES);
  if (empty($_FILES['feature']['error'])) {
    $allowed_img = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($_FILES['feature']['type'], $allowed_img)) {
      $msg = '图片格式不支持。';
      return;
    }
    if ($_FILES['feature']['size'] > 10 * 1024 * 1024) { // 图片必须小于10m
      $msg = '图片过大。';
      return;
    }
    // 存储图片到指定位置
    $target_folder = '../static/uploads/' . time() . '.' . substr($_FILES['feature']['type'], 6);
    $temp_file = $_FILES['feature']['tmp_name'];
    if (!move_uploaded_file($temp_file, $target_folder)) {
      $msg = '上传图片失败请重试。';
      return;
    }
    $img_file = substr($target_folder, 2);
  }
  $feature = isset($img_file) ? $img_file : '';
  // 存入数据库
  $add_sql = sprintf(
    "insert into posts values (null, '%s', '%s', '%s', '%s', '%s', 0, 0, '%s', %d, %d);",
    $slug,
    $title,
    $feature,
    $created,
    $content,
    $status,
    $user_id,
    $category_id
  );
  echo $add_sql;
  var_dump($add_sql);
  $affected_rows = bx_add_data_to_db($add_sql);
  if (!$affected_rows) {
    $msg = '存入数据库失败';
    return;
  }
  // TODO: 展示新增数据
  header('Location: /admin/posts.php');
}
?>
<!-- ===================================== -->
<!-- 调用数据处理函数 -->
<?php
// 提前声明防止报错===未定义
$status = '';
$category_id = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // var_dump($_POST);
  post_add();
}
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
          <strong>错误！</strong><?php echo $msg; ?>
        </div>
      <?php endif; ?>
      <form class="row" action="<?php $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
        <div class="col-md-9">
          <div class="form-group">
            <label for="title">标题</label>
            <input id="title" class="form-control input-lg" name="title" type="text" placeholder="文章标题" <?php echo empty($title) ? '' : " value='{$title}'"; ?>>
          </div>
          <div class="form-group">
            <label for="content">内容</label>
            <textarea id="content" class="form-control input-lg" name="content" cols="30" rows="10" placeholder="内容"><?php echo empty($content) ? '' : stripslashes($content); ?></textarea>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="slug">别名</label>
            <input id="slug" class="form-control" name="slug" type="text" placeholder="slug" <?php echo empty($slug) ? '' : " value='{$slug}'"; ?>>
            <p class="help-block">https://zce.me/post/<strong>slug</strong></p>
          </div>
          <div class="form-group">
            <label for="feature">特色图像</label>
            <!-- show when image chose -->
            <div class="help-block img-thumbnail"></div>
            <!-- <img class="help-block thumbnail" style="display: none"> -->
            <!-- <input id="feature" class="form-control" name="feature" type="file" accept="image/*"> -->
          </div>
          <div class="form-group">
            <label for="category">所属分类</label>
            <select id="category" class="form-control" name="category">
              <?php foreach ($categories_arr as $key => $value) : ?>
                <option value="<?php echo $value['id']; ?>" <?php echo ($category_id === $value['id']) ? ' selected' : ''; ?>><?php echo $value['name']; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label for="created">发布时间</label>
            <input id="created" class="form-control" name="created" type="datetime-local" <?php echo empty($created) ? '' : " value='{$created}'"; ?>>
          </div>
          <div class="form-group">
            <label for="status">状态</label>
            <select id="status" class="form-control" name="status">
              <option value="drafted" <?php echo $status === 'drafted' ? ' selected' : ''; ?>>草稿</option>
              <option value="published" <?php echo $status === 'published' ? ' selected' : ''; ?>>已发布</option>
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
  <script src="/static/assets/vendors/simplemde/simplemde.min.js"></script> <!-- 引入富文本插件库 -->
  <script src="/static/assets/vendors/upload/upload.js"></script><!-- 引入图片上传预览库 -->
  <script>
    // 创建一个富文本对象
    var simplemde = new SimpleMDE({
      element: $('#content')[0]
    });
    var dragImgUpload = new DragImgUpload(".img-thumbnail", {
      callback: function(fileInput) {
        $(fileInput).appendTo($('.img-thumbnail'));// 将生成的input对象添加到表单中
      }
    })
  </script>
  <script>
    NProgress.done()
  </script>
</body>

</html>