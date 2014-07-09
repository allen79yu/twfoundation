<?php
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

$response = http($target=$action, $ref, $method, $data_array, EXCL_HEAD);
$response_parse = parse_array($response["FILE"],"<td","</td>");
$text=parse_array($response_parse[97],"<td>","</td>");
$string = (string)$text[0];
$pattern = "/[\d]/";
preg_match_all($pattern, $string, $match);
$total = implode($match[0]);
$total = (int)$total;
?>