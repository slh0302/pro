<?php
/**
 * Created by PhpStorm.
 * User: Su
 * Date: 2017/3/1
 * Time: 15:12
 */


$base64 =
//echo $base64;
$img = base64_decode($base64);
$a = file_put_contents('./test.jpg', $img);//保存图片，返回的是字节数
print_r($a);
Header( "Content-type: image/jpeg");//直接输出显示jpg格式图片


?>