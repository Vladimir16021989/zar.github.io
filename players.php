<?php
	header('Content-Type: text/html; charset=windows-1251');
	
	define('GAME',true);
	include('_incl/__config.php');	
	include('_incl/class/__db_connect.php');	
	include('_incl/class/__user.php');
	include('_incl/class/__filter_class.php');
	include('_incl/class/__chat_class.php');
	
	if( $u->info['admin'] == 0 ) {
		header('location: /index.php');
	}
	
	$html = '';
	$i = 1;
	$sp = mysql_query('SELECT `id`,`timeMain`,`room`,`city`,`login`,`level`,`online`,`align`,`clan`,`admin`,`host_reg`,`battle` FROM `users` WHERE `online` > '.(time()-520).' AND `inUser` = "0" AND `host_reg` NOT LIKE "%bot%" AND `level` < 12 AND `admin` = 0 ORDER BY `login` ASC');
	while($pl = mysql_fetch_array($sp)) {
		$room = mysql_fetch_array(mysql_query('SELECT `id`,`name` FROM `room` WHERE `id` = "'.$pl['room'].'" LIMIT 1'));
		$chat = mysql_fetch_array(mysql_query('SELECT `id` FROM `chat` WHERE `login` = "'.$pl['login'].'" AND `to` != "" AND `to` NOT LIKE "%,%" AND `time` > "'.(time()-520).'" ORDER BY `id` DESC LIMIT 1'));
		$info = '';
		if(isset($chat['id'])) {
			$info .= ' - <b>Общается в чате</b>';
		}elseif($pl['timeMain'] < time() - 60*60) {
			$info .= ' - <b title="Игрок не у компьютера">Afk.</b>';
		}
		if($pl['battle'] > 0){ 
			$info .= ' - <a target="_blank" href="http://combatz.ru/logs.php?log='.$pl['battle'].'">в поединке</a>';
		}
		$html .= '<div>'.$i.'. '.$u->microLogin($pl['id'],1).' - <i><small>'.$room['name'].'</small></i><small>'.$info.'</small></div>';
		$i++;
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<meta http-equiv=Cache-Control Content=no-cache>
<meta http-equiv=PRAGMA content=NO-CACHE>
<meta http-equiv=Expires Content=0>
<link href="http://img.combatz.ru/css/main.css" rel="stylesheet" type="text/css">
</head>
<body style="padding-top:0px; margin-top:7px; background-color:#dedede;">
<div style="padding-bottom:5px;"><b style="color:#093;">Список игроков online (<?=$i?> чел.):</b></div>
<?=$html?>
</body>
</html>