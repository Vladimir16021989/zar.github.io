<?
define('GAME',true);
include('_incl/__config.php');
include('_incl/class/__db_connect.php');
include('_incl/class/__user.php');

if(isset($_GET['re'])) {
	$i = 0;
	$j = 0;
	$sp = mysql_query('SELECT `id`,`delete`,`lastUPD` FROM `items_users` WHERE `delete` = 0 ORDER BY `lastUPD` ASC LIMIT 100');
	while( $pl = mysql_fetch_array($sp)) {
		if( $pl['id'] > 0 ) {
			mysql_query('UPDATE `items_users` SET `delete` = "1000" WHERE `inGroup` = "'.$pl['id'].'" AND `delete` > "'.(time()-86400).'"');$i++;
			mysql_query('UPDATE `items_users` SET `lastUPD` = "'.time().'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
			echo floor((time()-$pl['lastUPD'])/60).'<br>';
		}
		$j++;
	}
	if( $i > 0 ) {
		
		echo '<script>function test(){ top.location.href="http://combatz.ru/towerlog.php?re=1"; } setTimeout("test()",500);</script>';
		
	}
	die('['.time().'] del '.$i.' '.$j);
}


$r = ''; $p = ''; $b = '<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tbody>
    <tr valign="top">
      <td valign="bottom" nowrap="" title=""><input onClick="location=location;" style="padding:5px;" type="submit" name="analiz2" value="��������"></td>
    </tr>
  </tbody>
</table>';
if( !isset($_GET['towerid'])) {
	$_GET['towerid'] = 1;
}
$_GET['towerid'] = round((int)$_GET['towerid']);
$notowerlog = false;
$log = mysql_fetch_array(mysql_query('SELECT `id`,`count_bs`,`m` FROM `bs_logs` WHERE `count_bs` = "'.mysql_real_escape_string((int)$_GET['id']).'" AND `id_bs` = "'.mysql_real_escape_string($_GET['towerid']).'" ORDER BY `id` ASC LIMIT 1'));
if(!isset($log['id']))
{
	$notowerlog = true;
	$r = '<br><br><center>������ ����� ���������� ����� ������� ��������� � ��������� �������� ...</center>';
}else{
	$sp = mysql_query('SELECT * FROM `bs_logs` WHERE `count_bs` = "'.$log['count_bs'].'" ORDER BY `id` ASC');
	while( $pl = mysql_fetch_array($sp) ) {
		$datesb = '';
		if( $pl['type'] == 2 ) {
			$datesb = '2';
		}
		$r .= '<br><span class="date'.$datesb.'">'.date('d.m.y H:i',$pl['time']).'</span> '.$pl['text'].'';
	}
}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<title>�����: ������ � ����� ������</title>
<script src="http://img.combatz.ru/js/Lite/gameEngine.js" type="text/javascript"></script>
<link href="http://img.combatz.ru/css/main.css" rel="stylesheet" type="text/css">
<style type="text/css">
h3 {
	text-align: center;
}
.CSSteam	{ font-weight: bold; cursor:pointer; }
.CSSteam0	{ font-weight: bold; cursor:pointer; }
.CSSteam1	{ font-weight: bold; color: #6666CC; cursor:pointer; }
.CSSteam2	{ font-weight: bold; color: #B06A00; cursor:pointer; }
.CSSteam3 	{ font-weight: bold; color: #269088; cursor:pointer; }
.CSSteam4 	{ font-weight: bold; color: #A0AF20; cursor:pointer; }
.CSSteam5 	{ font-weight: bold; color: #0F79D3; cursor:pointer; }
.CSSteam6 	{ font-weight: bold; color: #D85E23; cursor:pointer; }
.CSSteam7 	{ font-weight: bold; color: #5C832F; cursor:pointer; }
.CSSteam8 	{ font-weight: bold; color: #842B61; cursor:pointer; }
.CSSteam9 	{ font-weight: bold; color: navy; cursor:pointer; }
.CSSvs 		{ font-weight: bold; }
</style>
</head>

<body bgcolor="#E2E0E0">
<H3> ����� ������. ����� � �������. &nbsp; <a href="http://www.combatz.ru/">www.combatz.ru</a></H3>
<? if( $notowerlog == false ) { ?>
�������� ����: <b><?=$log['m']?> ��.</b>
<? }
echo $r; ?>
</body>
</html>