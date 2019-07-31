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
$check_slug = bx_get_db_data("SELECT slug FROM posts WHERE slug = 'lcx';")[0];
if(!$check_slug){
  echo 'nihao';
}
var_dump($check_slug);
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

?>
<div class="lds-css ng-scope">
<div class="lds-spinner" style="width:100%;height:100%"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
<style type="text/css">@keyframes lds-spinner {
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
</style></div>