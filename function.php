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
 * 连接数据库并建立查询
 */
function query($sql)
{
    // 引入配置文件
    require_once 'config.php';
    // 连接数据库
    $conn = mysqli_connect(BX_DB_HOST, BX_DB_USER, BX_DB_PASS, BX_DB_NAME);
    if (!$conn) { // 数据库连接失败
        exit("<h1>连接数据库失败，请重试！</h1>");
    }
    // 建立查询
    $res = mysqli_query($conn, $sql);
    if (!$res) {
        return false;
    }
    return array($conn,$res);

}
/**
 * 获取数据库信息
 * @param string $sql==>sql查询语句
 * @return false|array
 * ==>如果返回多条数据==>array[array[key:value],array[key:value],...]
 * ==>返回一条数据==>array[key:value]
 */
function bx_get_db_data($sql)
{
    $res = query($sql)[1];
    if(!$res) return false;
    
    while ($row = mysqli_fetch_assoc($res)) {
        $result[] = $row;
    }
    // 如果没有数据
    if (empty($result)) {
        return false;
    }
    // 返回结果数组
    return $result;
}
/**
 * 新增数据至数据库
 */
function bx_add_data_to_db($sql)
{
    $query = query($sql);
    if(!$query) return false;
    $conn = $query[0];
    // 获取受影响的行数
    $affected_rows = mysqli_affected_rows($conn);
    return $affected_rows;
}

/**
 * 修改数据传入数据库
 */
function bx_edit_data_to_db($sql)
{
    return bx_add_data_to_db($sql);
}

/**
 * 从数据库删除数据
 */
function bx_delete_data_to_db($sql)
{
    return bx_add_data_to_db($sql);
}
