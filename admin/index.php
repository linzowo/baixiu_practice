<?php
// 载入依赖的函数
require_once '../function.php';
// 检测用户是否登录
bx_check_login_status();
// 获取站点需要的数据
// 查询语句列表
$queryArr = [
  'query_posts_count' => "SELECT COUNT(1) FROM posts;", // 文章总数
  'query_posts_published_count' => "SELECT COUNT(1)  FROM posts WHERE `status`= 'published';", // 草稿数
  'query_categories_count' => "SELECT COUNT(1) FROM categories;", // 分类数
  'query_comments_count' => "SELECT COUNT(1) FROM comments;", // 评论数
  'query_comments_approved_count' => "SELECT COUNT(1) FROM comments WHERE `status`='approved';" // 待审核评论
];
$resArr = [
  'res_posts_count' =>'',
  'res_posts_published_count' =>'',
  'res_categories_count' =>'',
  'res_comments_count' =>'',
  'res_comments_approved_count' =>''
];
// 获取数据
foreach ($queryArr as $key => $value) {
  $resKey = 'res'.substr($key,5);
  $res = bx_get_db_data($value);
  $resArr[$resKey] = mysqli_fetch_assoc($res)['COUNT(1)'];
}
?>
<!DOCTYPE html>
<!--
 * @Author: linzwo
 * @Date: 2019-07-08 15:13:08
 * @LastEditors: linzwo
 * @LastEditTime: 2019-07-08 20:21:52
 * @Description: 管理员主页
 -->
<html lang="zh-CN">

<head>
  <meta charset="utf-8" />
  <title>Dashboard &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css" />
  <link rel="stylesheet" href="/static/assets/vendors/font-awesome/css/font-awesome.css" />
  <link rel="stylesheet" href="/static/assets/vendors/nprogress/nprogress.css" />
  <link rel="stylesheet" href="/static/assets/css/admin.css" />
  <script src="/static/assets/vendors/nprogress/nprogress.js"></script>
</head>

<body>
  <script>
    NProgress.start();
  </script>

  <div class="main">
    <?php include 'inc/navbar.php'; ?>
    <div class="container-fluid">
      <div class="jumbotron text-center">
        <h1>One Belt, One Road</h1>
        <p>Thoughts, stories and ideas.</p>
        <p>
          <a class="btn btn-primary btn-lg" href="post-add.html" role="button">写文章</a>
        </p>
      </div>
      <div class="row">
        <div class="col-md-4">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">站点内容统计：</h3>
            </div>
            <ul class="list-group">
              <li class="list-group-item">
                <strong><?php echo empty($resArr['res_posts_count'])?0:$resArr['res_posts_count']; ?></strong>篇文章（<strong><?php echo empty($resArr['res_posts_published_count'])?0:$resArr['res_posts_published_count']; ?></strong>篇草稿）
              </li>
              <li class="list-group-item"><strong><?php echo empty($resArr['res_categories_count'])?0:$resArr['res_categories_count']; ?></strong>个分类</li>
              <li class="list-group-item">
                <strong><?php echo empty($resArr['res_comments_count'])?0:$resArr['res_comments_count']; ?></strong>条评论（<strong><?php echo empty($resArr['res_comments_approved_count'])?0:$resArr['res_comments_approved_count']; ?></strong>条待审核）
              </li>
            </ul>
          </div>
        </div>
        <div class="col-md-4"></div>
        <div class="col-md-4"></div>
      </div>
    </div>
  </div>

  <?php $current_page = 'index'; ?>
  <?php include 'inc/slider.php'; ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>
    NProgress.done();
  </script>
</body>

</html>