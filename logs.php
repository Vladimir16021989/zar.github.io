<?
define('GAME',true);
include('_incl/__config.php');
include('_incl/class/__db_connect.php');
include('_incl/class/__user.php');
$btl = mysql_fetch_array(mysql_query('SELECT * FROM `battle` WHERE `id` = "'.mysql_real_escape_string((int)$_GET['log']).'" LIMIT 1'));
$r = ''; $p = ''; $b = '<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tbody>
    <tr valign="top">
      <td valign="bottom" nowrap="" title="">
	  <input onClick="location=location;" style="padding:5px;" type="submit" name="analiz2" value="��������">';

if($btl['team_win'] != -1) {
	if(!isset($_GET['analiz'])) {
		$b .= ' <input onClick="location=\'logs.php?log='.((int)$_GET['log']).'&analiz=1\';" style="padding:5px;" type="submit" name="analiz3" value="����������">';
	}else{
		$b .= ' <input onClick="location=\'logs.php?log='.((int)$_GET['log']).'\';" style="padding:5px;" type="submit" name="analiz3" value="��� ���">';
	}
}
$b .= '</td>
    </tr>	
  </tbody>
</table>';
if(isset($btl['id']) && $btl['team_win'] != -1 && isset($_GET['analiz'])) {
	function rzv($v)
	{
		$v = explode('.',$v);
		if(!isset($v[1]))
		{
			$v = $v[0].'.0';
		}else{
			$v = $v[0].'.'.$v[1];
		}
		return $v;
	}
	$tmStart = floor(($btl['time_over']-$btl['time_start'])/6)/10;
	$tmStart = rzv($tmStart);
	$tbtl = '<img src="http://img.combatz.ru/i/fighttype'.$btl['type'].'.gif">';
	if( $btl['invis'] > 0 ) {
		$tbtl .= '<img src="http://img.combatz.ru/i/fighttypehidden0.gif">';
	}
	if($btl['type'] == 0) {
		$tbtl = '��� ���: '.$tbtl.' (���������� ��������) &nbsp; &nbsp; ';
	}elseif($btl['type'] == 1) {
		$tbtl = '��� ���: '.$tbtl.' (�������� ��������) &nbsp; &nbsp; ';
	}else{
		$tbtl = '��� ���: '.$tbtl.' (���������� ��������) &nbsp; &nbsp; ';
	}
	
	if( $btl['izlom'] > 0 ) {
		$tbtl .= '�����: '.$btl['izlomRoundSee'].' &nbsp; &nbsp; ';
	}
	
	$tbtl .= '����������������� ���: '.$tmStart.' ���.<br>';
	
	$users = array(

	);
	
	$uids = array(
	
	);
	
	function con_login($us) {
		$r = '';
		if( $us['align'] > 0 ) {
			$r .= '<img src="http://img.combatz.ru/i/align/align'.$us['align'].'.gif" width="12" height="15">';
		}
		if( $us['clan'] > 0 ) {
			$r .= '<a href="clans_inf.php?'.$us['clan'].'" target="_blank"><img src="http://img.combatz.ru/i/clan/'.$us['clan'].'.gif" width="24" height="15"></a>';
		}
		$r .= '<b class="CSSteam'.$us['team'].'">'.$us['login'].' ['.$us['level'].']</b>';
		$r .= '<a href="inf.php?'.$us['uid'].'" target="_blank"><img src="http://img.combatz.ru/i/inf_capitalcity.gif" width="12" height="11"></a>';
		return $r;
	}
	
	//�������� ����
	$tpas = array(
		1 => 'X',
		2 => '<font color=#AAAAEE>&bull;</font>',
		3 => '<b>&bull;</b>',
		4 => '<font color=#FF0000>&curren;</font>',
		5 => '<font color=#FF0000>X</font>',
		6 => '<font color=#AAAAEE>&bull;</font>',
		7 => '<font color=777777><B>&bull;</B></font>',
		8 => '<font color=#AAAAEE>&bull;</font>'
	);
	/*$tpbs = array(
		0 => 'X',
		1 => 'X',
		2 => '<font color=#AAAAAA><B>&bull;</B></font>',
		3 => '<font color=#AAAAEE><B>&bull;</B></font>',
		4 => 'X',
		5 => '<font color=#FF0000><B>&bull;</B></font>',
		6 => '<font color=#AAAAEE><B>&bull;</B></font>',
		7 => '<font color=#777777><B>&bull;</B></font>',
		8 => '<font color=#AAAAAA><B>&bull;</B></font>'
	);*/
	$tpbs = array(
		0 => '',
		1 => '<B>&bull;</B>',
		2 => '<font color=#AAAAAA><B>&bull;</B></font>',
		3 => 'X',
		4 => '<font color=#FFEEEE><B>X</B></font>',
		5 => '<font color=#FFEEEE><B>&bull;</B></font>',
		6 => '<font color=#AAAAEE><B>&bull;</B></font>',
		7 => '<font color=#777777><B>&bull;</B></font>',
		8 => '<font color=#AAAAAA><B>&bull;</B></font>'
	);
	$sp = mysql_query('SELECT * FROM `battle_users` WHERE `battle` = "'.$btl['id'].'"');
	while($pl = mysql_fetch_array($sp)) {
		if(!isset($uids[$pl['id']])) {
			$i = count($users);
			$users[$i] = $pl;
			$uids[$pl['uid']] = $i;
			//
			$users[$i]['value'] = array(
				'y' => 0, //��������+�����������+������ �����
				'b' => 0, //�������� ������
				'p' => 0, //�� �������� ������, �� ��������� ������
				'zb' => array( //������ ��� �����
					
				),
				'sa' => array( //���������� ������
					0 => '',
					1 => '',
					2 => '',
					3 => '',
					4 => '',
					5 => ''
				),
				'sb' => array( //���������� ������
					0 => '',
					1 => '',
					2 => '',
					3 => '',
					4 => '',
					5 => ''
				)
			);
			$sp2 = mysql_query('SELECT * FROM `battle_stat` WHERE `battle` = "'.$btl['id'].'" AND `uid1` = "'.$pl['uid'].'" ORDER BY `id` ASC');
			while($pl2 = mysql_fetch_array($sp2)) {
				//��������� ������
				$users[$i]['yrn'] += $pl2['yrn'];
				$users[$i]['yrn_krit'] += $pl2['yrn_krit'];
				//���������� �����
				$users[$i]['gaa']++;
				if( $users[$i]['yrn'] > 0 ) {
					$users[$i]['ga']++;
				}
				if( $users[$i]['yrn_krit'] > 0 ) {
					$users[$i]['gak']++;
				}
				//�������� ���� ��� �����
				$j = 0;
				while($j < $pl2['ma']) {
					$users[$i]['zona'][$pl2['a'][$j]]++;
					//
					$za = $pl2['a'][$j];
					$k = 1;
					while($k <= 5) {
						if( $za == $k ) {
							$tpa = $pl2['type_a'][$j];
							$zag[$k] = true;							
							$users[$i]['value']['sa'][$k] .= $tpas[$tpa];
						}else{
							$zag[$k] = false;
							//$users[$i]['value']['sa'][$za] .= '.';
						}
						$k++;
					}
					//
					$j++;
				}
				$j = $pl2['b'];
				$k = 0;
				while($k < $pl2['mb']) {
					if( $j > 5 ) {
						$j = 1;
					}
					$users[$i]['value']['zb'][] = array( 0 => $j , 1 => 0 );
					$users[$i]['zonb'][$j]++;
					$j++;
					$k++;
				}
				//
				$k = 1;
				while($k <= 5) {
					if( $zag[$k] == false ) {
						$users[$i]['value']['sa'][$k] .= ' ';
					}
					$k++;
				}
				//
			}
			//
			$sp2 = mysql_query('SELECT * FROM `battle_stat` WHERE `battle` = "'.$btl['id'].'" AND `uid2` = "'.$pl['uid'].'" ORDER BY `id` ASC');
			$k = 0;
			while($pl2 = mysql_fetch_array($sp2)) {
				//��������� ������
				$users[$i]['_yrn'] -= $pl2['yrn'];
				$users[$i]['_yrn_krit'] -= $pl2['yrn_krit'];
				//�������� ���� ���� ������
				$j = 0; $zag = array();
				while($j < $pl2['ma']) {
					$users[$i]['value']['zb'][$k][1] = $pl2['type_a'][$j];
					if( $pl2['type_a'][$j] == 2 || $pl2['type_a'][$j] == 6 || $pl2['type_a'][$j] == 7 || $pl2['type_a'][$j] == 8 ) {
						$users[$i]['value']['y']++;
					}elseif( $pl2['type_a'][$j] == 3 ) {
						$users[$i]['value']['b']++;
					}else{
						$users[$i]['value']['p']++;
					}
					//
					$j++;
				}
				//
				$k++;
				//
			}
			//���������� ������
			$k = 0;
			$h = 0;
			$bjj = array();
			while( $k < count($users[$i]['value']['zb']) ) {
				$zb = 0+$users[$i]['value']['zb'][$k][0];
				$zt = 0+$users[$i]['value']['zb'][$k][1];
				$bjj[$zb] = true;
				$users[$i]['value']['sb'][$zb] .= ''.$tpbs[$zt].'';
				if( $h < 1 ) {
					$h++;
				}else{
					$d = 1;
					while($d <= 5) {
						if( $bjj[$d] == true ) {
							
						}else{
							$users[$i]['value']['sb'][$d] .= ' ';
						}
						$d++;
					}
					$bjj = array();
					$h = 0;
				}
				$k++;
			}
			//
		}	
	}
	
	$usr = '';
	$tm = array();
	$tm_u = array();
	$tm_v = array();
	
	$i = 0;
	while($i < count($users)) {
		if( $users[$i] > 0 ) {
			$us = $users[$i];
			if( !isset($tm[$us['team']]) ) {
				$tm[$us['team']] = '';
				$tm_v[] = $us['team'];
			}
			$tm_u[$us['team']][] = $i;
			$tm[$us['team']] .= con_login($us);
			$tm[$us['team']] .= ', ';
			unset($us);
		}
		$i++;	
	}	
	$i = 0;
	while($i < count($tm_v)) {
		$usr .= rtrim($tm[$tm_v[$i]],', ');
		if( $i < count($tm_v)-1 ) {
			$usr .= ' &nbsp; <b>������</b> &nbsp; ';
		}
		$i++;
	}
	//
	$usr = '<H4>��������� ��������</H4>'.$usr.'<br><br>';
	//
	$r = '';
	//
	$r .= '<H4>������������������ ������</H4>';	
	$r .= '<TABLE border=1 cellspacing=0 cellpadding=4>
<TR><TD align=center>�����</TD><TD>���� �</TD><TD>������������������ ������</TD></TR>';	
	$i = 0;
	while($i <= count($tm_v)) {
		$j = 0;
		$team_data = array( 'g' => false );
		while($j < count($tm_u[$tm_v[$i]])) {
			$us = $users[$tm_u[$tm_v[$i]][$j]];
			if($us['id'] > 0) {
				$rh = '';
				$rh .= ''.$us['value']['sa'][1].'';
				$rh .= '<br>'.$us['value']['sa'][2].'';
				$rh .= '<br>'.$us['value']['sa'][3].'';
				$rh .= '<br>'.$us['value']['sa'][4].'';
				$rh .= '<br>'.$us['value']['sa'][5].'';
				$r .= '<TR><TD align=center nowrap>'.con_login($us).'</TD><TD nowrap align="right"><pre>������<br>�����<br>�����<br>����<br>����</pre></TD><TD style="font-size:16px" nowrap><pre>'.$rh.'</pre></TD></TR>';
			}
			unset($us);
			$j++;
		}
				
		$i++;
	}
	$r .= '</TABLE>';
	$r .= '(<b>X</b>&nbsp;-&nbsp;�������&nbsp;����, <font color=red><B>&curren;</b></font> - ����������� ���� ������ ���� , <font color=red><B>X</B></font>&nbsp;-&nbsp;�����������&nbsp;����, <font color=red><B><code>&Xi;</code></B></font>&nbsp;-&nbsp;���� + ������������, <font color=006600><B>X</B></font>&nbsp;-&nbsp;���.&nbsp;����������&nbsp;����,<BR>
&nbsp;<B>&bull;</B>&nbsp;-&nbsp;���������&nbsp;����������&nbsp;����, <font color=AAAAAA><B>&bull;</B></font>&nbsp;-&nbsp;���������, <font color=AAAAEE><B>&bull;</B></font>&nbsp;-&nbsp;���������, <font color=777777><B>&bull;</B></font>&nbsp;-&nbsp;����� ���� �����)';
	//
	$r .= '<H4>������������������ ������</H4>';
	$r .= '<TABLE border=1 cellspacing=0 cellpadding=4>
<TR><TD align=center>�����</TD><TD>����</TD><TD>������������������ ������</TD></TR>';	
	$i = 0;
	while($i <= count($tm_v)) {
		$j = 0;
		$team_data = array( 'g' => false );
		while($j < count($tm_u[$tm_v[$i]])) {
			$us = $users[$tm_u[$tm_v[$i]][$j]];
			if($us['id'] > 0) {
				$rh = '';
				$rh .= ''.$us['value']['sb'][1].'';
				$rh .= '<br>'.$us['value']['sb'][2].'';
				$rh .= '<br>'.$us['value']['sb'][3].'';
				$rh .= '<br>'.$us['value']['sb'][4].'';
				$rh .= '<br>'.$us['value']['sb'][5].'';
				$r .= '<TR><TD align=center nowrap>'.con_login($us).'</TD><TD nowrap align="right"><pre>������<br>�����<br>�����<br>����<br>����</pre></TD><TD style="font-size:16px" nowrap><pre>'.$rh.'</pre></TD></TR>';
			}
			unset($us);
			$j++;
		}
				
		$i++;
	}
	$r .= '</TABLE>';
	$r .= '(<b>X</b>&nbsp;-&nbsp;������� ����, <b><FONT COLOR=red>X</FONT></b>&nbsp;-&nbsp;������� ���� ������, <B>&bull;</B>&nbsp;-&nbsp;��������� ����,
<font color=#AAAAAA><B>&bull;</B></font>&nbsp;-&nbsp;���������, 
<font color=#AAAAEE><B>&bull;</B></font>&nbsp;-&nbsp;���������, 
<font color=#777777><B>&bull;</B></font>&nbsp;-&nbsp;����� �����)';
	//
	$r .= '<H4>��������</H4>';	
	$r .= '<TABLE border=1 cellspacing=0 cellpadding=4>
<TR><TD align=center>&nbsp;</TD><TD align=center>�����</TD><TD>�����</TD><TD>�����</TD><TD>���������</TD><TD>������</TD><TD>����</TD><TD>������</TD><TD>��������</TD></TR>';
	//<TR><TD align=right>&nbsp;</TD><TD>&nbsp;</TD><TD>&nbsp;</TD><TD align=center>&nbsp;</TD><TD align=center>&nbsp;</TD><TD align=center>&nbsp;</TD><TD align=center>&nbsp;</TD><TD align=center>&nbsp;</TD></TR>
	$i = 0;
	while($i <= count($tm_v)) {
		$j = 0;
		$team_data = array( 'g' => false );
		while($j < count($tm_u[$tm_v[$i]])) {
			$us = $users[$tm_u[$tm_v[$i]][$j]];
			if($us['id'] > 0) {
				$team_data['g'] = true;
				if( $us['hp'] < 0 ) {
					$us['hp'] = 0;
				}
				if( $us['yrn'] < 0 ) {
					$us['yrn'] = 0;
				}
				if( $us['yrn_krit'] < 0 ) {
					$us['yrn_krit'] = 0;
				}
				if( $us['_yrn'] > 0 ) {
					$us['_yrn'] = 0;
				}
				if( $us['_yrn_krit'] > 0 ) {
					$us['_yrn_krit'] = 0;
				}
				$team_data['ga'] += $us['ga'];
				$team_data['gaa'] += $us['gaa'];
				$team_data['gak'] += $us['gak'];
				$team_data['hp'] += $us['hp'];
				$team_data['hpAll'] += $us['hpAll'];
				$team_data['yrn'] += $us['yrn'];
				$team_data['yrn_krit'] += $us['yrn_krit'];
				$team_data['_yrn'] += $us['_yrn'];
				$team_data['_yrn_krit'] += $us['_yrn_krit'];
				$team_data['val_b'] += $us['value']['b'];
				$team_data['val_y'] += $us['value']['y'];
				$team_data['val_p'] += $us['value']['p'];
				$winw = '';
				if( $us['hp'] < 1 ) {
					$us['hp'] = '<font color=red>0</font>';
					$winw = '<img title="�����" width="7" height="7" src="http://img.combatz.ru/i/ico/looses.gif">';
				}else{
					$winw = '<img title="�����" width="7" height="7" src="http://img.combatz.ru/i/ico/wins.gif">';
				}
				$r .= '<TR><TD valign=middle align=center>'.$winw.'</TD><TD align=right>'.con_login($us).' ['.$us['hp'].'/'.$us['hpAll'].']</TD><TD>'.(0+$us['zona'][1]).'/'.(0+$us['zona'][2]).'/'.(0+$us['zona'][3]).'/'.(0+$us['zona'][4]).'/'.(0+$us['zona'][5]).'</TD><TD>'.(0+$us['zonb'][1]).'/'.(0+$us['zonb'][2]).'/'.(0+$us['zonb'][3]).'/'.(0+$us['zonb'][4]).'/'.(0+$us['zonb'][5]).'</TD><TD align=center>'.(0+$us['ga']).'(<font color=red>'.(0+$us['gak']).'</font>)/'.($us['gaa']).'</TD><TD align=center>'.$us['value']['b'].'/'.$us['value']['y'].'/'.$us['value']['p'].'</TD><TD align=center>'.$us['yrn'].'/<font color=red>'.$us['yrn_krit'].'</font></TD><TD align=center>'.(-$us['_yrn']).'</TD><TD align=center>0</TD></TR>';
			}
			unset($us);
			$j++;
		}
		if( $team_data['g'] == true ) {
			$winw = '--';
			if( $team_data['hp'] < 1 ) {
				$team_data['hp'] = '0';
			}else{
				$winw = '<img src="http://img.combatz.ru/i/flag.gif" width="20" height="20" title="����������">';
			}
			$r .= '<TR bgcolor=d2d0d0><TD align=center>'.$winw.'</TD><TD align=right><b class="CSSteam'.$tm_v[$i].'">����� ['.$team_data['hp'].'/'.$team_data['hpAll'].']</b></TD><TD>&nbsp;</TD><TD>&nbsp;</TD><TD align=center>'.(0+$team_data['ga']).'(<font color=red>'.(0+$team_data['gak']).'</font>)/'.($team_data['gaa']).'</TD><TD align=center>'.$team_data['val_b'].'/'.$team_data['val_y'].'/'.$team_data['val_p'].'</TD><TD align=center>'.$team_data['yrn'].'/<font color=red>'.$team_data['yrn_krit'].'</font></TD><TD align=center>'.(-$team_data['_yrn']).'</TD><TD align=center>0</TD></TR>';
		}
		
		$i++;
	}
	$r .= '</TABLE>';
	//
	$r .= '
����� - ��� ��������� � ������� �����: [������/�����]<br>
����� - ���������� ������ �� ��������: ������/�����/�����/����/����<br>
����� - ���������� ������ �� ��������: ������/�����/�����/����/����<br>
��������� - ������� ��������� <font color=red>(�� ��� ������)</font> / ����� ������<br>
������ - ������ ������������� / �������� / ��������� ������<br>
���� - ������ HP �� ����������� / �� ��� <font color=red>�������</font><br>
������ - �������� ����������� <br>
�������� - ������������� HP<br>';
	
	$r = '<div>'.$b.'</div><div><span style="float:left;">'.$tbtl.$p.'</span><span style="float:right;">�������� ����� ���: <i>����������</i></span></div><br><br>'.$usr.$r.'<div align="left">'.$p.'</div>';

}elseif(!isset($btl['id']))
{
	$r = '<br><br><center>������ ����� ���������� ����� ������� ��������� � ��������� ���� ...</center>';
}else{
	include('jx/battle/log_text.php');
	function testlog($pl)
	{
		global $log_text,$c,$u,$code;
		if($pl['type']==1 || $pl['type']==6)
			{
			$dt = explode('||',$pl['vars']);
			$i = 0; $d = array();
			while($i<count($dt))
			{
				$r = explode('=',$dt[$i]);
				if($r[0]!='')
				{
					$d[$r[0]] = $r[1];
				}
				$i++;
			}
			//������� ����
			$rt = $pl['text'];
			//�������� ������
			$rt = str_replace('{u1}','<span onClick="top.addTo(\''.$d['login1'].'\',\'to\'); return false;" oncontextmenu="top.infoMenu(\''.$d['login1'].'\',event,\'chat\'); return false;" class="CSSteam'.$d['t1'].'">'.$d['login1'].'</span>',$rt);
			$rt = str_replace('{u2}','<span onClick="top.addTo(\''.$d['login2'].'\',\'to\'); return false;" oncontextmenu="top.infoMenu(\''.$d['login2'].'\',event,\'chat\'); return false;" class="CSSteam'.$d['t2'].'">'.$d['login2'].'</span>',$rt);
			$rt = str_replace('{pr}','<b>'.$d['prm'].'</b>',$rt);
			$rt = str_replace('^^^^','=',$rt);
			$rt = str_replace('{tm1}','<span class="date">'.date('H:i',$d['time1']).'</span>',$rt);
			$rt = str_replace('{tm2}','<span class="date">'.date('H:i',$d['time2']).'</span>',$rt);
			$rt = str_replace('{tm3}','<span class="date">'.date('d.m.Y H:i',$d['time1']).'</span>',$rt);
			$rt = str_replace('{tm4}','<span class="date">'.date('d.m.Y H:i',$d['time2']).'</span>',$rt);
					
			$k01 = 1;
			$zb1 = array(1=>0,2=>0,3=>0,4=>0,5=>0);
			$zb2 = array(1=>0,2=>0,3=>0,4=>0,5=>0);
					
			if($d['bl2']>0)
			{
				$b11 = 1;
				$b12 = $d['bl1'];
				while($b11<=$d['zb1'])
				{
					$zb1[$b12] = 1;
					if($b12>=5 || $b12<0)
					{
						$b12 = 0;
					}
					$b12++;
					$b11++;
				}
			}
					
			if($d['bl2']>0)
			{
				$b11 = 1;
				$b12 = $d['bl2'];
				while($b11<=$d['zb2'])
				{
					$zb2[$b12] = 1;
					if($b12>=5 || $b12<0)
					{
						$b12 = 0;
					}
					$b12++;
					$b11++;
				}
			}
					
				
			while($k01<=5)
			{
				$zns01 = ''; $zns02 = '';
				$j01 = 1;
				while($j01<=5)
				{
					$zab1 = '0'; $zab2 = '0';
					if($j01==$k01)
					{
						$zab1 = '1';
						$zab2 = '1';
					}
					
					$zab1 .= $zb1[$j01];
					$zab2 .= $zb2[$j01];
						
					$zns01 .= '<img src="http://img.combatz.ru/i/zones/'.$d['t1'].'/'.$d['t2'].''.$zab1.'.gif">';
					$zns02 .= '<img src="http://img.combatz.ru/i/zones/'.$d['t2'].'/'.$d['t1'].''.$zab2.'.gif">';
					$j01++;
				}
				$rt = str_replace('{zn1_'.$k01.'}',$zns01,$rt);
				$rt = str_replace('{zn2_'.$k01.'}',$zns02,$rt);
				$k01++;
			}

			$j = 1;
			while($j<=21)
			{
				//������ R - ����� 1
				$r = $log_text[$d['s1']][$j];
				$k = 0;
				while($k<=count($r))
				{
					if(isset($log_text[$d['s1']][$j][$k]))
					{
						$rt = str_replace('{1x'.$j.'x'.$k.'}',$log_text[$d['s1']][$j][$k],$rt);
					}
					$k++;
				}
				//������ R - ����� 2
				$r = $log_text[$d['s2']][$j];
				$k = 0;
				while($k<=count($r))
				{
					if(isset($log_text[$d['s2']][$j][$k]))
					{
						$rt = str_replace('{2x'.$j.'x'.$k.'}',$log_text[$d['s2']][$j][$k],$rt);
					}
					$k++;
				}						
				$j++;
			}
			
			//�������� ������ ��������
			$rt = str_replace('{u1}','<span onClick="top.addTo(\''.$d['login1'].'\',\'to\'); return false;" oncontextmenu="top.infoMenu(\''.$d['login1'].'\',event,\'chat\'); return false;" class="CSSteam'.$d['t1'].'">'.$d['login1'].'</span>',$rt);
			$rt = str_replace('{u2}','<span onClick="top.addTo(\''.$d['login2'].'\',\'to\'); return false;" oncontextmenu="top.infoMenu(\''.$d['login2'].'\',event,\'chat\'); return false;" class="CSSteam'.$d['t2'].'">'.$d['login2'].'</span>',$rt);
			$rt = str_replace('{pr}','<b>'.$d['prm'].'</b>',$rt);
			$rt = str_replace('^^^^','=',$rt);
			$rt = str_replace('{tm1}','<span class="date">'.date('H:i',$d['time1']).'</span>',$rt);
			$rt = str_replace('{tm2}','<span class="date">'.date('H:i',$d['time2']).'</span>',$rt);
			$rt = str_replace('{tm3}','<span class="date">'.date('d.m.Y H:i',$d['time1']).'</span>',$rt);
			$rt = str_replace('{tm4}','<span class="date">'.date('d.m.Y H:i',$d['time2']).'</span>',$rt);
			
			//��������� ��������
			$pl['text'] = $rt;
		}
		return $pl['text'];
	}
	//�������� ����
	$min = round(30*((int)$_GET['p']-1));
	if($min<1)
	{
		$min = 0;
	}
	$max = $min+29;
	
	$based = 'battle_logs_save';
	$sp_cnt = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `battle_logs_save` WHERE `battle` = "'.$btl['id'].'" AND `id_hod` > '.$min.' AND `id_hod` <= '.$max.' ORDER BY `id_hod`, `time` ASC LIMIT 1'));
	if( $sp_cnt[0] < 1 ) {
		$based = 'battle_logs';
	}
	
	//���������� ��������
	$pmax = mysql_fetch_array(mysql_query('SELECT `id_hod`,`id` FROM `'.$based.'` WHERE `battle` = "'.$btl['id'].'" ORDER BY  `id_hod` DESC  LIMIT 1'));
	$pmax = $pmax['id_hod'];
	$pmax = ceil($pmax/30);
	
	if($min > round(30*($pmax-1)))
	{
		$min = round(30*($pmax-1));
		$max = $min+29;
	}
	$i = 1;
	while($i<=$pmax)
	{
		if((int)$_GET['p']==$i || ((int)$_GET['p']>$pmax && $i==$pmax) || ((int)$_GET['p']<1 && $i==1))
		{
			$p .= ' <a style="color:maroon" href="?log='.$btl['id'].'&p='.$i.'&rnd='.$code.'">'.$i.'</a> ';
		}else{
			$p .= ' <a href="?log='.$btl['id'].'&p='.$i.'&rnd='.$code.'">'.$i.'</a> ';	
		}
		$i++;
	}
	$h = 0; $clr = 'e2e0e0'; $cclr = '';
	$sp = mysql_query('SELECT * FROM `'.$based.'` WHERE `battle` = "'.$btl['id'].'" AND `id_hod` > '.$min.' AND `id_hod` <= '.($max+1).' ORDER BY `id_hod`, `id` ASC LIMIT 200');	
	while($pl = mysql_fetch_array($sp))
	{
		$pl['text'] = testlog($pl);
		$pl['text'] = str_replace('\"','"',$pl['text']);
		if($h!=$pl['id_hod'])
		{
			if($h>0)
			{
				if($clr == 'e2e0e0') {
					$clr = 'e3e1e1';
				}else{
					$clr = 'e2e0e0';
				}
				$cclr = 'border-top:1px solid #b1b1b1;';
				//$r .= '<hr>';
			}
			$h = $pl['id_hod'];
		}else{
			//$r .= '<br>';	
		}
		$r .= '<div style="background-color:#'.$clr.';'.$cclr.'padding:1px;">'.$pl['text'].'</div>';	
		$cclr = '';
	}
	//�������� ��������
	$p = '��������: '.$p;
	$usr = '';
	if($btl['team_win'] == -1) {
		$sp = mysql_query('SELECT 
			`u`.`id`,`u`.`login`,`u`.`level`,`u`.`sex`,`u`.`align`,`u`.`online`,`u`.`battle`,`u`.`clan`,
			`s`.`hpNow`,`s`.`bot`,`s`.`team`,`u`.`city`
		 FROM `users` AS `u` LEFT JOIN `stats` AS `s` ON `s`.`id` = `u`.`id` WHERE `u`.`battle` = "'.$btl['id'].'" AND `s`.`hpNow` >= 1');
		 
		 $usrs = array(-1 => array());
		 
		while($pl = mysql_fetch_array($sp)) {
			if(!isset($usrs[$pl['team']])) {
				$usrs[$pl['team']] = '';
				$usrs[-1][count($usrs[-1])] = $pl['team'];
			}
			if($pl['align'] > 0) {
				$usrs[$pl['team']] .= '<img src="http://img.combatz.ru/i/align/align'.$pl['align'].'.gif" width="12" height="15">';
			}
			if($pl['clan'] > 0) {
				$usrs[$pl['team']] .= '<img src="http://img.combatz.ru/i/clan/'.$pl['clan'].'.gif" width="24" height="15">';
			}
			$pl['stats_r'] = $u->getStats($pl['id']);			
			$usrs[$pl['team']] .= '<b class="CSSteam'.$pl['team'].'">'.$pl['login'].'</b> ['.ceil($pl['stats_r']['hpNow']).'/'.$pl['stats_r']['hpAll'].'],';		
		}
		
		if(count($usrs[-1]) > 0) {
			$i = 0;
			while($i < count($usrs[-1])) {
				$usr .= rtrim($usrs[$usrs[-1][$i]],',');
				if(count($usrs[-1]) > $i+1) {
					$usr .= ' &nbsp; <b><font color=black>������</font></b> &nbsp; ';
				}
				$i++;
			}
		}
		
		if($usr != '') {
			$usr = '<div align="center">'.$usr.'</div><hr>';
		}
	}
	$tbtl = '<img src="http://img.combatz.ru/i/fighttype'.$btl['type'].'.gif">';
	if( $btl['invis'] > 0 ) {
		$tbtl .= '<img src="http://img.combatz.ru/i/fighttypehidden0.gif">';
	}
	if($btl['type'] == 0) {
		$tbtl = '��� ���: '.$tbtl.' (���������� ��������) &nbsp; &nbsp; ';
	}elseif($btl['type'] == 1) {
		$tbtl = '��� ���: '.$tbtl.' (�������� ��������) &nbsp; &nbsp; ';
	}else{
		$tbtl = '��� ���: '.$tbtl.' (���������� ��������) &nbsp; &nbsp; ';
	}
	
	if( $btl['izlom'] > 0 ) {
		$tbtl .= '�����: '.$btl['izlomRoundSee'].' &nbsp; &nbsp; ';
	}
	
	$r = '<div>'.$b.'</div><div><span style="float:left;">'.$tbtl.$p.'</span><span style="float:right;">�������� ����� ���: <i>����������</i></span></div><br><hr>'.$usr.$r.'<hr>'.$usr.'<div align="left">'.$p.'</div>';
}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<title>�����: ��������</title>
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
<H3><IMG SRC="http://img.combatz.ru/i/fighttype2.gif" WIDTH=20 HEIGHT=20> ���������� ����<? if( $based != 'battle_logs' ) { echo ' (����� ���������)'; } ?> &nbsp; <a href="http://www.combatz.ru/">www.combatz.ru</a> <IMG SRC="http://img.combatz.ru/i/fighttype2.gif" WIDTH=20 HEIGHT=20></H3>
<? echo $r; ?>
</body>
</html>