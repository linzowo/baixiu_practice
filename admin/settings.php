<?php 
require_once '../function.php';
bx_check_login_status();
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Settings &laquo; Admin</title>
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
        <h1>网站设置</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <div class="alert alert-danger" id="msg" style="display:none">
        <strong>错误！</strong>发生XXX错误
      </div>
      <form class="form-horizontal">
        <div class="form-group">
          <label for="site_logo" class="col-sm-2 control-label">网站图标</label>
          <div class="col-sm-6">
            <input id="site_logo" name="site_logo" type="hidden">
            <label class="form-image">
              <input id="logo" type="file">
              <img src="/static/assets/img/logo.png">
              <i class="mask fa fa-upload"></i>
            </label>
          </div>
        </div>
        <div class="form-group">
          <label for="site_name" class="col-sm-2 control-label">站点名称</label>
          <div class="col-sm-6">
            <input id="site_name" name="site_name" class="form-control" type="type" placeholder="站点名称">
          </div>
        </div>
        <div class="form-group">
          <label for="site_description" class="col-sm-2 control-label">站点描述</label>
          <div class="col-sm-6">
            <textarea id="site_description" name="site_description" class="form-control" placeholder="站点描述" cols="30" rows="6"></textarea>
          </div>
        </div>
        <div class="form-group">
          <label for="site_keywords" class="col-sm-2 control-label">站点关键词</label>
          <div class="col-sm-6">
            <input id="site_keywords" name="site_keywords" class="form-control" type="type" placeholder="站点关键词">
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label">评论</label>
          <div class="col-sm-6">
            <div class="checkbox">
              <label><input id="comment_status" name="comment_status" type="checkbox" checked>开启评论功能</label>
            </div>
            <div class="checkbox">
              <label><input id="comment_reviewed" name="comment_reviewed" type="checkbox" checked>评论必须经人工批准</label>
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-6">
            <a id="submit" class="btn btn-primary">保存设置</a>
          </div>
        </div>
      </form>
    </div>
  </div>

  <?php $current_page = 'settings'; ?>
    <?php include 'inc/slider.php'; ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>
    // 显示错误信息
    function notify(msg) {
      $('#msg').html("<strong>错误！</strong>" + msg).fadeIn();
      // 3000ms后隐藏
      setTimeout(function() {
        $('#msg').fadeOut();
      }, 3000);
    }// end  function notify

    $(function(){
      // 显示原始设置
      // ==================================
      function loadData(callback){
        // 获取原始数据
        $.get('/admin/options.php',function(res){
          if(!res.success) return notify('获取数据失败请重试');
          callback(res.data);
        });// end get
      }// end function loadData

      // 首次调用加载数据逻辑
      loadData(function(data){
        // 获取元素
        let logoImg = $('#logo').siblings('img');

        // 页面中所有需要用到的key
        let keyArray = ['site_logo','site_name','site_description','site_keywords','comment_status','comment_reviewed'];

        data.forEach(function(ele){
          // key不在当前需要设置的范围===>执行下一次循环
          if(keyArray.indexOf(ele.key) === -1) return true;

          // key等于logo设置===>将展示图片换为数据值===>执行洗一次循环
          if(ele.key === 'site_logo'){
            logoImg.attr('src',ele.value);
            return true;
          }

          // key是设置checkbox的
          if(/^comment/.test(ele.key)){
            $('#'+ele.key).prop('checked',ele.value == true);
            return true;
          }
          
          // 其他文本输入框就直接显示数据值
          $('#'+ele.key).val(ele.value);
        });// end foreach

      });

      // TODO：存储新设置
      // =================================
      
      /**
      *存储数据
      *@param {string} key key组成的字符串中间用 , 隔开
      *@param {string} value value组成的字符串中间用 , 隔开
      *@param {function} callback 回调函数==>请求完成后执行
      */
      function saveData(key,value,callback){
        $.post('/admin/options.php',{
          key: key,
          value: value
        },function(res){
          if (!res.success) return callback(res.msg);
          
          callback(null)
        })
      }// end function saveData

      /**
      *存储图片
      *@param {object} file 图片对象
      *@param {function} callback 回调函数
      *@return {boolean} res.success 存储图片是否成功
      *@return {string} res.data 图片存储在服务器上的绝对路径
      */
      function saveImg(file,callback){
        let formData = new FormData();
        formData.append('file',file);
        formData.append('upload_folder',"../../static/assets/img");
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
        })// end $.ajax
      }// end function saveImg

      

      /**
      * 修改设置逻辑
      */
      function editSite(){
        // 获取元素
        let btn = $('#submit');

        // 绑定点击事件
        btn.on('click',function(){
          // 声明储存数据位置
          let keyArr = [];
          let valueArr = [];
          // 存储原始数据的对象
          let baseData = {};

          /**
          *为了解决因为存储图片的异步操作，导致无法将图片数据传递到后台，将存储图片之后的代码单独提取出来，方便多次调用
          */
          function saveAndShowDataAfterSaveImg(){
            // 获取元素
            let name = $('#site_name');
            let description = $('#site_description');
            let keywords = $('#site_keywords');
            let status = $('#comment_status');
            let reviewed = $('#comment_reviewed');
            
            let objArr = [name,description,keywords,status,reviewed];
            
            // 校验数据
            for(var i = 0 ;i < objArr.length; i++){
              let obj = objArr[i];
              let key = obj.attr('id');
              let value = obj.val();
              if(i < 3){
                if(value && (value !== baseData[key])){
                  keyArr.push(key);
                  valueArr.push(value);
                } // end if
              }else{
                keyArr.push(key);
                valueArr.push(obj.prop('checked') == true?1:0);
              } // end if
            }// end for
            
            // 存储数据===>使用“ ',./' ”作为分隔符是为了防止在存储数据的时候将数据进行错误的拆分
            let keyStr = keyArr.join("',./'");
            let valueStr = valueArr.join("',./'");
            
            saveData(keyStr,valueStr,function(errArr){
              // 存在错误信息
              if(errArr){
                // 名称对照对象
                let nameObj = {
                  "site_name":"网站名称",
                  "site_description":"站点描述",
                  "site_keywords":"站点关键词",
                  "comment_status":"开启评论",
                  "comment_reviewed":"评论经过人工批准"
                };
                let msg = [];
                errArr.forEach(function(ele){
                  msg.push(nameObj[ele]);
                });// end forEach
                // msgStr = msg.join(',');
                $('#msg').html("<strong>提示！</strong>" + "以下内容未做修改：" + msg).fadeIn();
                // 3000ms后隐藏
                setTimeout(function() {
                  $('#msg').fadeOut();
                }, 10000);
              } // end if

              // 再次加载页面数据
              loadData(function(data){
                // 获取元素
                let logoImg = $('#logo').siblings('img');

                // 页面中所有需要用到的key
                let keyArray = ['site_logo','site_name','site_description','site_keywords','comment_status','comment_reviewed'];

                data.forEach(function(ele){
                  // key不在当前需要设置的范围===>执行下一次循环
                  if(keyArray.indexOf(ele.key) === -1) return true;

                  // key等于logo设置===>将展示图片换为数据值===>执行洗一次循环
                  if(ele.key === 'site_logo'){
                    logoImg.attr('src',ele.value);
                    return true;
                  }

                  // key是设置checkbox的
                  if(/^comment/.test(ele.key)){
                    $('#'+ele.key).prop('checked',ele.value == true);
                    return true;
                  }
                  
                  // 其他文本输入框就直接显示数据值
                  $('#'+ele.key).val(ele.value);
                });// end foreach

              }); // end loadData
            }); // end saveData
          }// end saveAndShowDataAfterSaveImg

          // 获取数据库数据
          loadData(function(data){       
            data.forEach(function(ele){
              baseData[ele.key] = ele.value;
            });// end foreach
          })

          // 校验并存储图片
          let imgFile = $('#logo').prop('files')[0];
          // 选择了图片
          if(imgFile){
            // 存储图片
            saveImg(imgFile,function(err,data){
              // 存储失败==>结束执行显示错误
              if(err) return notify(err.message);
              // 存储成功
              keyArr.push('site_logo');
              valueArr.push(data);

              saveAndShowDataAfterSaveImg()
              // 执行完成后不再向下执行
              return;
            }); // end saveImg
          }

          // 没有选择图片
          saveAndShowDataAfterSaveImg()
          
        }); // end btn.on('click'
      } // end function editSite

      // 调用存储数据逻辑
      // saveData();
      editSite();


    });// end $(function)
  </script>
  <script>NProgress.done()</script>
</body>
</html>
