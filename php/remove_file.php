<?php
/**
 * Created by PhpStorm.
 * User: Su
 * Date: 2017/2/26
 * Time: 12:35
 */
if(isset($_POST['file'])){
    $file = './php/upload/' . $_POST['file'];
    if(file_exists($file)){
        unlink($file);
    }
}
?>