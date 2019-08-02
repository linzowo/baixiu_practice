<?php 
// 项目配置文件

// 数据库信息============================

/**
 *  数据库host 
 */
define('BX_DB_HOST','localhost');
/**
 * 数据库用户名
 */
define('BX_DB_USER','root');
/**
 * 数据库密码
 */
define('BX_DB_PASS','111111');
/**
 * 数据库名称
 */
define('BX_DB_NAME','baixiu-dev');

/**
 * 允许上传的图像类型
 */
define('BX_ALLOWED_IMG_TYPE',json_encode(array('image/jpeg', 'image/png', 'image/gif', 'image/webp')));

/**
 * 允许上传的图片大小==>10M
 */
define('BX_ALLOWED_IMG_SIZE',10 * 1024 * 1024);