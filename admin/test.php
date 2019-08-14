<!-- <body>
  <div class="father"></div>
</body> -->

<script src="../static/assets/vendors/jquery/jquery.js"></script>
<script>
  // for(var i = 0 ;i < 10; i++){
  //   // 动态生成子元素
  //   var children = $('<div class="children">你好啊</div>')
  //   // 将子元素加入父元素
  //   children.appendTo($('.father'));
  // }
  // // 为子元素注册事件
  // $('.father').on('click','.children',function(){
  //   // 输出所有子元素
  //   console.log($('.father .children'));
  // });
  //   localStorage.setItem('page',1);

  // if(localStorage.getItem('page')){
  //   console.log(localStorage.getItem('page'));
  // }else{
  //   localStorage.setItem('page',1);
  // }
  // console.log(window.location.href);
  // console.log(self.location.href);
  // console.log(document.URL);
  // console.log(document.location);
  // var obj = new Object();
  // if(!obj.abc){
  //   console.log('hh');
  // }
  // obj.abc = 'abc';
  // if(obj.abc){

  //   console.log(obj.abc);
  // }
  // var divobj = $('<div id="preview"><img src="'+this.img_src+'" class="img-responsive"  style="width: 100%;height: auto;" alt="" title=""><input></div>');
  // // var input = $('<input>').appendTo(divobj);
  // // input.appendTo(divobj);
  // console.log(divobj.children('input')[0]);
  
  // var array = ['ni','hao'];
  // console.log(Object.prototype.toString.call(array));

  // var a = [];
  // if(Object.prototype.toString.call(a) === '[object Array]'){
  //   console.log('是array');
  // }else{
  //   console.log('不是');
  // }
</script>

<?php 
require_once '../function.php';
// $numstr = '你好';
// echo (int)$numstr;


// $edit_sql = "SELECT * FROM categories WHERE `id`= '1';";
// $edit_res = bx_get_db_data($edit_sql);
// var_dump($edit_res);

// if(!$edit_res) {
//     echo '查询失败';
// };



// $get_users_data_sql = "SELECT * FROM users;";
// $get_users_data = bx_get_db_data($get_users_data_sql);
/* 
array(3) {
  [0]=&gt;
  array(8) {
    ["id"]=&gt;
    string(1) "1"
    ["slug"]=&gt;
    string(5) "admin"
    ["email"]=&gt;
    string(16) "admin@linzowo.me"
    ["password"]=&gt;
    string(6) "111111"
    ["nickname"]=&gt;
    string(9) "管理员"
    ["avatar"]=&gt;
    string(26) "/static/uploads/avatar.jpg"
    ["bio"]=&gt;
    NULL
    ["status"]=&gt;
    string(9) "activated"
  }
  [1]=&gt;
  array(8) {
    ["id"]=&gt;
    string(1) "2"
    ["slug"]=&gt;
    string(7) "linzowo"
    ["email"]=&gt;
    string(12) "w@linzowo.me"
    ["password"]=&gt;
    string(6) "111111"
    ["nickname"]=&gt;
    string(9) "林除夕"
    ["avatar"]=&gt;
    string(26) "/static/uploads/avatar.jpg"
    ["bio"]=&gt;
    NULL
    ["status"]=&gt;
    string(9) "activated"
  }
  [2]=&gt;
  array(8) {
    ["id"]=&gt;
    string(1) "3"
    ["slug"]=&gt;
    string(3) "ice"
    ["email"]=&gt;
    string(12) "ice@wedn.net"
    ["password"]=&gt;
    string(6) "111111"
    ["nickname"]=&gt;
    string(6) "小冰"
    ["avatar"]=&gt;
    string(26) "/static/uploads/avatar.jpg"
    ["bio"]=&gt;
    NULL
    ["status"]=&gt;
    string(9) "activated"
  }
}

*/


// $check_slug_sql = "SELECT slug FROM users WHERE slug = 'iabc';";
// $all_slug_in_db = bx_get_db_data($check_slug_sql);
// var_dump($all_slug_in_db);
// array(3) { [0]=> array(1) { ["slug"]=> string(5) "admin" } [1]=> array(1) { ["slug"]=> string(3) "ice" } [2]=> array(1) { ["slug"]=> string(7) "linzowo" } }

// var_dump((bool)'false');
// var_dump((bool)'true');
// var_dump((bool)0);
// var_dump((bool)1);
// var_dump((bool)2);

/* 
$get_posts_sql = "SELECT * FROM posts;";
$posts = bx_get_db_data($get_posts_sql);

array(4) {
  [0]=&gt;
  array(11) {
    ["id"]=&gt;
    string(1) "1"
    ["slug"]=&gt;
    string(11) "hello-world"
    ["title"]=&gt;
    string(15) "世界，你好"
    ["feature"]=&gt;
    string(29) "/uploads/2017/hello-world.jpg"
    ["created"]=&gt;
    string(19) "2017-07-01 08:08:00"
    ["content"]=&gt;
    string(102) "欢迎使用阿里百秀。这是您的第一篇文章。编辑或删除它，然后开始写作吧！"
    ["views"]=&gt;
    string(3) "222"
    ["likes"]=&gt;
    string(3) "111"
    ["status"]=&gt;
    string(9) "published"
    ["user_id"]=&gt;
    string(1) "1"
    ["category_id"]=&gt;
    string(1) "1"
  }
  [1]=&gt;
  array(11) {
    ["id"]=&gt;
    string(1) "2"
    ["slug"]=&gt;
    string(13) "simple-post-2"
    ["title"]=&gt;
    string(21) "第一篇示例文章"
    ["feature"]=&gt;
    NULL
    ["created"]=&gt;
    string(19) "2017-07-01 09:00:00"
    ["content"]=&gt;
    string(51) "欢迎使用阿里百秀。这是一篇示例文章"
    ["views"]=&gt;
    string(3) "123"
    ["likes"]=&gt;
    string(2) "10"
    ["status"]=&gt;
    string(7) "drafted"
    ["user_id"]=&gt;
    string(1) "1"
    ["category_id"]=&gt;
    string(1) "1"
  }
  [2]=&gt;
  array(11) {
    ["id"]=&gt;
    string(1) "3"
    ["slug"]=&gt;
    string(13) "simple-post-3"
    ["title"]=&gt;
    string(21) "第二篇示例文章"
    ["feature"]=&gt;
    NULL
    ["created"]=&gt;
    string(19) "2017-07-01 12:00:00"
    ["content"]=&gt;
    string(51) "欢迎使用阿里百秀。这是一篇示例文章"
    ["views"]=&gt;
    string(2) "20"
    ["likes"]=&gt;
    string(3) "120"
    ["status"]=&gt;
    string(7) "drafted"
    ["user_id"]=&gt;
    string(1) "1"
    ["category_id"]=&gt;
    string(1) "2"
  }
  [3]=&gt;
  array(11) {
    ["id"]=&gt;
    string(1) "4"
    ["slug"]=&gt;
    string(13) "simple-post-4"
    ["title"]=&gt;
    string(21) "第三篇示例文章"
    ["feature"]=&gt;
    NULL
    ["created"]=&gt;
    string(19) "2017-07-01 14:00:00"
    ["content"]=&gt;
    string(51) "欢迎使用阿里百秀。这是一篇示例文章"
    ["views"]=&gt;
    string(2) "40"
    ["likes"]=&gt;
    string(3) "100"
    ["status"]=&gt;
    string(7) "drafted"
    ["user_id"]=&gt;
    string(1) "1"
    ["category_id"]=&gt;
    string(1) "3"
  }
}

*/

// $datestr = "2017-07-01 09:00:00";
// $mytime = strtotime($datestr);
// echo $mytime;
// echo date("Y年-m月-d日",$mytime);
// echo '<br/>';
// echo date("H:i:s",$mytime);
// echo (string)date("Y年-m月-d日<br>H:i:s",$mytime);

// var_dump(100.4>=(1004/10));
// var_dump((int)(1004/10));
// var_dump(ceil(1004/10));

// $str = '你';
// echo strlen($str);
// $check_slug = bx_get_db_data("SELECT slug FROM posts WHERE slug = 'lcx';")[0];
// if(!$check_slug){
//   echo 'nihao';
// }
// var_dump($check_slug);
// $categories_arr = bx_get_db_data("SELECT * FROM categories;");
// // var_dump(in_array('1',$categories_arr));
// foreach ($categories_arr as $key => $value) {
//   if(in_array('1',$value)){
//     var_dump('hh');
//     break;
//   }
// }
/* 
array(7) {
  [0]=>
  array(3) {
    ["id"]=>
    string(1) "1"
    ["slug"]=>
    string(13) "uncategorized"
    ["name"]=>
    string(9) "未分类"
  }
  [1]=>
  array(3) {
    ["id"]=>
    string(1) "2"
    ["slug"]=>
    string(5) "funny"
    ["name"]=>
    string(9) "奇趣事"
  }
  [2]=>
  array(3) {
    ["id"]=>
    string(1) "3"
    ["slug"]=>
    string(6) "living"
    ["name"]=>
    string(9) "会生活"
  }
  [3]=>
  array(3) {
    ["id"]=>
    string(1) "4"
    ["slug"]=>
    string(6) "travel"
    ["name"]=>
    string(9) "爱旅行"
  }
  [4]=>
  array(3) {
    ["id"]=>
    string(2) "38"
    ["slug"]=>
    string(3) "adc"
    ["name"]=>
    string(10) " 林除夕"
  }
  [5]=>
  array(3) {
    ["id"]=>
    string(2) "39"
    ["slug"]=>
    string(4) "adc1"
    ["name"]=>
    string(11) " 林除夕1"
  }
  [6]=>
  array(3) {
    ["id"]=>
    string(2) "41"
    ["slug"]=>
    string(4) "adc3"
    ["name"]=>
    string(10) "林除夕3"
  }
}
*/
// session_start();
// var_dump($_SESSION['user']['id']);

// $slug = 'slug';
// $title = 'title';
// $feature = '123';
// $created = 'created';
// $content = 'content';
// $status = 'status';
// $user_id = '1';
// $category_id = '10';
// $add_sql = sprintf(
//   "insert into posts values (null, '%s', '%s', '%s', '%s', '%s', 0, 0, '%s', %d, %d)",
//   $slug,
//   $title,
//   $feature,
//   $created,
//   $content,
//   $status,
//   $user_id,
//   $category_id
// );
// var_dump($add_sql);
/* 
insert into posts values (null, 'lcx', '达所发生的法师法师法地方撒放水阀发的发疯', '/static/uploads/1564029931.jpeg', '2019-07-27T00:00', '
$add_sql = sprintf(
  "insert into posts values (null, '%s', '%s', '%s', '%s', '%s', 0, 0, '%s', %d, %d)",
  $slug,
  $title,
  $feature,
  $created,
  $content,
  $status,
  $user_id,
  $category_id
);', 0, 0, 'drafted', 1, 38)
string(399) "insert into posts values (null, 'lcx', '达所发生的法师法师法地方撒放水阀发的发疯', '/static/uploads/1564029931.jpeg', '2019-07-27T00:00', '
$add_sql = sprintf(
  "insert into posts values (null, '%s', '%s', '%s', '%s', '%s', 0, 0, '%s', %d, %d)",
  $slug,
  $title,
  $feature,
  $created,
  $content,
  $status,
  $user_id,
  $category_id
);', 0, 0, 'drafted', 1, 38)"




  
*/
// 相对路径==>绝对路径
// $target_folder = "../../baixiu/admin/profile.php";
// $path_arr = explode('/',$target_folder);
// var_dump($path_arr);
// foreach ($path_arr as $key => $value) {
//     if($value === '..'){
//         unset($path_arr[$key]);
//     }
// }
// $img_path = '/'.join('/',$path_arr);
// echo $img_path;

// $target_folder = "../../baixiu/admin/profile.php";
// $path_arr = explode('../',$target_folder);
// var_dump($path_arr);
// $img_path = end($path_arr);
// echo $img_path;