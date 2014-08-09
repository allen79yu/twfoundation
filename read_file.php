<?php

include("lib/LIB_http.php");
include("lib/LIB_parse.php");
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
$limit = 10;

for($index=0; $index<count($court_name); $index++){	
  echo "Running {$court_name[$index]} \n";
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
  $first_row = TRUE;
  for($x=1;$x<$limit;$x++){
    usleep(500000);
    $url = $rows[$x][2];
    $response_start = http_get(str_replace("WHD6K05","WHD6K03",$url), "http://cdcb.judicial.gov.tw/abbs/wkw/");
    $response_parse_start = parse_array($response_start["FILE"],"<td","</td>");

    $fields = array();
    $row = '';
    if($first_row){
      for($i=1;$i<count($response_parse_start);$i++){
        if($i%2==1){
          $fields[] = iconv("BIG5","UTF-8", trim(preg_replace('/[\n\r\t]/','',strip_tags($response_parse_start[$i]))));
        }	
      }
      $fields[] = '董監事';
      $row = array2csv(array($fields));
      file_put_contents($output, $row, FILE_APPEND);

      $fields = array();
      $row = '';
      $first_row = FALSE;
    }
    for($i=1;$i<count($response_parse_start);$i++){
      if($i%2==0){
        $str = iconv("BIG5","UTF-8", trim(preg_replace('/[\n\r\t]/','',strip_tags($response_parse_start[$i]))));
        if($i != 26 && $i != 32){
          $str = chinese2num($str);
        }
        $fields[] = $str;
      }	
    }
    $fields[] = trustee_get(str_replace('WHD6K05', 'WHD6K04', $url));
    $row = array2csv(array($fields));
    $row = removespace($row);
    echo "Processed $row\n";
		file_put_contents($output, $row, FILE_APPEND);
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

function trustee_get($url){
  $res = http_get($url, "http://cdcb.judicial.gov.tw/abbs/wkw/");
  $res_parsed = parse_array($res['FILE'], "<tr", "</tr>");
  $rows = array();
  foreach($res_parsed as $key => $r){
    if($key < 3) {
      continue;
    }
    $fields = array();
    $fields = parse_array($r, "<td", "</td>");
    foreach($fields as $k => $f){
      if($k < 1) {
        unset($fields[$k]);
        continue;
      }
      $f = preg_replace("@</?td[^>]*>@i", '', $f);
      $f = iconv("BIG5", "UTF-8", trim($f));
      $f = removespace($f);
      $fields[$k] = $f;
    }
    $rows[] = implode(':', $fields);
  }
  return implode("|", $rows);
}

function removespace($i){
  return str_replace(array('&nbsp;', '　', ' '), '', $i);
}

function chinese2num($in){
  $map = array(
    '一'=>1,
    '二'=>2,
    '三'=>3,
    '四'=>4,
    '五'=>5,
    '六'=>6,
    '七'=>7,
    '八'=>8,
    '九'=>9,
    '十'=>10,
    '０'=>0,
  ); 
  return str_replace(array_keys($map), $map , $in);
}
