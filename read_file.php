<?php
	include("lib/LIB_http.php");
	include("lib/LIB_parse.php");
	$array[]=1;
	$tmp[]=1;
	$file=fopen("org_data_utf8.txt","r");
	$csv=fopen("data_test.csv","a+");//file setting
	while(! feof($file)){
	array_push($array,fgets($file));
	}
	$response_start=http_get ("http://cdcb.judicial.gov.tw/abbs/wkw/WHD6K03.jsp?ab=AAAdkxAAHAAACxwAAF&bc=033&cd=%AA%6B%B5%6E++++++++++++++++&de=000001&ef=TPD&fg=1","http://cdcb.judicial.gov.tw/abbs/wkw/");
	$response_parse_start = parse_array($response_start["FILE"],"<td","</td>");
		for($i=1;$i<count($response_parse_start);$i++){
		if($i%2==1){
			echo "meta";
			echo trim(preg_replace('/[\n\r\t]/','',strip_tags($response_parse_start[$i]))).",";
			fwrite($csv,trim(preg_replace('/[\n\r\t]/','',strip_tags($response_parse_start[$i]))).",");
		}
	}
	fwrite($csv,"\r\n");
	for($x=1;$x<11;$x++){
		$tmp = explode('|',$array[$x],2);
		echo "1".$tmp[1]."</br>";
	$response=http_get ($tmp[1],"http://cdcb.judicial.gov.tw/abbs/wkw/");
	echo $response["FILE"];
	//get response
	//parse
	$response_parse = parse_array($response["FILE"],"<td","</td>");	
	for($i=2;$i<count($response_parse);$i++){
		if($i%2==0){
			echo trim(preg_replace('/[\n\r\t]/','',strip_tags($response_parse[$i]))).",";
			fwrite($csv,trim(preg_replace('/[\n\r\t]/','',strip_tags($response_parse[$i]))).",");
		}
		if($i==59){
			echo trim(preg_replace('/[\n\r\t]/','',strip_tags($response_parse[$i])))."</br>";
			fwrite($csv,trim(preg_replace('/[\n\r\t]/','',strip_tags($response_parse[$i])))."\r\n");
			}
		}
	}
fclose($file);
fclose($csv);
?>
