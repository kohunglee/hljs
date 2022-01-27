<?php
/**
 * 代码高亮（hljs）对自定义扩展样式的一些操作
 */

//判断是否是管理员登录状态
require_once '../../../init.php';
!(ROLE == 'admin') && exit('非管理员权限！');
error_reporting(E_ALL);

$getInfo		= isset($_GET["act"]) ? addslashes($_GET["act"]) : '';
$param		    = isset($_GET["param"]) ? addslashes($_GET["param"]) : '';

$writeHtml      = isset($_POST["writeHtml"]) ? addslashes($_POST["writeHtml"]) : '';

if($getInfo == 'getHtml'){  // 获取信息 
    echo "get";
    $url = BLOG_URL.'content/plugins/hljs/custom_preset/'.$param;
    echo file_get_contents($url);
}elseif($getInfo == 'setHtml'){  // 写入信息
    echo "set";
    file_put_contents("custom_preset/".$param,$writeHtml);
    echo "success";
}else{
    exit('NULL');
}

?>