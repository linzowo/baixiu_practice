<?php 
/**
 * 处理图片轮播设置中的图片校验以及保存
 */
// 载入依赖
require_once '../../function.php';

// 如果选择了文件:$_FILES['file']['error'] => 0
if(empty($_FILES['file']['error'])){
    $upload_folder = empty($_POST['upload_folder'])?'../../static/uploads':$_POST['upload_folder'];
    $img_path = bx_check_upload_img('file',$upload_folder);
}

// 设置返回头
header('Content-Type: application/json');

// 如果上传不成功 $img_path ==》 false 成功 就为图片的绝对路径
if(!$img_path){
    echo json_encode(array(
        'success' => false
    ));
    exit; // 结束执行
}

echo  json_encode(array(
    'success' => true,
    'data' => $img_path
));