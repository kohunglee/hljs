<?php
/**
 * 预览页面
 */
//判断是否是管理员登录状态
require_once '../../../init.php';
!(ROLE == 'admin') && exit('非管理员权限！');
error_reporting(0);

// 获取get
$getConfig = [
	'is_viewLine'		=> isset($_GET['isLine']) ? addslashes($_GET['isLine']) : '',
	'cssName'			  => isset($_GET['cssName']) ? addslashes($_GET['cssName']) : '',
	'vLanguage'			=> isset($_GET['vLanguage']) ? addslashes($_GET['vLanguage']) : '',
];

//根据get来生成数据
$css = file_get_contents('hljs_css/'.$getConfig['cssName'].'.min.css');
$isViewLine = $getConfig['is_viewLine'];
?>

<style>
	pre,code {
    background: #f6f6f6;
		border-radius:0;
		border:0;
		outline:0;
		vertical-align:baseline;
		background:transparent;
	}

	/* 只显示指定语言 */
	pre{
		display: none;
	}
	.<?php echo $getConfig['vLanguage']; ?>{
		display: block;
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
<script src="hljs_src/highlight.min.js"></script>
<script src="LineNumber.js"></script>
<script>
	hljs.highlightAll()
	<?php if($isViewLine == 'y'){ ?> hljs.initLineNumbersOnLoad() <?php } ?>
</script>

<body style="margin:0">

<pre class="cpp"><code class="language-C++">//C++
#include &lt;iostream&gt;
using namespace std;
 
// main() 是主程序，程序运行的入口
 
int main()
{
   int a;
   int b;

   cout << "Hello World!"; // 屏幕输出 Hello World!
   return 0;
}



</code></pre>

<pre class="c"><code class="language-C">#include &lt;stdio.h&gt;
// 枚举变量的定义
enum DAY
{
    MON=1, TUE, WED, THU, FRI, SAT, SUN
};
 
int main()
{
    enum DAY day;
    day = WED;
    printf("%d",day);
    return 0;
}
// 以上实例输出结果为：3




</code></pre>

<pre class="java"><code class="language-Java">class Solution {  // 递归·LC144_二叉树的前序遍历
    ArrayList<Integer> preOrderReverse(TreeNode root) {
        ArrayList<Integer> result = new ArrayList<Integer>();
        preOrder(root, result);
        return result;
    }

    void preOrder(TreeNode root, ArrayList<Integer> result) {
        if (root == null) return;

        result.add(root.val); // 注意这一句
        preOrder(root.left, result);
        preOrder(root.right, result);
    }
}




</code></pre>

<pre class="php"><code class="language-PHP">/**
 * 生成一个随机的字符串
 */

function getRandStr($length = 12, $special_chars = true) {
  $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
  if ($special_chars) {
    $chars .= '!@#$%^&*()';
  }
  $randStr = '';
  for ($i = 0; $i < $length; $i++) {
    $randStr .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
  }
  return $randStr;
}




</code></pre>

<pre class="js"><code class="language-javascript">/*
* 为不具备 Iterator 接口的对象提供遍历方法
*/

function* objectEntries(obj) {
  const propKeys = Reflect.ownKeys(obj);
  for (const propKey of propKeys) {
    yield [propKey, obj[propKey]];
  }
}

const jane = { first: 'Jane', last: 'Doe' };
for (const [key,value] of objectEntries(jane)) {
    console.log(`${key}: ${value}`);
}  // 输出 first: Jane   last: Doe




</code></pre>

<pre class="language-html html"><code class="language-html">&lt;!DOCTYPE html&gt;
&lt;html&gt;
&lt;head&gt;
  &lt;meta charset=&quot;utf-8&quot;&gt;
  &lt;title&gt;串串狗xk(ccgxk.com)&lt;/title&gt;
&lt;/head&gt;

&lt;body&gt;

  &lt;h1&gt;这是标题&lt;/h1&gt;
  &lt;p&gt;这是段落&lt;/p&gt;

&lt;/body&gt;

&lt;/html&gt;




</code></pre>

<pre class="python"><code class="language-python">#!/usr/bin/python
# -*- coding: UTF-8 -*-

# 输出100以内的所有素数

i = 2
while(i < 100):
   j = 2
   while(j <= (i/j)):
      if not(i%j): break
      j = j + 1
   if (j > i/j) : print i
   i = i + 1
 
print "结束!"




</code></pre>

<pre class="r"><code class="language-R"># 该程序可以创建一个数字矩阵
# byrow 为 TRUE 元素按行排列
M <- matrix(c(3:14), nrow = 4, byrow = TRUE)
print(M)

# Ebyrow 为 FALSE 元素按列排列
N <- matrix(c(3:14), nrow = 4, byrow = FALSE)
print(N)

# 定义行和列的名称
rownames = c("row1", "row2", "row3", "row4")
colnames = c("col1", "col2", "col3")

P <- matrix(c(3:14), nrow = 4, byrow = TRUE, dimnames = list(rownames, colnames))
print(P)




</code></pre>

<pre class="go"><code class="language-GO">func BubbleSort(values []int) { //Go语言实现冒泡排序
flag := true
vLen := len(values)
  for i := 0; i < vLen-1; i++ {
  flag = true
    for j := 0; j < vLen-i-1; j++ {
      if values[j] > values[j+1] {
        values[j], values[j+1] = values[j+1], values[j]
        flag = false
        continue
      }
    }
    if flag {break}
  }
}




</code></pre>

<pre class="swift"><code class="language-Swift">import Cocoa  // swift的fallthrough语句
var index = 10

switch index {
   case 100  :
      print( "index 的值为 100")
      fallthrough
   case 10,15  :
      print( "index 的值为 10 或 15")
      fallthrough
   case 5  :
      print( "index 的值为 5")
   default :
      print( "默认 case")
}



</code></pre>

</body>
