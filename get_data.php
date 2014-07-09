<?php
include("lib/LIB_http.php");
include("lib/LIB_parse.php");
$file=fopen("data.csv","a+");//file setting
/*欄位資料*/
$action2 = "http://cdcb.judicial.gov.tw/abbs/wkw/WHD6K03.jsp";
	$method = "GET";
	$ref = " ";
	//data setting
	$data_array2["ab"] = "AAAdkxAAHAAACxwAAG";
	$data_array2["bc"] = "033";
	//$data_array2["cd"] = "%AA%6B%B5%6E++++++++++++++++";
	//$data_array2["de"] = "000002";
	//$data_array2["ef"] = "TPD";
	//$data_array2["fg"] = "1";
	/*董監事資料
$action3 = "http://cdcb.judicial.gov.tw/abbs/wkw/WHD6K04.jsp";
	$method = "GET";
	$ref = " ";
	//data setting
	$data_array3["ab"] = "AAAdkxAAHAAACxwAAG";
	$data_array3["bc"] = "033";
	$data_array3["cd"] = "%AA%6B%B5%6E++++++++++++++++";
	$data_array3["de"] = "000002";
	$data_array3["ef"] = "TPD";
	$data_array3["fg"] = "1";
	*/
//get response
	$response2 = http($target=$action2, $ref, $method, $data_array2, EXCL_HEAD);
	//$response3 = http($target=$action3, $ref, $method, $data_array3, EXCL_HEAD);
//parse
$response_parse = parse_array($response2["FILE"],"<td","</td>");
for($i=1;$i<count($response_parse);$i++){
	if($i%2==1&&$i!=59){
		echo trim(preg_replace('/[\n\r\t]/','',strip_tags($response_parse[$i]))).",";
		fwrite($file,trim(preg_replace('/[\n\r\t]/','',strip_tags($response_parse[$i]))).",");
	}
}
fwrite($file,"\r\n");
for($i=2;$i<count($response_parse);$i++){
	if($i%2==0){
		echo trim(preg_replace('/[\n\r\t]/','',strip_tags($response_parse[$i]))).",";
		fwrite($file,trim(preg_replace('/[\n\r\t]/','',strip_tags($response_parse[$i]))).",");
	}
	if($i==59){
		echo trim(preg_replace('/[\n\r\t]/','',strip_tags($response_parse[$i])))."</br>";
		fwrite($file,trim(preg_replace('/[\n\r\t]/','',strip_tags($response_parse[$i])))."\r\n");
	}
}

fclose($file);
?>