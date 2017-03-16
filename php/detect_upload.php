<?php
/**
 * Created by PhpStorm.
 * User: Su
 * Date: 2017/3/14
 * Time: 20:27
 */
function deleteAll($path) {
    $op = dir($path);
    while(false != ($item = $op->read())) {
        if($item == '.' || $item == '..') {
            continue;
        }
        if(is_dir($op->path.'/'.$item)) {
            deleteAll($op->path.'/'.$item);
            rmdir($op->path.'/'.$item);
        } else {
            unlink($op->path.'/'.$item);
        }

    }
}
function my_exec($cmd, $input='')
{
    $proc = proc_open($cmd, array(0 => array('pipe', 'r'), 1 => array('pipe', 'w'), 2 => array('pipe', 'w')), $pipes);
    fwrite($pipes[0], $input);
    fclose($pipes[0]);
    $stdout = stream_get_contents($pipes[1]);
    fclose($pipes[1]);
    $stderr = stream_get_contents($pipes[2]);
    fclose($pipes[2]);
    $rtn = proc_close($proc);
    return array('stdout' => $stdout,
        'stderr' => $stderr,
        'return' => $rtn
    );
}

$input = filter_input_array(INPUT_POST);

$data=$input['data'];
//echo $data;
$base64 =trim($data);
//echo $base64;
$img = base64_decode($base64);
$filename = date('YmdHis') .'.jpg';
$a = file_put_contents('../searchFile/'. $filename, $img);//保存图片，返回的是字节数
///modify
//if(file_exists("../run/runResult/detect.txt")){
//    delFile("../run/runResult/detect.txt");
//}
deleteAll("../run/runResult/");

//exec 执行
$execString="../run/search/DoDetect.sh  "."/var/www/html/pro/searchFile/". $filename;
//echo $execString;$results=my_exec($execString);
$results=my_exec($execString);
$file_result=array();
$usetime="";
$file_detected="";
$dir = "../run/runResult/";
$dir_target = "./run/runResult/";
$total =0;
// Open a known directory, and proceed to read its contents
if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
        while (($path = readdir($dh)) !== false) {
            if(filetype($dir . $path) != "dir"){
                $total++;
                if($path == $filename ) $file_detected = $dir_target.$path;
                else{
                    array_push($file_result,$dir_target . $path);
                }
            }
        }
        closedir($dh);
    }
}


//$origin_file_path="./searchFile/".$filename;
$length = count($file_result);

if( $length > 0 ){
    $result = Array("msg"=>"success","data"=>$results,"bytes"=>$a,"img"=>$file_result,"origin_pic"=>$file_detected,"count"=>$total);
}else{
    $result = Array("msg"=>"FAIL","data"=>$results,"bytes"=>$a);
}
echo json_encode($result);
?>
