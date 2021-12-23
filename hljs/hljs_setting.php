<?php
!defined('EMLOG_ROOT') && exit('error');
error_reporting(0); 
?>

<!--hljs代码高亮插件仪表盘的样式文件-->
<style>
	.copyright{display:none}
	.hljs_msg{padding-left:10px;color:#1cc88a00;animation:myfirst 4s;}
	@keyframes myfirst{from {color:#1cc88a} to {color:#1cc88a00}}
	@media all and (max-width: 480px){.mh {display: none}}
</style>

<?php
function plugin_setting_view()
{
	$hljs_msg 		= '';
	$lineChecked	= '';
	$cssOpt 		= '';
	$plugin_storage = Storage::getInstance('plugin_hljs');  // 初始化emlog插件存储实例plugin_hljs

	$dir = dirname(__FILE__);
	$cssFileArray = scandir($dir.'/hljs_css');

	if ($_GET["set_act"] == 'save') {  // 当接收到的GET = 保存
		$getConfig = [
			'is_viewLine'	=> isset($_POST['is_viewLine']) ? addslashes($_POST['is_viewLine']) : 'n',
			'cssName'		=> isset($_POST['cssName']) ? addslashes($_POST['cssName']) : ''
		];
		$plugin_storage->setValue('isViewLine', $getConfig[is_viewLine]);
		$plugin_storage->setValue('hljsCssNum', $getConfig[cssName]);
		$plugin_storage->setValue('hljsCssUrl', BLOG_URL.'content/plugins/hljs/hljs_css/'.$cssFileArray[$getConfig[cssName]]);
		$hljs_msg = "保存成功！";
	}else{  // 当接收到的GET != 保存
		$getConfig = [
			'is_viewLine'	=> $plugin_storage->getValue('isViewLine') ,
			'cssName'		=> $plugin_storage->getValue('hljsCssNum')
		];
	}

if($getConfig[is_viewLine] == 'y'){  // 根据emlog插件存储实例中的内容，决定'显示行号'是否选中
	$lineChecked = 'checked="checked"';
}

foreach($cssFileArray as $x=>$css_name)  // 将样式条列入栈$cssOpt
{
	$isSelect = '';
	if($x == $getConfig[cssName]){
		$isSelect = 'selected="selected"';
	}
	if($css_name !== '.' && $css_name !== '..' && $css_name !== '.DS_Store'){  // 过滤linux/unix系统里的.和..文件等
		$cssOpt = $cssOpt.'<option value="'.$x.'"'.$isSelect.'>'.substr($css_name,0,-8).'</option>';
	}
}

?>
<!-----------代码高亮插件的后台设置输出区---------->
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
					<input type="button" name="back" id="back" value="返回" onclick="window.location.href = 'plugin.php';" class="btn btn-sm btn-success">
					<input type="submit" value="保存" class="btn btn-sm btn-primary"/>
					<hr width="250px" />
			</form>

			<div class="test">
				版本V1.1 <a id="hljsCheckUpdate" href="javascript:void(0)"> 检查更新 </a>
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
<script>
var version 		= 11;  // 这是当前版本号代码，是版本更新的依据
var $CheckUpdate 	= $("#hljsCheckUpdate");  // '更新'的触发链接对象
var updateServer	= "../content/plugins/hljs/pluginsUpdate.php";  // 更新服务程序地址

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

function changeCss(val){  // 改变主题
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

function previewCss(){  // 预览
	let isLine		= $('#is_viewLine').is(":checked")?'y':'n';
	let cssName		= $('#cssName').find("option:selected").text();
	let vLanguage	= $('#language').val();
	$('#previewContent').attr('src','../content/plugins/hljs/preview.php?isLine='+isLine+'&cssName='+cssName+'&vLanguage='+vLanguage);
}

$('#is_viewLine,#cssName,#changeLang').change(function(){
	previewCss();
});

$(document).ready(function(){
	previewCss();
});

</script>
<!-----------代码高亮插件的后台设置输出区（结束）---->
<?php
}


