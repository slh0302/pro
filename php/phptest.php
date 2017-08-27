<?php
/**
 * Created by PhpStorm.
 * User: Su
 * Date: 2017/3/5
 * Time: 15:48
 */
header("Content-type: text/html; charset=utf-8");


 function dir_is_empty($dir){
     if($handle = opendir("$dir")) {
         while($item = readdir($handle)){
             if ($item != "." && $item != "..")
                 return false;
         }
     }
     return true;
 }
 function delFile($dirName){
     if(file_exists($dirName) && $handle=opendir($dirName)){
         while(false!==($item = readdir($handle))){
             if($item!= "." && $item != ".."){
                 if(file_exists($dirName.'/'.$item) && is_dir($dirName.'/'.$item)){
                     delFile($dirName.'/'.$item);
                 }else{
                     if(unlink($dirName.'/'.$item)){
                         return true;
                     }
                 }
             }
         }
         closedir( $handle);
     }
 }

// server status
$service_port = 18000;
$address = 'localhost';
$socket = socket_create(AF_INET, SOCK_STREAM, getprotobyname("tcp"));
if ($socket === false) {
    echo json_encode("socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n");
}
// handle input
$input = filter_input_array(INPUT_POST);
$filename="";
$a="";

$isDetect = "false";
$usage = "vehicle";
$out = "";


if($isDetect == "false") {
    $data = $input['data'];
    $base64 = trim($data);
    $img = base64_decode($base64);
    $filename = date('YmdHis') . '.jpg';
    $a = file_put_contents('../searchFile/' . $filename, $img);//保存图片，返回的是字节数

    // init socket
    $result = socket_connect($socket, $address, $service_port);
    $in = "/home/slh/pro/searchFile/". $filename . " 1 512";
    switch ($usage){
        case 'vehicle':
            socket_write($socket, "2 ", 10);
            $in .= " 1";
            socket_write($socket, $in, strlen($in));
            $out = socket_read($socket, 8192);
            socket_close($socket);
            break;
        case 'person':
            socket_write($socket, "1 ", 8192);
            $in .= " 0";
            socket_write($socket, $in, strlen($in));
            $out = socket_read($socket, 8192);
            socket_close($socket);
            break;
    }
}else{
    $filename =  basename($input['data']);;
//	echo $filename;
    switch ($usage){
        case 'vehicle':
            $execString="../run/search/DoDetect.sh  "."/home/slh/pro/searchFile/". $filename;
            break;
        case 'person':
            $execString="../run/search/DoPerson.sh  "."/home/slh/pro/searchFile/". $filename;
            break;
    }
    $a="0";
}


//echo $results;
$file_result=array();
$usetime="";
$length = 0;

$map_array = array();
if($out != "") {
    $list = explode(",", $out);
    $usetime = $list[0];
    $num = $list[1];
    // title , pic num, point
    for($i = 1; $i <$num; $i++){
        $temp_array = array();
        $temp_array["title"] = $list[1 + $i];
        $i ++;
        $file_num = intval($list[1 + $i]);
        $i ++;
        $temp_array["point"] = $list[1 + $i];
        $i ++;
        $temp_array["content"] = $list[1 + $i];
        $temp_array['url'] = array();
        array_push($temp_array['url'], $temp_array["content"]);
        for( $j=1 ;$j<$file_num; $j++){
            if($list[1 + $i + $j] != ""){
                array_push($temp_array['url'], $list[1 + $i + $j]);
            }
        }
        array_push($map_array, $temp_array );
        $i += $file_num  ;
    }
}

$origin_file_path="./searchFile/".$filename;
$length = count($file_result);

if( $length > 0 ){
    $result = Array("msg"=>"success","data"=>$results,"bytes"=>$a,"img"=>$file_result,"cost time"=>$usetime,
        "origin_img"=>$origin_file_path,"isPerson"=>$isPerson,"map"=>$map_array);
}else{
    $result = Array("msg"=>"FAIL","data"=>$results,"bytes"=>$a,"isPerson"=>$isPerson,"map"=>$map_array);
}

echo json_encode($result);
?>
