<?php
/**
 * for database.
 *
 * @author xuqihua <qihuaxu@qq.com>
 * @version 1.0
 */
echo <<<EOT
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html dir="ltr" xml:lang="zh" xmlns="http://www.w3.org/1999/xhtml" lang="zh"><head>
<title>数据字典</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style>
html {font-size: ;}
body {
font-family:        sans-serif;
padding:            0;
margin:             0.5em;
color:              #000000;
background:         #F5F5F5;}
h2 {font-size:          120%;font-weight:        bold;}
table tr.odd th,.odd {background: #E5E5E5;}
table tr.even th,.even {background: #D5D5D5;}
table tr.odd th,table tr.odd,table tr.even th,table tr.even {text-align:         left;}
.odd:hover,.even:hover,.hover {background: #CCFFCC;color: #000000;}
table tr.odd:hover th,table tr.even:hover th,table tr.hover th {background:   #CCFFCC;color:   #000000;}
</style>
</head>
<body>
EOT;
//取数据表的字段
$dbh = new PDO(
	'mysql:host=localhost;dbname=test;port=3306;', 
	'root', 
	'',
	array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8") 
); 

$sql = "SHOW TABLE STATUS";
$statement = $dbh->prepare($sql);
$statement->execute();
$data = $statement->fetchAll(PDO::FETCH_ASSOC);
//print_r($data);exit;
foreach($data as $k => $v) {
	$list[$v['Name']] = array(
		$v['Name'],
		$v['Comment'],
	);
}

$tables = array();
foreach($list as $k =>$v) {
	$sql = "SHOW FULL COLUMNS FROM `$k`";
	$statement = $dbh->prepare($sql);
	$statement->execute();
	$tables[$k] = $statement->fetchAll(PDO::FETCH_ASSOC);
}
//print_r($tables);exit;
foreach($tables as $n => $t) {
	echo "<div style=\"page-break-before: always;\">
	<h2>{$n}&nbsp;&nbsp;&nbsp;({$list[$n][1]})</h2>\n";
	echo '<table class="print" width="100%">
<tbody><tr><th width="50">字段</th>
	<th width="80">类型</th>
	<th width="40">Null</th>
	<th width="70">默认</th>
	<th>注释</th>
</tr>';
	foreach($t as $k => $v) {
		$class = ($k%2==0)?'odd':'even';
		$v['Null'] = $v['Null'] == 'NO' ?'':'是';
	echo <<<EOT
	<tr class="{$class}">
		<td nowrap="nowrap"><u>{$v['Field']}</u>    </td>
		<td xml:lang="en" dir="ltr" nowrap="nowrap">{$v['Type']} </td>
		<td>{$v['Null']}</td>
		<td nowrap="nowrap">{$v['Default']}</td>
		<td>{$v['Comment']}</td>
	</tr>
EOT;
	}
	echo '</table></div>';
}

	echo <<<EOT
</body></html>
EOT;
