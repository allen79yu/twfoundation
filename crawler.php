<?php
include("lib/LIB_http.php");
include("lib/LIB_parse.php");
require("catch_total.php");
//define
$total_items=$total;
$total_page=((int)($total_items/10))+1;
for($i=1;$i<=$total_page;$i++){
	sleep(1);
	$action = "http://cdcb.judicial.gov.tw/abbs/wkw/WHD6K02.jsp";
	$method = "POST";
	$ref = " ";
	//data setting
	$data_array["court"] = "TPD&臺灣台北地方法院";
	$data_array["classType"] = "RA001";
	$data_array["year"] = "";
	$data_array["word"] = "";
	$data_array["no"] = "";
	$data_array["recno"] = "";
	$data_array["kind"] = "0";
	$data_array["Date1Start"] = "";
	$data_array["Date1End"] = "";
	$data_array["kind1"] = "0";
	$data_array["comname"] = "";
	$data_array["pageSize"] = "10";
	$data_array["pageTotal"]= $total_items;
	$data_array["pageNow"] = $i;
//get response
	$response = http($target=$action, $ref, $method, $data_array, EXCL_HEAD);
	$file = fopen("test.txt","a+"); //open file
	$link =  parse_array($response["FILE"], "<a", "/>");//get link
	$after_parse = parse_array($response["FILE"], "<div ", "</div>");//parse
	$counter=1;
	$name_array[]=10;
	$link_array[]=10;
	$index=0;
//Crating name array
	for($i=0;$i<count($after_parse);$i++){
		echo $after_parse[$i];
		if($counter==5){	
			$name_array[$index]=return_between($after_parse[$i], "<div align='center'>", "</div>" ,EXCL);//remove div
			$name_array[$index]=str_replace("&nbsp;","",$name_array[$index]);//remove space
			$index++;
		}
		if($counter==8){
			$counter=0;
		}
		$counter+=1;
	}
//Creating link array
	for($i=0;$i<count($link);$i++){
		$link_href = get_attribute($link[$i], $attribute="href");
		$link_array[$i]="http://cdcb.judicial.gov.tw/abbs/wkw/".$link_href;
	}
//write in txt
	for($i=0;$i<count($name_array);$i++){
		fwrite($file,$name_array[$i]."|".$link_array[$i]."\r\n");
	}
}
echo "Done!";
fclose($file);
?>