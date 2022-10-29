<?php



function getIP() {
   if(isset($_SERVER['HTTP_X_REAL_IP'])) return $_SERVER['HTTP_X_REAL_IP'];
   return $_SERVER['REMOTE_ADDR'];
}

/*if( $_SERVER['HTTP_CF_CONNECTING_IP'] != $_SERVER['SERVER_ADDR'] && $_SERVER['HTTP_CF_CONNECTING_IP'] != '127.0.0.1' ) {	die('Hello pussy!');   }

if(getIP() != $_SERVER['SERVER_ADDR'] && getIP() != '127.0.0.1' && getIP() != '' && getIP() != '212.224.113.192') {
	die(getIP().'<br>'.$_SERVER['SERVER_ADDR']);
}*/

define('GAME',true);

include('_incl/__config.php');
include('_incl/class/__db_connect.php');
include('_incl/class/__user.php');
include('_incl/class/__zv.php');

function e($t) {
	mysql_query('INSERT INTO `chat` (`text`,`city`,`to`,`type`,`new`,`time`) VALUES ("core #'.date('d.m.Y').' %'.date('H:i:s').' (Критическая ошибка): <b>'.mysql_real_escape_string($t).'</b>","capitalcity","LEL","6","1","-1")');
}

function send_chat($type,$from,$text,$time) {
	mysql_query('INSERT INTO `chat` (`text`,`city`,`login`,`to`,`type`,`new`,`time`,`room`) VALUES ("'.mysql_real_escape_string($text).'","capitalcity","'.mysql_real_escape_string($from).'","","'.$type.'","1","'.mysql_real_escape_string($time).'","3")');
}

//Проверка боев
/*$sp = mysql_query('SELECT `id`,`time_start` FROM `battle` WHERE `team_win` = -1');
while( $pl = mysql_fetch_array($sp) ) {
	$test = mysql_fetch_array(mysql_query('SELECT * FROM `battle_logs` WHERE `battle` = "'.$pl['id'].'" ORDER BY `id` DESC LIMIT 1'));
	$end = 0;
	if(!isset($test['id']) && $pl['time_start'] < time() - 3600 ) {
		$end = 1;
	}elseif( $test['time'] < time() - 3600 ) {
		$end = 1;
	}
	e($pl['id']);
	if( $end == 1 ) {
		mysql_query('UPDATE `battle` SET `team_win` = "0" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
		mysql_query('UPDATE `users` SET `battle` = "0" WHERE `battle` = "'.$pl['id'].'" LIMIT 1');
	}
}*/

$zv->testCronZv();
?>