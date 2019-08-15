<?php 
require_once '../function.php';
bx_check_login_status();
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Slides &laquo; Admin</title>
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
        <h1>图片轮播</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <div class="alert alert-danger" style="display:none" id="msg">
        <strong>错误！</strong>发生XXX错误
      </div>
      <div class="row">
        <div class="col-md-4">
          <form>
            <h2>添加新轮播内容</h2>
            <div class="form-group">
              <label for="image">图片</label>
              <!-- show when image chose -->
              <img class="help-block thumbnail" style="display: none">
              <input id="image" class="form-control" name="image" type="file">
            </div>
            <div class="form-group">
              <label for="text">文本</label>
              <input id="text" class="form-control" name="text" type="text" placeholder="文本">
            </div>
            <div class="form-group">
              <label for="link">链接</label>
              <input id="link" class="form-control" name="link" type="text" placeholder="链接">
            </div>
            <div class="form-group">
              <a class="btn btn-primary" id="submit">添加</a>
            </div>
          </form>
        </div>
        <div class="col-md-8">
          <div class="page-action">
            <!-- show when multiple checked -->
            <a id="batch_deletion"class="btn btn-danger btn-sm" href="javascript:;" style="display: none">批量删除</a>
          </div>
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th class="text-center" width="40"><input type="checkbox"></th>
                <th class="text-center">图片</th>
                <th>文本</th>
                <th>链接</th>
                <th class="text-center" width="100">操作</th>
              </tr>
            </thead>
            <tbody>
              <!-- <tr>
                <td class="text-center"><input type="checkbox"></td>
                <td class="text-center"><img class="slide" src="/static/uploads/slide_1.jpg"></td>
                <td>XIU功能演示</td>
                <td>#</td>
                <td class="text-center">
                  <a href="javascript:;" class="btn btn-danger btn-xs">删除</a>
                </td>
              </tr>
              <tr>
                <td class="text-center"><input type="checkbox"></td>
                <td class="text-center"><img class="slide" src="/static/uploads/slide_2.jpg"></td>
                <td>XIU功能演示</td>
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

  <?php $current_page = 'slides'; ?>
    <?php include 'inc/slider.php'; ?>
  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script src="/static/assets/vendors/jsrender/jsrender.js"></script>
  <script id="options_tmpl" type="text/x-jsrender">
    {{for options}}
      <tr data-index="{{: #index}}">
        <td class="text-center"><input type="checkbox"></td>
        <td class="text-center"><img class="slide" src="{{:image}}"></td>
        <td>{{:text}}</td>
        <td>{{:link}}</td>
        <td class="text-center">
          <a href="javascript:;" class="btn btn-danger btn-xs btn-delete">删除</a>
        </td>
      </tr>
    {{/for}}
  </script>
  <script>
    $(function(){

      // 报错
      function notify(msg) {
        $('#msg').html("<strong>错误！</strong>" + msg).fadeIn();
        // 3000ms后隐藏
        setTimeout(function() {
          $('#msg').fadeOut();
        }, 3000);
      }


      // 动态加载数据
      // ==================================
      /**
       * 处理动态加载数据的逻辑
       * @param {function} callback 处理获取到的数据
       */
      function loadData(callback){
        $.get('/admin/options.php', {
          key: 'home_slides'
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

      // 首次调用加载数据逻辑
      loadData(function(err,data){
        if (err) return notify(err.message);

        $('tbody').fadeOut().html($('#options_tmpl').render({
          options: data
        })).fadeIn();
      });

      // 保存数据
      // ====================================
      /**
       * 存储数据逻辑
       */
      function saveData(data,callback){
        $.post('/admin/options.php', {
          key: 'home_slides',
          value: JSON.stringify(data)
        }, function(res) {
          // 操作失败===>返回错误信息
          if(!res.success) return callback(new Error(res.msg));
          
          // 操作成功就返回null
          callback(null);
        });
      }

      /**
       * 存储图片逻辑
       */
      function saveImg(file,callback){
        let formData = new FormData();
        formData.append('file',file);
        $.ajax({
          url: '/admin/api/slides-or-settings-img-save.php',
          cache: false,
          contentType: false,
          processData: false,
          data: formData,
          type: 'post',
          success: function (res) {
            if(!res.success) return callback(new Error('存储图片失败请重试'));

            callback(null,res.data);
          }
        })
      }

      /**
       * 新增数据
       */
      function addSlides(){
        // 获取按钮
        let btn = $('#submit');
        // 创建一个正则对照对象
        let regexpObj = {
          'text': /^[^\s]{1,16}$/,
          'link': /^https:\/\/[a-zA-Z0-9]+.[a-z]{2,5}$/
        }

        // 名称对照表
        let nameObj = {
          'text': '文本',
          'link': '连接'
        }
        // 为上传图片按钮注册点击事件
        btn.on('click',function(){
          // 存储错误信息的数组
          let msg = [];
          // 存储用户输入的对象
          let input = {};

          // 校验数据是否有效
          
          // 获取图片文件对象
          // let imgFile = $('form input:file')[0]['files'][0];
          let imgFile = $('form input:file').prop('files')[0];
          
          // 发起存储图片请求
          saveImg(imgFile,function(err,data){
            
            if(err) return notify(err.message);

            // 将图片信息存入结果对象
            input['image'] = data;
            
            // 遍历输入框
            $('form input:text').each(function(i, ele) {
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
            }// end if
  
            // 发起存储数据请求
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

              });// end saveData
            });// end loadData
          });// end saveImg
        });// end btn click
      }// end addSlides
      addSlides();

      // 删除数据
      // ===================================
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
      
      // 批量删除
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
      });// end 全选

      // 单选
      $('tbody').on('change','input',function(){
        // 获取当前点击的索引值
        let index = $(this).parent().parent().data('index');
        
        // 根据选择===>取消还是选中index
        // 取消
        if(!$(this).prop('checked')){
          deleteIndex.splice(deleteIndex.indexOf(index),1);
          checkedAll.prop('checked',false);
          deleteIndex.length !== 0?batchDeletion.show():batchDeletion.hide();
          return;
        }

        // 选中
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
      });//end 单选

      // 为批量删除按钮注册点击事件
      batchDeletion.on('click',function(){
        // 获取数据
        loadData(function(err,data){
          if(err) return notify(err.message);

          // 将所有要删除的索引按照倒序排列===》防止遍历删除的时候出现无法删除的bug
          deleteIndex.sort(function(a,b){ return b - a; });
          // 移除数据
          deleteIndex.forEach(function(ele){
            data.splice(ele,1);
          });

          // 将新数据存入数据库
          saveData(data,function(err){
           if(err) return notify(err.message);
           
           loadData(function(err){
            if(err) return notify(err.message);

            // 成功刷新页面数据
            $('tbody').fadeOut().html($('#options_tmpl').render({
              options: data
            })).fadeIn();

            // 清空删除数组
            deleteIndex = [];
            // 隐藏批量删除按钮
            batchDeletion.hide();
           });// end loadData
          });// end saveData
        });// end loadData
      });// end btn batchDeletion
    });// end $.(function)
  </script>
  <script>NProgress.done()</script>
</body>
</html>
