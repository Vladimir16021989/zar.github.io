<?

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

function e($t) {
	mysql_query('INSERT INTO `chat` (`text`,`city`,`to`,`type`,`new`,`time`) VALUES ("core #'.date('d.m.Y').' %'.date('H:i:s').' (����������� ������): <b>'.mysql_real_escape_string($t).'</b>","capitalcity","TABU","6","1","-1")');
}

if(isset($_GET['cron_core'])) {
	$id = array(
		'id' => $_GET['uid'],
		'pass' => $_GET['pass']
	);
	if(md5($id['id'].'_brfCOreW@!_'.$id['pass']) == $_GET['cron_core']) {
		$uzr = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`pass` FROM `users` WHERE `id` = "'.mysql_real_escape_string($id['id']).'" AND `pass` = "'.mysql_real_escape_string($id['pass']).'" LIMIT 1'));
		if(isset($uzr['id'])) {
			$CRON_CORE = true;
			$_COOKIE['login'] = $uzr['login'];
			$_COOKIE['pass'] = $uzr['pass'];
			$_POST['id'] = 'reflesh';
			
			
			if(isset($_GET['atack'])) {
				$_POST['atack'] = $_GET['atack'];
			}
			if(isset($_GET['block'])) {
				$_POST['block'] = $_GET['block'];
			}
			if(isset($_GET['usepriem'])) {
				$_POST['usepriem'] = $_GET['usepriem'];
			}
			if(isset($_GET['useitem'])) {
				$_POST['useitem'] = $_GET['useitem'];
			}			
		}		
	}
}

if(!isset($uzr['id'])) {
	header('location: main.php');
	die();
}

unset($uzr);

include('_incl/class/__magic.php');
include('_incl/class/__user.php');
include('_incl/class/__filter_class.php');
include('_incl/class/__quest.php');

$tjs = '';
#--------��� ������, � ����� ��� �����
$sleep = $u->testAction('`vars` = "sleep" AND `uid` = "'.$u->info['id'].'" LIMIT 1',1);
if($u->room['file']!="objaga" && $sleep['id']>0) {
    mysql_query('UPDATE `actions` SET `vars` = "unsleep" WHERE `id` = "'.$sleep['id'].'" LIMIT 1');
}
if($u->room['file']=="objaga" || $u->room['file']=="post"){$trololo=0;}else{$trololo=1;}

#--------��� ������, � ����� ��� �����
if($u->info['online'] < time()-60)
{
	$filter->setOnline($u->info['online'],$u->info['id'],0);	
	mysql_query("UPDATE `users` SET `online`='".time()."',`timeMain`='".time()."' WHERE `id`='".$u->info['id']."' LIMIT 1");	
}elseif($u->info['timeMain'] < time()-60)
{
	mysql_query("UPDATE `users` SET `online`='".time()."',`timeMain`='".time()."' WHERE `id`='".$u->info['id']."' LIMIT 1");	
}

if(!isset($u->info['id']) || ($u->info['joinIP']==1 && $u->info['ip']!=$_SERVER['HTTP_X_REAL_IP']) || $u->info['banned']>0)
{
	die($c['exit']);
}

if(isset($_GET['atak_user']) && $u->info['battle'] == 0 && $_GET['atak_user']!=$u->info['id'] )
{	
	if($u->room['noatack'] == 0) {
		$ua = mysql_fetch_array(mysql_query('SELECT `id`,`clan` FROM `users` WHERE`id` = "'.mysql_real_escape_string($_GET['atak_user']).'" LIMIT 1'));
		$cruw = mysql_fetch_array(mysql_query('SELECT `id` FROM `clan_wars` WHERE
		((`clan1` = "'.$ua['clan'].'" AND `clan2` = "'.$u->info['clan'].'") OR (`clan2` = "'.$ua['clan'].'" AND `clan1` = "'.$u->info['clan'].'")) AND
		`time_finish` > '.time().' LIMIT 1'));
		unset($ua);
		if(isset($cruw['id'])) {
			$cruw = 1;
		}
	
		$ua = mysql_fetch_array(mysql_query('SELECT `s`.`team`,`s`.`id`,`s`.`bbexp`,`u`.`battle`,`u`.`id`,`u`.`room`,`u`.`login`,`u`.`online` FROM `stats` AS `s` LEFT JOIN `users` AS `u` ON `s`.`id` = `u`.`id` WHERE (`s`.`atack` > "'.time().'" OR `s`.`atack` = 1 OR 1 = '.$cruw.') AND `s`.`id` = "'.mysql_real_escape_string($_GET['atak_user']).'" LIMIT 1'));
		if(isset($ua['id']) && $ua['online'] > time()-520)
		{	
			$usta = $u->getStats($ua['id'],0); // ����� ����
			$minHp = $usta['hpAll']/100*33; // ����������� ����� �������� ���� ��� ������� ����� �������
	
			if($ua['room']==$u->info['room'] && ($minHp<$usta['hpNow'] || $ua['battle']>0))
			{
				$magic->atackUser($u->info['id'],$ua['id'],$ua['team'],$ua['battle'],$ua['bbexp'],50);
				
				$rtxt = '[img[items/pal_button8.gif]] &quot;'.$u->info['login'].'&quot; ��������'.$sx.' ��������� �� ����� �� ��������� &quot;'.$ua['login'].'&quot;.';
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
	}
	$u->error = '��� ����������� ��������� ��� ����������...';
}

//mysql_query('START TRANSACTION');

if($u->info['battle_text']!='')
{
	//���������� �������� � ������� ������
	if($u->info['last_b']>0)
	{		
		mysql_query('INSERT INTO `battle_last` (`battle_id`,`uid`,`time`,`act`,`level`,`align`,`clan`,`exp`) VALUES ("'.$u->info['last_b'].'","'.$u->info['id'].'","'.time().'","'.$u->info['last_a'].'","'.$u->info['level'].'","'.$u->info['align'].'","'.$u->info['clan'].'","'.$u->info['exp'].'")');
	}
	mysql_query('UPDATE `stats` SET `battle_text` = "",`last_b`="0" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
}

/*echo '
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<meta http-equiv=Cache-Control Content=no-cache>
<meta http-equiv=PRAGMA content=NO-CACHE>
<meta http-equiv=Expires Content=0>
<link href="http://img.combatz.ru/css/main.css" rel="stylesheet" type="text/css">
</head>
<body style="padding-top:0px; margin-top:7px; background-color:#e2e0e0;">';
*/
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
//�������� ������
$ul = $u->testLevel();	
if($ul==1)
{
	$act = 1;
}	
if($u->info['repass'] > 0 && $u->info['id'] != 207153) {
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
/*-----------------------*/
if($u->info['battle']==0)
{
	//��������/������ ��������
	
	//��������/������ ���������
	//$act2 = $u->testItems($u->info['id'],$u->stats,0);
	if($act2!=-2 && $act==-2)
	{
		$act = $act2;
	}
	
	if(!isset($u->tfer['id']) && $u->room['block_all'] == 0)
	{
		//�����/����� �������
		if(isset($_GET['rstv']) && isset($_GET['inv'])) {
			$act = $u->freeStatsMod($_GET['rstv'],$_GET['mf'],$u->info['id']);
		}elseif(isset($_GET['ufs2']) && isset($_GET['inv']))
		{
			$act = $u->freeStats2Item($_GET['itmid'],$_GET['ufs2'],$u->info['id'],1);
		}elseif(isset($_GET['ufs2mf']) && isset($_GET['inv']))
		{
			$act = $u->freeStats2Item($_GET['itmid'],$_GET['ufs2mf'],$u->info['id'],2);
		}elseif(isset($_GET['ufsmst']) && isset($_GET['inv']))
		{
			$act = $u->itemsSmSave($_GET['itmid'],$_GET['ufsmst'],$u->info['id']);
		}elseif(isset($_GET['ufsms']) && isset($_GET['inv']))
		{
			$act = $u->itemsSmSave($_GET['itmid'],$_GET['ufsms']+100,$u->info['id']);
		}elseif(isset($_GET['ufs']) && isset($_GET['inv']))
		{
			$act = $u->freeStatsItem($_GET['itmid'],$_GET['ufs'],$u->info['id']);
		}elseif(isset($_GET['sid']) && isset($_GET['inv']))
		{
			$act = $u->snatItem($_GET['sid'],$u->info['id']);
		}elseif(isset($_GET['oid']) && isset($_GET['inv']))
		{
			$act = $u->odetItem($_GET['oid'],$u->info['id']);
		}elseif(isset($_GET['item_rune']) && isset($_GET['inv']))
		{			
			$act = $u->runeItem(NULL);			
		}elseif(isset($_GET['remitem'],$_GET['inv']))
		{
			$act = $u->snatItemAll($u->info['id']);
		}elseif(isset($_GET['delete']) && isset($_GET['inv']) && $u->newAct($_GET['sd4']))
		{
			$u->deleteItem(intval($_GET['delete']),$u->info['id']);
		}elseif(isset($_GET['stack']) && isset($_GET['inv']))
		{
			$u->stack($_GET['stack']);
		}elseif(isset($_GET['unstack']) && isset($_GET['inv']))
		{
			$u->unstack($_GET['unstack']);
		}elseif(isset($_GET['end_qst_now']))
		{
			$q->endq((int)$_GET['end_qst_now'],'end');
		}
		//������������ ������
		if(isset($_GET['use_pid']))
		{
			$magic->useItems((int)$_GET['use_pid']);
		}
	}

}elseif($u->info['battle_text']!='')
{
	//���������� �������� � ������� ������
	if($u->info['last_b']>0)
	{
		
	}
	mysql_query('UPDATE `stats` SET `battle_text` = "",`last_b`="0" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
}

if($magic->youuse > 0)
{
	$act = 1;
}
//��������� ������
if($act!=-2)
{
	$u->stats = $u->getStats($u->info['id'],0,1);
	$u->aves = $u->ves(NULL);
	$act2 = $u->testItems($u->info['id'],$u->stats,0);
	if($act2!=-2 && $act==-2)
	{
		$act = $act2;
	}
}
}

if((isset($_GET['zayvka']) && $u->info['battle']==0) || (isset($_GET['zayvka']) && ($_GET['r']==6 || $_GET['r']==7 || !isset($_GET['r'])) && $u->info['battle']>0) && !isset($u->tfer['id']))
{
	include('modules/_zv.php');
}

/*-----------------------*/
/*
if(isset($_GET['security']) && !isset($u->tfer['id']) && $trololo==1)
{
	include('modules/_changepass.php');
}elseif(isset($_GET['quests']))
{
	include('modules/_quests.php');
}elseif($u->info['level']>1 && isset($_GET['friends']) && !isset($u->tfer['id']))
{
	include('modules/_friends.php');
}elseif((($u->info['align']>=1 && $u->info['align']<2) || $u->info['admin']>0) && isset($_GET['light']) && !isset($u->tfer['id']))
{
	include('modules/_mod.php');
}elseif((($u->info['align']>=3 && $u->info['align']<4) || $u->info['admin']>0) && isset($_GET['dark']) && !isset($u->tfer['id']))
{
	include('modules/_mod.php');
}elseif(($u->info['clan']>0 || (($u->info['align']>1 && $u->info['align']<2) || ($u->info['align']>3 && $u->info['align']<4))) && isset($_GET['clan']) && !isset($u->tfer['id']))
{
	if(($u->info['align']>1 && $u->info['align']<2) || ($u->info['align']>3 && $u->info['align']<4)) {
		include('modules/_clan2.php');
	}else{
		include('modules/_clan.php');
	}
}elseif(isset($_GET['admin']) && $u->info['admin']>0)
{
	if($u->info['id']==7) {
	include('modules/_light.php');
	}else{include('modules/_mod.php');}
}elseif(isset($_GET['help']))
{
	include('modules/help.php');
}elseif(isset($_GET['vip']) && !isset($u->tfer['id']))
{
	include('modules/vip.php');
}elseif((isset($_GET['zayvka']) && $u->info['battle']==0) || (isset($_GET['zayvka']) && ($_GET['r']==6 || $_GET['r']==7 || !isset($_GET['r'])) && $u->info['battle']>0) && !isset($u->tfer['id']))
{
	if($u->room['zvsee'] == 1) {
		include('modules/_zv2.php');
	}else{
		include('modules/_zv.php');
	}
}elseif(isset($_GET['alh']) && $u->info['level']>0 && !isset($u->tfer['id']))
{
	include('modules/_alh.php');
}elseif(isset($_GET['alhp']) && $u->info['admin']==1 && !isset($u->tfer['id']))
{
	include('modules/_alhp.php');
}elseif($u->info['battle']!=0)
{
	//��������
	include('modules/btl_.php');
}else{
	if(isset($_GET['talk']) && !isset($u->tfer['id']))
	{
		if($u->info['dnow']>0 && !isset($u->tfer['id']))
		{
			include('_incl/class/__dungeon.php');
		}
		include('modules/_dialog.php');
	}elseif(isset($_GET['act_sec']) && !isset($u->tfer['id']) && $trololo==1)
	{
		include('modules/_security.php');
	}elseif(isset($_GET['inv']) && !isset($u->tfer['id']) && $trololo==1)
	{
		include('modules/_inv.php');
	}elseif(isset($_GET['cryshop']) && !isset($u->tfer['id']) && $trololo==1  && $u->info['level']>0)
	{
		include('modules/_cryshop.php');
	}elseif(isset($_GET['referals']) && $trololo==1 && !isset($u->tfer['id']) && $u->info['level']>0){
		include('modules/_ref.php');
	}elseif(isset($_GET['obraz']) && !isset($u->tfer['id']) && $trololo==1)
	{
		include('modules/_obraz.php');
	}elseif(isset($_GET['skills']) && !isset($u->tfer['id']) && $trololo==1)
	{
		include('modules/_umenie.php');
	}elseif((isset($_GET['transfer']) || isset($u->tfer['id'])) && $u->info['level']>=$c['level_ransfer'] && $trololo==1)
	{
		include('modules/_transfers.php');
	}elseif(isset($_GET['anketa']) && !isset($u->tfer['id']) && $trololo==1)
	{
		include('modules/_anketa.php');
	}elseif(isset($_GET['pet']) && $u->info['animal']>0 && $trololo==1)
	{
		include('modules/_animal.php');
	}elseif(isset($_GET['act_trf']) && $u->room['block_all']==0) {
		include('modules/act_trf.php');
	}elseif(!isset($u->tfer['id']))
	{
		include('modules/_locations.php');
	}
}
*/

//mysql_query('COMMIT');

if($u->room['name']=='����� ������' && $u->info['inUser']>0 && $u->info['lost']>0)
{
	//mysql_query('UPDATE `users` SET `inUser` = "0" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
	//������ ������
	//header('location: main.php');
}

//��������� ������ �� ����������
//$q->testquest();

/*-----------------------*/
/*echo '<script>'.$tjs.'top.ctest("'.$u->info['city'].'");top.sd4key="'.$u->info['nextAct'].'"; var battle = '.(0+$u->info['battle']).'; top.hic();</script></body>
</html>';*/

//unlink($lock_file);

mysql_query('UNLOCK TABLES');
?>
