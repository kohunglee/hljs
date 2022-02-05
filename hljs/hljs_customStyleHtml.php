<?php
/**
 * 代码高亮（hljs）对自定义扩展样式的一些操作
 */

//判断是否是管理员登录状态
require_once '../../../init.php';
!(ROLE == ('admin' || 'founder')) && exit('非管理员权限！');
error_reporting(0);

$plugin_storage = Storage::getInstance('plugin_hljs');  // 初始化emlog插件存储实例 plugin_hljs

$getInfo		= isset($_GET["act"]) ? addslashes($_GET["act"]) : '';
$param		    = isset($_GET["param"]) ? addslashes($_GET["param"]) : '';

$writeHtml      = isset($_POST["writeHtml"]) ? addslashes($_POST["writeHtml"]) : '';

if($getInfo == 'getHtml'){  // 获取信息
    $url = BLOG_URL.'content/plugins/hljs/custom_preset/'.$param;
    echo stripslashes(file_get_contents($url));
}elseif($getInfo == 'setHtml'){  // 写入信息和将应用外观文件
    file_put_contents("custom_preset/".$param,$writeHtml);
    $plugin_storage->setValue('customStyleUrl', BLOG_URL.'content/plugins/hljs/custom_preset/'.$param);
    echo "success";
}else{
    exit('NULL');
}

?>
