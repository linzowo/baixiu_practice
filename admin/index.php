<?php
// 载入依赖的函数
require_once '../function.php';
// 检测用户是否登录
bx_check_login_status();
// 获取站点需要的数据
// 查询语句列表
$queryArr = [
  'query_posts_count' => "SELECT COUNT(1) as num FROM posts;", // 文章总数
  'query_posts_published_count' => "SELECT COUNT(1) as num  FROM posts WHERE `status`= 'published';", // 草稿数
  'query_categories_count' => "SELECT COUNT(1) as num FROM categories;", // 分类数
  'query_comments_count' => "SELECT COUNT(1) as num FROM comments;", // 评论数
  'query_comments_held_count' => "SELECT COUNT(1) as num FROM comments WHERE `status`='held';" // 待审核评论
];
$resArr = [
  'res_posts_count' => '',
  'res_posts_published_count' => '',
  'res_categories_count' => '',
  'res_comments_count' => '',
  'res_comments_held_count' => ''
];
// 获取数据
foreach ($queryArr as $key => $value) {
  $res = bx_get_db_data($value);
  // array(1) { ["num"]=> string(1) "4" }
  $resKey = 'res' . substr($key, 5);
  // 如果查询结果不存在就将该值设置为0
  if (empty($res['num'])) {
    $resArr[$resKey] = 0;
  }
  $resArr[$resKey] = $res['num'];
}
// exit();
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
                <strong><?php echo $resArr['res_posts_count']; ?></strong>篇文章（<strong><?php echo $resArr['res_posts_published_count']; ?></strong>篇草稿）
              </li>
              <li class="list-group-item"><strong><?php echo $resArr['res_categories_count']; ?></strong>个分类</li>
              <li class="list-group-item">
                <strong><?php echo $resArr['res_comments_count']; ?></strong>条评论（<strong><?php echo $resArr['res_comments_held_count']; ?></strong>条待审核）
              </li>
            </ul>
          </div>
        </div>
        <div class="col-md-4">
          <canvas id="myChart" width="400" height="400"></canvas>
        </div>
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
  <script src="../static/assets/vendors/chart/chart2.8.js"></script>
  <script>
    var ctx = $('#myChart');
    var myChart = new Chart(ctx, {
      type: 'pie',
      data: {
        labels: ['文章', '分类', '评论'],
        datasets: [{
            label: '# of Votes',
            data: [<?php echo $resArr['res_posts_count'] . ',' . $resArr['res_categories_count'] . ',' . $resArr['res_comments_count']; ?>],
            backgroundColor: [
              'pink',
              'hotpink',
              'deeppink'
            ],
            borderColor: [
              'rgba(255, 99, 132, 1)',
              'rgba(54, 162, 235, 1)',
              'rgba(255, 206, 86, 1)'
            ],
            borderWidth: 1
          }
        ]
      },
      options: {
        scales: {
          yAxes: [{
            ticks: {
              beginAtZero: true
            }
          }]
        }
      }
    });
  </script>
</body>

</html>