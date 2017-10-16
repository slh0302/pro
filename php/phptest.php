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
$usage = "person";
$out = "";


if(!dir_is_empty("../run/runResult/originResult/")){
    delFile("../run/runResult/originResult/");
}

if($isDetect == "false") {
    $data = $input['data'];
    $base64 = trim($data);
    $img = base64_decode($base64);
    $filename = date('YmdHis') . '.jpg';
    $a = file_put_contents('../searchFile/' . $filename, $img);//保存图片，返回的是字节数

    // init socket
    $result = socket_connect($socket, $address, $service_port);
    $in = "/home/slh/pro/searchFile/"."20170827205815.jpg" . " 1 512";
    switch ($usage){
        case 'vehicle':
            socket_write($socket, "2 ", 10);
            $return = socket_read($socket, 7);
            echo "adasdasdasd   " . $return;
            echo '</br>';
            socket_write($socket, $in, strlen($in));
            $out = socket_read($socket, 10);
            echo "adasdasdasd   " . $out;
            echo '</br>';
            socket_close($socket);
            break;
        case 'person':
            socket_write($socket, "1 ", 5);
            $return = socket_read($socket, 8);
            socket_write($socket, $in, strlen($in));
            $out = socket_read($socket, 5);
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
$file_result = array();
$usetime="";
$length = 0;

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
        $temp_array["content"] = str_replace("\n","",fgets($myfile));
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

echo json_encode($map_array);
?>
