<!-- 检测用户是否登录开始 -->
<?php
require_once '../function.php';
bx_check_login_status();
?>
<!-- 检测用户是否登录结束 -->

<!-- 获取文章数据开始 -->
<!-- 数据转换函数 -->
<?php
/**
 * 创建时间转换显示
 * @param date $created
 * @return string created 年-月-日 <br/> 时:分:秒
 */
function bx_convert_created($created)
{
  $timestamp = strtotime($created);
  // return date("Y年-m月-d日",$timestamp).'<br/>'.date("H:i:s",$timestamp);
  return date('Y年-m月-d日<b\r>H:i:s', $timestamp);
}

/**
 * 状态转换显示
 * @param string $status  英文状态
 * @return string 用户昵称 中文状态
 */
function bx_convert_status($status)
{
  $dict = array(
    'drafted' => '草稿',
    'published' => '已发布',
    'trashed' => '回收站'
  );
  return isset($dict[$status]) ? $dict[$status] : '未知';
}
?>
<?php
// 获取数据库数据总条数===》解决查询范围溢出的问题
// 获取最大页码
$size = 10; // 每页展示多少条

$count_posts = bx_get_db_data("SELECT 
count(1) as num 
FROM posts 
INNER JOIN categories on posts.category_id = categories.id 
INNER JOIN users on posts.user_id=users.id;")[0]['num'];

$max_page = (int) ceil($count_posts / $size);  // 最大页数
// ==========================================================
// 设置分页循环需要的参数
$visibles = 5; // 可见页码
$page = (int) (empty($_GET['page']) ? 1 : $_GET['page']); // 获取页码
$region = (int) (($visibles - 1) / 2); // 左右区间
$begin = (int) ($page - $region); // 开始页码
$end = (int) ($begin + $visibles); // 结束页码
$offset = ($page - 1) * $size; // 越过多少条数据
$previous_page = ($page - 1) > 0 ? ($page - 1) : 1; // 上一页
$next_page = ($page + 1) > $max_page ? $max_page : ($page + 1); // 下一页
// ==========================================================
// 前后一批数据
if (($page-$region) > 1 ) {
  $before = $page - $visibles;
  if($before < 1 ){
    $before = 3;
  }
}
if (($max_page - $page) > $region) {
  $after = $page + $visibles;
  if($after > $max_page){
    $after = $max_page -$region;
  }
}
// ==========================================================
// 根据页码限制开始和结束范围
// $begin > 0;
// $end < $max_page +1
if ($page < 3) {
  $begin = 1;
  $end = $begin + $visibles;
}
if ($page > ($max_page - 2)) {
  $end = $max_page + 1;
  $begin = $end - $visibles;
  if ($begin < 0) {
    $begin = 1;
  }
}
// ========================================================
// 控制页码不溢出
// 0 < 页码 < $max_page + 1
if (($page < 1) || ($_GET['page'] === 0)) {
  header('Location: /admin/posts.php?page=1');
}
if ($page > ($max_page)) {
  header("Location: /admin/posts.php?page={$max_page}");
}

// ========================================================
?>
<?php
// 数据库查询
$get_posts_sql = "SELECT 
posts.id,
posts.title,
users.nickname as user_name,
categories.`name` as category_name,
posts.created,
posts.`status` 
FROM posts 
INNER JOIN categories on posts.category_id = categories.id 
INNER JOIN users on posts.user_id=users.id 
ORDER BY posts.created DESC
LIMIT {$offset},{$size};";
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

        <!-- 分页开始 -->
        <ul class="pagination pagination-sm pull-right">
          <li><a href="?page=<?php echo $previous_page; ?>">上一页</a></li>
          <?php if (!empty($before)) : ?>
            <li><a href="?page=<?php echo $before; ?>"><?php echo '...'; ?></a></li>
          <?php endif; ?>

          <?php for ($i = $begin; $i < $end; $i++) : ?>
            <li <?php echo $page === $i ? "class='active'" : ''; ?>><a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
          <?php endfor; ?>

          <?php if (!empty($after)) : ?>
            <li><a href="?page=<?php echo $after; ?>"><?php echo '...'; ?></a></li>
          <?php endif; ?>
          <li><a href="?page=<?php echo $next_page; ?>">下一页</a></li>
        </ul>
        <!-- 分页结束 -->

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
              <td class="text-center"><?php echo bx_convert_created($value['created']); ?></td>
              <td class="text-center"><?php echo bx_convert_status($value['status']); ?></td>
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