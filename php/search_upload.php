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
 function dir_is_empty($dir){
    if($handle = opendir("$dir")) {
        while($item = readdir($handle)){
            if ($item != "." && $item != "..")
                return false;
        }
    }
    return true;
 }

$input = filter_input_array(INPUT_POST);

$data=$input['data'];
//echo $data;
$base64 =trim($data);
//echo $base64;
$img = base64_decode($base64);
$filename = date('YmdHis') .'.jpg';
$a = file_put_contents('/var/www/html/pro/searchFile/'. $filename, $img);//保存图片，返回的是字节数
//print_r($a);
//Header( "Content-type: image/jpeg");//直接输出显示jpg格式图片


//exec 执行
$execString="../run/search/DoSearch.sh  "."/var/www/html/pro/searchFile/". $filename;
//echo $execString;
$results=exec($execString);
$file_result=array();
$usetime="";
if(!dir_is_empty("../run/runResult")) {

   if($myfile = fopen("../run/runResult/result.txt", "r") or die("Unable to open file!")){
        $usetime =fgets($myfile);
        while(!feof($myfile)) {
            $path = fgets($myfile);
            if($path != ""){
	    	array_push($file_result,$path);
            }
	}
    }
}

$length = count($file_result);

if( $length > 0 ){
    $result = Array("msg"=>"success","data"=>$results,"bytes"=>$a,"img"=>$file_result,"cost time"=>$usetime);
}else{
    $result = Array("msg"=>"FAIL","data"=>$results,"bytes"=>$a);
}

echo json_encode($result);
?>
