<?php
define('GAME',true);

include('_incl/__config.php');
include('_incl/class/__db_connect.php');
include('_incl/class/__chat_class.php');
include('_incl/class/__filter_class.php');

if( isset($_POST['ajax_reg']) ) {	
	include('_incl/class/__reg.php');
	include('_incl/class/__user.php');
	if( isset($u->info['id']) && $u->info['bithday'] == '01.01.1800') {
		//
		$_POST['reg_login'] = iconv('UTF-8', 'windows-1251', $_POST['reg_login']);
		//
		$reg_d = array(
			0 => $_POST['reg_login'],
			1 => $_POST['reg_pass'],
			2 => $_POST['reg_pass2'],
			3 => $_POST['reg_mail'],
			7 => $_POST['reg_dd'],
			8 => $_POST['reg_mm'],
			9 => $_POST['reg_yy'],
			15 => $_POST['reg_sex']
		);
		//
		$error = '';
		//
					//����������� ������
					$nologin = array(0=>'�����',1=>'angel',2=>'�������������',3=>'administration',4=>'�����������',5=>'�����������',6=>'��������',7=>'���������',8=>'����������',9=>'����������',10=>'�����������',11=>'��������',12=>'���� �����������',13=>'����������',14=>'��������������',15=>'���������',16=>'����������');
					$blacklist = "!@#$%^&*()\+��|/'`\"";
					$sr = '_-���������������������������������1234567890';
					$i = 0;
					while($i<count($nologin))
					{
						if(preg_match("/".$nologin[$i]."/i",$filter->mystr($reg_d[0])))
						{
							$error = '��������, ����������, ������ ���.<br>'; $_POST['step'] = 1; $i = count($nologin);
						}
						$i++;
					}
					$reg_d[0] = str_replace('  ',' ',$reg_d[0]);
					//����� �� 2 �� 20 ��������
					if(strlen($reg_d[0])>20) 
					{ 
						$error = '����� ������ ��������� �� ����� 20 ��������.<br>'; $_POST['step'] = 1;
					}
					if(strlen($reg_d[0])<2) 
					{ 
						$error = '����� ������ ��������� �� ����� 2 ��������.<br>'; $_POST['step'] = 1;
					}
					//���� �������
					$er = $r->en_ru($reg_d[0]);
					if($er==true)
					{
						$error = '� ������ ��������� ������������ ������ ����� ������ �������� �������� ��� �����������. ������ ���������.<br>'; $_POST['step'] = 1;
					}
					//����������� �������
					if(strpos($sr,$reg_d[0]))
					{
						$error = '����� �������� ����������� �������.<br>'; $_POST['step'] = 1;
					}				
					//��������� � ����
					$log = mysql_fetch_array(mysql_query('SELECT `id` from `users` where `login`="'.mysql_real_escape_string($reg_d[0]).'" LIMIT 1'));
					$log2 = mysql_fetch_array(mysql_query('SELECT `id` from `lastNames` where `login`="'.mysql_real_escape_string($reg_d[0]).'" LIMIT 1'));
					if(isset($log['id']) || isset($log2['id']))
					{
						$error = '����� '.$reg_d[0].' ��� �����, �������� ������.<br>'; $_POST['step'] = 1;
					}
					//�����������
					if(substr_count($reg_d[0],' ')+substr_count($reg_d[0],'-')+substr_count($reg_d[0],'_')>2)
					{
						$error = '�� ����� ���� ������������ ������������ (������, ����, ������ �������������).<br>'; $_POST['step'] = 1;
					}
					$reg_d[0] = trim($reg_d[0],' ');	
					
					//��������� ������
					if(strlen($reg_d[1])<6 || strlen($reg_d[1])>30)
					{
						$error = '����� ������ �� ����� ���� ������ 6 �������� ��� ����� 30 ��������.<br>'; $_POST['step'] = 2;
					}
					if($reg_d[1]!=$reg_d[2])
					{
						$error = '� ������ ������ ����� ������ ������, ��� ��������. �� ������ ��� �� ��� ����� �������, ������ ������������.<br>'; $_POST['step'] = 2;
					}
					if(preg_match('/'.$reg_d[0].'/i',$reg_d[1]))
					{
						$error = '������ �������� �������� ������.<br>'; $_POST['step'] = 2;
					}
					if( $reg_d[1] != $reg_d[2] ) {
						$error = '������ �� ���������.<br>'; $_POST['step'] = 2;
					}
					if($_POST['step']!=2)
					{
						$stp = 3; $noup = 0;
					}
					//��������� e-mail
					if(strlen($reg_d[3])<6 || strlen($reg_d[3])>50)
					{
						$error = 'E-mail �� ����� ���� ������ 6-� �������� � ������ 50-��.<br>'; $_POST['step'] = 3;
					}
					
					if(!preg_match('#^[a-z0-9.!\#$%&\'*+-/=?^_`{|}~]+@([0-9.]+|([^\s]+\.+[a-z]{2,6}))$#si', $reg_d[3]))
					{
						$error = '�� ������� ���� ��������� E-mail.<br>'; $_POST['step'] = 3;
					}
					
					if( $_POST['mail_post'] != 'true' ) {
						$error = '����� ���������� �� ����������� �������� ���������� �� ��� E-mail';
					}
					
					$reg_d[4] = $chat->str_count($reg_d[4],30);
					$reg_d[5] = $chat->str_count($reg_d[5],30);
					
					if($_POST['step']!=3)
					{
						$stp = 4; $noup = 0;
					}
					
					$reg_d[6] = $chat->str_count($reg_d[6],90);
					$reg_d[7] = round($reg_d[7]);
					$reg_d[8] = round($reg_d[8]);
					$reg_d[9] = round($reg_d[9]);
					
					if($reg_d[7]<1 || $reg_d[7]>31 || $reg_d[8]<1 || $reg_d[8]>12 || $reg_d[9]<1920 || $reg_d[9]>2006)
					{
						$error = '������ � ��������� ��� ��������.<br>'; $_POST['step'] = 4;
					}
					
					if($reg_d[15]!=1 && $reg_d[15]!=2)
					{
						$error = '�� ������� �� ������ ���.<br>'; $_POST['step'] = 4;
					}			
		
		if( $error == '' ) {
			if( $reg_d[15] != 2 ) {
				$reg_d[15] = 0;
			}else{
				$reg_d[15] = 1;
			}
			setcookie('login',$reg_d[0],time()+60*60*24*7,'',$c['host']);
			setcookie('pass',md5($reg_d[1]),time()+60*60*24*7,'',$c['host']);
			mysql_query('UPDATE `users` SET
			`login` = "'.mysql_real_escape_string($reg_d[0]).'",
			`activ` = "1",
			`pass` = "'.mysql_real_escape_string(md5($reg_d[1])).'",
			`mail` = "'.mysql_real_escape_string($reg_d[3]).'",
			`bithday` = "'.mysql_real_escape_string($reg_d[7].'.'.$reg_d[8].'.'.$reg_d[9]).'",
			`sex` = "'.mysql_real_escape_string($reg_d[15]).'",
			`fnq` = "0"
			WHERE `id` = "'.mysql_real_escape_string($u->info['id']).'" LIMIT 1');
			
			if( $u->info['host_reg'] > 0 ) {
				$refer = mysql_fetch_array(mysql_query('SELECT `id` FROM `users` WHERE `id` = "'.$u->info['host_reg'].'" LIMIT 1'));
				if( isset($refer['id']) ) {
					$u->addItem(3199,$u->info['id']);
					$u->addItem(4005,$refer['id']);
				}else{
					$u->addItem(3199,$u->info['id']);
					$nast = 1001398;
					mysql_query('UPDATE `users` SET
					`host_reg` = "'.$nast.'"
					WHERE `id` = "'.mysql_real_escape_string($u->info['id']).'" LIMIT 1');
				}
			}else{
				$u->addItem(3199,$u->info['id']);
				$nast = 1001398;
				mysql_query('UPDATE `users` SET
				`host_reg` = "'.$nast.'"
				WHERE `id` = "'.mysql_real_escape_string($u->info['id']).'" LIMIT 1');
			}
			
			//������ �������� � ���������� ��������� � ���//������ �������� � ���������� ��������� � ���//������ �������� � ���������� ��������� � ���//������ �������� � ���������� ��������� � ���
			
				$text = '<b>'.$reg_d[0].'</b>, ���� � ��� �������� ����������� � ����������� ������, ��������� �� ��������� ������ - <a href=http://combatz.ru/library/noobguide/ target=_blank >www.combatz.ru/library/noobguide</a> ';
			mysql_query("INSERT INTO `chat` (`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`new`) VALUES ('capitalcity','0','','".$reg_d[0]."','".$text."','".time()."','6','0','1')");
			/*	$text = '��� ������ �������� � ���� ���������. ���� ������ �������� ���� �� �������, ���������� ��� � ��������� ������. �������� ��������� � ��������� �����>> ������ ������, ������������ &quot;������ ��������&quot;, ������� ���� ���� +300 000 �����, 8�� �������, � �������� ����� �� ���������� � ������ ������ � ���� � ������� ���� �����������. ����� ����, ��� ����� ������ ������, �� ������������ ���� �������, � ����: �������� ��������� +15, ������� �������, � ������ ������ -����- , ������� �������� ������� ���� � ��� ������� �� 99% �� ���������, � ������ ���� �� ������ ������� ��������! ���� ��������, ����� ����������� ����� ����� ��������. �� ������������ ������ �������, �������� �� ��������: ������� ���������� ����� �� ����������� ������� (��������� <a href=http://events.combatz.ru/?paged=0&st=13 target=_blank >events.combatz.ru</a> ). �� ���� ������� ��������, �� ������ ������ ���������� ���������� ������ �����';
			mysql_query("INSERT INTO `chat` (`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`new`) VALUES ('capitalcity','0','','".$reg_d[0]."','".$text."','".time()."','6','0','1')");
			*/
			
			$refer = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`banned`,`admin`,`level` FROM `users` WHERE `id` = "'.mysql_real_escape_string($_GET['ref']).'" LIMIT 1'));
			if(isset($refer['id'])) {
				mysql_query("INSERT INTO `items_users` (`gift`,`uid`,`item_id`,`data`,`iznosMAX`,`geniration`,`maidin`,`time_create`) VALUES ('".$refer['login']."','".$u->info['id']."','3199','noodet=1|items_in_file=sunduk_new|var_id=1|open=1|noremont=1|nodelete=1|nosale=1|sudba=".mysql_real_escape_string($reg_d[0])."',1,2,'capitalcity',".time().")");
			}
			
			//������
			$re = $u->addItem(1,$u->info['id'],'|');
			if( $re > 0 ) {
				mysql_query('UPDATE `items_users` SET `gift` = "�����������" WHERE `id` = "'.$re.'" LIMIT 1');
			}
			//�����
			$re = $u->addItem(73,$u->info['id'],'|');
			if( $re > 0 ) {
				mysql_query('UPDATE `items_users` SET `gift` = "��������" WHERE `id` = "'.$re.'" LIMIT 1');
			}
			$re = $u->addItem(2133,$u->info['id'],'|sudba='.$reg_d[0].'|nosale=1|srok='.(86400*14).'');
			if( $re > 0 ) {
				mysql_query('UPDATE `items_users` SET `gift` = "����������" WHERE `id` = "'.$re.'" LIMIT 1');
			}
			//������ +300.000 �����
			/*$re = $u->addItem(4014,$u->info['id'],'|sudba='.$reg_d[0].'|nosale=1|nodelete=1');
			if( $re > 0 ) {
				mysql_query('UPDATE `items_users` SET `gift` = "����������" WHERE `id` = "'.$re.'" LIMIT 1');
			}
			//������ ����
			//$re = $u->addItem(1190,$u->info['id'],'|sudba='.$reg_d[0].'|nosale=1',NULL,0);
			//����� �����
			$re = $u->addItem(724,$u->info['id'],'|sudba='.$reg_d[0].'|nosale=1',NULL,50);
			//����� ������
			$re = $u->addItem(1463,$u->info['id'],'|sudba='.$reg_d[0].'|nosale=1',NULL,1);
			//����� �������
			$re = $u->addItem(1462,$u->info['id'],'|sudba='.$reg_d[0].'|nosale=1',NULL,1);
			//����� �������
			$re = $u->addItem(1461,$u->info['id'],'|sudba='.$reg_d[0].'|nosale=1',NULL,1);
			//������ ������������
			$re = $u->addItem(4038,$u->info['id'],'|sudba='.$reg_d[0].'|nosale=1',NULL,1);
			//������ ��������
			$re = $u->addItem(4039,$u->info['id'],'|sudba='.$reg_d[0].'|nosale=1',NULL,1);
			//����� ������������
			$re = $u->addItem(4037,$u->info['id'],'|sudba='.$reg_d[0].'|nosale=1',NULL,1);
			//����� ����
			$re = $u->addItem(4040,$u->info['id'],'|sudba='.$reg_d[0].'|nosale=1',NULL,1);
			*/
			//������ �������� � ���������� ��������� � ���//������ �������� � ���������� ��������� � ���//������ �������� � ���������� ��������� � ���//������ �������� � ���������� ��������� � ���
			
			$error = '����������� ������ �������! �������!<br>����� 3 ���. �� ������ �������������� � ����!<script>setTimeout(\'top.location.href="/buttons.php"\',2000);</script>';
		}
		
		die( $error );
	}
}else{

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
	
	function error($e)
	{
		 global $c;
		 die('<html><head>
		 <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
		 <meta http-equiv="Content-Language" content="ru"><TITLE>��������� ������</TITLE></HEAD>
		 <BODY text="#FFFFFF"><p><font color=black>
		 ��������� ������: <pre>'.$e.'</pre><b><p><a onClick="window.history.go(-1); return false;" href="#">�����</b></a><HR>
		 <p align="right">(c) <a href="http://'.$c['host'].'/">'.$c['name'].'</a></p>
		 <!--Rating@Mail.ru counter--><!--// Rating@Mail.ru counter-->
		 </body></html>');
	}
	
	if( isset($_COOKIE['login']) ) {
			setcookie('login',false,time()-60*60*24*30,'','.combatz.ru/');
			setcookie('pass',false,time()-60*60*24*30,'','.combatz.ru/');
			setcookie('login',false,time()-60*60*24*30);
			setcookie('pass',false,time()-60*60*24*30);
	}
	
	$lr = mysql_fetch_array(mysql_query('SELECT `id`,`ipreg`,`pass`,`bithday`,`login` FROM `users` WHERE `cityreg`="capitalcity" AND `timereg`>"'.(time()-60*60*1).'" AND `ipreg` = "'.mysql_real_escape_string(IP).'" LIMIT 1'));
	if(/*isset($_COOKIE['reg_capitalcity']) || (int)$_COOKIE['reg_capitalcity']>time() ||*/ isset($lr['id2'])) {
		if( isset($lr['id']) && $lr['bithday'] == '01.01.1800' ) {
			if( isset($_GET['enter']) ) {
				setcookie('login',$lr['login'],time()+60*60*24*7,'',$c['host']);
				setcookie('pass',$lr['pass'],time()+60*60*24*7,'',$c['host']);
				header('location: http://combatz.ru/buttons.php');
			}
			error('������� � ������ IP ��� ��������������� ��������. � ������ IP ������ ��������� ����������� ���������� �� ����, ��� ��� � ���. ���������� �����.<br>��� ����������� <b>'.$lr['login'].'</b> ��������� �� ������: <a href="/reg.php?enter">����������������</a>');
		}else{
			error('������� � ������ IP ��� ��������������� ��������. � ������ IP ������ ��������� ����������� ���������� �� ����, ��� ��� � ���. ���������� �����.<br>');
		}
	}else{
		//������� ���������
		$pass = md5(md5(rand(0,100.).'#'.rand(0,1000)));
		mysql_query('INSERT INTO `users` (`host_reg`,`pass`,`ip`,`ipreg`,`city`,`cityreg`,`room`,`timereg`) VALUES (
			"'.mysql_real_escape_string(0+$_GET['ref']).'",
			"'.mysql_real_escape_string($pass).'",
			"'.mysql_real_escape_string(IP).'",
			"'.mysql_real_escape_string(IP).'",
			"capitalcity",
			"capitalcity",
			"0",
			"'.time().'"
		)');	
		$uid = mysql_insert_id();
		if( $uid > 0 ) {
			$login = '�������'.$uid;
			mysql_query('UPDATE `users` SET `login` = "'.mysql_real_escape_string($login).'" WHERE `id` = "'.$uid.'" LIMIT 1');
			//������� ����� ���������
			mysql_query("INSERT INTO `online` (`uid`,`timeStart`) VALUES ('".$uid."','".time()."')");
			mysql_query("INSERT INTO `stats` (`id`,`stats`) VALUES ('".$uid."','s1=3|s2=3|s3=3|s4=3|rinv=40|m9=5|m6=10')");	
			
			//������
			$ipm1 = mysql_fetch_array(mysql_query('SELECT * FROM `logs_auth` WHERE `uid` = "'.mysql_real_escape_string($uid).'" AND `ip`!="'.mysql_real_escape_string(IP).'" ORDER BY `id` ASC LIMIT 1'));
			$ppl = mysql_query('SELECT * FROM `logs_auth` WHERE `ip`!="" AND (`ip` = "'.mysql_real_escape_string(IP).'" OR `ip`="'.mysql_real_escape_string($ipm1['ip']).'" OR `ip`="'.mysql_real_escape_string($_COOKIE['ip']).'")');
			while($spl = mysql_fetch_array($ppl))
			{
				$ml = mysql_fetch_array(mysql_query('SELECT `id` FROM `mults` WHERE (`uid` = "'.$spl['uid'].'" AND `uid2` = "'.$uid.'") OR (`uid2` = "'.$spl['uid'].'" AND `uid` = "'.$uid.'") LIMIT 1'));
				if(!isset($ml['id']) && $spl['ip']!='' && $spl['ip']!='127.0.0.1')
				{
					mysql_query('INSERT INTO `mults` (`uid`,`uid2`,`ip`) VALUES ("'.$uid.'","'.$spl['uid'].'","'.$spl['ip'].'")');
				}
			}
			mysql_query("INSERT INTO `logs_auth` (`uid`,`ip`,`browser`,`type`,`time`,`depass`) VALUES ('".$uid."','".mysql_real_escape_string(IP)."','".mysql_real_escape_string($_SERVER['HTTP_USER_AGENT'])."','1','".time()."','')");
			
			//�������� �������
			mysql_query("UPDATE `users` SET `online`='".time()."',`ip` = '".mysql_real_escape_string(IP)."' WHERE `uid` = '".$uid."' LIMIT 1");
			
			if(!setcookie('login',$login, (time()+60*60*24*7) , '' , '.combatz.ru' ) || !setcookie('pass',$pass, (time()+60*60*24*7) , '' , '.combatz.ru' )) {
				die('������ ���������� cookie.');
			}else{
				/*
				die('������� �� �����������!<br><script>function test(){ top.location.href="http://combatz.ru/buttons.php"; } setTimeout("test()",1000);</script>');
				*/
			}
			header('location: http://combatz.ru/buttons.php');
		}
	}
}

?>