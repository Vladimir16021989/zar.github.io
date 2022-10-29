<?php
	define('GAME',true);
	include('_incl/__config.php');	
	include('_incl/class/__db_connect.php');	
	include('_incl/class/__user.php');
	
	if($u->info['admin'] == 0) {
		die('st.php');
	}
	
	$_GET['ip'] = htmlspecialchars($_GET['ip'],NULL,'cp1251');
	
	$html = '';
	
	$html .= '<html><head><link href="http://img.combatz.ru/css/main.css" rel="stylesheet" type="text/css">'.
			 '<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" /></head><body>';
	
	$html .= '<h4>ip-адрес: '.$_GET['ip'];
	
	$ip_online = mysql_fetch_array(mysql_query('SELECT `id` FROM `error_logs` WHERE `ip` = "'.mysql_real_escape_string($_GET['ip']).'" AND `time` > '.(time()-120).' LIMIT 1'));
	if(isset($ip_online['id'])) {
		$html .= ' <span style="color:green"><sup>online</sup></span>';
	}
	
	$html .= '</h4><hr style="border:0;border-top:1px solid grey;">';
	
	$users = '';
	
	$users_id = array();
		
	$sp = mysql_query('SELECT `id`,`login`,`level`,`sex`,`online`,`align`,`clan`,`banned`,`molch1` FROM `users` WHERE `ip` = "'.mysql_real_escape_string($_GET['ip']).'" OR `ipreg` = "'.mysql_real_escape_string($_GET['ip']).'"');
	while($pl = mysql_fetch_array($sp)) {
		if(!isset($users_id[$pl['id']])) {
			$users_id[$pl['id']] = true;
			$users .= '<img src="http://img.combatz.ru/i/align/align'.$pl['align'].'.gif"><img src="http://img.combatz.ru/i/clan/'.$pl['clan'].'.gif"><a href="inf.php?'.$pl['id'].'" target="_blank">'.$pl['login'].'</a>['.$pl['level'].']';
			if($pl['online'] > time()-120) {
				$users .= '<small style="color:green"><sup>online</sup></small>';
			}
			$users .= ', ';
		}
	}
	
	$sp = mysql_query('SELECT `id`,`uid` FROM `logs_auth` WHERE `ip` = "'.mysql_real_escape_string($_GET['ip']).'"');
	while($pla = mysql_fetch_array($sp)) {
		if(!isset($users_id[$pla['uid']])) {
			$pl = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`level`,`sex`,`online`,`align`,`clan`,`banned`,`molch1` FROM `users` WHERE `ip` = "'.mysql_real_escape_string($_GET['ip']).'" LIMIT 1'));
			$users_id[$pla['uid']] = true;
			$users .= '<img src="http://img.combatz.ru/i/align/align'.$pl['align'].'.gif"><img src="http://img.combatz.ru/i/clan/'.$pl['clan'].'.gif"><a href="inf.php?'.$pl['id'].'" target="_blank">'.$pl['login'].'</a>['.$pl['level'].']';
			if($pl['online'] > time()-120) {
				$users .= '<small style="color:green"><sup>online</sup></small>';
			}
			$users .= ', ';
		}
	}
	
	if($users != '') {
		$users = rtrim($users,', ');
		$html .= 'Пользователи: '.$users;
	}else{
		$html .= '<b style="color:grey">Пользователи с данным ip не найдено</b>';
	}
	
	$html .= '<hr style="border:0;border-top:1px solid grey;">';
	
	/* Постраничный вывод */
	$page = intval((int)$_GET['page']);
	if($page < 1) {
		$page = 1;
	}
	$num = round(($page-1)*100);
	
	$pg_all = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `error_logs` WHERE `ip` = "'.mysql_real_escape_string($_GET['ip']).'" LIMIT 1'));
	$pg_all = $pg_all[0];
	$pg_all = ceil($pg_all/100);
	
	$i = 1;
	$pages = '';
	while($i <= $pg_all) {
		if($page == $i) {
			$pages .= ' <b style="text-decoration:underline;">'.$i.'</b> ';
		}else{
			$pages .= ' <a href="st_ip.php?ip='.$_GET['ip'].'&page='.$i.'">'.$i.'</a> ';
		}
		$i++;
	}
	$pages = '<div style="padding:10px;">Страницы: '.$pages.'</div>';
	
	$html .= $pages;
	$sp = mysql_query('SELECT * FROM `error_logs` WHERE `ip` = "'.mysql_real_escape_string($_GET['ip']).'" ORDER BY `time` DESC LIMIT '.mysql_real_escape_string($num).',100');
	while($pl = mysql_fetch_array($sp)) {
		
		$css = '';
		
		$html .= '<div style="padding:2px;padding:5px;border-bottom:1px solid #efefef;'.$css.'">';
		
		$html .= '<small style="color:#BABABA">';
		if(date('d.m.y') == date('d.m.y',$pl['time'])) {
			$html .= date('H:i:s',$pl['time']);
		}else{
			$html .= date('d.m.y H:i:s',$pl['time']);
		}
		$html .= '</small>';
		
		/* Микро-данные */
		$server = unserialize($pl['SERVER']);
		$get = unserialize($pl['GET']);
		$post = unserialize($pl['POST']);		
		$cookie = unserialize($pl['COOKIE']);
		
		//COOKIE
			$html_cookie = '';		
			if($cookie['login'] != '') {
				$html_cookie .= '`login`->&quot;'.$cookie['login'].'&quot;';
			}
			if($cookie['ip'] != '' && $cookie['ip'] != $pl['ip']) {
				$html_cookie .= '`IP`->&quot;<a href="st_ip.php?ip='.$cookie['ip'].'" target="_blank">'.$cookie['ip'].'</a>&quot;';
			}
			
			if($html_cookie != '') {
				$html .= '<div style="display:inline-block;padding:5px;"><b>COOKIE:</b> <small>'.$html_cookie.'</small></div>';
			}
		
		//SERVER
			$html_server = '';
			
			if($server['REQUEST_URI']) {
				$html_server .= ' &nbsp; | &nbsp; <b>'.$server['REQUEST_METHOD'].'-запрос</b>: <a href="'.$server['REQUEST_URI'].'" target="_blank">'.$server['REQUEST_URI'].'</a>';
			}
			
			if($server['HTTP_REFERER']) {
				$html_server .= ' &nbsp; | &nbsp; <b>Переход с</b>: <a href="'.$server['HTTP_REFERER'].'" target="_blank">'.$server['HTTP_REFERER'].'</a>';
			}
			
			if($html_server != '') {
				$html .= '<div style="display:inline-block;padding:5px;"><small>'.$html_server.'</small></div>';
			}
		
		//POST
			$html_post = '';
			
			if(count($post) > 0 ) {
				
				if(isset($post['msg'])) {
					$post['msg'] = iconv('UTF-8','windows-1251', $post['msg']);
				}
				
				$post = print_r($post,true);
				$html_post = $post;
			}
			
			
			if($html_post != '') {
				$html .= '<div style="display:inline-block;padding:5px;"><b style="color:blue">POST-Date</b>: <small>'.$html_post.'</small></div>';
			}
		
		$html .= '</div>';
	}	
	$html .= $pages;
	
	$html .= '</body></html>';
	
	echo $html;
	
?>