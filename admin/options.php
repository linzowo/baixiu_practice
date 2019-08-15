<?php

// 引入依赖函数
require_once('../function.php');

// 确定返回数据类型===>json
header('Content-Type: application/json');

//  判断用户请求

// get请求
// ========================================
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    // 不带参数的请求==>查询全部数据
    if (empty($_GET['key'])) {
        $data = bx_get_db_data("SELECT * FROM `options`;");
        if(!$data) {
            echo json_encode(array(
                'success' => false
            ));
            exit;
        }
        echo json_encode(array(
            'success' => true,
            'data' => $data
        ));
        exit; // 不再向下执行
    }

    // 带参数的请求==>单独查询key的value
    // 根据key查询相应数据
    $data = bx_get_db_data(sprintf("SELECT `value` FROM `options` WHERE `key`='%s' LIMIT 1;", $_GET['key']));
    if ($data[0]['value']) { //数据存在
        echo json_encode(array(
            'success' => true,
            'data' => $data
        ));
        exit;
    } else { // 数据不存在
        echo json_encode(array(
            'success' => false,
            'msg' => 'option key does not exist',
        ));
        exit;
    }
}

// post请求
// ========================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 不带参数的请求
    if(empty($_POST['key']) || empty($_POST['value'])){
        echo json_encode(array(
            'success' => false,
            'msg' => 'option key and value required'
        ));
        exit;
    }

    // 带参数的请求
    
    // 处理多个key

    // 将key转为数组
    $key_arr = explode("',./'",$_POST['key']);
    $value_arr = explode("',./'",$_POST['value']);
    $msg_arr = array();

    if(count($key_arr) > 1){
        foreach ($key_arr as $key => $value) {
            // 将字符串转为数字
            if(substr($value,0,7) == 'comment'){
                $value_arr[$key] = (int) $value_arr[$key];
            }
            // 检查值是否存在
            $exist = bx_get_db_data(sprintf("SELECT count(1) FROM `options` WHERE `key`='%s';",$value))[0]['count(1)'] > 0;
            if($exist){
                // 存在==>修改
                $affected_rows = bx_edit_data_to_db(sprintf("UPDATE `options` SET `value` = '%s' WHERE `key` = '%s';",$value_arr[$key],$value));
            }else{
                // 不存在==>新增
                $affected_rows = bx_edit_data_to_db(sprintf("INSERT INTO `options` VALUES (null,'%s','%s');",$value,$value_arr[$key]));
            }
            if(!($affected_rows > 0)){
                $msg_arr[] = $value;
            }
        }
        // 存在错误信息
        if(count($msg_arr) > 0){
            echo json_encode(array(
                'success' => false,
                'msg' => $msg_arr
            ));
            exit;
        }

        // 没有错误信息
        echo json_encode(array(
            'success' => true
        ));
        exit;
    }

    // 处理单个key

    // 检查值是否存在
    $exist = bx_get_db_data(sprintf("SELECT count(1) FROM `options` WHERE `key`='%s';",$_POST['key']))[0]['count(1)'] > 0;
    if($exist){
        // 存在==>修改
        $affected_rows = bx_edit_data_to_db(sprintf("UPDATE `options` SET `value` = '%s' WHERE `key` = '%s';",$_POST['value'],$_POST['key']));
    }else{
        // 不存在==>新增
        $affected_rows = bx_edit_data_to_db(sprintf("INSERT INTO `options` VALUES (null,'%s','%s');",$_POST['key'],$_POST['value']));
    }
    echo json_encode(array(
        'success' => $affected_rows > 0
    ));

}
