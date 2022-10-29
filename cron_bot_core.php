<?php

/*

	Ядро для обработки данных.
	Обработка поединков, обработка заявок, обработка ботов, обработка пещер, обработка турниров, обработка временных генераций

*/

//if( $_SERVER['HTTP_CF_CONNECTING_IP'] != $_SERVER['SERVER_ADDR'] && $_SERVER['HTTP_CF_CONNECTING_IP'] != '127.0.0.1' ) {	die('Hello pussy!');   }

function getIP() {
   if(isset($_SERVER['HTTP_X_REAL_IP'])) return $_SERVER['HTTP_X_REAL_IP'];
   return $_SERVER['REMOTE_ADDR'];
}

if(getIP() != $_SERVER['SERVER_ADDR'] && getIP() != '127.0.0.1' && getIP() != '' && getIP() != '212.224.113.192') {
	if( !isset($_GET['test'])) {
		//die(getIP().'<br>'.$_SERVER['SERVER_ADDR']);
	}
}


define('GAME',true);

setlocale(LC_CTYPE ,"ru_RU.CP1251");

include('_incl/__config.php');
include('_incl/class/__db_connect.php');
include('_incl/class/__user.php');
include('_incl/class/bot.priem.php');
include('_incl/class/bot.logic.php');

$count = array(
	0,
	0,
	0,
	0,
	0,
	0
);

function e($t) {
	mysql_query('INSERT INTO `chat` (`text`,`city`,`to`,`type`,`new`,`time`) VALUES ("core #'.date('d.m.Y').' %'.date('H:i:s').' (Критическая ошибка): <b>'.mysql_real_escape_string($t).'</b>","capitalcity","LEL","6","1","-1")');
}

function inuser_go_btl($id) {
	if(isset($id['id'])) {
		file_get_contents('http://combatz.ru/jx/battle/refresh.php?uid='.$id['id'].'&cron_core='.md5($id['id'].'_brfCOreW@!_'.$id['pass']).'&pass='.$id['pass']);
	}
}

/*$sp = mysql_query('SELECT `id` FROM `users` WHERE `host_reg` = "real_bot_user" AND `login` != "delete" AND `banned` = "0" ORDER BY `online` DESC LIMIT 300');
while($pl = mysql_fetch_array($sp)) {
	botLogic::start( $pl['id'] );
}*/
//$sp = mysql_query('SELECT `u`.* , `s`.* FROM `stats` AS `s` LEFT JOIN `users` AS `u` ON `u`.`id` = `s`.`id` WHERE ( /*( `s`.`bot` > 0 AND `u`.`battle` > 0 ) OR*/ `u`.`pass` = "saintlucia" ) ORDER BY `s`.`nextAct` ASC LIMIT 20');
$sp = mysql_query('SELECT `u`.* , `s`.* FROM `stats` AS `s` LEFT JOIN `users` AS `u` ON `u`.`id` = `s`.`id` WHERE `u`.`pass` = "saintlucia" ORDER BY `s`.`nextAct` ASC LIMIT 100');
$btltest = array();
while($pl = mysql_fetch_array($sp)) {
	$i++;
	if( $pl['zv'] == 0 && ($pl['battle'] == 0 || !isset($btltest[$pl['battle']]) || $btltest[$pl['battle']] < 10)) {
		
		$btltest[$pl['battle']]++;
		
		if( $pl['timereg'] == 0 ) {
			mysql_query('UPDATE `users` SET `timereg` = "'.time().'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
		}else{
			mysql_query('UPDATE `users` SET `online` = "'.time().'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
		}
		
		if( $pl['exp'] > 400000 && $pl['level'] == 8 ) {
			$pl['exp'] = 400000;
			mysql_query('UPDATE `stats` SET `exp` = "400000" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
		}elseif( $pl['exp'] > 3500000 && $pl['level'] == 9 ) {
			$pl['exp'] = 3500000;
			mysql_query('UPDATE `stats` SET `exp` = "3500000" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
		}
		
		if( $pl['bot'] == 0 ) {
			mysql_query('UPDATE `stats` SET `bot` = "2" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
		}
			
		mysql_query('UPDATE `stats` SET `nextAct` = "'.time().'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
		mysql_query('UPDATE `users` SET `online` = "'.time().'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
		
		botLogic::start( $pl['id'] );
		
		//botLogic::e( $pl['battle'] .' -> '.$btltest[$pl['battle']] );
	}else{
		if( $pl['timereg'] == 0 ) {
			mysql_query('UPDATE `users` SET `timereg` = "'.time().'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
		}else{
			mysql_query('UPDATE `users` SET `online` = "'.time().'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
		}
		
		if( $pl['exp'] > 400000 && $pl['level'] == 8 ) {
			$pl['exp'] = 400000;
			mysql_query('UPDATE `stats` SET `exp` = "400000" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
		}elseif( $pl['exp'] > 3500000 && $pl['level'] == 9 ) {
			$pl['exp'] = 3500000;
			mysql_query('UPDATE `stats` SET `exp` = "3500000" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
		}
		
		if( $pl['bot'] == 0 ) {
			mysql_query('UPDATE `stats` SET `bot` = "2" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
		}
		mysql_query('UPDATE `stats` SET `nextAct` = "'.time().'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
		mysql_query('UPDATE `users` SET `online` = "'.time().'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
	}
}
?>