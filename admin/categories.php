<?php
require_once '../function.php'; // 引入依赖

bx_check_login_status(); // 判断用户是否登录
?>
<?php

// 新增数据开始===========================
/**
 * 处理用户新增分类的函数
 */
function add_categories()
{
  // 校验
  if (empty($_POST['name']) || empty($_POST['slug'])) {
    $GLOBALS['msg'] = '请填写完整表单';
    $GLOBALS['success'] = false;
    return;
  }
  $GLOBALS['name'] = $_POST['name'];
  $GLOBALS['slug'] = $_POST['slug'];
  // 因为slug标识是唯一的==》需要判重
  // 建立查询语句
  $check_slug_sql = "SELECT slug FROM categories WHERE slug = '{$_POST['slug']}';";
  $check_slug_query = bx_get_db_data($check_slug_sql);
  if ($check_slug_query) {
    $GLOBALS['msg'] = '此slug已存在，请重新输入';
    $GLOBALS['success'] = false;
    return;
  }

  // 持久化
  // 存入数据库
  // 建立查询语句
  $sql = "INSERT INTO categories (`name`,slug) VALUES ('{$_POST['name']}','{$_POST['slug']}');";
  // var_dump(bx_add_data_to_db($sql));
  $add = bx_add_data_to_db($sql);
  $GLOBALS['success'] = $add > 0;
  $GLOBALS['msg'] = $add <= 0 ? '添加失败' : '添加成功';
  // 响应
}
// 新增数据结束===========================

// 编辑数据开始==========================
function show_edit_categories()
{
  // 校验
  $GLOBALS['edit_flag'] = false; // 编辑状态关闭

  if (empty($_GET['edit_id'])) return; // 没有编辑id==》显示新增页面==》结束

  $editId = (int) $_GET['edit_id']; // 获取id

  if ($editId == 0) return; //不是数字==》结束
  // 是否存在数据库中
  $edit_sql = "SELECT * FROM categories WHERE `id`= '{$editId}';";
  $current_edit_categorie = bx_get_db_data($edit_sql)[0];
  // array(3) { ["id"]=> string(1) "1" ["slug"]=> string(13) "uncategorized" ["name"]=> string(9) "未分类" }
  if (!$current_edit_categorie) return; // 数据库中没有==》结束

  // 执行到此说明查询数据存在
  $GLOBALS['name'] = $current_edit_categorie['name'];
  $GLOBALS['slug'] = $current_edit_categorie['slug'];
  $GLOBALS['id'] = $current_edit_categorie['id'];
  $GLOBALS['edit_flag'] = true; // 编辑状态打开
}
function edit_categories()
{
  global $msg, $success, $edit_flag, $name, $slug, $id;
  if (empty($_POST['edit_name']) || empty($_POST['edit_slug']) || empty($_GET['edit_id'])) {
    $msg = '请填写完整表单';
    $success = false;
    $edit_flag = true;
    return;
  }
  $name = trim($_POST['edit_name']);
  $slug = trim($_POST['edit_slug']);
  $id = (int) (trim($_GET['edit_id']));
  // 因为slug标识是唯一的==》需要判重
  $check_slug_sql = "SELECT slug FROM categories WHERE  id != '{$id}' AND slug = '{$slug}';";
  $check_slug_query = bx_get_db_data($check_slug_sql);
  if ($check_slug_query) {
    $msg = '此slug已存在，请重新输入';
    $success = false;
    $edit_flag = true;
    return;
  }
  // 判断用户是否没有修改任何内容
  $get_data_sql = "SELECT * FROM categories WHERE id = '{$id}';";
  $current_edit_categorie = bx_get_db_data($get_data_sql)[0];
  if (!$current_edit_categorie) {
    $msg = '你要修改的id有误';
    $success = false;
    $edit_flag = false;
    return;
  }
  if ($current_edit_categorie['name'] === $name && $current_edit_categorie['slug'] === $slug) {
    $msg = '你没有修改任何内容。';
    $success = true;
    $edit_flag = false;
    return;
  }
  // 存入数据库
  $edit_sql = "UPDATE categories SET slug = '{$slug}' , `name` = '{$name}' WHERE id = {$id};";
  $affected_rows = bx_edit_data_to_db($edit_sql);
  $success = $affected_rows > 0;
  $msg = $affected_rows <= 0 ? '修改失败' : '修改成功';
  $edit_flag = $affected_rows <= 0 ? true : false;
}
// 编辑数据结束==========================
?>
<!-- 引入依赖 声明必要函数 -->

<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  show_edit_categories();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  (!empty($_GET['edit_id'])) ? edit_categories() : add_categories();
}
?>
<!-- 业务判断 -->

<?php
// 获取数据库已有数据
$sql = "SELECT * FROM categories;";
$all_categories = bx_get_db_data($sql);
/* 
  $all_categories===>
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
?>
<!-- 获取渲染页面必须的数据 -->

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
      <!-- 错误信息开始 -->
      <!-- 有错误信息时展示 -->
      <?php if (!empty($msg)) :; ?>
        <?php if ($success) :; ?>
          <div class="alert alert-success">
            <strong>成功！</strong><?php echo $msg; ?>
          </div>
        <?php else :; ?>
          <div class="alert alert-danger">
            <strong>错误！</strong><?php echo $msg; ?>
          </div>
        <?php endif; ?>
      <?php endif; ?>
      <!-- 错误信息结束 -->

      <!-- 新增或修改开始 -->
      <div class="row">
        <div class="col-md-4">
          <?php if (isset($edit_flag) && $edit_flag) : ?>
            <form action="<?php echo $_SERVER["PHP_SELF"]; ?>?edit_id=<?php echo (empty($id)) ? '' : $id; ?>" method="post">
              <h2>修改分类目录</h2>
              <div class="form-group">
                <label for="name">名称</label>
                <input id="name" name="edit_name" class="form-control" type="text" placeholder="分类名称" value="<?php echo (empty($name)) ? '' : $name; ?>">
              </div>
              <div class="form-group">
                <label for="slug">别名</label>
                <input id="slug" name="edit_slug" class="form-control" type="text" placeholder="slug" value="<?php echo (empty($slug)) ? '' : $slug; ?>">
                <p class="help-block">https://zce.me/category/<strong>slug</strong></p>
              </div>
              <div class="form-group">
                <button class="btn btn-primary" type="submit">修改</button>
              </div>
            <?php else : ?>
              <form action="<?php echo $_SERVER["PHP_SELF"]; ?>?add" method="post">
                <h2>添加新分类目录</h2>
                <div class="form-group">
                  <label for="name">名称</label>
                  <input id="name" name="name" class="form-control" type="text" placeholder="分类名称" value="<?php echo (empty($name) || $success) ? '' : $name; ?>">
                </div>
                <div class="form-group">
                  <label for="slug">别名</label>
                  <input id="slug" name="slug" class="form-control" type="text" placeholder="slug" value="<?php echo (empty($slug) || $success) ? '' : $slug; ?>">
                  <p class="help-block">https://zce.me/category/<strong>slug</strong></p>
                </div>
                <div class="form-group">
                  <button class="btn btn-primary" type="submit">添加</button>
                </div>
              <?php endif; ?>
            </form>
        </div>
        <!-- 新增或修改结束 -->

        <!-- 数据展示区开始 -->
        <div class="col-md-8">
          <div class="page-action">
            <!-- show when multiple checked -->
            <a id="batch_deletion" class="btn btn-danger btn-sm" href="javascript:;" style="display: none">批量删除</a>
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
              <?php foreach ($all_categories as $value) :; ?>
                <tr>
                  <td class="text-center"><input type="checkbox" data-id="<?php echo $value['id']; ?>"></td>
                  <td><?php echo $value['name']; ?></td>
                  <td><?php echo $value['slug']; ?></td>
                  <td class="text-center">
                    <a href="?edit_id=<?php echo $value['id']; ?>" class="btn btn-info btn-xs">编辑</a>
                    <a href="/admin/api/categorie-delete.php?id=<?php echo $value['id']; ?>" class="btn btn-danger btn-xs">删除</a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
      <!-- 数据展示区结束 -->

    </div>
  </div>

  <?php $current_page = 'categories'; ?>
  <?php include 'inc/slider.php'; ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>
    NProgress.done()
  </script>

  <!-- 批量删除开始 -->
  <script>
    $(function() {
      // 创建一个结果数组
      var deleteIdArr = [];
      // 获取元素
      // 批量删除按钮
      var batch_deletion = $('#batch_deletion');
      // 全选按钮
      var checkboxAllObj = $('thead input:checkbox');
      // 单选按钮
      var checkboxOneArr = $('tbody input:checkbox');

      // 为全选框注册点击事件
      checkboxAllObj.on('change', function() {
        checkboxOneArr.prop('checked', $(this).prop('checked')).trigger('change');
      });

      // 为每个tbody中的input注册点击事件
      checkboxOneArr.on('change', function() {
        var id = $(this).data('id');
        $(this).prop('checked') ? deleteIdArr.includes(id) || deleteIdArr.push(id) : deleteIdArr.splice(deleteIdArr.indexOf(id), 1);

        checkboxAllObj.prop('checked', true); // 默认全选
        checkboxOneArr = $('tbody input:checkbox'); // 获取最新的列表
        // 判断是否全选
        $(this).prop('checked') ? checkboxOneArr.each(function(i, item) {
          if (!$(item).prop('checked')) {
            // 存在没有选择的==>取消全选
            checkboxAllObj.prop('checked', false);
            // 结束each循环
            return false;
          }
        }) : checkboxAllObj.prop('checked', false);;

        // 批量删除按钮显示/隐藏
        deleteIdArr.length == 0 ? batch_deletion.hide() : batch_deletion.show();
        console.log(deleteIdArr);
      });

      // 用户点击批量删除
      batch_deletion.on('click', function() {
        var url = '/admin/api/categorie-delete.php?id=' + deleteIdArr.join(',');
        $(this).prop('href', url);
      });
    });
  </script>
  <!-- 批量删除结束 -->
</body>

</html>