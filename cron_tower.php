<?php
function getIP() {
   if(isset($_SERVER['HTTP_X_REAL_IP'])) return $_SERVER['HTTP_X_REAL_IP'];
   return $_SERVER['REMOTE_ADDR'];
}

if( $_SERVER['HTTP_CF_CONNECTING_IP'] != $_SERVER['SERVER_ADDR'] && $_SERVER['HTTP_CF_CONNECTING_IP'] != '127.0.0.1' ) {	die('Hello pussy!');   }

if(getIP() != $_SERVER['SERVER_ADDR'] && getIP() != '127.0.0.1' && getIP() != '' && getIP() != '212.224.113.192') {
	//die(getIP().'<br>'.$_SERVER['SERVER_ADDR']);
}

define('GAME',true);
setlocale(LC_CTYPE ,"ru_RU.CP1251");
include('_incl/__config.php');
include('_incl/class/__db_connect.php');
include('_incl/class/__user.php');
include('_incl/class/__magic.php');

function microLogin2($bus) {
	$bus['login_BIG']  = '<b>';
	if( $bus['align'] > 0 ) {
		$bus['login_BIG'] .= '<img src=http://img.combatz.ru/i/align/align'.$bus['align'].'.gif width=12 height=15 >';
	}
	if( $bus['clan'] > 0 ) {
		$bus['login_BIG'] .= '<img src=http://img.combatz.ru/i/clan/'.$bus['clan'].'.gif width=24 height=15 >';
	}
	$bus['login_BIG'] .= ''.$bus['login'].'</b>['.$bus['level'].']<a target=_blank href=/inf.php?'.$bus['id'].' ><img width=12 hiehgt=11 src=http://img.combatz.ru/i/inf_capitalcity.gif ></a>';
	return $bus['login_BIG'];
}

function addItem($id,$uid,$md = NULL,$dn = NULL,$mxiznos = NULL) {
		$rt = -1;
		$i = mysql_fetch_array(mysql_query('SELECT `im`.`id`,`im`.`name`,`im`.`img`,`im`.`type`,`im`.`inslot`,`im`.`2h`,`im`.`2too`,`im`.`iznosMAXi`,`im`.`inRazdel`,`im`.`price1`,`im`.`price2`,`im`.`magic_chance`,`im`.`info`,`im`.`massa`,`im`.`level`,`im`.`magic_inci`,`im`.`overTypei`,`im`.`group`,`im`.`group_max`,`im`.`geni`,`im`.`ts`,`im`.`srok`,`im`.`class`,`im`.`class_point`,`im`.`anti_class`,`im`.`anti_class_point`,`im`.`max_text`,`im`.`useInBattle`,`im`.`lbtl`,`im`.`lvl_itm`,`im`.`lvl_exp`,`im`.`lvl_aexp` FROM `items_main` AS `im` WHERE `im`.`id` = "'.mysql_real_escape_string($id).'" LIMIT 1'));
		if(isset($i['id']))
		{
			$d = mysql_fetch_array(mysql_query('SELECT `id`,`items_id`,`data` FROM `items_main_data` WHERE `items_id` = "'.$i['id'].'" LIMIT 1'));		
			//����� ����
			$data = $d['data'];	
			if($i['ts']>0)
			{
				$ui = mysql_fetch_array(mysql_query('SELECT `id`,`login` FROM `users` WHERE `id` = "'.mysql_real_escape_string($uid).'" LIMIT 1'));
				$data .= '|sudba='.$ui['login'];
			}
			if($md!=NULL)
			{
				$data .= $md;
			}	
			
	
			if($dn!=NULL)
			{
				//������� � ����������� �� ����������
				if($dn['del']>0)
				{
					$i['dn_delete'] = 1;
				}
			}
			if($mxiznos > 0) {
				$i['iznosMAXi'] = $mxiznos;
			}
			$ins = mysql_query('INSERT INTO `items_users` (`overType`,`item_id`,`uid`,`data`,`iznosMAX`,`geniration`,`magic_inc`,`maidin`,`lastUPD`,`time_create`,`dn_delete`) VALUES (
											"'.$i['overTypei'].'",
											"'.$i['id'].'",
											"'.$uid.'",
											"'.$data.'",
											"'.$i['iznosMAXi'].'",
											"'.$i['geni'].'",
											"'.$i['magic_inci'].'",
											"capitalcity",
											"'.time().'",
											"'.time().'",
											"'.$i['dn_delete'].'")');
			if($ins)
			{
				$rt = mysql_insert_id();
			}else{
				$rt = 0;	
			}			
	}
	return $rt;
}


function timeOut($ttm) {
	    $out = '';
		$time_still = $ttm;
		$tmp = floor($time_still/2592000);
		$id=0;
		if ($tmp > 0) 
		{ 
			$id++;
			if ($id<3) {$out .= $tmp." ���. ";}
			$time_still = $time_still-$tmp*2592000;
		}
		/*
		$tmp = floor($time_still/604800);
		if ($tmp > 0) 
		{ 
			$id++;
			if ($id<3) {$out .= $tmp." ���. ";}
			$time_still = $time_still-$tmp*604800;
		}
		*/
		$tmp = floor($time_still/86400);
		if ($tmp > 0) 
		{ 
			$id++;
			if ($id<3) {$out .= $tmp." ��. ";}
			$time_still = $time_still-$tmp*86400;
		}
		$tmp = floor($time_still/3600);
		if ($tmp > 0) 
		{ 
			$id++;
			if ($id<3) {$out .= $tmp." �. ";}
			$time_still = $time_still-$tmp*3600;
		}
		$tmp = floor($time_still/60);
		if ($tmp > 0) 
		{ 
			$id++;
			if ($id<3) {$out .= $tmp." ���. ";}
		}
		if($out=='')
		{
			if($time_still<0)
			{
				$time_still = 0;
			}
			$out = $time_still.' ���.';
		}
		return $out;
}

function e($t) {
	mysql_query('INSERT INTO `chat` (`text`,`city`,`to`,`type`,`new`,`time`) VALUES ("<font color=#cb0000>'.mysql_real_escape_string($t).'</font>","capitalcity","","6","1","'.time().'")');
}

function e2($t) {
	mysql_query('INSERT INTO `chat` (`text`,`city`,`to`,`type`,`new`,`time`) VALUES ("<font color=#cb0000>'.mysql_real_escape_string($t).'</font>","capitalcity","LEL","6","1","-1")');
}

//����� ��������
$cnfg = array(
	'time_restart' => 0.75,
	'time_puti' => 240
);

//�������� 1 �������� �� 2
function bs_atack($bs,$u1,$u2) {
	global $magic;
	if( isset($u1['id'],$u2['id']) ) {
		$btl_id = $magic->atackUser($u1['id'],$u2['id'],$u2['team'],$u2['battle']);
		if( $btl_id > 0 ) {
			mysql_query('UPDATE `battle` SET `inTurnir` = "'.$bs['id'].'" WHERE `id` = "'.$btl_id.'" LIMIT 1');
		}
		$usr_real = mysql_fetch_array(mysql_query('SELECT `id`,`sex`,`login`,`align`,`clan`,`battle`,`level` FROM `users` WHERE `login` = "'.$u2['login'].'" AND `inUser` = "'.$u2['id'].'" LIMIT 1'));
		if( !isset($usr_real['id']) ) {
			$usr_real = $u2;
		}
		$me_real = mysql_fetch_array(mysql_query('SELECT `id`,`sex`,`login`,`align`,`clan`,`battle`,`level` FROM `users` WHERE `inUser` = "'.$u1['id'].'" AND `login` = "'.$u1['login'].'" LIMIT 1'));
		if( !isset($me_real['id']) ) {
			$me_real = $u1;
		}
		if( $u2['battle'] > 0 ) {
			$u2['battle'] = mysql_fetch_array(mysql_query('SELECT `id` FROM `battle` WHERE `id` = "'.$u2['battle'].'" AND `team_win` = "-1" LIMIT 1'));	
			if( isset($u2['battle']['id']) ) {
				$u2['battle'] = $u2['battle']['id'];
			}else{
				$u2['battle'] = 0;
			}
		}
		if( $u2['battle'] > 0 ) {
			//������� � ��� ��
			if( $u1['sex'] == 0 ) {
				$text = '{u1} �������� � �������� ������ {u2} <a target=_blank href=/logs.php?log='.$btl_id.' >��</a>';
			}else{
				$text = '{u1} ��������� � �������� ������ {u2} <a target=_blank href=/logs.php?log='.$btl_id.' >��</a>';
			}
		}else{
			//������� � ��� ��
			if( $u1['sex'] == 0 ) {
				$text = '{u1} ����� �� {u2} ��������� ��� <a target=_blank href=/logs.php?log='.$btl_id.' >��</a>';
			}else{
				$text = '{u1} ������ �� {u2} ��������� ��� <a target=_blank href=/logs.php?log='.$btl_id.' >��</a>';
			}
		}
		if( isset($usr_real['id'])) {
			$usrreal = '';
			if( $usr_real['align'] > 0 ) {
				$usrreal .= '<img src=http://img.combatz.ru/i/align/align'.$usr_real['align'].'.gif width=12 height=15 >';
			}
			if( $usr_real['clan'] > 0 ) {
				$usrreal .= '<img src=http://img.combatz.ru/i/clan/'.$usr_real['clan'].'.gif width=24 height=15 >';
			}
			$usrreal .= '<b>'.$usr_real['login'].'</b>['.$usr_real['level'].']<a target=_blank href=/inf.php?'.$usr_real['id'].' ><img width=12 hiehgt=11 src=http://img.combatz.ru/i/inf_capitalcity.gif ></a>';
		}else{
			$mereal = '<i>���������</i>[??]';
		}
		if( isset($me_real['id']) ) {
			$mereal = '';
			if( $me_real['align'] > 0 ) {
				$mereal .= '<img src=http://img.combatz.ru/i/align/align'.$me_real['align'].'.gif width=12 height=15 >';
			}
			if( $me_real['clan'] > 0 ) {
				$mereal .= '<img src=http://img.combatz.ru/i/clan/'.$me_real['clan'].'.gif width=24 height=15 >';
			}
			$mereal .= '<b>'.$me_real['login'].'</b>['.$me_real['level'].']<a target=_blank href=/inf.php?'.$me_real['id'].' ><img width=12 hiehgt=11 src=http://img.combatz.ru/i/inf_capitalcity.gif ></a>';
		}else{
			$mereal = '<i>���������</i>[??]';
		}
		$text = str_replace('{u1}',$mereal,$text);
		$text = str_replace('{u2}',$usrreal,$text);
		//��������� � ��� ��
		mysql_query('INSERT INTO `bs_logs` (`type`,`text`,`time`,`id_bs`,`count_bs`,`city`,`m`,`u`) VALUES (
			"1", "'.mysql_real_escape_string($text).'", "'.time().'", "'.$bs['id'].'", "'.$bs['count'].'", "'.$bs['city'].'",
			"'.round($bs['money']*0.85,2).'","'.$i.'"
		)');
	}
}

//������ �� ���������
function nostart($pl) {
	global $cnfg;
	$r = false;
	if( $pl['users'] < 2 ) {
		//������������ �������
		$r = true;
		$pl['time_start'] = time() + $cnfg['time_restart'] * (60*60);
		if( $pl['users'] > 0 ) {
			e('������ ��� '.$pl['to_lvl'].' ������� � <b>����� ������</b> �� ������� �� �������: ������������ ����������. ������ ���������� ������� ����� '.timeOut($pl['time_start']-time()).' (<small>'.date('d.m.Y H:i',$pl['time_start']).'</small>)');
		}else{
			//if( timeOut($pl['time_start']-time()) != '44 ���.' ) {
				e('������ ������� ��� '.$pl['to_lvl'].' ������� � <b>����� ������</b> ����� '.timeOut($pl['time_start']-time()).' (<small>'.date('d.m.Y H:i',$pl['time_start']).'</small>), ������� �������� ����: 0.00 ��., ������: 0');
			//}
		}
		//������� ������� �������
		$sp = mysql_query('SELECT * FROM `bs_zv` WHERE `bsid` = "'.$pl['id'].'" AND `finish` = "0"');
		while( $pu = mysql_fetch_array($sp) ) {
			mysql_query('UPDATE `users` SET `money` = `money` + "'.$pu['money'].'" WHERE `id` = "'.$pu['uid'].'" LIMIT 1');
			mysql_query('UPDATE `bs_zv` SET `finish` = "'.time().'" WHERE `id` = "'.$pu['id'].'" LIMIT 1');
		}
		//���������� �������
		mysql_query('UPDATE `bs_turnirs` SET `ch1` = "0",`ch2` = "0", `status` = "0", `money` = "0", `time_start` = "'.$pl['time_start'].'",`users` = "0",`users_finish` = "0" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
	}
	return $r;
}

//���������� "�����������"
function add_arhiv($pl,$user) {
	$return = 0;
	mysql_query('INSERT INTO `users` (`login`,`pass`,`level`,`inTurnir`,`sex`,`obraz`,`name`,`online`,`city`,`room`,`align`,`clan`,`cityreg`,`bithday`,`activ`) VALUES (
		"'.$user['login'].'","bstowerbot","'.$user['level'].'","'.$pl['id'].'","'.$user['sex'].'","'.$user['obraz'].'","'.$user['login'].'","'.(time()+60*60*24).'","'.$user['city'].'","'.$user['room'].'","'.$user['align'].'","'.$user['clan'].'","capitalcity","01.02.2003","0"
	)');
	$return = mysql_insert_id();
	if( $return > 0 ) {
		$ins = mysql_query('INSERT INTO `stats` (`id`,`stats`,`bot`,`x`,`y`,`upLevel`) VALUES (
			"'.$return.'","s1=30|s2=31|s3=33|s4=30|s5=30|s6=1|s7=25|rinv=40|m9=5|m6=10","2","'.$user['x'].'","'.$user['y'].'","98"
		)');
		if(!$ins) {
			mysql_query('DELETE FROM `users` WHERE `id` = "'.$return.'" LIMIT 1');
			$return = 0;
		}
	}
	return $return;
}

//��������� ������� ������
function backusers($pl) {
	$sp = mysql_query('SELECT * FROM `bs_zv` WHERE `bsid` = "'.$pl['id'].'" AND `off` = "0" AND `inBot` > 0');
	while( $pu = mysql_fetch_array($sp) ) {
		//�������� �����
		mysql_query('DELETE FROM `users` WHERE `id` = "'.$pu['inBot'].'" LIMIT 1');
		mysql_query('DELETE FROM `stats` WHERE `id` = "'.$pu['inBot'].'" LIMIT 1');
		mysql_query('DELETE FROM `actions` WHERE `uid` = "'.$pu['inBot'].'"');
		mysql_query('DELETE FROM `items_users` WHERE `uid` = "'.$pu['inBot'].'"');
		mysql_query('DELETE FROM `eff_users` WHERE `uid` = "'.$pu['inBot'].'"');
		mysql_query('DELETE FROM `users_delo` WHERE `uid` = "'.$pu['inBot'].'"');
		//���������� ���������
		mysql_query('UPDATE `users` SET `inUser` = "0" WHERE `id` = "'.$pu['uid'].'" LIMIT 1');
		//��������� ������
		mysql_query('UPDATE `bs_zv` SET `off` = "'.time().'" WHERE `id` = "'.$pu['id'].'" LIMIT 1');
	}
	//�����������
	$sp = mysql_query('SELECT * FROM `users` WHERE `pass` = "bstowerbot" AND `inTurnir` = "'.$pl['id'].'"');
	while( $pu = mysql_fetch_array($sp) ) {
		mysql_query('DELETE FROM `users` WHERE `id` = "'.$pu['id'].'" LIMIT 1');
		mysql_query('DELETE FROM `stats` WHERE `id` = "'.$pu['id'].'" LIMIT 1');
		mysql_query('DELETE FROM `actions` WHERE `uid` = "'.$pu['id'].'"');
		mysql_query('DELETE FROM `items_users` WHERE `uid` = "'.$pu['id'].'"');
		mysql_query('DELETE FROM `eff_users` WHERE `uid` = "'.$pu['id'].'"');
		mysql_query('DELETE FROM `users_delo` WHERE `uid` = "'.$pu['id'].'"');
	}
	//������� �������� ����������� �� ��
	mysql_query('DELETE FROM `bs_items` WHERE `bid` = "'.$pl['id'].'" AND `count` = "'.$pl['count'].'"');
	//������� ������� � ��
	mysql_query('DELETE FROM `bs_actions` WHERE `bid` = "'.$pl['id'].'" AND `count` = "'.$pl['count'].'"');
	//������� ������� � ��
	mysql_query('DELETE FROM `bs_trap` WHERE `bid` = "'.$pl['id'].'" AND `count` = "'.$pl['count'].'"');
}

$exp2 = array(
	1=>30000,
	2=>300000
);
$st2s = array(
	7=>array(
		0=>10,
		1=>64,
		2=>8	
	),
	8=>array(
		0=>11,
		1=>78,
		2=>9	
	)
);

$sp = mysql_query('SELECT * FROM `bs_turnirs`');
while( $pl = mysql_fetch_array($sp) ) {
	$pl['to_lvl'] = $pl['level'];
	if( $pl['level'] != $pl['level_max'] ) {
		$pl['to_lvl'] .= '-'.$pl['level_max'].'';
	}
	$pl['to_lvl'] = '����';
	if( $pl['status'] == 1 ) {
		
		//������ ����, ��������� ����� �������, ���� ��������� ����� 6 �����
		if( $pl['time_start'] < time() - 6*60*60 ) {
			//��������� ������ �� �����
			//��������� � ��� ��
			$text = '������ ��������. ����������: <i>�����������</i> (������ ���������� �� ��������). �������� ����: <b>'.round($pl['money']*0.85,2).'</b> ��.';
			mysql_query('INSERT INTO `bs_logs` (`type`,`text`,`time`,`id_bs`,`count_bs`,`city`,`m`,`u`) VALUES (
				"1", "'.mysql_real_escape_string($text).'", "'.time().'", "'.$pl['id'].'", "'.$pl['count'].'", "'.$pl['city'].'",
				"'.round($pl['money']*0.85,2).'","'.$i.'"
			)');
			//
			//��������� ����������
			mysql_query('INSERT INTO `bs_statistic` (`bsid`,`count`,`time_start`,`time_finish`,`time_sf`,`type_bs`,`money`,`wlogin`,`wuid`,`walign`,`wclan`) VALUES (
				"'.$pl['id'].'","'.$pl['count'].'","'.$pl['time_start'].'","'.time().'","'.(time()-$pl['time_start']).'","'.$pl['type_btl'].'","'.round($pl['money']*0.85,2).'",
				"2","0","0","0"
			)');
			$pl['time_start'] = time() + $cnfg['time_restart'] * (60*60);
			e('������ ��� '.$pl['to_lvl'].' ������� � <b>����� ������</b> ���������� �� ��������. ������ ������ ������� ����� '.timeOut($pl['time_start']-time()).' (<small>'.date('d.m.Y H:i',$pl['time_start']).'</small>)');
			backusers($pl);
			$pl['count']++;
			mysql_query('UPDATE `bs_turnirs` SET `money` = "0",`count` = "'.$pl['count'].'",`status` = "0",`time_start` = "'.$pl['time_start'].'",`users` = "0",`users_finish` = "0",`ch1` = "0",`arhiv` = "0" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
		}else{
			mysql_query('UPDATE `users` SET `online` = "'.(time()+60*60*6).'" WHERE `inTurnir` = "'.$pl['id'].'" OR (`room` >= 362 AND `room` <= 366)  LIMIT '.($pl['users']+$pl['arhiv']));
			//��������� ����� �������
			if(  $pl['users'] < 2 ) {
				mysql_query('DELEE FROM `users` WHERE `login` LIKE "%(����%" AND `inTurnir` = "'.$pl['id'].'"');
				if(  $pl['users'] == 1 ) {
					$pl['usersn'] = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `users` WHERE `inTurnir` = "'.$pl['id'].'" LIMIT 1'));
					$pl['usersn'] = $pl['usersn'][0];
					if( $pl['users'] != $pl['usersn'] ) {
						//$pl['users'] = $pl['usersn'];
					}
				}
				//���. �������� �����
				if(  $pl['users'] == 1 ) {
					//��������� ������, ���� 1 ����������
					if( $pl['arhiv'] == 0 ) {
						//����������� ���, ��������� ������
						$uwin_bot = mysql_fetch_array(mysql_query('SELECT `id`,`money`,`login`,`level`,`align`,`clan` FROM `users` WHERE `inTurnir` = "'.$pl['id'].'" LIMIT 1'));
						$swin_bot = mysql_fetch_array(mysql_query('SELECT `id`,`exp` FROM `stats` WHERE `id` = "'.$uwin_bot['id'].'" LIMIT 1'));
						$uwin = mysql_fetch_array(mysql_query('SELECT `id`,`money`,`login`,`level`,`align`,`clan` FROM `users` WHERE `inUser` = "'.$uwin_bot['id'].'" AND `login` = "'.$uwin_bot['login'].'" LIMIT 1'));
						$swin = mysql_fetch_array(mysql_query('SELECT `id`,`exp` FROM `stats` WHERE `id` = "'.$uwin['id'].'" LIMIT 1'));
						
						//��������� ����������
						mysql_query('INSERT INTO `bs_statistic` (`bsid`,`count`,`time_start`,`time_finish`,`time_sf`,`type_bs`,`money`,`wlogin`,`wuid`,`walign`,`wclan`,`wlevel`) VALUES (
							"'.$pl['id'].'","'.$pl['count'].'","'.$pl['time_start'].'","'.time().'","'.(time()-$pl['time_start']).'","'.$pl['type_btl'].'","'.round($pl['money']*0.85,2).'",
							"'.$uwin['login'].'","'.$uwin['id'].'","'.$uwin['align'].'","'.$uwin['clan'].'","'.$uwin['level'].'"
						)');
						$pl['time_start'] = time() + $cnfg['time_restart'] * (60*60);
						if( isset($uwin['id']) ) {
							mysql_query('UPDATE `users` SET `money` = "'.($uwin['money']+round($pl['money']*0.85,2)).'" WHERE `id` = "'.$uwin['id'].'" LIMIT 1');
							mysql_query('UPDATE `stats` SET `exp` = "'.($swin['exp']+$swin_bot['exp']).'" WHERE `id` = "'.$uwin['id'].'" LIMIT 1');
							e('#'.$pl['usersn'].' ������ ��� '.$pl['to_lvl'].' ������� � <b>����� ������</b> ����������. ����������: '.microLogin2($uwin).'. ����: <b>'.round($pl['money']*0.85,2).'</b> ��. � <b>'.round($swin_bot['exp']).'</b> �����. ������ ������ ������� ����� '.timeOut($pl['time_start']-time()).' (<small>'.date('d.m.Y H:i',$pl['time_start']).'</small>)');
						}
						//��������� � ��� ��
						$text = '������ ��������. ����������: '.microLogin2($uwin).' ['.$uwin.'*'.$uwin_bot['login'].']. ����: <b>'.round($pl['money']*0.85,2).'</b> ��. � <b>'.round($swin_bot['exp']).'</b> �����.';
						mysql_query('INSERT INTO `bs_logs` (`type`,`text`,`time`,`id_bs`,`count_bs`,`city`,`m`,`u`) VALUES (
							"1", "'.mysql_real_escape_string($text).'", "'.time().'", "'.$pl['id'].'", "'.$pl['count'].'", "'.$pl['city'].'",
							"'.round($pl['money']*0.85,2).'","'.$i.'"
						)');
						//
						backusers($pl);
						$pl['count']++;
						mysql_query('UPDATE `bs_turnirs` SET `money` = "0",`count` = "'.$pl['count'].'",`status` = "0",`time_start` = "'.$pl['time_start'].'",`users` = "0",`users_finish` = "0",`ch1` = "0",`arhiv` = "0" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
					}else{
						//������� ���� ����� ����� �����������
						
					}
				}else{
					//��������� ����������
					mysql_query('INSERT INTO `bs_statistic` (`bsid`,`count`,`time_start`,`time_finish`,`time_sf`,`type_bs`,`money`,`wlogin`,`wuid`,`walign`,`wclan`) VALUES (
						"'.$pl['id'].'","'.$pl['count'].'","'.$pl['time_start'].'","'.time().'","'.(time()-$pl['time_start']).'","'.$pl['type_btl'].'","'.round($pl['money']*0.85,2).'",
						"1","0","0","0"
					)');
					//������ ��������� ������, �����
					$pl['time_start'] = time() + $cnfg['time_restart'] * (60*60);
					//��������� � ��� ��
					$text = '������ ��������. ����������: <i>�����������</i> (����� �� ������� � �����). �������� ����: <b>'.round($pl['money']*0.85,2).'</b> ��.';
					mysql_query('INSERT INTO `bs_logs` (`type`,`text`,`time`,`id_bs`,`count_bs`,`city`,`m`,`u`) VALUES (
						"1", "'.mysql_real_escape_string($text).'", "'.time().'", "'.$pl['id'].'", "'.$pl['count'].'", "'.$pl['city'].'",
						"'.round($pl['money']*0.85,2).'","'.$i.'"
					)');
					//
					backusers($pl);
					$pl['count']++;
					e('������ ��� '.$pl['to_lvl'].' ������� � <b>����� ������</b> ����������. ����������: <i>�����������</i> (����� �� ������� � �����). �������� ���� <b>'.round($pl['money']*0.85,2).'</b> ��. ������ ������ ������� ����� '.timeOut($pl['time_start']-time()).' (<small>'.date('d.m.Y H:i',$pl['time_start']).'</small>)');
					mysql_query('UPDATE `bs_turnirs` SET `money` = "'.round($pl['money']*0.85,2).'",`count` = "'.$pl['count'].'",`status` = "0",`time_start` = "'.$pl['time_start'].'",`users` = "0",`users_finish` = "0",`ch1` = "0",`arhiv` = "0" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
				}
			}else{
				//��� ����
				if( $pl['arhiv'] > 0 ) {
					$a_sp = mysql_query('SELECT `s`.`timeGo`,`u`.`align`,`u`.`clan`,`u`.`sex`,`u`.`pass`,`u`.`id`,`u`.`level`,`u`.`login`,`u`.`battle`,`s`.`x`,`s`.`y` FROM `users` AS `u` LEFT JOIN `stats` AS `s` ON `s`.`id` = `u`.`id` WHERE `u`.`pass` = "bstowerbot" AND `u`.`inTurnir` = "'.mysql_real_escape_string($pl['id']).'" LIMIT 10');
					while( $a_pl = mysql_fetch_array($a_sp) ) {
						$xy = mysql_fetch_array(mysql_query('SELECT * FROM `bs_map` WHERE `x` = "'.$a_pl['x'].'" AND `y` = "'.$a_pl['y'].'" LIMIT 1'));
						if( isset($xy['id']) ) {
							if( $a_pl['battle'] == 0 ) {
								//��������� ��������
								$sp_itm = mysql_query('SELECT * FROM `bs_items` WHERE `x` = "'.$a_pl['x'].'" AND `y` = "'.$a_pl['y'].'" AND `bid` = "'.$pl['id'].'" AND `count` = "'.$pl['count'].'" LIMIT 20');
								while( $pl_itm = mysql_fetch_array( $sp_itm ) ) {
									if( rand(0,100) < 21 ) {
										//��������� ������� �������
										$itm_id = mysql_fetch_array(mysql_query('SELECT * FROM `items_main` WHERE `id` = "'.$pl_itm['item_id'].'" LIMIT 1'));
										if( isset($itm_id['id']) ) {
											$itm_id['odevaem'] = addItem($itm_id['id'],$a_pl['id']);
											mysql_query('DELETE FROM `bs_items` WHERE `id` = "'.$pl_itm['id'].'" LIMIT 1');
											if( $itm_id['level'] <= $a_pl['level'] && $itm_id['odevaem'] > 0 ) {
												//��������
												if( $itm_id['inslot'] == 10 ) {
													$itm_id['inslot'] = rand(10,12);
												}
												mysql_query('UPDATE `items_users` SET `inOdet` = "0" WHERE `inOdet` = "'.$itm_id['inslot'].'" AND `uid` = "'.$a_pl['id'].'" LIMIT 1');
												mysql_query('UPDATE `items_users` SET `inOdet` = "'.$itm_id['inslot'].'" WHERE `id` = "'.$itm_id['odevaem'].'" LIMIT 1');
											}
										}
									}
								}
								unset($itm_id,$sp_itm,$pl_itm);
								//��������/����������� � ��������
								if( $pl['time_start'] < time() - $cnfg['time_puti'] ) {
									$sp_usr = mysql_query('SELECT `u`.`id`,`u`.`battle`,`u`.`login`,`u`.`level`,`u`.`align`,`u`.`clan`,`u`.`sex`,`s`.`team` FROM `stats` AS `s` LEFT JOIN `users` AS `u` ON `u`.`id` = `s`.`id` WHERE `s`.`x` = "'.$a_pl['x'].'" AND `u`.`pass` != "'.$a_pl['pass'].'" AND `s`.`y` = "'.$a_pl['y'].'" ORDER BY `s`.`timeGo` ASC LIMIT 5');
									while( $pl_usr = mysql_fetch_array($sp_usr) ) {
										if( rand(0,100) < 31 && $a_pl['battle'] == 0 ) {
											$pl_usr_real = mysql_fetch_array(mysql_query('SELECT `id`,`sex`,`login`,`level`,`clan`,`align`,`battle` FROM `users` WHERE `inUser` = "'.$pl_usr['id'].'" LIMIT 1'));
											if( isset($pl_usr_real['id']) ) {
												mysql_query('UPDATE `stats` SET `hpNow` = `hpNow` + 10 WHERE `id` = "'.$a_pl['id'].'" LIMIT 1');
												mysql_query('UPDATE `stats` SET `hpNow` = `hpNow` + 10 WHERE `id` = "'.$pl_usr['id'].'" LIMIT 1');
												bs_atack($pl,$a_pl,$pl_usr);
												$a_pl['battle'] = 1;
											}
										}
									}
									unset($sp_usr,$pl_usr);
								
									if( $a_pl['battle'] == 0 && rand(0,100) < 71 && $a_pl['timeGo'] < time()) {
										//�������������
										$stor = array();
										if( $xy['up'] > 0 ) {
											$stor[] = 'up';
										}
										if( $xy['down'] > 0 ) {
											$stor[] = 'down';
										}
										if( $xy['left'] > 0 ) {
											$stor[] = 'left';
										}
										if( $xy['right'] > 0 ) {
											$stor[] = 'right';
										}
										$stor = $stor[rand(0,count($stor)-1)];
										if( $stor == 'up' ) {
											$stgo = $xy[$stor];
										}elseif( $stor == 'down' ) {
											$stgo = $xy[$stor];
										}elseif( $stor == 'left' ) {
											$stgo = $xy[$stor];
										}elseif( $stor == 'right' ) {
											$stgo = $xy[$stor];
										}
										if( $stgo == 1 ) {
											if( $stor == 'up' ) {
												$a_pl['x']--;
											}elseif( $stor == 'down' ) {
												$a_pl['x']++;
											}elseif( $stor == 'left' ) {
												$a_pl['y']--;
											}elseif( $stor == 'right' ) {
												$a_pl['y']++;
											}
										}else{
											$stgo = mysql_fetch_array(mysql_query('SELECT * FROM `bs_map` WHERE `id` = "'.$stgo.'" LIMIT 1'));
											if( isset($stgo['id']) ) {
												$a_pl['x'] = $stgo['x'];
												$a_pl['y'] = $stgo['y'];	
											}
										}
										mysql_query('UPDATE `stats` SET `x` = "'.$a_pl['x'].'",`y` = "'.$a_pl['y'].'" WHERE `id` = "'.$a_pl['id'].'" LIMIT 1');									
										unset($stor,$stgo);
									}
								}
								
							}else{
								//���������
								
							}
						}
					}
				}
			}
		}
	}elseif( $pl['status'] == 0 && $pl['time_start'] < time() ) {
		//�������� ������
		if( nostart( $pl ) == false ) {
			
			//�������� ������!
			$spm = mysql_query('SELECT `x`,`y` FROM `bs_map` WHERE `mid` = "'.$pl['type_map'].'"');
			$maps = array( );
			while( $plm = mysql_fetch_array($spm) ) {
				$maps[] = array($plm['x'],$plm['y']);
			}
			$i = 0; $j = 0; $usrlst = array();
			$ubss = '';
			$sp_u = mysql_query('SELECT * FROM `bs_zv` WHERE `finish` = "0" AND `bsid` = "'.$pl['id'].'" ORDER BY `money` DESC');
			while( $pl_u = mysql_fetch_array($sp_u) ) {
				if( $i < 40 && !isset($usrlst[$pl_u['uid']]) ) {
					//����������� ���������
					$usrlst[$pl_u['uid']] = true;
					$bus = mysql_fetch_array(mysql_query('SELECT `align`,`chatColor`,`molch1`,`molch2`,`id`,`login`,`clan`,`align`,`level`,`sex`,`online`,`room` FROM `users` WHERE `id` = "'.mysql_real_escape_string($pl_u['uid']).'" LIMIT 1'));
					$bus['login_BIG']  = '<b>';
					if( $bus['align'] > 0 ) {
						$bus['login_BIG'] .= '<img src=http://img.combatz.ru/i/align/align'.$bus['align'].'.gif width=12 height=15 >';
					}
					if( $bus['clan'] > 0 ) {
						$bus['login_BIG'] .= '<img src=http://img.combatz.ru/i/clan/'.$bus['clan'].'.gif width=24 height=15 >';
					}
					$bus['login_BIG'] .= ''.$bus['login'].'</b>['.$bus['level'].']<a target=_blank href=/inf.php?'.$bus['id'].' ><img width=12 hiehgt=11 src=http://img.combatz.ru/i/inf_capitalcity.gif ></a>';
					$ubss .= ', '.$bus['login_BIG'];
					//
					//������� ���������� � �����
					if( $bus['align'] >= 1 && $bus['align'] < 2 ) {
						$bus['align'] = 1;
					}elseif( $bus['align'] >= 3 && $bus['align'] < 4 ) {
						$bus['align'] = 3;
					}elseif( $bus['align'] == 7 ) {
						$bus['align'] = 7;
					}else{
						$bus['align'] = 0;
					}
					mysql_query('INSERT INTO `users` (`chatColor`,`align`,`inTurnir`,`molch1`,`molch2`,`activ`,`login`,`room`,`name`,`sex`,`level`,`bithday`) VALUES (
						"'.$bus['chatColor'].'","'.$bus['align'].'","'.$pl['id'].'","'.$bus['molch1'].'","'.$bus['molch2'].'","0","'.$bus['login'].'","362","'.$bus['name'].'","'.$bus['sex'].'","'.$pl['level'].'","'.date('d.m.Y').'")');
					//
					$inbot = mysql_insert_id(); //���� ����
					if( $inbot > 0 ) {
						//���
						$m1 = $maps[rand(0,count($maps)-1)];
						$x1 = round($m1[0]);
						$y1 = round($m1[1]);
						mysql_query('INSERT INTO `stats` (`timeGo`,`timeGoL`,`upLevel`,`dnow`,`id`,`stats`,`exp`,`ability`,`skills`,`x`,`y`) VALUES ("'.(time()+$cnfg['time_puti']).'","'.(time()+$cnfg['time_puti']).'","98","0","'.$inbot.'","s1=3|s2=3|s3=3|s4='.$st2s[$pl['level']][0].'|s5=0|s6=0|rinv=40|m9=5|m6=10","'.$exp2[$pl['level']].'","'.$st2s[$pl['level']][1].'","'.$st2s[$pl['level']][2].'",'.$x1.','.$y1.')');
						mysql_query('UPDATE `users` SET `inUser` = "'.$inbot.'" WHERE `id` = "'.$bus['id'].'" LIMIT 1');
					}
					//��������� ����
					//
					mysql_query('INSERT INTO `eff_users` (`id_eff`,`uid`,`name`,`data`,`overType`,`timeUse`,`img2`) VALUES (
						"2","'.$inbot.'","����","puti='.(time()+$cnfg['time_puti']).'","1","'.(time()+$cnfg['time_puti']).'","chains.gif"
					) ');
					//
					//��������� ������ ������ ��
					mysql_query('UPDATE `bs_zv` SET `finish` = "'.time().'",`inBot` = "'.$inbot.'" WHERE `id` = "'.$pl_u['id'].'" LIMIT 1');
					//
					unset($bus['login_BIG']);
					$i++;
				}
				$j++;
			}
			unset($sp_u,$pl_u,$bus,$usrlst);
			//�������� ��� ��
			/*
				+0. ������� - ������ ������, ��� ����������� ����������.
				
				+1. ������� - � ���������� � ��������� ����� �������� �������, � ������� �� ���������.
				
				+2. ������ - ������ ��� ���� �������� �����, ������ �������� � ��� � ����� �������.
				
				3. ������� - �������������� ����� � ����� ���������� � 2 ���� ������� ��������.
				
				4. ��������� - �������������� ����� � ����� ���������� � 2 ���� ��������� ��������.
				
				5. ������ - �������� ����� ������ ����� ����� 10 ��. �������� ����� ������ ��� � ������� ��� ����� ������ ����������.
				
				6. �������� - ���������� � ����� ������� � ������ ����������, �� � ������� ������������ ������������� ��������� � ��� � ���.
				
				7. ��� HP- ����� � ����� �� �����������������.
			*/
			$pl['type_btl'] = rand(0,7);
			//
			//��������� �����
			//
			$ia = 1;
			$ialogins = array(
				'�������� �����������',
				'����������',
				'�������� ����������� (1)',
				'�������� ����������� (2)'
			);
			while( $ia <= 2 ) {
				if( isset( $ialogins[$ia] ) ) {
					$m1 = $maps[rand(0,count($maps)-1)];
					$x1 = round($m1[0]);
					$y1 = round($m1[1]);
					$user = array(
						'login' => $ialogins[$ia],
						'level' => 8,
						'align' => 7,
						'clan' => '',
						'sex' => 0,
						'obraz' => '0.gif',
						'city' => 'capitalcity',
						'room' => 362,
						'x' => $x1,
						'y' => $y1
					);
					$user['id'] = add_arhiv($pl,$user);
					if( $user['id'] > 0 ) {
						$ubss .= ', '.microLogin2($user);
						$pl['arhiv']++;
					}
				}
				$ia++;
			}
			//
			//��������� �������� (��������)
			/*$mis = array();
			$sp_i = mysql_query('SELECT `id`,`item_id` FROM `bs_items_use` WHERE `bid` = "'.$pl['id'].'"');
			while( $pl_i = mysql_fetch_array($sp_i) ) {
				$mis[] = $pl_i['item_id'];
			}
			unset($sp_i,$pl_i);
			$i2 = 0;
			while( $i2 <= count( $maps ) * 7 ) {
				if( rand(0,100) < 51 ) {
					$m1 = $maps[rand(0,count($maps)-1)];
					$x1 = round($m1[0]);
					$y1 = round($m1[1]);
					$itm1 = $mis[rand(0,count($mis)-1)];
					if( $itm1 > 0 ) {
						mysql_query('INSERT INTO `bs_items` (`x`,`y`,`bid`,`count`,`item_id`) VALUES (
							"'.$x1.'","'.$y1.'","'.$pl['id'].'","'.$pl['count'].'","'.$itm1.'"
						)');
					}
				}
				$i2++;
			}*/
			$mis = array(
			    4404,
				4404,
				4058,
				4058,
				4060,
				4060,
				4061,
				4061,
				4065,
				4068,
				4068,
				4074,
				4074,
				4076,
				4080,
				4085,
				4098,
				4098,
				4100,
				4100,
				4105,
				4105,
				4108,
				4126,
				4126,
				4128,
				4131,
				4132,
				4132,
				4133,
				4134,
				4052,
				4052,
				4052,
				4053,
				4053,
				4053,
				4054,
				4054,
				4054,
				4115,
				4115,
				4144,
				4144,
				4143,
				4143,
				4141,
				4141,
				4142,
				4142,
				4147,
				4147,
				4148,
				4148,
				4153,
				4090,
				4090,
				4094,
				4094,
				4095,
				4095,
				4104,
				4154,
				4154,
				4155,
				4155,
				4156,
				4156,
				4158,
				4158,
				4159,
				4159,
				4167,
				4167,
				4227,
				4227,
				4228,
				4228,
				4229,
				4229,
				4231,
				4231,
				4173,
				4173,
				4172,
				4172,
				4172,
				4191,
				4191,
				4197,
				4197,
				4200,
				4200,
				4200,
				4202,
				4202,
				4204,
				4211,
				4211,
				4213,
				4213,
				4214,
				4214,
				4219,
				4219,
				4219,
				4219,
				
				4241,
				4241,
				4241,
				4241,
				
				4242,
				4242,
				4242,
				4242,
				4242,
				4242,
				4242,
				4242,
				4242,
				
				2481,2481,2481,2481,
				2542,2542,2542,2542,
				2543,2543,2543,2543,
				2544,2544,2544,2544,
				4508,4508,4508,
				
				4056,
				4057
				
			);
			$i2 = 0;
			while( $i2 < count($mis) ) {
				$m1 = $maps[rand(0,count($maps)-1)];
				$x1 = round($m1[0]);
				$y1 = round($m1[1]);
				$itm1 = $mis[$i2];
				if( $itm1 > 0 ) {
					mysql_query('INSERT INTO `bs_items` (`x`,`y`,`bid`,`count`,`item_id`) VALUES (
						"'.$x1.'","'.$y1.'","'.$pl['id'].'","'.$pl['count'].'","'.$itm1.'"
					)');
				}
				$i2++;
			}
			//��������� ���� �� ��. � �� ���. �� �����
			$m1 = $maps[rand(0,count($maps)-1)];
			$x1 = round($m1[0]);
			$y1 = round($m1[1]);
			$itm1 = array( 4174 , 4175 , 4176 , 4177 , 4178 , 4179 , 4180 ); //������������ ��. �����
			$itm1 = $itm1[rand(0,count($itm1)-1)];
			if( $itm1 > 0 ) {
				mysql_query('INSERT INTO `bs_items` (`x`,`y`,`bid`,`count`,`item_id`) VALUES (
					"'.$x1.'","'.$y1.'","'.$pl['id'].'","'.$pl['count'].'","'.$itm1.'"
				)');
			}
			//
			$m1 = $maps[rand(0,count($maps)-1)];
			$x1 = round($m1[0]);
			$y1 = round($m1[1]);
			//$itm1 = array( 4184,4185,4186,4187 ); //������������ ���. �����
			$itm1 = array( 4185,4186,4187 ); //������������ ���. �����
			$itm1 = $itm1[rand(0,count($itm1)-1)];
			if( $pl['count'] == 21 || $pl['count'] == 22 ) {
				$itm1 = 4182; //��� �� 100 ���.
			}
			if( $itm1 > 0 ) {
				mysql_query('INSERT INTO `bs_items` (`x`,`y`,`bid`,`count`,`item_id`) VALUES (
					"'.$x1.'","'.$y1.'","'.$pl['id'].'","'.$pl['count'].'","'.$itm1.'"
				)');
			}
			//
			unset($mis,$m1,$x1,$y1,$i2);
			//
			$ubss = ltrim($ubss,', ');
			//���������� ������� ����� ������ � �������� ������
			mysql_query('UPDATE `bs_turnirs` SET `type_btl` = "'.$pl['type_btl'].'", `status` = "1", `users` = "'.$i.'", `arhiv` = "'.$pl['arhiv'].'", `users_finish` = "0" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
			mysql_query('UPDATE `bs_zv` SET `finish` = "'.time().'" WHERE `bsid` = "'.$pl['id'].'" AND `finish` = "0" AND `inBot` = "0"');
			//��������� � ��� ��
			$text = '������ �������. ���������: '.$ubss;
			mysql_query('INSERT INTO `bs_logs` (`type`,`text`,`time`,`id_bs`,`count_bs`,`city`,`m`,`u`) VALUES (
				"1", "'.mysql_real_escape_string($text).'", "'.time().'", "'.$pl['id'].'", "'.$pl['count'].'", "'.$pl['city'].'",
				"'.round($pl['money']*0.85,2).'","'.$i.'"
			)');
			//
			e('������� ������ ��� '.$pl['to_lvl'].' ������� � <b>����� ������</b>. ���������: '.$ubss.'.');
		}
	}else{
		//��������� ���������� � ������ ������� �� 60 ���., � ���-�� �� 10 ���.
		if( $pl['status'] == 0 ) {
			if( $pl['ch1'] == 0 && $pl['time_start'] - 60*60 < time()) {
				mysql_query('UPDATE `bs_turnirs` SET `ch1` = `ch1` + 1 WHERE `id` = "'.$pl['id'].'" LIMIT 1');
				e('������ ������� ��� '.$pl['to_lvl'].' ������� � <b>����� ������</b> ����� '.timeOut($pl['time_start']-time()).' (<small>'.date('d.m.Y H:i',$pl['time_start']).'</small>), ������� �������� ����: '.round($pl['money']*0.85,2).' ��., ������: '.$pl['users'].'');
			}elseif( $pl['ch1'] == 1 && $pl['time_start'] - 10*60 < time()) {
				mysql_query('UPDATE `bs_turnirs` SET `ch1` = `ch1` + 1 WHERE `id` = "'.$pl['id'].'" LIMIT 1');
				e('������ ������� ��� '.$pl['to_lvl'].' ������� � <b>����� ������</b> ����� '.timeOut($pl['time_start']-time()).' (<small>'.date('d.m.Y H:i',$pl['time_start']).'</small>), ������� �������� ����: '.round($pl['money']*0.85,2).' ��., ������: '.$pl['users'].'');
			}
		}
	}
}
?>