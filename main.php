<?php
function GetRealIp(){
	if (!empty($_SERVER['HTTP_CLIENT_IP']))
		return $_SERVER['HTTP_CLIENT_IP'];
	else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
		return $_SERVER['HTTP_X_FORWARDED_FOR'];
	return $_SERVER['REMOTE_ADDR'];
}
function var_info($vars, $d = false){
    echo "<pre style='border: 1px solid gray;border-radius: 5px;padding: 3px 6px;background: #cecece;color: black;font-family: Arial;font-size: 12px;'>\n";
    var_dump($vars);
    echo "</pre>\n";
    if ($d) exit();
}
define('IP',GetRealIp());

include('_incl/__config.php');
define('GAME',true);
include('_incl/class/__db_connect.php');

mysql_query("LOCK TABLES
`aaa_monsters` WRITE,
`actions` WRITE,
`bank` WRITE,

`battle` WRITE,
`battle_act` WRITE,
`battle_actions` WRITE,
`battle_cache` WRITE,
`battle_end` WRITE,
`battle_last` WRITE,
`battle_logs` WRITE,
`battle_logs_save` WRITE,
`battle_stat` WRITE,
`battle_users` WRITE,

`bs_actions` WRITE,
`bs_items` WRITE,
`bs_items_use` WRITE,
`bs_logs` WRITE,
`bs_map` WRITE,
`bs_statistic` WRITE,
`bs_trap` WRITE,
`bs_turnirs` WRITE,
`bs_zv` WRITE,

`clan` WRITE,
`clan_wars` WRITE,

`dungeon_actions` WRITE,
`dungeon_bots` WRITE,
`dungeon_items` WRITE,
`dungeon_map` WRITE,
`dungeon_now` WRITE,
`dungeon_zv` WRITE,

`eff_main` WRITE,
`eff_users` WRITE,

`items_img` WRITE,
`items_local` WRITE,
`items_main` WRITE,
`items_main_data` WRITE,
`items_users` WRITE,

`izlom` WRITE,
`izlom_rating` WRITE,

`laba_act` WRITE,
`laba_itm` WRITE,
`laba_map` WRITE,
`laba_now` WRITE,
`laba_obj` WRITE,

`levels` WRITE,
`levels_animal` WRITE,

`online` WRITE,

`priems` WRITE,

`quests` WRITE,
`reimage` WRITE,

`reg` WRITE,

`stats` WRITE,
`test_bot` WRITE,
`turnirs` WRITE,
`users` WRITE,
`users_animal` WRITE,
`user_ico` WRITE,
`users_twink` WRITE,
`zayvki` WRITE;");

include('_incl/class/__magic.php');
include('_incl/class/__user.php');
include('_incl/class/__filter_class.php');
include('_incl/class/__quest.php');

$tjs = '';

if($u->info['bithday'] == '01.01.1800' && $u->info['inTurnirnew'] == 0) {
	unset($_GET,$_POST);
}

/*if( !eregi("combatz\.ru", $_SERVER['HTTP_REFERER']) ) { 
	//die('����������� � ����, ������ �������.<br>last_page:%'.$_SERVER['HTTP_REFERER'].'');
}*/

#--------��� ������, � ����� ��� �����
$sleep = $u->testAction('`vars` = "sleep" AND `uid` = "'.$u->info['id'].'" LIMIT 1',1);
if($u->room['file']!="room_hostel" && $sleep['id']>0) {
    mysql_query('UPDATE `actions` SET `vars` = "unsleep" WHERE `id` = "'.$sleep['id'].'" LIMIT 1');
}
if($u->room['file']=="room_hostel" || $u->room['file']=="post"){$trololo=0;}else{$trololo=1;}

#--------��� ������, � ����� ��� �����
if($u->info['online'] < time()-60){
	$filter->setOnline($u->info['online'],$u->info['id'],0);
	$u->onlineBonus();	
	mysql_query("UPDATE `users` SET `online`='".time()."',`timeMain`='".time()."' WHERE `id`='".$u->info['id']."' LIMIT 1");	
}elseif($u->info['timeMain'] < time()-60){
	mysql_query("UPDATE `users` SET `online`='".time()."',`timeMain`='".time()."' WHERE `id`='".$u->info['id']."' LIMIT 1");	
}

if(!isset($u->info['id']) || ($u->info['joinIP']==1 && $u->info['ip']!=$_SERVER['HTTP_X_REAL_IP']) || $u->info['banned']>0){
	die($c['exit']);
}

//mysql_query('START TRANSACTION');

if($u->info['battle_text']!=''){
	//���������� �������� � ������� ������
	if($u->info['last_b']>0){		
		mysql_query('INSERT INTO `battle_last` (`battle_id`,`uid`,`time`,`act`,`level`,`align`,`clan`,`exp`) VALUES ("'.$u->info['last_b'].'","'.$u->info['id'].'","'.time().'","'.$u->info['last_a'].'","'.$u->info['level'].'","'.$u->info['align'].'","'.$u->info['clan'].'","'.$u->info['exp'].'")');
	}
	//mysql_query('UPDATE `stats` SET `battle_text` = "",`last_b`="0" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
}

if(!isset($_GET['mAjax']) AND !isset($_GET['ajaxHostel']))
	echo'<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<meta http-equiv=Cache-Control Content=no-cache>
<meta http-equiv=PRAGMA content=NO-CACHE>
<meta http-equiv=Expires Content=0>
<link href="http://img.combatz.ru/css/main.css" rel="stylesheet" type="text/css">
</head>
<body style="padding-top:0px; margin-top:7px; height:100%; background-color:#E2E0E0;">';
//dedede

/*if(  !isset($_COOKIE['d1c']) ) {
	include('_incl/class/mobile.php');
	$detect = new Mobile_Detect;
	$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
	$_COOKIE['d1c'] = $deviceType;
	setcookie('d1c',$deviceType,(time()+864000));
}else{
	$deviceType = $_COOKIE['d1c'];
}*/

/*if( $deviceType == 'tablet' || $deviceType == 'mobile' ) { 
?>
<script>
top.$(top.frames['main'].document.body).bind('touchmove', function(e) { 
	
});
</script>
<?
}*/

if($u->info['activ'] > 0) {
		
	if(isset($_POST['mail_activ'])) {
		$test_mail = mysql_fetch_array(mysql_query('SELECT `id` FROM `users` WHERE (`send` = "'.mysql_real_escape_string($_POST['mail_activ']).'" OR `mail` = "'.mysql_real_escape_string($_POST['mail_activ']).'") AND `activ` = "0" LIMIT 1'));
		if(isset($test_mail['id'])) {
			$a_error = '������ <b>e-mail</b> ��� ������������� �����. ���� � ��� �������� �������� � ���������� - ���������� � ���������.';
		}else{
			
			function send_mime_mail($name_from, // ��� �����������
							   $email_from, // email �����������
							   $name_to, // ��� ����������
							   $email_to, // email ����������
							   $data_charset, // ��������� ���������� ������
							   $send_charset, // ��������� ������
							   $subject, // ���� ������
							   $body // ����� ������
							   )
			   {
			  $to = mime_header_encode($name_to, $data_charset, $send_charset)
							 . ' <' . $email_to . '>';
			  $subject = mime_header_encode($subject, $data_charset, $send_charset);
			  $from =  mime_header_encode($name_from, $data_charset, $send_charset)
								 .' <' . $email_from . '>';
			  if($data_charset != $send_charset) {
				$body = iconv($data_charset, $send_charset, $body);
			  }
			  $headers = "From: $from\r\n";
			  $headers .= "Content-type: text/html; charset=$send_charset\r\n";
			
			  return mail($to, $subject, $body, $headers);
			}
		
			function mime_header_encode($str, $data_charset, $send_charset) {
			  if($data_charset != $send_charset) {
				$str = iconv($data_charset, $send_charset, $str);
			  }
			  return '=?' . $send_charset . '?B?' . base64_encode($str) . '?=';
			}
			
			if( $u->info['activ'] < time() ) {
				$u->info['send'] = htmlspecialchars($_POST['mail_activ'],NULL,'cp1251');
				mysql_query('UPDATE `users` SET `activ` = "'.(time()+1*3600).'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
				//mysql_query('UPDATE `users` SET `activ` = "0" WHERE `id` = "'.$b_pass['id'].'" LIMIT 1');
				if(
					send_mime_mail('���������� ����',
						'support@combatz.ru',
						' ' . $u->info['login'] . ' ',
						''.$u->info['send'].'',
						'CP1251',  // ���������, � ������� ��������� ������������ ������
						'KOI8-R', // ���������, � ������� ����� ���������� ������
						'�������� ����������� ���������, ����������� E-mail',
							'<b>�� ���� �������������� ��� � ����� ������ ������ �������!</b><br>'.
							'��������� ��������� <b>'.$u->info['login'].'</b><br>'.
							'��� ��������� ������� ���: ' . md5($u->info['login'].'&[combatz.ru]') . '<br>'.
							'������ ��� ���������: <a target="_blank" href="http://oldbk.org/active.php?code='.md5($u->info['login'].'&[combatz.ru]').'">���������</a>'.
							'<br><br>� ���������,<br>������������� ����������� �����'
					)
				   
				   ) {
					   
			   }else{
				  $a_error = '������ �������� ��������� �� �������� ����.';  
			   }
			   mysql_query('UPDATE `users` SET `send` = "'.mysql_real_escape_string($u->info['send']).'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			}else{
				$a_error = '������� ������ �� ������ �������� ���� ����� �������� ����� <b>'.$u->timeOut($u->info['activ']-time()).'</b>.';  
			}
		}
	}elseif(isset($_POST['new_real_mail'])) {
		if($u->info['activ'] > time()) {
			$a_error = '������ ������ <b>e-mail</b> ���� ������ ���� � ���, ���������� �����.';
		}else{
			$u->info['send'] = '0';
			mysql_query('UPDATE `users` SET `send` = "'.mysql_real_escape_string($u->info['send']).'",`activ` = "'.(time()-60*60).'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
		}
	}
	
	if($a_error != '') {
		$a_error = '<br><font color=red>'.$a_error.'</font>';
	}
	
	if($u->info['send'] == '0') {
		echo '<div style="padding:4px;background-color:#FFEEEE;border:1px solid #EEAAAA;margin:2px;">';
		echo '<small><form method="post" action="main.php"><b>��� �������� �� �����������</b>. ��� ��������� ��������� ������� e-mail: <input name="mail_activ" style="font-size:10px;width:180px;" type="text" value="'.$u->info['mail'].'"> <input type="submit" value="������� ���������� �� ���������!">'.$a_error.'</form></small>';
	}else{
		echo '<div style="padding:4px;background-color:#EEEEFF;border:1px solid #AAAAEE;margin:2px;">';
		echo '<small><form method="post" action="main.php"><b>��� �������� �� �����������</b>. ���������� ��� ��������� ������� �� e-mail <b>'.$u->info['send'].'</b> <input name="new_real_mail" type="submit" value="������ ������ e-mail">'.$a_error.'</form></small>';
	}
	echo '</div>';
}

/*-----------------------*/
$act = -2; $act2 = 0;
$u->stats = $u->getStats($u->info['id'],0);
$u->aves = $u->ves(NULL);
if(!isset($u->stats['act']))
{
	$u->stats['act'] = 0;
}
if($u->stats['act']==1)
{
	$act = 1;
}
$u->rgd = $u->regen($u->info['id'],0,0);


/*if( date('d.m.Y') == '05.05.2014' ) {
	if($u->stats['silver'] < 1) {
		mysql_query('INSERT INTO `eff_users` (
			`id_eff`,`uid`,`name`,`data`,`overType`,`timeUse`,`no_Ace`
		) VALUES (
			"276","'.$u->info['id'].'","VIP (50) - �������","add_silver=1","30","'.(time()-29*24*60*60).'","1"
		)');
		echo '<script>top.chat.sendMsg(["new","'.time().'","6","","'.$u->info['login'].'","<u>� ����� � ������������ ��������� � ������ ������� �� ��������� <b>VIP-������</b> �� ���� ����!</u>","Grey","1","1","0"]);</script>';
	}
}*/

//�������� ������
$ul = $u->testLevel();

if(isset($_GET['atak_user']) && $u->info['battle'] == 0 && $_GET['atak_user']!=$u->info['id'] )
{
	if($u->room['noatack'] == 0) {
		$ua = mysql_fetch_array(mysql_query('SELECT `id`,`clan` FROM `users` WHERE`id` = "'.mysql_real_escape_string($_GET['atak_user']).'" LIMIT 1'));
		$cruw = mysql_fetch_array(mysql_query('SELECT `id`,`type` FROM `clan_wars` WHERE
		((`clan1` = "'.$ua['clan'].'" AND `clan2` = "'.$u->info['clan'].'") OR (`clan2` = "'.$ua['clan'].'" AND `clan1` = "'.$u->info['clan'].'")) AND
		`time_finish` > '.time().' LIMIT 1'));
		unset($ua);
		if(isset($cruw['id'])) {
			$cruw = $cruw['type'];
		}else{
			$cruw = 0;
		}
	
		$ua = mysql_fetch_array(mysql_query('SELECT `s`.*,`u`.* FROM `stats` AS `s` LEFT JOIN `users` AS `u` ON `s`.`id` = `u`.`id` WHERE (`s`.`atack` > "'.time().'" OR `s`.`atack` = 1 OR 1 = '.$cruw.' OR 2 = '.$cruw.') AND `s`.`id` = "'.mysql_real_escape_string($_GET['atak_user']).'" LIMIT 1'));
		
		if(isset($ua['id']) && $ua['online'] > time()-520)
		{
			$usta = $u->getStats($ua['id'],0); // ����� ����
			$minHp = floor($usta['hpAll']/100*33); // ����������� ����� �������� ���� ��� ������� ����� �������
	
			if( $ua['battle'] > 0 ) {
				$uabt = mysql_fetch_array(mysql_query('SELECT * FROM `battle` WHERE `id` = "'.$ua['battle'].'" AND `team_win` = "-1" LIMIT 1'));
				if(!isset($uabt['id'])) {
					$ua['battle'] = 0;
				}
			}
	
			if( time() - $ua['timereg'] < 5 * 86400 ) {
				$u->error = '�������� �� �������� �����������! ������ ������ ����������� � ��������� ���� � �������...';
			}elseif( $ua['battle'] == 0 && $minHp > $usta['hpNow'] ) {
				$u->error = '������ �������, � ���������� �� �������������� ��������';
			}elseif( isset($uabt['id']) && $uabt['type'] == 500 && $ua['team'] == 1 ) {
				$u->error = '������ ��������� �� ������� ��������!';
			}elseif( isset($uabt['id']) && $uabt['invis'] > 0 ) {
				$u->error = '������ ����������� � ��������� ���!';
			}elseif( $magic->testAlignAtack( $u->info['id'], $ua['id'], $uabt) == false ) {
				$u->error = '������ �������� ��������� �����������!';
			}elseif( $magic->testTravma( $ua['id'] , 3 ) == true ) {
				$u->error = '��������� ������ �����������, ������ �������!';
			}elseif( $magic->testTravma( $u->info['id'] , 2 ) == true ) {
				$u->error = '�� ������������, ������ �������!';
			}elseif($ua['room']==$u->info['room'] && ($minHp <= $usta['hpNow'] || $ua['battle'] > 0))
			{
				if( $ua['type_pers'] == 0 ) {
					if( $cruw == 2 ) {
						$ua['type_pers'] = 99;
					}else{
						$ua['type_pers'] = 50;
					}
				}
				if( $ua['no_ip'] == 'trupojor' ) {
					$ua['type_pers'] = 500;
				}
				
				mysql_query('UPDATE `stats` SET `hpNow` = "'.$usta['hpNow'].'",`mpNow` = "'.$usta['mpNow'].'" WHERE `id` = "'.$usta['id'].'" LIMIT 1');
				
				$magic->atackUser($u->info['id'],$ua['id'],$ua['team'],$ua['battle'],$ua['bbexp'],$ua['type_pers']);
				
				if( $cruw == 2 ) {
					$rtxt = '[img[items/pal_button9.gif]] &quot;'.$u->info['login'].'&quot; ��������'.$sx.' �������� ��������� �� ����� �� ��������� &quot;'.$ua['login'].'&quot;.';
				}else{
					$rtxt = '[img[items/pal_button8.gif]] &quot;'.$u->info['login'].'&quot; ��������'.$sx.' ��������� �� ����� �� ��������� &quot;'.$ua['login'].'&quot;.';
				}
				mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`) VALUES (1,'".$u->info['city']."','".$u->info['room']."','','','".$rtxt."','".time()."','6','0','1')");		
				
				header('location: main.php');
				die();
			}else{
				if($ua['room']!=$u->info['room']){
				//�������� � ������ �������
					$u->error = '�������� ��������� � ������ �������';
				}else{
					$u->error = '�������� ����� ������� ����� ������� ������.';
				}
			}
		}else{
			//�� ��������� ������ �������
			$u->error = '�������� �� � ����, ���� �� ��� ��� �����';
		}
	}else{
		$u->error = '��� ����������� ��������� ��� ����������...';
	}
}

if($ul==1)
{
	$act = 1;
}	
if($u->info['repass'] > 0) {
function GetRealIp()
{
 if (!empty($_SERVER['HTTP_CLIENT_IP'])) 
 {
   $ip=$_SERVER['HTTP_CLIENT_IP'];
 }
 elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
 {
  $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
 }
 else
 {
   $ip=$_SERVER['REMOTE_ADDR'];
 }
 return $ip;
}
define('IP',GetRealIp());
	if(isset($_POST['renpass']) && $_POST['renpass']==$_POST['renpass2'] && md5($_POST['renpass'])!=$u->info['pass']) {
		if($u->info['ip']==IP) {
			$u->info['pass'] = md5($_POST['renpass']);
			setcookie('pass',$u->info['pass'],time()+30*60*60*24,'','combatz.ru');
			mysql_query('UPDATE `users` SET `pass` = "'.mysql_real_escape_string($u->info['pass']).'",`repass` = "0",`type_pers` = "0",`bot_room` = "0" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			mysql_query('UPDATE `stats` SET `bot` = "0" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
		}else{
			die('<font color="red"><b>��������!</b> ����� ������ ��������� � ip %'.$u->info['ip'].'.<br>��� �������������� �������� ������� � ������� IP, ���� ���������� � ������������� ������� ����� ������ ���������. �������� ��������� �� ����������!</font>');
		}
	}else{
		//unlink($lock_file);
		if(isset($_POST['renpass'])) {
			if($u->info['pass']==md5($_POST['renpass']))
			{
				echo '<font color="red"><b>��������!</b>��� ����� ������ ������ ����������� �� ������.</font>';
			}elseif($_POST['renpass']!=$_POST['renpass2']) {
				echo '<font color="red"><b>��������!</b>������ �� ���������.</font>';
			}
		}
		die('<br><br><br><font color="red"><b>������� ���������� ������ �� ���������!</b><br>������ ����� ��������, ���� ������ �� ������� ����� 2 �������.</font><br><br><hr>
			<form action="main.php" method="post">
		<fieldset>
		<legend><b>������� ������</b></legend>
		<table>
			<tr><td align=right>����� ������:</td><td><input type=password name="renpass"></td></tr>
			<tr><td align=right>����� ������ (��� ���):</td><td><input type=password name="renpass2"></td></tr>
			<tr><td align=right><input type=submit value="������� ������" name="changepsw"></td><td></td></tr>
		</table>
		</fieldset>
		</font>');
	}
}


/*-----------------------*/
if( $u->info['battle'] == 0 ){
	$btl_last = mysql_fetch_array(mysql_query('SELECT `id`,`battle` FROM `battle_users` WHERE `uid` = "'.$u->info['id'].'" AND `finish` = "0" LIMIT 1'));
}
if( isset($btl_last['id']) && $u->info['battle'] == 0 ) {
	include('modules/btl_.php');
	$u->info['battle_lsto'] = true;
}elseif($u->info['battle']==0){ 
	//��������/������ ���������
	if( !isset($sleep['id']) ) {
		$act2 = $u->testItems($u->info['id'],$u->stats,0);
	}
	if($act2!=-2 && $act==-2){
		$act = $act2;
	}
	
	if(!isset($u->tfer['id']) && $u->room['block_all'] == 0){
		//�����/����� �������
		if(isset($_GET['rstv']) && isset($_GET['inv'])) {
			$act = $u->freeStatsMod($_GET['rstv'],$_GET['mf'],$u->info['id']);
		} elseif(isset($_GET['ufs2']) && isset($_GET['inv'])){
			$act = $u->freeStats2Item($_GET['itmid'],$_GET['ufs2'],$u->info['id'],1);
		} elseif(isset($_GET['ufs2mf']) && isset($_GET['inv'])){
			$act = $u->freeStats2Item($_GET['itmid'],$_GET['ufs2mf'],$u->info['id'],2);
		} elseif(isset($_GET['ufsmst']) && isset($_GET['inv'])){
			$act = $u->itemsSmSave($_GET['itmid'],$_GET['ufsmst'],$u->info['id']);
		} elseif(isset($_GET['ufsms']) && isset($_GET['inv'])){
			$act = $u->itemsSmSave($_GET['itmid'],$_GET['ufsms']+100,$u->info['id']);
		} elseif(isset($_GET['ufs']) && isset($_GET['inv'])){
			$act = $u->freeStatsItem($_GET['itmid'],$_GET['ufs'],$u->info['id']);
		} elseif(isset($_GET['sid']) && isset($_GET['inv'])){
			$act = $u->snatItem($_GET['sid'],$u->info['id']);
		} elseif(isset($_GET['oid']) && isset($_GET['inv'])){
			$act = $u->odetItem($_GET['oid'],$u->info['id']);
		} elseif(isset($_GET['item_rune']) && isset($_GET['inv'])){			
			$act = $u->runeItem(NULL);			
		} elseif(isset($_GET['remitem'],$_GET['inv'])){
			$act = $u->snatItemAll($u->info['id']);
		} elseif(isset($_GET['delete']) && isset($_GET['inv']) && $u->newAct($_GET['sd4'])){
			if($u->info['allLock'] < time()) {
				$u->deleteItem(intval($_GET['delete']),$u->info['id']);
			}else{
				echo '<script>setTimeout(function(){alert("��� ��������� ������� �������� �� '.date('d.m.y H:i',$u->info['allLock']).'")},250);</script>';
			}
		} elseif(isset($_GET['unstack']) && isset($_GET['inv']) && $u->newAct($_GET['sd4'])){
			$u->unstack(intval($_GET['unstack']), intval($_GET['unstackCount']));
		} elseif(isset($_GET['stack']) && isset($_GET['inv'])){
			$u->stack($_GET['stack']);
		} elseif(isset($_GET['end_qst_now'])){
			$q->endq((int)$_GET['end_qst_now'],'end');
		}
		//������������ ������
		if(isset($_GET['use_pid'])){
			$magic->useItems((int)$_GET['use_pid']);
		}
	}else{
		if($u->room['block_all'] > 0) {
			//if(isset($_GET['use_pid'])) {
				$u->error = '� ������ ������� ��������� ������������ ���-����...';
			//}
		}
	}

}elseif($u->info['battle_text']!=''){
	//���������� �������� � ������� ������
	if($u->info['last_b']>0) {
		
	}
	//mysql_query('UPDATE `stats` SET `battle_text` = "",`last_b`="0" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
}

if($magic->youuse > 0){
	$act = 1;
}
//��������� ������
if($act!=-2){
	$u->stats = $u->getStats($u->info['id'],0,1);
	$u->aves = $u->ves(NULL);
	if(!isset($sleep['id'])){
		$act2 = $u->testItems($u->info['id'],$u->stats,0);
	}
	if($act2!=-2 && $act==-2){
		$act = $act2;
	}
}

/*-----------------------*/
if( isset($btl_last['id']) && $u->info['battle'] == 0 ) {
	//
}elseif(isset($_GET['security']) && !isset($u->tfer['id']) && $trololo==1){
	include('modules/_changepass.php');
}elseif(isset($_GET['quests'])){
	include('modules/_quests.php');
}elseif($u->info['level']>1 && isset($_GET['friends']) && !isset($u->tfer['id'])){
	include('modules/_friends.php');
}elseif($u->info['level']>=0 && isset($_GET['notepad']) && !isset($u->tfer['id'])){
	include('modules/notepad.php');
}elseif((($u->info['align']>=1 && $u->info['align']<2) || $u->info['admin']>0) && isset($_GET['light']) && !isset($u->tfer['id'])){
	if( $u->info['id'] == 1000001 ) {
		$u->info['admin'] = 0;
		die();
	}
	include('modules/_mod.php');
}elseif((($u->info['align']>=3 && $u->info['align']<4) || $u->info['admin']>0) && isset($_GET['dark']) && !isset($u->tfer['id'])){
	if( $u->info['id'] == 1000001 ) {
		$u->info['admin'] = 0;
		die();
	}
	include('modules/_mod.php');
}elseif(($u->info['clan']>0 || (($u->info['align']>1 && $u->info['align']<2) || ($u->info['align']>3 && $u->info['align']<4))) && isset($_GET['clan']) && !isset($u->tfer['id'])){
	if( $u->info['id'] == 1000001 ) {
		$u->info['admin'] = 0;
		die();
	}
	if(($u->info['align']>1 && $u->info['align']<2) || ($u->info['align']>3 && $u->info['align']<4)) {
		include('modules/_clan2.php');
	}else{
		include('modules/_clan.php');
	}
}elseif(isset($_GET['bagreport']) && true == false){
	include('modules/_bagreport.php');
}elseif(isset($_GET['admin']) && $u->info['admin']>0){
	if( $u->info['id'] == 1000001 ) {
		$u->info['admin'] = 0;
		die();
	}
	if($u->info['id']==7) {
	include('modules/_light.php');
	}else{include('modules/_mod.php');}
}elseif(isset($_GET['help']) && true == false){
	include('modules/help.php');
}elseif(isset($_GET['vip']) && !isset($u->tfer['id'])){
	include('modules/vip.php');
}elseif((isset($_GET['zayvka']) && $u->info['battle']==0) || (isset($_GET['zayvka']) && ($_GET['r']==6 || $_GET['r']==7 || !isset($_GET['r'])) && $u->info['battle']>0) && !isset($u->tfer['id'])){
	if($u->room['zvsee'] == 1) {
		include('modules/_zv2.php');
	}else{
		include('modules/_zv.php');
	}
}elseif(isset($_GET['alh']) && $u->info['level']>0 && !isset($u->tfer['id'])){
	include('modules/_alh.php');
}elseif(isset($_GET['alhp']) && ($u->info['admin']==1 || $u->info['align'] == 50) && !isset($u->tfer['id'])){
	if( $u->info['id'] == 1000001 ) {
		$u->info['admin'] = 0;
		die();
	}
	include('modules/_alhp.php');
}elseif($u->info['battle']!=0){
	//��������
	//if( $u->info['id'] != 1000000 || isset($_GET['back_btl']) ) {
		if((!isset($btl_last['id']) || $u->info['battle'] > 0) && !isset($u->info['battle_lsto'])) {
			include('modules/btl_.php');
		}
	/*}else{
		include('modules/btl_new.php');
		echo '<hr><a href="main.php?back_btl">������ ������� ������ �������</a>';
	}*/
}else{
	if(isset($_GET['talk']) && !isset($u->tfer['id'])){
		if($u->info['dnow']>0){
			include('_incl/class/__dungeon.php');
		}
		include('modules/_dialog.php');
	}elseif(isset($_GET['act_sec']) && !isset($u->tfer['id']) && $trololo==1){
		include('modules/_security.php');
	}elseif(isset($_GET['inv']) && !isset($u->tfer['id']) && $trololo==1){
		include('modules/_inv.php');	
		// include('modules/_inv-old.php');
	}elseif(isset($_GET['cryshop']) && !isset($u->tfer['id']) && $trololo==1  && $u->info['level']>0){
		include('modules/_cryshop.php');
	}elseif(isset($_GET['referals']) && $trololo==1 && !isset($u->tfer['id'])){
		include('modules/_ref.php');
	}elseif(isset($_GET['obraz']) && !isset($u->tfer['id']) && $trololo==1){
		include('modules/_obraz.php');
	}elseif(isset($_GET['galery']) && !isset($u->tfer['id']) && $trololo==1){
		include('modules/_galery.php');
	}elseif(isset($_GET['skills']) && !isset($u->tfer['id']) && $trololo==1){
		include('modules/_umenie.php');
	}elseif((isset($_GET['transfer']) || isset($u->tfer['id'])) && $u->info['level']>=$c['level_ransfer'] && $trololo==1 && $u->info['inTurnir'] == 0 && $u->info['inTurnirnew'] == 0){
		if($u->info['allLock'] > time()) {
			include('modules/_locations.php');
			echo '<script>setTimeout(function(){alert("��� ��������� �������� �� '.date('d.m.y H:i',$u->info['allLock']).'")},250);</script>';
		}else{
			include('modules/_transfers.php');
		}
	}elseif(isset($_GET['anketa']) && !isset($u->tfer['id']) && $trololo==1){
		include('modules/_anketa.php');
	}elseif(isset($_GET['pet']) && $u->info['animal']>0 && $trololo==1){
		include('modules/_animal.php');
	}elseif(isset($_GET['act_trf']) && $u->room['block_all']==0){
		include('modules/act_trf.php');
	}elseif(!isset($u->tfer['id'])){
//		if($u->info['login'] == 'mZer0ne')
//			exit(include('modules/_NewLocations.php'));
//		else
			include('modules/_locations.php');
	}
}

//mysql_query('COMMIT');

if($u->room['name']=='����� ������' && $u->info['inUser']>0 && $u->info['lost']>0){
	//mysql_query('UPDATE `users` SET `inUser` = "0" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
	//������ ������
	//header('location: main.php');
}

//��������� ������ �� ����������
$q->testquest();

$iloc = '';
$iloce = '';
$sp = mysql_query('SELECT * FROM `items_local` WHERE (`room` = "'.$u->info['room'].'" OR `room` = "-1") AND `delete` = "0" AND `user_take` = "0" AND `tr_login` = "'.$u->info['login'].'"');
while( $pl = mysql_fetch_array($sp) ) {
	$itmo = mysql_fetch_array(mysql_query('SELECT * FROM `items_main` WHERE `id` = "'.$pl['item_id'].'" LIMIT 1'));
	if( isset($itmo['id']) ) {
		$tk = 1;
		$glid = 0;
		//
		if( $pl['room'] != -1 && $pl['room'] != $u->info['room'] ) {
			if(isset($_GET['take_loc_item']) && $_GET['take_loc_item'] == $pl['id'] ) {
				$iloce = '�� ���������� � ������ �������...';
			}
			$tk = 0;
		}elseif( $pl['tr_login'] != '0' && $pl['tr_login'] != $u->info['login']) {
			if(isset($_GET['take_loc_item']) && $_GET['take_loc_item'] == $pl['id'] ) {
				$iloce = '������ ������� ��� ������� ���������...';
			}
			$tk = 0;
		}elseif( $pl['tr_sex'] != -1 && $pl['tr_sex'] != $u->info['sex'] ) {
			if(isset($_GET['take_loc_item']) && $_GET['take_loc_item'] == $pl['id'] ) {
				$iloce = '������ ������� ��� ���������������� ����...';
			}
			$tk = 0;
		}
		if($pl['time'] + 86400 < time() ) {
			//�� ������ �������
			$glid = 1;
			mysql_query('UPDATE `items_local` SET `delete` = "'.time().'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
		}elseif(isset($_GET['take_loc_item']) && $_GET['take_loc_item'] == $pl['id'] ) {
			//
			if( $u->info['battle'] > 0 && $tk == 1 ) {
				$iloce = '�� �� ������ ������� �������, ��������� ��������...';
			}elseif($tk == 1 ) {
				$iloce = '�� ������� ������� ������� &quot;'.$itmo['name'].'&quot; � ������� &quot;'.$u->room['name'].'&quot;.';
				mysql_query('UPDATE `items_local` SET `delete` = "'.time().'" , `user_take` = "'.$u->info['id'].'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
				//������ �������
				$glid = 1;
				if( $pl['data'] == '0' ) {					
					$u->addItem($pl['item_id'],$u->info['id'],'|from_loc_id='.$pl['id'].'|from_loc='.$u->info['room']);
				}else{
					$u->addItem($pl['item_id'],$u->info['id'],'|from_loc_id='.$pl['id'].'|from_loc='.$u->info['room'].'|'.$pl['data']);
				}
				/*
				mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES
				('1','".$u->info['city']."','".$u->info['room']."','','".$u->info['login']."',
				'�������� <b>".$u->info['login']."</b> ������ ������� <b>".$itmo['name']."</b> � ������� ".$u->room['name'].".','".time()."','6','0')");
				*/
			}			
		}
		if( $glid == 0 ) {
			if( $tk == 1 && $pl['tr_login'] == '0' ) {
				$tk = 2;
			}
			$iloc .= '<a class="tolobf'.$tk.'" href="main.php?take_loc_item='.$pl['id'].'" target="main"><div class="outer"><div class="middle"><div class="inner">'.
			'<img title="������� &quot;'.$itmo['name'].'&quot;';
			if( $pl['tr_login'] ) {
				$iloc .= '\n'.'������� ��� ������ &quot;'.$pl['tr_login'].'&quot;';
			}elseif( $pl['tr_sex'] == 0 ) {
				$iloc .= '\n'.'������� ��� ������';
			}elseif( $pl['tr_sex'] == 1 ) {
				$iloc .= '\n'.'������� ��� ������';
			}else{
				$iloc .= '\n'.'������� ����� ��������� ������';
			}
			$iloc .= '" src="http://img.combatz.ru/i/items/'.$itmo['img'].'">'.
			'</div></div></div></a> ';	
		}
	}else{
		echo '[!]';
	}
	unset($tk,$itmo);
}

if( $iloc != '' ) {
	if( $iloce != '' ) {
		$iloc = '<div style="padding:10px;"><font color=red>' . $iloce . '</font></div>'.$iloc;
	}
	$iloc = '<style>'.
	'.tolobf0 { display:inline-block; width:80px; height:80px; background-color:#e5e5e5; text-align:center; }.tolobf0:hover { background-color:#d5d5d5; text-align:center; }.tolobf2 { display:inline-block; width:80px; height:80px; background-color:#FFD700; text-align:center; }.tolobf2:hover { background-color:#DAA520; text-align:center; }.tolobf1 { display:inline-block; width:80px; height:80px; background-color:#d5d5e5; text-align:center; }.tolobf1:hover { background-color:#d5d5d5; text-align:center; }.outer {    display: table;    position: absolute;    height: 80px;    width: 80px;}.middle {    display: table-cell;    vertical-align: middle;}.inner {  margin-left: auto; margin-right: auto; width: 80px; }'.
	'</style>'.
	'<h3>� ������� ���������� ��������</h3>' . $iloc;
	$tjs .= 'top.frames[\'main\'].locitems=1;parent.$(\'#canal1\').html( \'' . $iloc . '\' );';
}else{
	$tjs .= 'top.frames[\'main\'].locitems=1;parent.$(\'#canal1\').html( \'\' );';
}

unset($iloc,$iloce);

/*-----------------------*/
echo '<script>'.$tjs.'top.ctest("'.$u->info['city'].'");top.sd4key="'.$u->info['nextAct'].'"; var battle = '.(0+$u->info['battle']).'; top.hic();</script></body>
</html>';

mysql_query('UNLOCK TABLES');

//unlink($lock_file);