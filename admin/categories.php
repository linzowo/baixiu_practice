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
  if (empty($_POST['name']) || empty($_POST['slug'])) {
    $GLOBALS['msg'] = '请填写完整表单';
    return;
  }
  // 因为slug标识是唯一的==》需要判重
  // 建立查询语句
  $check_slug_sql = "SELECT slug FROM categories WHERE slug = '{$_POST['slug']}';";
  $check_slug_query = bx_get_db_data($check_slug_sql);
  if ($check_slug_query) {
    $GLOBALS['msg'] = '此slug已存在，请重新输入';
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
$res_categories = bx_get_db_data($sql);
/* 
$res_categories===>
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
      <div class="row">
        <div class="col-md-4">
          <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
            <h2>添加新分类目录</h2>
            <div class="form-group">
              <label for="name">名称</label>
              <input id="name" name="name" class="form-control" name="name" type="text" placeholder="分类名称" value="<?php echo (empty($_POST['name']) || $success) ? '' : $_POST['name']; ?>">
            </div>
            <div class="form-group">
              <label for="slug">别名</label>
              <input id="slug" name="slug" class="form-control" name="slug" type="text" placeholder="slug" value="<?php echo (empty($_POST['slug']) || $success) ? '' : $_POST['slug']; ?>">
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
              <?php foreach ($res_categories as $value) :; ?>
                <tr>
                  <td class="text-center"><input type="checkbox" data-id="<?php echo $value['id']; ?>"></td>
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
        // 所有单选框的状态随全选框状态变化而变化==>全选将所有选择的id加入数组==>全不选将所有id删除

        // 清空数组
        deleteIdArr = [];
        var checkboxAllObj = this;
        checkboxOneArr.each(function(i, item) {
          if ($(checkboxAllObj).prop('checked')) {
            deleteIdArr.push($(item).data('id'));
          }
          $(item).prop('checked', $(checkboxAllObj).prop('checked'));
        });

        // 批量删除按钮显示/隐藏
        deleteIdArr.length == 0 ? batch_deletion.hide() : batch_deletion.show();
        // console.log(deleteIdArr);
      });

      // 为每个tbody中的input注册点击事件
      checkboxOneArr.on('change', function() {
        // 改变当前点击按钮的状态并且将批量删除按钮显示出来

        // 执行到此说明所有的单选按钮都选择了
        checkboxAllObj.prop('checked', true);

        // 检查当前元素的选中状态===》删除还是新增id到数组中
        var dataId = $(this).data('id');

        if ($(this).prop('checked')) { // 当前选项被选中
          // 将id放入数组
          deleteIdArr.push(dataId);

          // 获取当前最新的元素信息
          checkboxOneArr = $('tbody input:checkbox');
          // 检查所有的元素
          checkboxOneArr.each(function(i, item) {
            // 检测是否全选
            if (!$(item).prop('checked')) {
              // 存在没有选择的
              checkboxAllObj.prop('checked', false);
              // 结束each循环
              return false;
            }
          });
        } else { // 当前选项被取消选中
          // 将id删除
          deleteIdArr.splice(deleteIdArr.indexOf(dataId), 1);
          // 有选项被取消选中就肯定不是全选===》取消全选框选中状态
          checkboxAllObj.prop('checked', false);
        }

        // 批量删除按钮显示/隐藏
        deleteIdArr.length == 0 ? batch_deletion.hide() : batch_deletion.show();
        // console.log(deleteIdArr);
      });

      // 用户点击批量删除
      batch_deletion.on('click', function() {
        var url = '/admin/api/delete-categorie.php?id=' + deleteIdArr.join(',');
        $(this).prop('href', url);
      });
    });
  </script>
</body>

</html>