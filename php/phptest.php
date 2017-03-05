<?php
 function dir_is_empty($dir){
    if($handle = opendir("$dir")) {
        while($item = readdir($handle)){
            if ($item != "." && $item != "..")
                return false;
        }
    }
    return true;
 }
echo "heloo";
$file_result=array();
if(!dir_is_empty("/home/slh/web/runResult")) {
	echo "hello";
    if($myfile = fopen("/home/slh/web/runResult/result.txt", "r") or die("Unable to open file!")){
        $usetime =fgets($myfile);
        while(!feof($myfile)) {
            $path = fgets($myfile);
            array_push($file_result,$path);
        }
    }
}
print_r($file_result);
//$dir = "../resultFile/";
//$file = scandir($dir);
$length = count($file_result);

if( $length > 0 ){
    $result = Array("msg"=>"success","data"=>$results,"bytes"=>$a,"img"=>$file_result,"cost time"=>$usetime);
}else{
    $result = Array("msg"=>"FAIL","data"=>$results,"bytes"=>$a);
}
print_r($result);
?>

