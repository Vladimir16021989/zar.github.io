<?php

	header('Content-Type: text/html; charset=windows-1251');	
	define('GAME',true);
	include('_incl/__config.php');	
	include('_incl/class/__db_connect.php');
	
	$echo = '';
	$cnfg = array(
		'lvl' => 0
	);
	$sp = mysql_query( 'SELECT * FROM `levels` ORDER BY `upLevel` ASC' );
	$ma = 0;
	$sa = 12;
	$vinos = array(
		0,
		1,
		1,
		1,
		1,
		1,
		1,
		1,
		1,
		2,
		3,
		5,
		30
	);
	while( $pl = mysql_fetch_array( $sp ) ) {
		/*if( $cnfg['lvl'] != $pl['nextLevel'] ) {
			$echo .= '<hr>';
			$cnfg['lvl'] = $pl['nextLevel'];
		}else{
			$echo .= '<br>';
		}
		$echo .= '[' . $pl['nextLevel'] . '] - ' . $pl['exp'] . '';
		$echo .= ','.$pl['upLevel'].' => '.$pl['exp'].'';*/
		if( $pl['skills'] > 0 ) {
			$pl['skills'] = '+'.$pl['skills'];
		}else{
			$pl['skills'] = '';
		}
		$sa += $pl['ability'];
		$ma += $pl['money'];
		$stl = '';
		$vi = '';
		if( $cnfg['lvl'] != $pl['nextLevel'] || $pl['upLevel'] == 0 ) {
			$stl = ' style="background-color:#efefef"';
			$cnfg['lvl'] = $pl['nextLevel'];
			if( $vinos[$pl['nextLevel']] > 0 ) {
				$vi = '+'.$vinos[$pl['nextLevel']];
			}
		}
		$echo .= '  <tr'.$stl.'>
    <td align="center" valign="middle">'.$pl['nextLevel'].'</td>
    <td align="center" valign="middle">'.$pl['expBtlMax'].'</td>
    <td align="center" valign="middle">'.$pl['ability'].' ('.($sa+$vinos[$pl['nextLevel']]).')</td>
	<td align="center" valign="middle">'.$vi.'</td>	
    <td align="center" valign="middle">'.$pl['skills'].'</td>	
    <td align="center" valign="middle">+'.$pl['money'].' ��. &nbsp; ('.$ma.')</td>
    <td align="center" valign="middle">'.$pl['exp'].'</td>
  </tr>';
	}
	echo '<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" valign="middle">�������</td>
    <td align="center" valign="middle">������� ����</td>
    <td align="center" valign="middle">���������� ������ (�����)</td>
	<td align="center" valign="middle">������������</td>
    <td align="center" valign="middle">����������</td>
    <td align="center" valign="middle">������ (�����)</td>
    <td align="center" valign="middle">����</td>
  </tr>
'.$echo.'
</table>';
	unset( $echo, $cnfg );
	
?>