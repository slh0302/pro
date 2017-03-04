<?php
/**
 * Created by PhpStorm.
 * User: Su
 * Date: 2017/3/2
 * Time: 22:41
 */

//$dir = "../resultFile/";
//$file = scandir($dir);
//$length = count($file);
//if( $length > 2 ){
//    $newFile=array_slice($file,2,count($file));
//}


//print_r($newFile);



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

 $uploadDir = 'upload';
 if(!dir_is_empty($uploadDir)){
     $input=array(
         "msg"=>"NO_FILE"
     );
     echo $input;
     exit;
 }else{
     var_export(my_exec('sh /home/slh/test.sh'));
     if(file_exists("/home/slh/result.txt")){
         $str = file_get_contents("/home/slh/result.txt"); //
         $arr = explode(" ", $str);
         echo $arr[0];
     }
 }




//echo phpinfo();
?>
