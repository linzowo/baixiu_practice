<!--
 * @Author: linzwo
 * @Date: 2019-07-08 16:01:29
 * @LastEditors: linzwo
 * @LastEditTime: 2019-07-08 21:14:47
 * @Description: 公共侧边导航栏
 -->
 <!-- 使侧边栏跟随url地址变化而变化================php处理版本开始 -->
 <!-- 再次声明$current_page防止出现未声明导致的报错 -->
 <?php $current_page = isset($current_page)?$current_page:''; ?>
<div class="aside">
  <div class="profile">
    <img class="avatar" src="<?php echo $_SESSION['user']['avatar']; ?>" />
    <h3 class="name"><?php echo $_SESSION['user']['nickname']; ?></h3>
  </div>
  <ul class="nav">
    <li class="<?php echo $current_page === 'index'?'active':''; ?>">
      <a href="/admin/index.php"><i class="fa fa-dashboard"></i>仪表盘</a>
    </li>
    <?php $menuPostsArr = array('posts','post-add','categories'); ?>
    <li class="<?php echo in_array($current_page,$menuPostsArr)?'active':''; ?>">
      <a href="#menu-posts" class="collapsed" data-toggle="collapse">
        <i class="fa fa-thumb-tack"></i>文章<i class="fa fa-angle-right"></i>
      </a>
      <ul id="menu-posts" class="collapse<?php echo in_array($current_page,$menuPostsArr)?' in':''; ?>">
        <li class="<?php echo $current_page === 'posts'?'active':''; ?>"><a href="/admin/posts.php">所有文章</a></li>
        <li class="<?php echo $current_page === 'post-add'?'active':''; ?>"><a href="/admin/post-add.php">写文章</a></li>
        <li class="<?php echo $current_page === 'categories'?'active':''; ?>"><a href="/admin/categories.php">分类目录</a></li>
      </ul>
    </li>
    <li class="<?php echo $current_page === 'comments'?'active':''; ?>">
      <a href="/admin/comments.php"><i class="fa fa-comments"></i>评论</a>
    </li>
    <li class="<?php echo $current_page === 'users'?'active':''; ?>">
      <a href="/admin/users.php"><i class="fa fa-users"></i>用户</a>
    </li>
    <?php $menuSettingsArr = array('nav-menus','slides','settings'); ?>
    <li class="<?php echo in_array($current_page,$menuSettingsArr)?'active':''; ?>">
      <a href="#menu-settings" class="collapsed" data-toggle="collapse">
        <i class="fa fa-cogs"></i>设置<i class="fa fa-angle-right"></i>
      </a>
      <ul id="menu-settings" class="collapse<?php echo in_array($current_page,$menuSettingsArr)?' in':''; ?>">
        <li class="<?php echo $current_page === 'nav-menus'?'active':''; ?>"><a href="/admin/nav-menus.php">导航菜单</a></li>
        <li class="<?php echo $current_page === 'slides'?'active':''; ?>"><a href="/admin/slides.php">图片轮播</a></li>
        <li class="<?php echo $current_page === 'settings'?'active':''; ?>"><a href="/admin/settings.php">网站设置</a></li>
      </ul>
    </li>
  </ul>
</div>
<!-- 使侧边栏跟随url地址变化而变化================php处理版本结束 -->

<!-- 使侧边栏跟随url地址变化而变化================js处理版本开始 -->
<!-- <div class="aside">
  <div class="profile">
    <img class="avatar" src="/static/uploads/avatar.jpg" />
    <h3 class="name">布头儿</h3>
  </div>
  <ul class="nav">
    <li class="active">
      <a href="/admin/index.php"><i class="fa fa-dashboard"></i>仪表盘</a>
    </li>
    <li>
      <a href="#menu-posts" class="collapsed" data-toggle="collapse">
        <i class="fa fa-thumb-tack"></i>文章<i class="fa fa-angle-right"></i>
      </a>
      <ul id="menu-posts" class="collapse">
        <li><a href="/admin/posts.php">所有文章</a></li>
        <li><a href="/admin/post-add.php">写文章</a></li>
        <li><a href="/admin/categories.php">分类目录</a></li>
      </ul>
    </li>
    <li>
      <a href="/admin/comments.php"><i class="fa fa-comments"></i>评论</a>
    </li>
    <li>
      <a href="/admin/users.php"><i class="fa fa-users"></i>用户</a>
    </li>
    <li>
      <a href="#menu-settings" class="collapsed" data-toggle="collapse">
        <i class="fa fa-cogs"></i>设置<i class="fa fa-angle-right"></i>
      </a>
      <ul id="menu-settings" class="collapse">
        <li><a href="/admin/nav-menus.php">导航菜单</a></li>
        <li><a href="/admin/slides.php">图片轮播</a></li>
        <li><a href="/admin/settings.php">网站设置</a></li>
      </ul>
    </li>
  </ul>
</div> -->
<!-- <script>
  window.onload = function() {
    // 获取当前url中指向文件名称
    var pathname = location.pathname;

    // 查找标签中与其名称一致的a连接所在的位置
    // console.log($('.aside li a').attr('href'));
    var alist = $(".aside li a");
    for (var i = 0; i < alist.length; i++) {
      // console.log($(alist[i]).attr('href'));
      // a连接地址与当前url中的文件名不一致就跳过本次循环
      if ($(alist[i]).attr("href") !== pathname) {
        continue;
      }

      // 执行至此说明a连接地址与当前url中的文件名一致
      // 清除所有li标签的class
      $(".aside>.nav>li").removeClass("active");

      // 为其父级li添加class
      $(alist[i])
        .parent("li")
        .addClass("active");

      // 其父级li所属的ul的class为nav就结束循环
      if (
        $(alist[i])
          .parent("li")
          .parent("ul")
          .hasClass("nav")
      ) {
        break;
      }

      // 不是Nav就在为其父级li添加active
      $(alist[i])
        .parent("li")
        .parent("ul")
        .addClass("in")
        .siblings("a")
        .removeClass('collapsed')
        .parent("li")
        .addClass("active");

      break;
    }

    // 为子级ul标签添加点击事件
    $('.aside>li>ul').siblings('a').click(function(){
      if($(this).hasClass('collapsed')){
        $(this).removeClass('collapsed');
      }else{
        $(this).addClass('collapsed');
      }
    });
  };
</script> -->
<!-- 使侧边栏跟随url地址变化而变化================js处理版本结束 -->