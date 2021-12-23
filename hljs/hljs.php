<?php
/*
Plugin Name: hljs语法着色+行号（一键换主题）
Version: 1.1
Description: 引用highlight.js，可对代码进行语法着色，行号显示
Author: 串串狗xk
Author URL: http://www.ccgxk.com
*/
!defined('EMLOG_ROOT') && exit('access deined!');
error_reporting(0);
/* 版本更新时，版本号要改三个地方 */
function hljs() {

	$plugin_storage = Storage::getInstance('plugin_hljs');  // 初始化emlog插件存储实例plugin_hljs

    $hljsCssUrl = $plugin_storage->getValue('hljsCssUrl');  // 获取高亮样式的地址
	$isViewLine = $plugin_storage->getValue('isViewLine');  // 获取是否显示行

	$css = file_get_contents($hljsCssUrl);  // 获取css文件内容，并正则替换将 class 'hljs' 的background的颜色加上 !important

	preg_match('/(?<=[\}\/]\.hljs\{).*?(?=\})/', $css, $cssInfo);
	preg_match('/(?<=background:).*?(?=;)/', $cssInfo[0], $colorInfo);
	if(isset($colorInfo[0])){
		$result = preg_replace('/'.$colorInfo[0].'/i', $colorInfo[0].'!important', $cssInfo[0]);
	}else{
		$result = $cssInfo[0].'!important';
	}
	$css = preg_replace('/'.$cssInfo[0].'/i', $result, $css);

?>
<!-----------代码高亮插件的前台输出区---------->
<style>
	pre,pre code {
		color: #ffffff00; /* 初始透明，更优雅 */
		border-radius:0;
		border:0 !important;
		outline:0;
		vertical-align:baseline;
		background:transparent;
	}
	pre{
		padding: 0 !important;
	}
	pre code {
		margin: 0!important;
	}
	<?php if($isViewLine == 'y'){ ?> 
	.hljs {
		border-radius: 0px!important;
	}
	.hljs-line-numbers{
		user-select: none;
		margin: 0!important;
    	padding-right: 0!important;
	}
	<?php } ?>
	<?php echo $css; ?>
</style>
<script src="<?php echo BLOG_URL; ?>content/plugins/hljs/hljs_src/highlight.min.js"></script>
<script src="<?php echo BLOG_URL; ?>content/plugins/hljs/LineNumber.js"></script>
<script>
	hljs.highlightAll()
	<?php if($isViewLine == 'y'){ ?> hljs.initLineNumbersOnLoad() <?php } ?>
</script>
<!-----------代码高亮插件的前台输出区（结束）---->

<?php
}

addAction('index_head', 'hljs');