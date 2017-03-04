<?php
/**
 * Created by PhpStorm.
 * User: Su
 * Date: 2017/3/1
 * Time: 15:12
 *
 */
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
//print_r($a);
//Header( "Content-type: image/jpeg");//直接输出显示jpg格式图片

$execString="../run/search/DoSearch.sh  "."../searchFIle/". $filename;
//echo $execString;
$results=my_exec($execString);
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
