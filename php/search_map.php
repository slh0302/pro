<?php
/**
 * Created by PhpStorm.
 * User: slh
 * Date: 2017/8/27
 * Time: 11:58
 */

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
$service_port = 19000;
$address = '172.17.0.1';
$socket = socket_create(AF_INET, SOCK_STREAM, getprotobyname("tcp"));
if ($socket === false) {
    echo json_encode("socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n");
}
// handle input
$input = filter_input_array(INPUT_POST);
$filename="";
$a="";

$isDetect = $input['isDetect'];
$usage = $input['usage'];
$out = "";

if(!dir_is_empty("../run/runResult/")){
    delFile("../run/runResult/");
}

if($isDetect == "false") {
    $data = $input['data'];
    $base64 = trim($data);
    $img = base64_decode($base64);
    $filename = date('YmdHis') . '.jpg';
    $a = file_put_contents('../searchFile/' . $filename, $img);//保存图片，返回的是字节数

    // init socket
    $result = socket_connect($socket, $address, $service_port);
    $in = $filename . " 1 512";
    switch ($usage){
        case 'vehicleMap':
            socket_write($socket, "2 ", 10);
            $return = socket_read($socket, 7);
            socket_write($socket, $in, strlen($in));
            $out = socket_read($socket, 10);
            socket_close($socket);
            break;
        case 'personMap':
            socket_write($socket, "1 ", 10);
            $return = socket_read($socket, 7);
            socket_write($socket, $in, strlen($in));
            $out = socket_read($socket, 10);
            socket_close($socket);
            break;
    }
}


//echo $results;
$file_result=array();
$usetime="";
$length = 0;
$ROOT_DIR = "run/oripic/";
$map_array = array();
if($myfile = fopen("../run/runResult/map.txt", "r") or die("Unable to open file!")){
    $usetime = fgets($myfile);
    while(!feof($myfile)) {
        $temp_array = array();
        $title = fgets($myfile);
        $numSpace = fgets($myfile);
        $point = fgets($myfile);
        $file_num = intval($numSpace);
        $temp_array["title"] = str_replace("\n","",$title);
        $temp_array["content"] = $ROOT_DIR . str_replace("\n","",fgets($myfile));
        $temp_array["point"] = str_replace("\n","",$point);
        $temp_array['url'] = array();
        array_push($temp_array['url'], $temp_array["content"]);
        for( $i=1 ;$i<$file_num; $i++){
            $url_temp = str_replace("\n","",fgets($myfile));
            if($url_temp != ""){
                array_push($temp_array['url'], $url_temp);
            }
        }
        array_push($map_array, $temp_array );
    }
}
$length = count($map_array);
$origin_file_path="./searchFile/".$filename;


if( $length > 0 ){
    $result = Array("msg"=>"success","bytes"=>$a,"img"=>$file_result,"cost time"=>$usetime,
        "origin_img"=>$origin_file_path,"isPerson"=>$isPerson,"map"=>$map_array);
}else{
    $result = Array("msg"=>"FAIL","data"=>$results,"bytes"=>$a,"isPerson"=>$isPerson,"map"=>$map_array);
}

echo json_encode($result);
?>
