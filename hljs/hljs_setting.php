<?php
!defined('EMLOG_ROOT') && exit('error');
error_reporting(0); 
?>

<!-- hljs 代码高亮插件仪表盘的样式文件-->
<style>
	.copyright{display:none}
	.hljs_msg{display:initial;padding-left:10px;color:#1cc88a00;animation:myfirst 4s;}
	.custom_msg{display:initial;padding-left:10px;color:#1cc88a;}
	@keyframes myfirst{from {color:#1cc88a} to {color:#1cc88a00}}
	@media all and (max-width: 480px){.mh {display: none}}
</style>

<?php
function plugin_setting_view()

{
	$hljs_msg 		= '';
	$lineChecked	= '';
	$cssOpt 		= '';
	$getStyleOpt	= '';
	$plugin_storage = Storage::getInstance('plugin_hljs');  // 初始化 emlog 插件存储实例 plugin_hljs
	$getInfo		= isset($_GET["set_act"]) ? addslashes($_GET["set_act"]) : '';

	$dir = dirname(__FILE__);
	$cssFileArray = scandir($dir.'/hljs_css');
	$customStyleFileArray = scandir($dir.'/custom_preset');

	if ($getInfo == 'save') {  // 当接收到的 GET = 保存
		$getConfig = [
			'is_viewLine'	=> isset($_POST['is_viewLine']) ? addslashes($_POST['is_viewLine']) : 'n',
			'cssName'		=> isset($_POST['cssName']) ? addslashes($_POST['cssName']) : ''
		];
		$plugin_storage->setValue('isViewLine', $getConfig['is_viewLine']);
		$plugin_storage->setValue('hljsCssNum', $getConfig['cssName']);
		$plugin_storage->setValue('hljsCssUrl', BLOG_URL.'content/plugins/hljs/hljs_css/'.$cssFileArray[$getConfig['cssName']]);
		$hljs_msg = "保存成功！";
	}else{  // 当接收到的 GET != 保存
		$getConfig = [
			'is_viewLine'	=> $plugin_storage->getValue('isViewLine') ,
			'cssName'		=> $plugin_storage->getValue('hljsCssNum')
		];
	}

if($getConfig['is_viewLine'] == 'y'){  // 根据 emlog 插件存储实例中的内容，决定'显示行号'是否选中
	$lineChecked = 'checked="checked"';
}

foreach($cssFileArray as $x=>$css_name)  // 将样式条列入栈 $cssOpt
{
	$isSelect = '';
	if($x == $getConfig['cssName']){
		$isSelect = 'selected="selected"';
	}
	if($css_name !== '.' && $css_name !== '..' && $css_name !== '.DS_Store'){  // 过滤linux/unix系统里的.和..文件等
		$cssOpt = $cssOpt.'<option value="'.$x.'"'.$isSelect.'>'.substr($css_name,0,-8).'</option>';
	}
}

foreach($customStyleFileArray as $x=>$style_FileName)  // 将自定义扩展样式的文件条例存入栈 $getStyleOpt
{
	$isSelect = '';
	if(BLOG_URL.'content/plugins/hljs/custom_preset/'.$style_FileName == $plugin_storage->getValue('customStyleUrl')){
		$isSelect = 'selected="selected"';
	}
	if($style_FileName !== '.' && $style_FileName !== '..' && $style_FileName !== '.DS_Store'){  // 过滤linux/unix系统里的.和..文件等
		$getStyleOpt = $getStyleOpt.'<option value="'.$x.'"'.$isSelect.'>'.$style_FileName.'</option>';
	}
}

///////////////////////////////////////// - 设置后台输出 - /////////////////////////////////////////
?>
<!----------- 代码高亮插件的后台设置输出区 ---------->
	<div class="card" style="max-width: 900px;">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold">语法着色（hljs型）插件设置<span class="hljs_msg"><?php echo $hljs_msg; ?></span></h6>
		</div>
		<div style="display: flex">
			<div class="card-body" style="max-width: 300px;">
				<form action="plugin.php?plugin=hljs&set_act=save" method="post" name="input" id="input">
					<div class="form-check">
						<input class="form-check-input" type="checkbox" value="y" name="is_viewLine" id="is_viewLine" <?php echo $lineChecked; ?> />
						<label class="form-check-label">显示行号启用</label>
					</div>
					<hr width="250px" />
					选择高亮主题:
					<span style='float:right' class="mh">
						<a href="javascript:void(0)" onclick="changeCss(1)"><-</a>
						<a href="javascript:void(0)" onclick="changeCss(2)">-></a>
					</span>
					<div class="form-group" style="margin: 8px;">
						<select name="cssName" id="cssName" class="form-control" value='9'>
							<?php echo $cssOpt; ?>
						</select>
					</div>
					<br/>
						<input type="button" name="back" id="custom_plugin" value="外观扩展" class="btn btn-sm btn-success" data-toggle="modal" data-target="#custom">
						<input type="button" name="back" id="back" value="返回" onclick="window.location.href = 'plugin.php';" class="btn btn-sm btn-success">
						<input type="submit" value="保存" class="btn btn-sm btn-primary"/>
						<hr width="250px" />
				</form>

				<div class="test">
					版本V1.2 
					<span id="updata_msg"></span>
				</div>
			</div>
			<div class="card-body" style="max-width: 600px;padding:0" id="cssPreview">
			<iframe src="" class='mh' scrolling="no" style="height:253.5px;width: 100%" id="previewContent" name="content" frameborder="0" ></iframe>
			</div>
			<div class="mh" id="changeLang" style="position: absolute;top: 54px;right: 16px">
				预览：
				<select id="language" >
					<option value="cpp">C++</option>
					<option value="c">C</option>
					<option value="java">Java</option>
					<option value="php">PHP</option>
					<option value="js">javascript</option>
					<option value="html">html</option>
					<option value="python">python</option>
					<option value="r">R</option>
					<option value="go">GO</option>
					<option value="swift">Swift</option>
				</select>
			</div>
		</div>
	</div>
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header"><h4 class="modal-title" id="myModalLabel">更新详情</h4></div>
				<div class="modal-body" id="updateInfo"><a id="updateInfo">...</a></div>
				<div style="padding: 1rem">商店的插件版本已同步更新，您也可以点击上方地址下载更新包自行安装，如果您信任这些内容，请点击右下角"在线更新"</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
					<button type="button" class="btn btn-primary" id="goUpdate">在线更新</button>
				</div>
			</div>
		</div>
	</div>
	<!--- 外观扩展模态框 --->
		<div class="modal fade" id="custom" tabindex="-1" role="dialog" aria-labelledby="custom1" aria-hidden="true" data-backdrop="static">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header" style="justify-content: left;">
						<div>自定义外观扩展</div>&nbsp;
						<span id="custom_msg" class="custom_msg"></span>
					</div>
					<div class="modal-body">
						<div>
						<label for="name">预设</label>
							<select class="form-control" id="custom_preset" >
								<?php echo $getStyleOpt; ?>
							</select>
						</div>
						<br>
						<div class="form-group">
							<label for="name">Html</label>
							<textarea class="form-control" id="custom_preset_textarea" rows="12"></textarea>
						</div>
						<br>
						<div class="custom_tips">
							<b>说明：</b> 自定义外观扩展可在代码高亮的基础上，继续美化外观，也可辅助修复一些前台显示问题。
							因为 Html 代码会在前台“代码高亮 CSS 内容”后部输出。
							如果有好的创意，欢迎制作 .style 文件提交到 <a href="http://github.com/kohunglee/hljs">kohunglee/hljs</a> 的 Custom_preset 文件夹，我会及时更新到商店。
						<div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">
							关闭
						</button>
						<button type="button" id="customApply" class="btn btn-primary">
							应用 Ctrl+S
						</button>
					</div>
				</div>
			</div>
		</div>
	<!--- 外观扩展模态框（结束） --->
	<script>
	var version 		= 11;  // 这是当前版本号代码，是版本更新的依据
	var $CheckUpdate 	= $("#hljsCheckUpdate");  // '更新'的触发链接对象
	var updateServer	= "../content/plugins/hljs/pluginsUpdate.php";  // 更新服务程序地址

	var selectStyleName	= $('#custom_preset').find("option:selected").text();  // 当前选中的外观扩展名
	var styleContent	= $("#custom_preset_textarea").text();  // 当前选中的外观扩展 Html 内容

	$CheckUpdate.click(function(){  // 检查
		$CheckUpdate.html("查询中...");
			$.get(updateServer+'?act=check&ver='+version,function(data,status){
				switch(data){
					case '0':
						$CheckUpdate.html("目标服务器无响应...");
						break;
					case '1':
						$CheckUpdate.html("已是最新版本");				
						break;
					default:
						$CheckUpdate.html("可以更新，点击查看更新详情...")
									.attr("data-toggle", "modal")
									.attr("data-target", "#myModal")
									.attr("id", "getInfo");
						$("#updateInfo").html(data);
						break;
				}
			}).fail(function () {
				$CheckUpdate.html("找不到更新程序...");
			});
	});

	$("#goUpdate").click(function(){  // 更新
		$("#goUpdate").html("更新中...");
		$.post(updateServer+'?&ver='+version,
		{
			act:"goUpdate"
		},
		function(data){
			alert(data);
			location.reload();
		});
	});

	function changeCss(val){  // 改变主题(代码样式)
		let num = $('#cssName').val();
		if(val == 1 && $('option[value='+(num-1)+']').length != 0){
			num--;
			$("#cssName").val(num);
			previewCss();
		}
		if(val == 2 && $('option[value='+(num-(-1))+']').length != 0){
			num++;
			$("#cssName").val(num);
			previewCss();
		}
	}

	function previewCss(){  // 预览(代码样式)
		let isLine		= $('#is_viewLine').is(":checked")?'y':'n';
		let cssName		= $('#cssName').find("option:selected").text();
		let vLanguage	= $('#language').val();
		$('#previewContent').attr('src','../content/plugins/hljs/preview.php?isLine='+isLine+'&cssName='+cssName+'&vLanguage='+vLanguage);
	}
	function resetSelectVar(){  // 重置以上的那两个变量
		selectStyleName	= $('#custom_preset').find("option:selected").text();
		styleContent	= $("#custom_preset_textarea").val();
	}
	function getCustomStyleHtml(param){  // 获取自定义外观样式
		$.get("../content/plugins/hljs/hljs_customStyleHtml.php?act=getHtml&param=" + param,function(data,status){
			$("#custom_preset_textarea").val(data);
		})
	}
	function setCustomStyleHtml(param,html){  // 应用自定义外观样式
		$("#custom_msg").html("提交中");
		$.post("../content/plugins/hljs/hljs_customStyleHtml.php?act=setHtml&param=" + param,
		{
			writeHtml:html
		},
		function(data){
			if(data == "success"){
				getCustomStyleHtml(param)
				$("#custom_msg").html("应用成功！");
			}else{
				$("#custom_msg").html("应用失败，可能是网络问题......");
			}
		});
	}
	$('#is_viewLine,#cssName,#changeLang').change(function(){
		previewCss();
	});
	$('#custom_preset').change(function(){  
		resetSelectVar();
		getCustomStyleHtml(selectStyleName);
	});
	$("#customApply").click(function(){
		resetSelectVar();
		setCustomStyleHtml(selectStyleName,styleContent);
	});
	$('#custom_preset_textarea').bind('input propertychange', function(){
		$("#custom_msg").html("已修改");
	});
	document.addEventListener('keydown', function(e){  // 快捷键
		if (e.keyCode == 83 && (navigator.platform.match("Mac") ? e.metaKey : e.ctrlKey)){
			e.preventDefault();
			resetSelectVar();
			setCustomStyleHtml(selectStyleName,styleContent);
		}
	});
	$(document).ready(function(){
		previewCss();
		getCustomStyleHtml($("#custom_preset option[selected='selected']").html());
	});
	</script>
<!----------- 代码高亮插件的后台设置输出区（结束）---->
<?php
}


