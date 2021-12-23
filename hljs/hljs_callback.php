<?php
!defined('EMLOG_ROOT') && exit('error');
error_reporting(0); 
?>


<?php
function callback_init() {
	$plugin_storage = Storage::getInstance('plugin_hljs');
	
	// 默认主题 vs
	$plugin_storage->setValue('isViewLine', 'y');
	$plugin_storage->setValue('hljsCssNum', '225');
	$plugin_storage->setValue('hljsCssUrl', BLOG_URL.'content/plugins/hljs/hljs_css/vs.min.css');
}


