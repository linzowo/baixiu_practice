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

$datestr = "2017-07-01 09:00:00";
$mytime = strtotime($datestr);
echo $mytime;
echo date("Y年-m月-d日\rH:i:s",$mytime);