<?php
/**
 * Created by PhpStorm.
 * User: Su
 * Date: 2017/3/1
 * Time: 15:12
 *
 */

$input = filter_input_array(INPUT_POST);

$data=$input['data'];
//echo $data;
$base64 =trim($data);
//echo $base64;
$img = base64_decode($base64);
$filename = date('YmdHis') .'.jpg';
$a = file_put_contents('../searchFile/'. $filename, $img);//保存图片，返回的是字节数
//print_r($a);
//Header( "Content-type: image/jpeg");//直接输出显示jpg格式图片


$results=exec("../run/DoSearch.sh ". $fileLocation . " " . $database);

$dir = "../resultFile/";
$file = scandir($dir);
$length = count($file);


if( $length > 2 ){
    $newFile=array_slice($file,2,count($file));
    $result = Array("msg"=>"success","data"=>$results,"bytes"=>$a,"img"=>$newFile);
}else{
    $result = Array("msg"=>"FAIL","data"=>$results,"bytes"=>$a);
}

echo json_encode($result);
?>