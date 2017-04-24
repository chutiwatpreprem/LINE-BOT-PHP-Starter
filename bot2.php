<?php 
//header("content-type:text/javascript;charset=utf-8"); 
header('Content-Type: text/html; charset=utf-8');
echo $thai_data = "สวัสดี";
echo $data = iconv("ISO-8859-11","utf-8",$thai_data);
echo "Finish";


;?>