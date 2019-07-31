<?php 
require_once '../function.php';
bx_check_login_status();
?>
<?php 
// TODO: 分页
// TODO: 批准
// TODO: 删除
// // TODO: 批量删除
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Comments &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="/static/assets/vendors/nprogress/nprogress.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
  <script src="/static/assets/vendors/nprogress/nprogress.js"></script>
</head>
<body>
  <script>NProgress.start()</script>

  <div class="main">
    <?php include 'inc/navbar.php'; ?>
    <div class="container-fluid">
      <div class="page-title">
        <h1>所有评论</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <div class="page-action">
        <!-- show when multiple checked -->
        <div class="btn-batch" style="display: none">
          <button class="btn btn-info btn-sm">批量批准</button>
          <button class="btn btn-warning btn-sm">批量拒绝</button>
          <button class="btn btn-danger btn-sm">批量删除</button>
        </div>
        <ul class="pagination pagination-sm pull-right"></ul>
      </div>
      <table class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <th class="text-center" width="40"><input type="checkbox"></th>
            <th class="text-center">作者</th>
            <th>评论</th>
            <th>评论在</th>
            <th>提交于</th>
            <th class="text-center">状态</th>
            <th class="text-center" width="100">操作</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>

  <?php $current_page = 'comments'; ?>
    <?php include 'inc/slider.php'; ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script src="/static/assets/vendors/jsrender/jsrender.js"></script>
  <script src="/static/assets/vendors/twbs-pagination/jquery.twbsPagination.js"></script>
  <script id="comments_tmpl" type="text/x-jsrender">
    {{for comments}}
      <tr class="{{:status === 'rejected'?'danger':status === 'held'?'warning': ''}}">
        <td class="text-center"><input type="checkbox"></td>
        <td class="text-center" width = "100">{{:author}}</td>
        <td>{{:content}}</td>
        <td style=" white-space:nowrap" >《{{:post_title}}》</td>
        <td width = "100">{{:created}}</td>
        <td class="text-center" width = "100">{{:status === 'rejected'?'拒绝':status === 'held'?'待审核': '通过'}}</td>
        <td class="text-center" width = '150'>
          {{if status === 'held'}}
            <a href="post-add.html" class="btn btn-info btn-xs">批准</a>
            <a class="btn btn-warning btn-xs btn-edit" href="javascript:;" data-status="rejected">拒绝</a>
          {{else}}
            <a href="javascript:;" class="btn btn-danger btn-xs">删除</a>
          {{/if}}
        </td>
      </tr>
    {{/for}}
  </script>
  <script>
   /**
    * 获取分页数据并渲染
    * @param int page 查询第几页的数据
    */
    function loadPageDate(page){
      // 获取元素
      var tbody = $('tbody');
      // 通过ajax获取数据
      tbody.fadeOut();
      $.get('/admin/api/comments.php',{ page:page },function(res){
        // 使用jquery分页库生成分页数据
        $('.pagination').twbsPagination({
          totalPages: res['total_page'],
          visiblePages: 5,
          initiateStartPageClick: false,
          onPageClick: function(e,page){
            loadPageDate(page);
          }
        });
        // 获取当前页面应该展示的数据并渲染
        var tr = $('#comments_tmpl').render({ comments:res['comments'] });
        $('tbody').html(tr);
        tbody.fadeIn();
      });
    }
    // 初始化页面
    loadPageDate(1);
  </script>
  <script>NProgress.done()</script>
</body>
</html>
