<?php
	include("lib/LIB_http.php");
	include("lib/LIB_parse.php");
	$array[]=1;
	$tmp[]=1;
	$court_name = array(
  'TPD',
  'PCD',
  'SLD',
  'TYD',
  'SCD',
  'MLD',
  'TCD',
  'NTD',
  'CHD',
  'ULD',
  'CYD',
  'TND',
  'KSD',
  'PTD',
  'TTD',
  'HLD',
  'ILD',
  'KLD',
  'PHD',
  'KSY',
  'LCD',
  'KMD',
);
for($index=0;$index<count($court_name);$index++){	
	$row=array();
	$court=$court_name[$index];
	$filename="output/".$court.".csv";
	$output="output/".$court."_detail.csv";

if(file_exists($filename)){
    $rows = csv2array($filename);
	$first_run = FALSE;
  }
  else{
	$first_run = TRUE;
  }
  $meta_flag=FALSE;
 for($x=1;$x<11;$x++){ 
	
	$response_start=http_get ( str_replace("WHD6K05","WHD6K03",$rows[$x][2]),"http://cdcb.judicial.gov.tw/abbs/wkw/");
	$response_parse_start = parse_array($response_start["FILE"],"<td","</td>");
	if(!$meta_flag){
	for($i=1;$i<count($response_parse_start);$i++){
		if($i%2==1){
			echo iconv("BIG5","UTF-8", trim(preg_replace('/[\n\r\t]/','',strip_tags($response_parse_start[$i])))).",";
			 $row =  iconv("BIG5","UTF-8", trim(preg_replace('/[\n\r\t]/','',strip_tags($response_parse_start[$i])))).",";
			 file_put_contents($output, $row, FILE_APPEND);
			$meta_flag=TRUE;
		}	
	}
		echo "</br>";
		 file_put_contents($output, "\r\n", FILE_APPEND);
	}
	for($i=1;$i<count($response_parse_start);$i++){
		if($i%2==0){
			$row =  iconv("BIG5","UTF-8", trim(preg_replace('/[\n\r\t]/','',strip_tags($response_parse_start[$i])))).",";
			 file_put_contents($output, $row, FILE_APPEND);
			echo iconv("BIG5","UTF-8", trim(preg_replace('/[\n\r\t]/','',strip_tags($response_parse_start[$i])))).",";
		}	
	}
		echo "</br>";
		file_put_contents($output, "\r\n", FILE_APPEND);
}
}
function csv2array($filename){
  $data = array();
//  copy($filename, $filename.'.bak');
  if (($handle = fopen($filename, "r")) !== FALSE) {
    while (($col = fgetcsv($handle, 0, ",")) !== FALSE) {
      $data[$col[0]] = $col;
    }
    fclose($handle);
  }
  krsort($data);
  return $data;
}
function array2csv($array){
  $csv = '';
  foreach($array as $k => $v){
    $csv .= '"'.implode('","', $v).'"'."\n";
  }
  return $csv;
}
?>