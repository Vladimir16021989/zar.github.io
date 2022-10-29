<?php
	define('GAME',true);
	include('_incl/__config.php');	
	include('_incl/class/__db_connect.php');	
	include('_incl/class/__user.php');
		
	if($u->info['admin'] == 0) {
		die('st.php');
	}
	
	$ip = array(
		'a' => array(), //Нумерация [0] => '127.0.0.1'
		'b' => array(), //Адресса и инфо по ним ['127.0.0.1'] => data
		'c' => array()  //Адресса и статистика запросов ['127.0.0.1'] => 100
	);
	
	echo '<link href="http://img.combatz.ru/css/main.css" rel="stylesheet" type="text/css">';
	
	//phpinfo();
	
	$min = 60; //За сколько минут статистика
	
	$sp = mysql_query('SELECT * FROM `error_logs` WHERE `time` > '.( time() - $min*60 ).' ORDER BY `ip` DESC');
	while($pl = mysql_fetch_array($sp)) {
		if( $ip['c'][$pl['ip']] < 1 ) {
			$ip['a'][count($ip['a'])] = $pl['ip'];
		}
		$ip['b'][$pl['ip']] = $pl;
		$ip['c'][$pl['ip']]++;
	}
	
	echo '<hr style="border:0;border-top:1px solid grey;">Статистика с <b>'.date('d.m.y H:i',( time() - $min*60 )).'</b> по <b>'.date('d.m.y H:i').'</b> (За последнии '.$min.' мин.)<hr style="border:0;border-top:1px solid grey;">';
	
	array_multisort($ip['c'], SORT_DESC);
	
	$ipa = array_keys($ip['c']);
	
	$i = 0;
	$online = 0;
	$all_cf = 0;
	while($i < count($ipa)) {
		
		$a = $ipa[$i];
		$b = $ip['b'][$a];
		$c = $ip['c'][$a];
		
		$color = 'black';
		$nm = '';
		$css = '';
		$usr0 = '';
		
		if( $b['ip'] == '144.76.102.21' ) {
			
			$color = 'red';
			$nm = ' (Server)';
			$css = 'background:#CECECE;';
			
		}elseif( $b['ip'] == save_ip() ) {
			
			$color = 'blue';
			$nm = ' (You)';
			$css = 'background:#CECECE;';
			
		}
		
		$cf = round(0.95*($c/($min*60)),3);
		
		$all_cf += $cf;
		
		if($cf > 0.6) {
			$cf = '<span><b style="background-color:red;color:white;width:75px;display:inline-block;text-align:center;"> !'.$cf.'% </b></span>';
		}elseif($cf > 0.3) {
			$cf = '<span><b style="background-color:#f66;color:white;width:75px;display:inline-block;text-align:center;"> !'.$cf.'% </b></span>';
		}elseif($cf > 0.15) {
			$cf = '<span><b style="background-color:#F99;color:white;width:75px;display:inline-block;text-align:center;"> !'.$cf.'% </b></span>';
		}elseif($cf > 0.05) {
			$cf = '<span><b style="background-color:#FCC;color:white;width:75px;display:inline-block;text-align:center;"> !'.$cf.'% </b></span>';
		}else{
			$cf = '<span style="background-color:#FEE;color:#f99;width:75px;display:inline-block;text-align:center;"> '.$cf.'% </span>';
		}
		
		$usr = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`align`,`clan`,`level` FROM `users` WHERE `ip` = "'.mysql_real_escape_string($b['ip']).'" AND `online` > "'.(time() - 120).'" AND `pass` != "Itisalife" ORDER BY `online` DESC LIMIT 1'));
		if(!isset($usr['id'])) {
			$usr = mysql_fetch_array(mysql_query('SELECT `id`,`uid` FROM `logs_auth` WHERE `ip` = "'.mysql_real_escape_string($b['ip']).'" ORDER BY `id` DESC LIMIT 1'));
			if(isset($usr['uid'])) {
				$usr = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`align`,`clan`,`level` FROM `users` WHERE `id` = "'.mysql_real_escape_string($usr['uid']).'" ORDER BY `online` DESC LIMIT 1'));
				if(isset($usr['id'])) {
					$usr0 =  '<img src="http://img.combatz.ru/i/align/align'.$usr['align'].'.gif">';
					if($usr['clan'] > 0) {
						$usr0 .= '<img src="http://img.combatz.ru/i/clan/'.$usr['clan'].'.gif">';
					}
					$usr0 .= '<a href="inf.php?'.$usr['id'].'" target="_blank">'.$usr['login'].'</a>['.$usr['level'].'] <b style="color:red"><small>(o_O)</small></b>';
				}else{
					$usr = '';
				}
			}else{
				$usr = '';
			}
		}else{
			$usr0 =  '<img src="http://img.combatz.ru/i/align/align'.$usr['align'].'.gif">';
			if($usr['clan'] > 0) {
				$usr0 .= '<img src="http://img.combatz.ru/i/clan/'.$usr['clan'].'.gif">';
			}
			$usr0 .= '<a href="inf.php?'.$usr['id'].'" target="_blank">'.$usr['login'].'</a>['.$usr['level'].']';
		}
		
		if($usr0 != '') {
			$online++;
		}
		
		if($u->info['admin'] > 0 || save_ip() == $b['ip']) {
			
		}else{
			$b['ip'] = '%IP_ADRES%';
		}
		
		$data = '';
		
		//if($usr0 == '' && $u->info['admin'] > 0) {
			$d = unserialize($b['SERVER']);
			$cc = unserialize($b['COOKIE']);
			//$data = '<b>Последнее действие:</b> ';			
			//if($d['PHP_SELF'] != '/var/www/combatz.ru/data/www/combatz.ru/cron_bot_core.php') {
				//$data .= 'Ссылка: <a href="'.$d['REQUEST_URI'].'" target="_blank">'.$d['REQUEST_URI'].'</a> &nbsp; &nbsp; ('.date('d.m.y H:i:s',$b['time']).')';
			//}	
			
			
			if($cc['login'] != '') {
				$data .= '<br><b>COOKIE:</b> '.$cc['login'].'';
				if(!isset($usr['id'])) {
					$usr = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`align`,`clan`,`level` FROM `users` WHERE `login` = "'.mysql_real_escape_string($cc['login']).'" ORDER BY `online` DESC LIMIT 1'));	
					if(isset($usr['id'])) {
						$usr0 .=  ' &raquo; <img src="http://img.combatz.ru/i/align/align'.$usr['align'].'.gif">';
						if($usr['clan'] > 0) {
							$usr0 .= '<img src="http://img.combatz.ru/i/clan/'.$usr['clan'].'.gif">';
						}
						$usr0 .= '<a href="inf.php?'.$usr['id'].'" target="_blank">'.$usr['login'].'</a>['.$usr['level'].'] <b style="color:red"><small>(o_O)</small></b>';
					}
				}
			}
			
			//if($d['HTTP_REFERER'] != '' && !isset($usr['id'])) {
			//	$data .= '<br><b style="color:red">Переход с</b>: <a href="'.$d['HTTP_REFERER'].'" target="_blank">'.$d['HTTP_REFERER'].'</a>';
			//}
			
			
			$data = '<small style="color:#CECECE">'.$data.'</small>';
		//}
		
		echo '<div style="padding:2px;border-bottom:1px solid #efefef;'.$css.'"> &nbsp; <span style="display:inline-block;width:50px;">'.($i+1).'.</span> <span style="display:inline-block;width:110px;color:'.$color.'"><b><a href="st_ip.php?ip='.$b['ip'].'" target="_blank">'.$b['ip'].'</a> '.$nm.'</b></span> <span style="text-align:center;display:inline-block;width:250px;">'.$usr0.'</span> Запросов: <span style="display:inline-block;width:50px;text-align:center">'.$c.'</span> '.$cf.'<br>'.$data.'</div>';
		
		$i++;
	}
	
	$all_cf_val = 'очень низкая';
	
	if($all_cf > 90) {
		$all_cf_val = 'очень высокая';
	}elseif($all_cf > 75) {
		$all_cf_val = 'высокая';
	}elseif($all_cf > 50) {
		$all_cf_val = 'выше среднего';
	}elseif($all_cf > 25) {
		$all_cf_val = 'средняя';
	}elseif($all_cf > 15) {
		$all_cf_val = 'низкая';
	}
	
	$all = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `users` WHERE `online` > "'.(time() - 120).'" AND `pass` != "Itisalife" AND `inUser` = "0" LIMIT 1'));
		
	if($all[0] > 0) {
		$all = '<small><sup>&bull; '.($all[0]).' online</sup></small>';
	}else{
		$all = '';
	}
	
	echo '<hr style="border:0;border-top:1px solid grey;">Посетителей за это время: '.$i.' '.$all.' &nbsp; | &nbsp; Общая нагрузка: '.round($all_cf/2,2).'% ('.$all_cf_val.')<hr style="border:0;border-top:1px solid grey;">';

?>