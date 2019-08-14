<?php

// 引入配置文件
require_once 'config.php';
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
 * @param string $sql 查询语句
 * @return array($conn,$res);
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
    return array($conn, $res);
}
/**
 * 获取数据库信息
 * @param string $sql==>sql查询语句
 * @return false|array
 * array[array[key:value],array[key:value],...]
 */
function bx_get_db_data($sql)
{
    $res = query($sql)[1];
    if (!$res) return false;

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
 * @param string $sql 查询语句
 * @return false|int $affected_rows 受影响的行数
 */
function bx_add_data_to_db($sql)
{
    $query = query($sql);
    if (!$query) return false;
    $conn = $query[0];
    // 获取受影响的行数
    $affected_rows = mysqli_affected_rows($conn);
    return $affected_rows;
}

/**
 * 修改数据传入数据库
 * @param string $sql 查询语句
 * @return false|int $affected_rows 受影响的行数
 */
function bx_edit_data_to_db($sql)
{
    return bx_add_data_to_db($sql);
}

/**
 * 从数据库删除数据
 * @param string $sql 查询语句
 * @return false|int $affected_rows 受影响的行数
 */
function bx_delete_data_to_db($sql)
{
    return bx_add_data_to_db($sql);
}

/**
 * 根据现有数据输出分页按钮
 * @param int $max_page 页面的最大页码数
 * @param string $format 连接模版，%d替换为具体数字
 * @param int $visibles 展示多少个分页按钮，默认展示5个
 * @example 
 * <?php bx_get_paging(10,'/list.php?page=%d',5); ?>
 */
function bx_get_paging($max_page, $format, $visibles = 5)
{
    // 获取数据库数据总条数===》解决查询范围溢出的问题
    // 获取最大页码
    $max_page = (int) $max_page;  // 最大页数
    // ==========================================================
    // 设置分页循环需要的参数
    $visibles = 5; // 可见页码
    $page = (int) (isset($_GET['page']) ? $_GET['page'] : 1); // 获取页码
    $region = (int) (($visibles - 1) / 2); // 左右区间
    $begin = (int) ($page - $region); // 开始页码
    $end = (int) ($begin + $visibles); // 结束页码
    $previous_page = ($page - 1) > 0 ? ($page - 1) : 1; // 上一页
    $next_page = ($page + 1) > $max_page ? $max_page : ($page + 1); // 下一页
    // ==========================================================
    // 前后一批数据
    if (($page - $region) > 1) {
        $before = $page - $visibles;
        if ($before < 1) {
            $before = 3;
        }
    }
    if (($max_page - $page) > $region) {
        $after = $page + $visibles;
        if ($after > $max_page) {
            $after = $max_page - $region;
        }
    }
    // ==========================================================
    // 根据页码限制开始和结束范围
    // $begin > 0;
    // $end < $max_page +1
    if ($page < 3) {
        $begin = 1;
        $end = $begin + $visibles;
    }
    if ($page > ($max_page - 2)) {
        $end = $max_page + 1;
        $begin = $end - $visibles;
        if ($begin < 0) {
            $begin = 1;
        }
    }
    // 输出页面按钮
    // 上一页
    if ($page > 1) {
        printf('<li><a href="%s">上一页</a></li>', sprintf($format, $previous_page));
    }
    // 省略号
    if (($page > 3) && ($max_page > 5)) {
        echo '<li><span>...</span></li>';
    }
    // 分页页码
    for ($i = $begin; $i < $end; $i++) {
        $activeClass = $page === $i ? " class='active'" : '';
        printf('<li%s><a href="%s">%s</a></li>', $activeClass, sprintf($format, $i), $i);
    }
    // 省略号 
    if (($page < ($max_page - $region)) && ($max_page > 5)) {
        echo '<li><span>...</span></li>';
    }
    // 下一页
    if ($page < $max_page) {
        printf('<li><a href="%s">下一页</a></li>', sprintf($format, $next_page));
    }
}

/**
 * 校验图片
 * @param string $file_name $_FILES中的key
 * @param string $upload_folder 存储图片的文件夹的相对路径
 * @return bool|string false | $img_path(图片的绝对路径)
 * @example bx_check_upload_img('avatar','../static/uploads')
 * 
 */
function bx_check_upload_img($file_name, $upload_folder)
{
    // 引入配置文件
    require_once 'config.php';
    
    if (!substr_count($_FILES[$file_name]['type'], 'image')) { // 检查是否是图片类型
        return false;
    }

    if ($_FILES[$file_name]['size'] > BX_ALLOWED_IMG_SIZE) { // 图片必须小于10m
        return false;
    }
    // 存储图片到指定位置
    $img_name = time() . '.' . pathinfo($_FILES[$file_name]['name'], PATHINFO_EXTENSION);
    $target_folder = "{$upload_folder}" . '/' . $img_name;
    $temp_file = $_FILES[$file_name]['tmp_name'];
    if (!move_uploaded_file($temp_file, $target_folder)) {
        return false;
    }
    // 相对路径==>绝对路径
    $path_arr = explode('../', $target_folder);
    $img_path = '/'.end($path_arr);
    return $img_path; // 返回图片在网站根目录地址===》/static/uploads/xxxx.jpg
}
