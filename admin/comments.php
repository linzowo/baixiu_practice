<?php
require_once '../function.php';
bx_check_login_status();
?>
<?php
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
  <!-- 动态loading的css -->
  <style type="text/css">
    .ng-scope {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      bottom: 0;
      right: 0;
      background-color: rgba(0, 0, 0, 0.5);
      z-index: 999;
      padding: 200px 50%;
    }

    @keyframes lds-spinner {
      0% {
        opacity: 1;
      }

      100% {
        opacity: 0;
      }
    }

    @-webkit-keyframes lds-spinner {
      0% {
        opacity: 1;
      }

      100% {
        opacity: 0;
      }
    }


    .lds-spinner {
      position: relative;
    }

    .lds-spinner div {
      left: 94px;
      top: 48px;
      position: absolute;
      -webkit-animation: lds-spinner linear 1s infinite;
      animation: lds-spinner linear 1s infinite;
      background: #facd9e;
      width: 12px;
      height: 24px;
      border-radius: 40%;
      -webkit-transform-origin: 6px 52px;
      transform-origin: 6px 52px;
    }

    .lds-spinner div:nth-child(1) {
      -webkit-transform: rotate(0deg);
      transform: rotate(0deg);
      -webkit-animation-delay: -0.916666666666667s;
      animation-delay: -0.916666666666667s;
    }

    .lds-spinner div:nth-child(2) {
      -webkit-transform: rotate(30deg);
      transform: rotate(30deg);
      -webkit-animation-delay: -0.833333333333333s;
      animation-delay: -0.833333333333333s;
    }

    .lds-spinner div:nth-child(3) {
      -webkit-transform: rotate(60deg);
      transform: rotate(60deg);
      -webkit-animation-delay: -0.75s;
      animation-delay: -0.75s;
    }

    .lds-spinner div:nth-child(4) {
      -webkit-transform: rotate(90deg);
      transform: rotate(90deg);
      -webkit-animation-delay: -0.666666666666667s;
      animation-delay: -0.666666666666667s;
    }

    .lds-spinner div:nth-child(5) {
      -webkit-transform: rotate(120deg);
      transform: rotate(120deg);
      -webkit-animation-delay: -0.583333333333333s;
      animation-delay: -0.583333333333333s;
    }

    .lds-spinner div:nth-child(6) {
      -webkit-transform: rotate(150deg);
      transform: rotate(150deg);
      -webkit-animation-delay: -0.5s;
      animation-delay: -0.5s;
    }

    .lds-spinner div:nth-child(7) {
      -webkit-transform: rotate(180deg);
      transform: rotate(180deg);
      -webkit-animation-delay: -0.416666666666667s;
      animation-delay: -0.416666666666667s;
    }

    .lds-spinner div:nth-child(8) {
      -webkit-transform: rotate(210deg);
      transform: rotate(210deg);
      -webkit-animation-delay: -0.333333333333333s;
      animation-delay: -0.333333333333333s;
    }

    .lds-spinner div:nth-child(9) {
      -webkit-transform: rotate(240deg);
      transform: rotate(240deg);
      -webkit-animation-delay: -0.25s;
      animation-delay: -0.25s;
    }

    .lds-spinner div:nth-child(10) {
      -webkit-transform: rotate(270deg);
      transform: rotate(270deg);
      -webkit-animation-delay: -0.166666666666667s;
      animation-delay: -0.166666666666667s;
    }

    .lds-spinner div:nth-child(11) {
      -webkit-transform: rotate(300deg);
      transform: rotate(300deg);
      -webkit-animation-delay: -0.083333333333333s;
      animation-delay: -0.083333333333333s;
    }

    .lds-spinner div:nth-child(12) {
      -webkit-transform: rotate(330deg);
      transform: rotate(330deg);
      -webkit-animation-delay: 0s;
      animation-delay: 0s;
    }

    .lds-spinner {
      width: 200px !important;
      height: 200px !important;
      -webkit-transform: translate(-100px, -100px) scale(1) translate(100px, 100px);
      transform: translate(-100px, -100px) scale(1) translate(100px, 100px);
    }
  </style>
  <script src="/static/assets/vendors/nprogress/nprogress.js"></script>
</head>

<body>
  <!-- 动态loading图案 -->
  <div class="lds-css ng-scope">
    <div class="lds-spinner" style="width:100%;height:100%">
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
    </div>
  </div>
  <script>
    NProgress.start()
  </script>

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
    <tr class="{{:status === 'rejected'?'danger':status === 'held'?'warning': ''}}" data-id="{{:id}}">
      <td class="text-center"><input type="checkbox"></td>
      <td class="text-center" width="100">{{:author}}</td>
      <td>{{:content}}</td>
      <td style=" white-space:nowrap">《{{:post_title}}》</td>
      <td width="100">{{:created}}</td>
      <td class="text-center" width="100">{{:status === 'rejected'?'拒绝':status === 'held'?'待审核': '通过'}}</td>
      <td class="text-center" width='150'>
        {{if status === 'held'}}
        <a href="post-add.html" class="btn btn-info btn-xs">批准</a>
        <a class="btn btn-warning btn-xs btn-edit" href="javascript:;" data-status="rejected">拒绝</a>
        {{else}}
          <a href="javascript:;" class="btn btn-danger btn-xs btn-delete">删除</a>
          {{/if}}
      </td>
    </tr>
    {{/for}}
  </script>
  <script>
    // loading特效
    $(document)
      .ajaxStart(function() {
        NProgress.start()
        // 加载loading图像
        $('.lds-css').fadeIn();
      })
      .ajaxStop(function() {
        NProgress.done()
        // 关闭loading图像
        $('.lds-css').fadeOut();
    })

    // 初始化数据
    var size = 30; // 页大小
    var current_page = 1; // 当前页码
    /**
     * 获取分页数据并渲染
     * @param int page 查询第几页的数据
     */
    function loadPageDate() {
      // 获取元素
      var tbody = $('tbody');
      // 通过ajax获取数据
      tbody.fadeOut();
      $.get('/admin/api/comments.php', { p: current_page , s : size }, function(res) {
        if(current_page > res['total_page']){
          current_page = res['total_page'];
          loadPageDate();
          return;
        }
        $('.pagination').twbsPagination('destroy');// 销毁初始化的分页按钮
        // 使用jquery分页库生成分页数据
        $('.pagination').twbsPagination({
          startPage: current_page,
          totalPages: res['total_page'],
          visiblePages: 5,
          first: '&laquo;',
          last: '&raquo;',
          prev: '&lt;',
          next: '&gt;',
          initiateStartPageClick: false,
          onPageClick: function(e, page) {
            current_page = page;
            loadPageDate();
          }
        });
        // 获取当前页面应该展示的数据并渲染
        var tr = $('#comments_tmpl').render({
          comments: res['comments']
        });
        $('tbody').html(tr);
        tbody.fadeIn();
      });
    }

    // 初始化页面
    loadPageDate();

    // TODO: 添加删除功能
    // =====================================
    // 注册点击事件==>因为删除按钮是动态加载的==>需要通过父元素委托注册点击事件
    $('tbody').on('click', '.btn-delete', function() {
      // 获取要删除的id
      var id = $(this).parent().parent().data('id');
      // 发起一个ajax请求删除数据
      $.get('/admin/api/comment-delete.php', { id : id }, function(res) {
        // console.log(res);
        if (!res) return; // 如果删除失败什么都不做
        // 重新获取当前页数据
        loadPageDate();
      });
      // 根据返回的情况展示页面数据
      // 实时更新最大页码数
    });
  </script>
  <script>
    NProgress.done()
  </script>
</body>

</html>