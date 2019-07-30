<!-- 检测用户是否登录开始 -->
  <?php
  require_once '../function.php';
  bx_check_login_status();
  ?>
<!-- 检测用户是否登录结束 -->

<!-- =================================================== -->
<!-- 分类筛选开始 -->
  <!-- 获取所有分类开始 -->
    <?php
      $all_categories_arr = bx_get_db_data("SELECT DISTINCT
      categories.`name`,
      categories.`id`
      FROM posts 
      INNER JOIN categories on posts.category_id = categories.id 
      INNER JOIN users on posts.user_id=users.id;");
      $all_status_arr = bx_get_db_data("SELECT DISTINCT
      posts.`status` 
      FROM posts 
      INNER JOIN categories on posts.category_id = categories.id 
      INNER JOIN users on posts.user_id=users.id;");
      // 数据转换
      /**
       * 转换status为中文状态
       * @param string $status 英文状态
       * @return string 中文状态
       */
      function change_status($status){
        $status_arr = array(
          'published' => '已发布',   
          'drafted' => '草稿',   
          'trashed' => '回收站'
        );
        return $status_arr[$status];
      }
    ?>
  <!-- 获取所有分类结束 -->

  <!-- 分类筛选后的分页开始 -->
    <?php 
      // 存储用户选中的分类
      $choose_category = empty($_GET['category'])?'all':$_GET['category'];
      $choose_status = empty($_GET['status'])?'all':$_GET['status'];
      $filter_search = "&category={$choose_category}&"."status={$choose_status}";
      // 根据用户的选择建立合适的数据库查询语句
      $filter_sql = ($choose_category !== 'all')?"posts.category_id = '{$choose_category}'":'1 = 1';
      $filter_sql .= ($choose_status !== 'all')?" and posts.`status` = '{$choose_status}'":'';
    ?>
  <!-- 分类筛选后的分页结束 -->

<!-- 分类筛选结束 -->

<!-- ======================================================== -->
<!-- 获取文章数据开始 -->

  <!-- 数据转换函数开始 -->
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
  <!-- 数据转换函数结束 -->

  <!-- 获取全部文章开始 -->
    <?php
      $page = (int) (isset($_GET['page']) ? $_GET['page'] : 1); // 获取页码
      $size = 10; // 每页展示多少条
      $offset = ($page - 1) * $size; // 越过多少条数据
      // 获取最大页码数
      $count_posts = bx_get_db_data("SELECT 
      count(1) as num 
      FROM posts 
      INNER JOIN categories on posts.category_id = categories.id 
      INNER JOIN users on posts.user_id=users.id
      WHERE {$filter_sql}
      ;")[0]['num'];
      $max_page = (int) ceil($count_posts / $size);  // 最大页数

      // ========================================================
      // 控制页码不溢出
      // 0 < 页码 < $max_page + 1
      if (($page < 0)) {
        header('Location: /admin/posts.php?page=1'.$filter_search);
      }
      if ($page > ($max_page)) {
        header("Location: /admin/posts.php?page={$max_page}".$filter_search);
      }

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
      WHERE {$filter_sql} 
      ORDER BY posts.created DESC
      LIMIT {$offset},{$size};";
      $posts = bx_get_db_data($get_posts_sql);
    ?>
  <!-- 获取全部文章结束 -->

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
        <a id="btn_batch_deletion" class="btn btn-danger btn-sm" href="javascript:;" style="display: none">批量删除</a>

        <!-- 分类筛选开始 -->
        <form class="form-inline" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
          <select name="category" class="form-control input-sm">
            <option value="all">所有分类</option>
            <?php foreach ($all_categories_arr as $value) : ?>
              <option value="<?php echo $value['id']; ?>"<?php echo ($choose_category == $value['id'])?' selected':''; ?>><?php echo $value['name']; ?></option>
            <?php endforeach; ?>
          </select>
          <select name="status" class="form-control input-sm">
            <option value="all">所有状态</option>
            <?php foreach ($all_status_arr as $value) : ?>
              <option value="<?php echo $value['status']; ?>"<?php echo ($choose_status == $value['status'])?' selected':''; ?>><?php echo change_status($value['status']); ?></option>
            <?php endforeach; ?>
          </select>
          <button class="btn btn-default btn-sm">筛选</button>
        </form>
        <!-- 分类筛选结束 -->

        <!-- 分页开始 -->
        <ul class="pagination pagination-sm pull-right">
          <?php bx_get_paging($max_page,"/admin/posts.php?page=%d{$filter_search}") ?>
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
              <td class="text-center"><input type="checkbox" data-id="<?php echo $value['id']; ?>"></td>
              <td><?php echo $value['title']; ?></td>
              <td><?php echo $value['user_name']; ?></td>
              <td><?php echo $value['category_name']; ?></td>
              <td class="text-center"><?php echo bx_convert_created($value['created']); ?></td>
              <td class="text-center"><?php echo bx_convert_status($value['status']); ?></td>
              <td class="text-center">
                <a href="javascript:;" class="btn btn-default btn-xs">编辑</a>
                <a href="/admin/api/posts-delete.php?id=<?php echo $value['id']; ?>" class="btn btn-danger btn-xs">删除</a>
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
  <!-- 批量删除开始 -->
  <script>
    $(function(){
      // 声明结果数组
      var result_arr = [];
      // 获取元素
      var selectd_all_obj = $('thead input');
      var selectd_one_arr = $('tbody input');
      var btn_batch_deletion = $('#btn_batch_deletion');
      // 点击全选
        selectd_all_obj.on('change',function(){
          selectd_one_arr.prop('checked',selectd_all_obj.prop('checked')).trigger('change');
        });
      // 点击单选
      selectd_one_arr.on('change',function(){
        selectd_all_obj.prop('checked',true);
        if(!$(this).prop('checked')){
          result_arr.splice(result_arr.indexOf($(this).data('id')),1);
          selectd_all_obj.prop('checked',false);
        }else{
          result_arr.push($(this).data('id'))
          selectd_one_arr = $('tbody input');
          selectd_one_arr.each(function(i,ele){
            if(!$(ele).prop('checked')){
              selectd_all_obj.prop('checked',false);
              return false;
            }
          });
        }
        result_arr.length>0?btn_batch_deletion.show():btn_batch_deletion.hide();
      });
      // 点击批量删除
      btn_batch_deletion.on('click',function(){
        $(this).prop('href','/admin/api/posts-delete.php?id='+result_arr.join(','));
      });
    })
  </script>
  <!-- 批量删除结束 -->
</body>

</html>