<?
define('GAME',true);
include('_incl/__config.php');
include('_incl/class/__db_connect.php');
include('_incl/class/__user.php');

/*
<tr height="20">
<td>1</td>
<td><a target="_blank" href="http://capitalcity.combats.com/encicl/alignment.html" title="���������� ����"><img src="http://img.combats.ru/i/align20.gif" width="12" height="15" border="0" /></a><a target="_blank" href="http://dungeon.combats.com/clans_inf.pl?Mercenaries" title="���������� � ����� Mercenaries"><img src="http://img.combats.ru/i/klan/Mercenaries.gif" width="24" height="15" border="0" /></a><strong>SeDuCeR</strong>&nbsp;[12]<a href="http://dungeon.combats.com/inf.pl?1069870465" title="���������� � SeDuCeR" target="_blank"><img src="http://img.combats.ru/i/inf.gif" width="12" height="11" border="0" /></a></td>
<td>243</td>
<td>47748</td>
<td><a href="http://dungeon.combats.com/logs.pl?log=1425817576.94166">&raquo;&raquo;</a></td>
</tr>
*/

$r1 = '';
$r2 = '';
$lvl = 8;
$lvl_name = '��������';
if( $_GET['level'] == 9 ) {
	$lvl = 9;
	$lvl_name = '�������';
}elseif( $_GET['level'] == 10 ) {
	$lvl = 10;
	$lvl_name = '�������������';
}elseif( $_GET['level'] == 11 ) {
	$lvl = 11;
	$lvl_name = '������';
}

$i = 1;
$j = 1;

$sp = mysql_query('SELECT `id`,`uid`,`level`,`time` FROM `izlom_rating` WHERE `level` = "' . $lvl . '" GROUP BY `uid` ORDER BY SUM(`obr`) DESC');
while( $pl = mysql_fetch_array($sp) ) {
	//
	$ret = mysql_fetch_array(mysql_query('SELECT SUM(`obr`) FROM `izlom_rating` WHERE `uid` = "'.$pl['uid'].'" AND `level` = "'.$pl['level'].'" LIMIT 1'));
	$ret = round($ret[0]*(154.97));
	//
	$pl2 = mysql_fetch_array(mysql_query('SELECT * FROM `izlom_rating` WHERE `uid` = "'.$pl['uid'].'" AND `level` = "'.$pl['level'].'" ORDER BY `time` DESC LIMIT 1'));
	//
	$r1 .= '<tr height="20">
<td>' . $i . '</td>
<td>' . $u->microLogin($pl['uid'],1) . '</td>
<td>' . $pl2['voln'] . '</td>
<td>'.$ret.'</td>
<td>&raquo;&raquo;</td>
</tr>';
	//
	if( date('d.m.Y') == date('d.m.Y',$pl2['time']) ) { 
		$r2 .= '<tr height="20">
<td>' . $j . '</td>
<td>' . $u->microLogin($pl['uid'],1) . '</td>
<td>' . $pl2['voln'] . '</td>
<td>'.$ret.'</td>
<td>&raquo;&raquo;</td>
</tr>';
		$j++;
	}
	$i++;
}

/*$sp = mysql_query('SELECT * FROM `izlom_rating` WHERE `level` = "' . $lvl . '" GROUP BY `uid` ORDER BY MAX(`time`) DESC');
while( $pl = mysql_fetch_array($sp) ) {
	//
	$ret = mysql_fetch_array(mysql_query('SELECT SUM(`obr`) FROM `izlom_rating` WHERE `uid` = "'.$pl['uid'].'" AND `level` = "'.$pl['level'].'" LIMIT 1'));
	$ret = round($ret[0]*(154.97));
	//
	$r1 .= '<tr height="20">
<td>' . $i . '</td>
<td>' . $u->microLogin($pl['uid'],1) . '</td>
<td>' . $pl['voln'] . '</td>
<td>'.$ret.'</td>
<td>&raquo;&raquo;</td>
</tr>';

if( $pl['uid'] == 1000000 ) {
	echo date('d.m.Y',$pl['time']).'<br>';
}
	if( date('d.m.Y') == date('d.m.Y',$pl['time']) ) { 
	$r2 .= '<tr height="20">
<td>' . $j . '</td>
<td>' . $u->microLogin($pl['uid'],1) . '</td>
<td>' . $pl['voln'] . '</td>
<td>' . $ret . '</td>
<td>&raquo;&raquo;</td>
</tr>';
		$j++;
	}
	$i++;
}*/

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<title>�����: ��������</title>
<link href="http://img.combatz.ru/css/main.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor="#E2E0E0">
<h4>
  <center>
    ����� �����
      <table width="100%">
        <tbody>
          <tr>
            <td valign="top"><fieldset>
              <legend>
              <h5><?=$lvl_name?> - ������ �� �����</h5>
              </legend>
              <? if( $r1 != '' ) { ?>
              <table align="center">
                <tbody>
                  <tr>
                    <td><h6>�����</h6></td>
                    <td><h6>����</h6></td>
                    <td><h6>��������� �����</h6></td>
                    <td><h6>�������</h6></td>
                    <td><h6>��� ���</h6></td>
                  </tr>
                  <?=$r1?>
                </tbody>
              </table>
              <? }else{
				echo '������� �����, ������ ����� �� ������� ����������...';  
			  } ?>
            </fieldset></td>
            <td valign="top"><fieldset>
              <legend>
              <h5>������� - ������ �� ����</h5>
              </legend>
              <? if( $r2 != '' ) { ?>
              <table align="center">
                <tbody>
                  <tr>
                    <td><h6>�����</h6></td>
                    <td><h6>����</h6></td>
                    <td><h6>��������� �����</h6></td>
                    <td><h6>�������</h6></td>
                    <td><h6>��� ���</h6></td>
                  </tr>
                  <?=$r2?>
                </tbody>
              </table>
              <? }else{
				echo '������� �����, ������ ����� �� ������� ����������...';  
			  } ?>
            </fieldset></td>
          </tr>
        </tbody>
      </table>
  </center>
</h4>
</body>
</html>