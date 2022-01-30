<?php
!defined('EMLOG_ROOT') && exit('error');
error_reporting(0); 
?>


<?php
function callback_init() {
	$plugin_storage = Storage::getInstance('plugin_hljs');
	
	// 默认设置 : 行号显示、vs高亮主题、外观扩展 Mac_light 预设
	$plugin_storage->setValue('isViewLine', 'y');
	$plugin_storage->setValue('hljsCssNum', '223');
	$plugin_storage->setValue('hljsCssUrl', BLOG_URL.'content/plugins/hljs/hljs_css/vs.min.css');
	$plugin_storage->setValue('customStyleUrl', BLOG_URL.'content/plugins/hljs/custom_preset/Mac_light.style');
}


