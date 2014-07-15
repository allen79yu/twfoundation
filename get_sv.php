<?php
	include("lib/LIB_http.php");
	include("lib/LIB_parse.php");
	$array[]=1;
	$tmp[]=1;
	$file=fopen("org_sv_data_utf8.txt","r");
	$csv=fopen("data_sv_test.csv","a+");//file setting
	while(! feof($file)){
	array_push($array,fgets($file));
	}
	$index=1;
	fwrite($csv, "法人名稱,董監事");
	fwrite($csv,"\r\n");
	for($x=1;$x<11;$x++){
		$tmp = explode('|',$array[$x],2);
		$response=http_get ($tmp[1],"http://cdcb.judicial.gov.tw/abbs/wkw/");		//get response
		$response_parse = parse_array($response["FILE"],"<td","</td>");   		//parse
		echo $tmp[0].",";
		fwrite($csv,$tmp[0].",");
		$index=1;
		echo "\"";
		fwrite($csv,"\"");
		for($i=6;$i<count($response_parse);$i++){
			if($index==3){
				if($i==count($response_parse)-1){
					echo  iconv("Big-5","UTF-8",strip_tags($response_parse[$i]));
					fwrite($csv, iconv("Big-5","UTF-8",strip_tags($response_parse[$i])));
				}
				else{
					echo iconv("Big-5","UTF-8",strip_tags($response_parse[$i])).",";
					fwrite($csv, iconv("Big-5","UTF-8",strip_tags($response_parse[$i])).",");
				}
				$index=0;
			}		
			$index++;
		}
		echo "\"";
		fwrite($csv,"\"");
		echo "</br>";
		fwrite($csv,"\r\n");
	}
fclose($file);
fclose($csv);
?>
