<?php
require_once '../function.php';
bx_check_login_status();
?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
  <meta charset="utf-8">
  <title>Navigation menus &laquo; Admin</title>
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
    <nav class="navbar">
      <button class="btn btn-default navbar-btn fa fa-bars"></button>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="profile.php"><i class="fa fa-user"></i>个人中心</a></li>
        <li><a href="logout.php"><i class="fa fa-sign-out"></i>退出</a></li>
      </ul>
    </nav>
    <div class="container-fluid">
      <div class="page-title">
        <h1>导航菜单</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <div class="alert alert-danger" id="msg" style="display:none">
        <strong>错误！</strong>发生XXX错误
      </div>
      <div class="row">
        <div class="col-md-4">
          <form>
            <h2>添加新导航链接</h2>
            <div class="form-group">
              <label for="icon">图标 Class</label>
              <input id="icon" class="form-control" name="icon" type="text" placeholder="图标 Class">
            </div>
            <div class="form-group">
              <label for="text">文本</label>
              <input id="text" class="form-control" name="text" type="text" placeholder="文本">
            </div>
            <div class="form-group">
              <label for="title">标题</label>
              <input id="title" class="form-control" name="title" type="text" placeholder="标题">
            </div>
            <div class="form-group">
              <label for="link">链接</label>
              <input id="link" class="form-control" name="link" type="text" placeholder="链接">
            </div>
            <div class="form-group">
              <a class="btn btn-primary" href="javascript:;" id="submit">添加</a>
            </div>
          </form>
        </div>
        <div class="col-md-8">
          <div class="page-action">
            <!-- show when multiple checked -->
            <a class="btn btn-danger btn-sm" href="javascript:;" style="display: none" id="batch_deletion">批量删除</a>
          </div>
          <table class="table table-striped table-bordered table-hover">
              <thead>
                <tr>
                <th class="text-center" width="40"><input type="checkbox"></th>
                <th>文本</th>
                <th>标题</th>
                <th>链接</th>
                <th class="text-center" width="100">操作</th>
              </tr>
            </thead>
            <tbody>
              <!-- <tr>
                <td class="text-center"><input type="checkbox"></td>
                <td><i class="fa fa-glass"></i>奇趣事</td>
                <td>奇趣事</td>
                <td>#</td>
                <td class="text-center">
                  <a href="javascript:;" class="btn btn-danger btn-xs">删除</a>
                </td>
              </tr>
              <tr>
                <td class="text-center"><input type="checkbox"></td>
                <td><i class="fa fa-phone"></i>潮科技</td>
                <td>潮科技</td>
                <td>#</td>
                <td class="text-center">
                  <a href="javascript:;" class="btn btn-danger btn-xs">删除</a>
                </td>
              </tr>
              <tr>
                <td class="text-center"><input type="checkbox"></td>
                <td><i class="fa fa-fire"></i>会生活</td>
                <td>会生活</td>
                <td>#</td>
                <td class="text-center">
                  <a href="javascript:;" class="btn btn-danger btn-xs">删除</a>
                </td>
              </tr> -->
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <?php $current_page = 'nav-menus'; ?>
  <?php include 'inc/slider.php'; ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script src="/static/assets/vendors/jsrender/jsrender.js"></script>
  <script id="options_tmpl" type="text/x-jsrender">
    {{for options}}
    <tr data-index="{{: #index}}">
      <td class="text-center"><input type="checkbox"></td>
      <td><i class="{{:icon}}"></i>{{:text}}</td>
      <td>{{:title}}</td>
      <td>{{:link}}</td>
      <td class="text-center">
        <a href="javascript:;" class="btn btn-danger btn-xs btn-delete">删除</a>
      </td>
    </tr>
    {{/for}}
  </script>
  <script>
    // 显示错误信息
    function notify(msg) {
      $('#msg').html("<strong>错误！</strong>" + msg).fadeIn();
      // 3000ms后隐藏
      setTimeout(function() {
        $('#msg').fadeOut();
      }, 3000);
    }

    // 页面加载完成后执行
    // ============================
    $(function() {

      // 动态加载设置数据
      // =======================================
      /**
       * 获取nav_menus的数据
       * @param {function} callback 处理获取到的数据
       */
      function loadData(callback) {
        $.get('/admin/options.php', {
          key: 'nav_menus'
        }, function(res) {
          if (!res['success']) { // 如果返回结果失败
            return callback(new Error(res['msg']));
          }

          var menus = []; // 声明一个空数组接收返回数据

          try {
            // 尝试以json方式解析数据
            menus = JSON.parse(res['data'][0]['value']);
          } catch {
            callback(new Error('获取数据失败'))
          }
          callback(null, menus);
        });
      }
      
      // 首次调用数据加载
      loadData(function(err, data) {
        if (err) return notify(err.message);
        
        $('tbody').fadeOut().html($('#options_tmpl').render({
          options: data
        })).fadeIn();
      });


      //  新增设置
      // ====================================
      /**
       * 存储数据逻辑
       */
      function saveData(data,callback){
        $.post('/admin/options.php', {
          key: 'nav_menus',
          value: JSON.stringify(data)
        }, function(res) {
          // 操作失败===>返回错误信息
          if(!res.success) return callback(new Error(res.msg));
          
          // 操作成功就返回null
          callback(null);
        });
      }

      function addNavLink() {
        // 获取按钮
        let btn = $('#submit');
        // 创建一个正则对照对象
        let regexpObj = {
          'icon':/^[f][a]\s[a-z\-]+$/,
          'text': /^[^\s]{1,16}$/,
          'title': /^[^\s]{1,16}$/,
          'link': /^\/[a-z0-9]+\/[a-z0-9]+$/
        }

        // 名称对照表
        let nameObj = {
          'icon':'图标',
          'text': '文本',
          'title': '标题',
          'link': '连接'
        }

        btn.on('click', function() {
          // 存储错误信息的数组
          let msg = [];
          // 存储用户输入的对象
          let input = {};

          // 遍历输入框
          $('form input').each(function(i, ele) {
            let id = $(ele).attr('id');
            if (!regexpObj[id].test($(ele).val())) {
              msg.push(nameObj[id]);
            }
            input[id] = $(ele).val();
          });

          // 检查是否有报错
          if (msg.length !== 0) {
            notify('以下内容不符合规范请修改：' + msg.join(','));
            return; // 结束执行
          }

          // 发起请求 存储数据
          loadData(function(err, data) {
            // 获取数据失败就返回错误信息
            if(err) return notify(err.message);

            // 将数据推入原始数据中
            data.push(input);

            // 发起请求更新数据
            saveData(data,function(err){

              if(err) return notify(err.message);

              // 成功刷新页面数据
              $('tbody').fadeOut().html($('#options_tmpl').render({
                options: data
              })).fadeIn();

              // 清空新增表单
              // $('form input').each(function(i, ele) {
              //   $(ele).val('');
              // });

            });
          })
        });
      }
      
      // 调用新增逻辑
      addNavLink();

      // 单条删除
      // ========================================
      // 注册点击事件
      $('tbody').on('click','.btn-delete',function(){
        // 获取要删除的index
        let index = parseInt($(this).parent().parent().data('index'));

        // 获取现有表单数据
        loadData(function(err,data){
          if(err) return notify(err.message);

          // 移除数据
          data.splice(index,1);

          // 将新数据存入数据库
          saveData(data,function(err){
           if(err) return notify(err.message);
           
           loadData(function(err){
            if(err) return notify(err.message);

            // 成功刷新页面数据
            $('tbody').fadeOut().html($('#options_tmpl').render({
              options: data
            })).fadeIn();
           });
          });
        });
        
      });
      
      // TODO: 批量删除
      // ================================================
      // 删除按钮
      var batchDeletion = $('#batch_deletion');

      // 必要元素
      var checkedAll = $('thead input');

      // 结果数组
      var deleteIndex = [];

      // 注册事件

      // 全选
      checkedAll.on('change',function(){
        $('tbody input').prop('checked',$(this).prop('checked')).change();
      });

      // 单选
      $('tbody').on('change','input',function(){
        // 获取当前点击的索引值
        let index = $(this).parent().parent().data('index');
        
        // 根据选择===>新增还是删除index
        // 删除
        if(!$(this).prop('checked')){
          deleteIndex.splice(deleteIndex.indexOf(index),1);
          checkedAll.prop('checked',false);
          deleteIndex.length !== 0?batchDeletion.show():batchDeletion.hide();
          return;
        }

        // 新增
        deleteIndex.indexOf(index) == -1?deleteIndex.push(index):'';

        // 默认全选
        checkedAll.prop('checked',true);

        // 检查是否为全选
        $('tbody input').each(function(i,ele){
          if(!$(ele).prop('checked')){
            checkedAll.prop('checked',false);
            return false;
          }
        });
        // 检测deleteindex是否为空
        deleteIndex.length !== 0?batchDeletion.show():batchDeletion.hide();
        
      });


    });
  </script>
  <script>
    NProgress.done()
  </script>
</body>

</html>