<?php
require_once '../function.php';
bx_check_login_status();
var_dump($_SESSION['user']);
/* 
array(8) {
  ["id"]=>
  string(1) "1"
  ["slug"]=>
  string(5) "admin"
  ["email"]=>
  string(16) "admin@linzowo.me"
  ["password"]=>
  string(6) "111111"
  ["nickname"]=>
  string(9) "管理员"
  ["avatar"]=>
  string(26) "/static/uploads/avatar.jpg"
  ["bio"]=>
  NULL
  ["status"]=>
  string(9) "activated"
}
*/
$img_src = empty($_SESSION['user']['avatar'])?'/static/assets/img/default.png':$_SESSION['user']['avatar'];
$email = $_SESSION['user']['email'];
$slug = $_SESSION['user']['slug'];
$nickname = $_SESSION['user']['nickname'];
$bio = empty($_SESSION['user']['bio'])?'':$_SESSION['user']['bio'];
?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
  <meta charset="utf-8">
  <title>Dashboard &laquo; Admin</title>
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
        <h1>我的个人资料</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <div class="alert alert-danger" id="msg">
        <!-- <strong>错误！</strong>发生XXX错误 -->
      </div>
      <form class="form-horizontal">
        <div class="form-group">
          <label class="col-sm-3 control-label">头像</label>
          <div class="col-sm-6">
            <label class="form-image">
              <input id="avatar" type="file">
              <img src="<?php echo $img_src; ?>" width="200">
              <i class="mask fa fa-upload"></i>
            </label>
          </div>
        </div>
        <div class="form-group">
          <label for="email" class="col-sm-3 control-label">邮箱</label>
          <div class="col-sm-6">
            <input id="email" class="form-control" name="email" type="type" value="<?php echo $email; ?>" placeholder="邮箱" readonly>
            <p class="help-block">登录邮箱不允许修改</p>
          </div>
        </div>
        <div class="form-group">
          <label for="slug" class="col-sm-3 control-label">别名</label>
          <div class="col-sm-6">
            <input id="slug" class="form-control" name="slug" type="type" value="<?php echo $slug; ?>" placeholder="slug">
            <p class="help-block">https://zce.me/author/<strong>zce</strong></p>
          </div>
        </div>
        <div class="form-group">
          <label for="nickname" class="col-sm-3 control-label">昵称</label>
          <div class="col-sm-6">
            <input id="nickname" class="form-control" name="nickname" type="type" value="<?php echo $nickname; ?>" placeholder="昵称">
            <p class="help-block">限制在 2-16 个字符</p>
          </div>
        </div>
        <div class="form-group">
          <label for="bio" class="col-sm-3 control-label">简介</label>
          <div class="col-sm-6">
            <textarea id="bio" class="form-control" placeholder="这个人很懒什么都没有留下" cols="30" rows="6"><?php echo $bio; ?></textarea>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-offset-3 col-sm-6">
            <button type="submit" class="btn btn-primary">更新</button>
            <a class="btn btn-link" href="password-reset.php">修改密码</a>
          </div>
        </div>
      </form>
    </div>
  </div>

  <?php $current_page = 'profile'; ?>
  <?php include 'inc/slider.php'; ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script src="/config.js"></script>
  <script>
    // 声明公用函数
    // ===========================================
    function showMsg(msg){
      $('#msg').html("<strong>错误！</strong>"+msg);
      $('#msg').show();
    }
    function hideMsg(){
      $('#msg').hide();
    }

    // 页面加载完成后执行
    // ===========================================
    $(function(){
      // 获取元素
      var msg = $('#msg');
      var avatar = $('#avatar');
      var email = $('#email');
      var slug = $('#slug');
      var nickname = $('#nickname');
      var bio = $('#bio');

      // 隐藏错误信息输出框
      msg.hide();

      // 为头像选择框注册事件
      avatar.on('change', function() {
        // 重置头像元素
        avatar = $(this);

        // 用户是否选择了头像
        var files = avatar.prop('files');
        if(!files.length > 0) return;

        // 获取文件
        var file = files[0];
        /* 
          lastModified: 1499760445000
          lastModifiedDate: Tue Jul 11 2017 16:07:25 GMT+0800 (中国标准时间) {}
          name: "avatar.jpg"
          size: 111756
          type: "image/jpeg"
          webkitRelativePath: ""
        */
        // 校验头像文件
        // 类型
        if(BX_ALLOWED_IMG_TYPE.indexOf(file.type) === -1){
          // 清空选择区
          avatar.val('');
          // 输出提示信息
          showMsg('图片格式不支持请重新选择');
        }
        // 大小
        if(file.size > BX_ALLOWED_IMG_SIZE){
          // 清空选择区
          avatar.val('');
          // 输出提示信息
          showMsg('图片过大不支持请重新新选择');
        }

        // 显示新头像
        var img_url = window.URL.createObjectURL(file);
        avatar.siblings('img').attr('src',img_url);

      });
    });

    // 本地校验信息

    // 本地预览头像
    // 通过ajax存储用户头像
    // $('#avatar').on('change', function() {
    //   var avatar = $(this);
    //   var files = avatar.prop('files');
    //   if(!files.length > 0) return;

    //   // 上传文件
    //   var formData = new FormData();
    //   formData.append('avatar',files[0]);
    //   $.ajax({
    //     url: '/admin/api/profile-avatar.php',
    //     cache: false,
    //     contentType: false,
    //     processData: false,
    //     data: formData,
    //     type: 'post',
    //     success: function(res){
    //       avatar.siblings('img').attr('src',res).fadeIn;
    //     }
    //   });
    // });
  </script>
  <script>
    NProgress.done()
  </script>
</body>

</html>