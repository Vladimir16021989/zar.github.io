<?

include('_incl/__config.php');
define('GAME',true);
include('_incl/class/__db_connect.php');
include('_incl/class/__magic.php');
include('_incl/class/__user.php');

if( $u->info['admin'] > 0 ) {

	$lab = mysql_fetch_array(mysql_query('SELECT * FROM `laba_now` WHERE `id` = "'.mysql_real_escape_string($_GET['id']).'" LIMIT 1'));
	if( isset($lab['id']) ) {
		$map = mysql_fetch_array(mysql_query('SELECT `id`,`data`,`update` FROM `laba_map` WHERE `id` = "'.$lab['id'].'" LIMIT 1'));
		if( !isset($map['id']) ) {
			
			die('Карта подземелий не найдена...');	
		}
		$objs = array();		
		$sp = mysql_query('SELECT `s`.`id`,`s`.`x`,`s`.`y`,`u`.`login` FROM `stats` AS `s` LEFT JOIN `users` AS `u` ON `u`.`id` = `s`.`id` WHERE `s`.`dnow` = "'.$lab['id'].'" AND `u`.`room` = 370 LIMIT 10');
		$usi = 1;
		while( $pl = mysql_fetch_array($sp) ) {
			$objs[$pl['x']][$pl['y']][2] = '<div title="'.$pl['login'].'" class="ddp1ee'.$usi.'"></div>';
			$usi++;
		}
		$map_d = json_decode($map['data']);
		$i = 0;
		while( $i <= count($map_d) ) {
			$j = 0;
			while( $j < count($map_d[$i]) ) {
				if( $map_d[$i][$j] == 1 ) {
					$mapsee .= '<div class="ddp1">'.$objs[$i][$j][2].'</div>';
				}else{
					$mapsee .= '<div class="ddp0">'.$objs[$i][$j][2].'</div>';
				}
				$j++;
			}
			$mapsee .= '<br>';	
			$i++;
		}
		echo $mapsee;
	}else{
		echo 'Лабиринт не найден.';
	}
	
}
?>
<style>
.ddp0 { 
	display:inline-block;
	width:15px;
	height:15px;
	background-image:url("http://img.combatz.ru/drgn/bg/o.gif");
}
.ddp1 { 
	display:inline-block;
	width:15px;
	height:15px;
	background-image:url("http://img.combatz.ru/drgn/bg/m.gif");
}
.ddpStart {
	display:inline-block;
	width:15px;
	height:15px;
	background-image:url("http://img.combatz.ru/drgn/bg/os.gif");
}
.ddpExit {
	display:inline-block;
	width:15px;
	height:15px;
	background-image:url("http://img.combatz.ru/drgn/bg/of.gif");
}
.ddp1s {
	display:inline-block;
	width:15px;
	height:15px;
	background-image:url("http://img.combatz.ru/drgn/bg/s.gif");
}
.ddp1m {
	display:inline-block;
	width:15px;
	height:15px;
	background-image:url("http://img.combatz.ru/drgn/bg/r.gif");
}
.ddp1h {
	display:inline-block;
	width:15px;
	height:15px;
	background-image:url("http://img.combatz.ru/drgn/bg/h.gif");
}
.ddp1l {
	display:inline-block;
	width:15px;
	height:15px;
	background-image:url("http://img.combatz.ru/drgn/bg/b.gif");
}
.ddp1p {
	display:inline-block;
	width:15px;
	height:15px;
	background-image:url("http://img.combatz.ru/drgn/bg/p.gif");
}
.ddp1me {
	display:inline-block;
	width:15px;
	height:15px;
	background-image:url("http://img.combatz.ru/drgn/bg/u.gif");
}
.ddp1ee1 {
	display:inline-block;
	width:15px;
	height:15px;
	background-image:url("http://img.combatz.ru/drgn/bg/e1.gif");
}
.ddp1ee2 {
	display:inline-block;
	width:15px;
	height:15px;
	background-image:url("http://img.combatz.ru/drgn/bg/e2.gif");
}
.ddp1ee3 {
	display:inline-block;
	width:15px;
	height:15px;
	background-image:url("http://img.combatz.ru/drgn/bg/e3.gif");
}
.ddp1ee4 {
	display:inline-block;
	width:15px;
	height:15px;
	background-image:url("http://img.combatz.ru/drgn/bg/e4.gif");
}
.ddp1ee5 {
	display:inline-block;
	width:15px;
	height:15px;
	background-image:url("http://img.combatz.ru/drgn/bg/e5.gif");
}
</style>