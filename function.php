<?php

/**
 * 声明公共函数区域
 */

/**
 * 检查用户是否登录
 * 没有登录就跳转至登录界面
 */
function bx_check_login_status()
{
    // 启动session
    session_start();
    // 接收登录信息，如果没有登录就返回登录界面
    if (empty($_SESSION['user'])) {
        // 存储一个请求源===》方便登录之后返回此页面
        $_SESSION['source'] = $_SERVER['SCRIPT_NAME'];
        header("Location: /admin/login.php");
        exit;
    }
}

/**
 * 获取数据库信息
 * @param string $query==>sql查询语句
 * @return false|array
 * ==>如果返回多条数据==>array[array[key:value],array[key:value],...]
 * ==>返回一条数据==>array[key:value]
  */
function bx_get_db_data($query){
    // 引入配置文件
    require_once 'config.php';
    // 连接数据库
    $conn = mysqli_connect(BX_DB_HOST, BX_DB_USER, BX_DB_PASS, BX_DB_NAME);
    if (!$conn) { // 数据库连接失败
        exit("<h1>连接数据库失败，请重试！</h1>");
    }
    // 建立查询
    $res = mysqli_query($conn, $query);
    if (!$res) {
        return false;
    }

    while($row = mysqli_fetch_assoc($res)){
        $result[] = $row;
    }
    // 如果只有一条数据就返回这一条数据
    if(count($result) == 1){
        return $result[0];
    }
    // 如果是多条数据就返回结果数组
    return $result;

}
