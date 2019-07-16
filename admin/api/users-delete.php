<?php
require_once '../../function.php';
// 防止sql注入
$idArr = explode(',', $_GET['del_id']);
if (count($idArr) == 1 && !((int)$idArr[0])) {
    header('Location: /admin/users.php?del_success=false');
    exit;
}
foreach ($idArr as $key => $value) {
    $idArr[$key] = (int)$value;
}
$idstr = implode(',', $idArr);

// 删除用户
$delete_user_sql = "DELETE FROM users WHERE id in ({$idstr});";
// $delete_user_sql = "DELETE FROM users WHERE id in (100);";
$delete_user_affected_rows = bx_delete_data_to_db($delete_user_sql);
$del_success = $delete_user_affected_rows?'true':'false';
header('Location: /admin/users.php?del_success='.$del_success);
