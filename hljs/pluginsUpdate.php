<?php
/**
 * 代码高亮（hljs）专用插件更新程序
 */

//判断是否是管理员登录状态
require_once '../../../init.php';
!(ROLE == 'admin') && exit('非管理员权限！');
error_reporting(0); 

//定义远程服务器的json地址
$json_url = "http://k.dushangself.site/MyPluginsFile/hljs.json?".time();

/**
 * json格式：last_ver（最新版本号）、last_ver_info（最新版本的说明）、last_ver_url（最新版本的下载地址）
 * 具体示例：{"last_ver":11,"last_ver_info":"<h3>新版本<h3>修复了一些bug.","last_ver_url":"http://examp.com/a.zip"}
 */

//curl获取最新json信息
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $json_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
if(curl_exec($ch) === false) {
    exit('0');
}
$json = curl_exec($ch);
curl_close($ch);

//获取get,post
$ver = isset($_GET['ver']) ? htmlspecialchars($_GET['ver']) : '';
$get_act = isset($_GET['act']) ? htmlspecialchars($_GET['act']) : '';
$post_act = isset($_POST['act']) ? htmlspecialchars($_POST['act']) : '';

//检查更新（计算是否是最新版本）
if ($get_act == 'check' and $ver != '' ) {  
	if($ver < json_decode($json,true)["last_ver"]) {
		echo json_decode($json,true)["last_ver_info"];
	} else {
		echo "1";
	}
}

// 下载远程文件函数
function downloadFile($source)
{
    $temp_file = tempnam(EMLOG_ROOT. '/content/cache/', 'emtemp_');
	$wh = fopen($temp_file, 'w+b');

	$timeout = ['http' => ['timeout' => 60]];//超时时间，单位为秒
	$ctx = stream_context_create($timeout);
	$rh = fopen($source, 'rb', false, $ctx);

	if (!$rh || !$wh) {
		return FALSE;
	}

	while (!feof($rh)) {
		if (fwrite($wh, fread($rh, 4096)) === FALSE) {
			return FALSE;
		}
	}
	fclose($rh);
	fclose($wh);
	return $temp_file;
}

// 一键更新（获取文件地址并更新本地文件）
if ($post_act == "goUpdate" and $ver < json_decode($json,true)["last_ver"]) {
    $temp_file = downloadFile(json_decode($json,true)["last_ver_url"]);
	if (!$temp_file) {
		exit('error_down');
	}
	$ret = emUnZip($temp_file, './', 'update');
	switch ($ret) {
		case 1:
		case 2:
			exit('error_dir');
			break;
		case 3:
			exit('error_zip');
			break;
	}
    exit('更新成功！');
}
?>
