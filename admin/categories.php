<?php
require_once '../function.php';
bx_check_login_status();
?>
<?php
// 用户新增数据
// 判断是否有用户传入了数据
/**
 * 处理用户新增分类的函数
 */
function add_categories()
{
  // 校验
  if (empty($_POST['name'])) {
    $GLOBALS['error_msg'] = '请输入分类名称';
    return;
  }
  if (empty($_POST['slug'])) {
    $GLOBALS['error_msg'] = '请输入slug';
    return;
  }
  // 因为slug标识是唯一的==》需要判重
  // 建立查询语句
  $check_slug_sql = "SELECT slug FROM categories WHERE slug = '{$_POST['slug']}';";
  $check_slug_query = bx_get_db_data($check_slug_sql);
  if($check_slug_query){
    $GLOBALS['error_msg'] = '此slug以存在，请重新输入';
    return;
  }
  // 持久化
  // 存入数据库
  // 建立查询语句
  $sql = "INSERT INTO categories (`name`,slug) VALUES ('{$_POST['name']}','{$_POST['slug']}');";
  if(!bx_add_data_to_db($sql)){
    $GLOBALS['error_msg'] = '添加到数据库失败';
    return;
  };
  // 响应
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  /* 
  $_POST===>array(2) { ["name"]=> string(10) " 林除夕" ["slug"]=> string(3) "adc" }
  */
  add_categories();
  // exit;
}
?>
<?php
// 获取数据库已有数据
// 获取数据

// 建立查询语句
$sql = "SELECT * FROM categories;";
$res = bx_get_db_data($sql);
/* 
$res===>
array(6) {
  [0]=&gt;
  array(3) {
    ["id"]=&gt;
    string(1) "1"
    ["slug"]=&gt;
    string(13) "uncategorized"
    ["name"]=&gt;
    string(9) "未分类"
  }
}


*/
// 渲染至页面



// exit;
?>

<!DOCTYPE html>
<html lang="zh-CN">

<head>
  <meta charset="utf-8">
  <title>Categories &laquo; Admin</title>
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
        <h1>分类目录</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <?php if (!empty($error_msg)) :; ?>
        <div class="alert alert-danger">
          <strong>错误！</strong><?php echo $error_msg; ?>
        </div>
      <?php endif; ?>
      <div class="row">
        <div class="col-md-4">
          <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
            <h2>添加新分类目录</h2>
            <div class="form-group">
              <label for="name">名称</label>
              <input id="name" name="name" class="form-control" name="name" type="text" placeholder="分类名称" value="<?php echo empty($_POST['name'])?'':$_POST['name']; ?>">
            </div>
            <div class="form-group">
              <label for="slug">别名</label>
              <input id="slug" name="slug" class="form-control" name="slug" type="text" placeholder="slug" value="<?php echo empty($_POST['slug'])?'':$_POST['slug']; ?>">
              <p class="help-block">https://zce.me/category/<strong>slug</strong></p>
            </div>
            <div class="form-group">
              <button class="btn btn-primary" type="submit">添加</button>
            </div>
          </form>
        </div>
        <div class="col-md-8">
          <div class="page-action">
            <!-- show when multiple checked -->
            <a class="btn btn-danger btn-sm" href="javascript:;" style="display: none">批量删除</a>
          </div>
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th class="text-center" width="40"><input type="checkbox"></th>
                <th>名称</th>
                <th>Slug</th>
                <th class="text-center" width="100">操作</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($res as $value) :; ?>
                <tr>
                  <td class="text-center"><input type="checkbox"></td>
                  <td><?php echo $value['name']; ?></td>
                  <td><?php echo $value['slug']; ?></td>
                  <td class="text-center">
                    <a href="javascript:;" class="btn btn-info btn-xs">编辑</a>
                    <a href="/admin/api/delete-categorie.php?id=<?php echo $value['id']; ?>" class="btn btn-danger btn-xs">删除</a>
                    <!-- 
                          编辑方案：
                            --跳转到新页面==》载入已有数据==》用户修改==》存储到数据库==》返回页面
                            --本页修改==》将数据加载至左边的新分类目录==》用户修改==》存储到数据库==》刷新页面
                          删除方案：
                            --跳转页面删除
                            --ajax发起删除请求
                         -->
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <?php $current_page = 'categories'; ?>
  <?php include 'inc/slider.php'; ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>
    NProgress.done()
  </script>
</body>

</html>