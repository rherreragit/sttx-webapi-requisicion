<?php

$INC_DIR = $_SERVER["DOCUMENT_ROOT"]. "/sttx-webapi-requisicion"; 
$array='';
$ip='';
$fh = fopen($INC_DIR.'/config.txt','r');
while ($line = fgets($fh)) {
  $array = explode('=', $line);
  if($array[0]=="webservice"){
  	$ip=$array[1];
  }
}
fclose($fh);
$root = $_SERVER["HTTP_HOST"];
?>
