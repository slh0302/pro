<?php
/**
 * Created by PhpStorm.
 * User: Su
 * Date: 2017/3/14
 * Time: 20:27
 */

$input = filter_input_array(INPUT_POST);

$data=$input['data'];
//echo $data;
$base64 =trim($data);
//echo $base64;
$img = base64_decode($base64);
$filename = date('YmdHis') .'.jpg';
$a = file_put_contents('../searchFile/'. $filename, $img);//保存图片，返回的是字节数

if(file_exists("../run/runResult/detect.txt")){
    unlink("../run/runResult/detect.txt");
}
//exec 执行
$execString="../run/search/DoDetect.sh  "."/var/www/html/pro/searchFile/". $filename;
//echo $execString;$results=my_exec($execString);
$results=exec($execString);
$file_result=array();
$usetime="";
if(!dir_is_empty("../run/runResult")) {
    if($myfile = fopen("../run/runResult/detect.txt", "r") or die("Unable to open file!")){

        $total_pic =fgets($myfile);
        while(!feof($myfile)) {
            $path = fgets($myfile);
            if($path != ""){
                array_push($file_result,$path);
            }
        }
    }
}
//$origin_file_path="./searchFile/".$filename;
$length = count($file_result);

if( $length > 0 ){
    $result = Array("msg"=>"success","data"=>$results,"bytes"=>$a,"img"=>$file_result,"origin_total"=>$total_pic);
}else{
    $result = Array("msg"=>"FAIL","data"=>$results,"bytes"=>$a);
}
echo json_encode($result);
?>
