<?
/*header('location: reg.php');
die();*/
//30.05.2060 07:25:06
define('GAME',true);
include('_incl/__config.php');
include('_incl/class/__db_connect.php');
include('_incl/class/__chat_class.php');
include('_incl/class/__filter_class.php');

if(isset($_GET['showcode'])) {
	include('show_reg_img/security.php');
	die();
}

/* ����������� AJAX */
if( isset($_POST['id']) ) {
	session_start();
	include('_incl/class/__reg.php');
	include('_incl/class/__user.php');
	$rt = '';
	//
	$gd = array( 0,0,0,0,0,0,0,0,0,0,0 );
	$reg_d = array(
		0 => htmlspecialchars(iconv('UTF-8', 'windows-1251', $_POST['login']),NULL,'cp1251'),
		1 => htmlspecialchars(iconv('UTF-8', 'windows-1251', $_POST['pass']),NULL,'cp1251'),
		2 => htmlspecialchars(iconv('UTF-8', 'windows-1251', $_POST['pass2']),NULL,'cp1251'),
		3 => (int)$_POST['dd'],
		4 => (int)$_POST['mm'],
		5 => (int)$_POST['yy'],
		6 => (int)$_POST['sex'],
		7 => (int)$_POST['rules'],
		8 => htmlspecialchars(iconv('UTF-8', 'windows-1251', $_POST['mail']),NULL,'cp1251'),
		9 => (int)$_POST['keycode']
	);
	//�������� ������
	//����������� ������
	$error = '';
	$good = 1;
	$nologin = array(0=>'�����',1=>'angel',2=>'�������������',3=>'administration',4=>'�����������',5=>'�����������',6=>'��������',7=>'���������',8=>'����������',9=>'����������',10=>'�����������',11=>'��������',12=>'���� �����������',13=>'����������',14=>'��������������',15=>'���������',16=>'����������');
					$blacklist = "!@#$%^&*()\+��|/'`\"";
					$sr = '_-���������������������������������1234567890';
					$i = 0;
					while($i<count($nologin))
					{
						if(preg_match("/".$nologin[$i]."/i",$filter->mystr($reg_d[0])))
						{
							$error = '��������, ����������, ������ ���.'; $_POST['step'] = 1; $i = count($nologin);
						}
						$i++;
					}
					$reg_d[0] = str_replace('  ',' ',$reg_d[0]);
					//����� �� 2 �� 20 ��������
					if(strlen($reg_d[0])>20) 
					{ 
						$error = '����� ������ ��������� �� ����� 20 ��������.'; $_POST['step'] = 1;
					}
					if(strlen($reg_d[0])<2) 
					{ 
						$error = '����� ������ ��������� �� ����� 2 ��������.'; $_POST['step'] = 1;
					}
					//���� �������
					$er = $r->en_ru($reg_d[0]);
					if($er==true)
					{
						$error = '� ������ ��������� ������������ ������ ����� ������ �������� �������� ��� �����������. ������ ���������.'; $_POST['step'] = 1;
					}
					//����������� �������
					if(strpos($sr,$reg_d[0]))
					{
						$error = '����� �������� ����������� �������.'; $_POST['step'] = 1;
					}				
					//��������� � ����
					$log = mysql_fetch_array(mysql_query('SELECT `id` from `users` where `login`="'.mysql_real_escape_string($reg_d[0]).'" LIMIT 1'));
					$log2 = mysql_fetch_array(mysql_query('SELECT `id` from `lastNames` where `login`="'.mysql_real_escape_string($reg_d[0]).'" LIMIT 1'));
					if(isset($log['id']) || isset($log2['id']))
					{
						$error = '����� '.$reg_d[0].' ��� �����, �������� ������.'; $_POST['step'] = 1;
					}
					//�����������
					if(substr_count($reg_d[0],' ')+substr_count($reg_d[0],'-')+substr_count($reg_d[0],'_')>2)
					{
						$error = '�� ����� ���� ������������ ������������ (������, ����, ������ �������������).'; $_POST['step'] = 1;
					}
					$reg_d[0] = trim($reg_d[0],' ');	
					if($error != '') {
						$gd[0] = $error;
						$good = 0;
					}else{
						$gd[0] = 1;
					}
					//��������� ������
					$error = '';
					if(strlen($reg_d[1])<6 || strlen($reg_d[1])>30)
					{
						$error = '����� ������ �� ����� ���� ������ 6 �������� ��� ����� 30 ��������.'; $_POST['step'] = 2;
					}
					if($reg_d[1]!=$reg_d[2])
					{
						$error = '� ������ ������ ����� ������ ������, ��� ��������. �� ������ ��� �� ��� ����� �������, ������ ������������.'; $_POST['step'] = 2;
					}
					if(preg_match('/'.$reg_d[0].'/i',$reg_d[1]))
					{
						$error = '������ �������� �������� ������.'; $_POST['step'] = 2;
					}
					if( $reg_d[1] != $reg_d[2] ) {
						$error = '������ �� ���������.'; $_POST['step'] = 2;
					}
					if($_POST['step']!=2)
					{
						$stp = 3; $noup = 0;
					}
					if($error != '') {
						$gd[1] = $error;
						$good = 0;
					}else{
						$gd[1] = 1;
					}
					
					//�������� ����
					$error = '';
					$ddmmyy = array(
						'',
						'January',
						'February',
						'March',
						'April',
						'May',
						'June',
						'July',
						'August',
						'September',
						'October',
						'November',
						'December'
					);
					
					$tstd = date('d.m.Y',strtotime(''.$reg_d[3].' '.$ddmmyy[$reg_d[4]].' '.$reg_d[5].''));
					if( $reg_d[3] < 10 ) {
						$reg_d[3] = '0'.$reg_d[3];
					}
					if( $reg_d[4] < 10 ) {
						$reg_d[4] = '0'.$reg_d[4];
					}
					if( $tstd != ''.$reg_d[3].'.'.$reg_d[4].'.'.$reg_d[5].'' ) {
						$error = '������ � ��������� ��� ��������.';
					}
					if($error != '') {
						$gd[2] = $error;
						$good = 0;
					}else{
						$gd[2] = 1;
					}
					
					if( $reg_d[7] != 1 ) {
						$error = '������� ���������� � ����� ���������� �� ����������� �������� ���������� �� ��� E-mail';
					}
					if($error != '') {
						$gd[3] = $error;
						$good = 0;
					}else{
						$gd[3] = 1;
					}
					
					$error = '';
					//��������� e-mail
					if(strlen($reg_d[8])<6 || strlen($reg_d[8])>50)
					{
						$error = 'E-mail �� ����� ���� ������ 6-� �������� � ������ 50-��.'; $_POST['step'] = 3;
					}
					
					if(!preg_match('#^[a-z0-9.!\#$%&\'*+-/=?^_`{|}~]+@([0-9.]+|([^\s]+\.+[a-z]{2,6}))$#si', $reg_d[8]))
					{
						$error = '�� ������� ���� ��������� E-mail.<br>'; $_POST['step'] = 3;
					}
					if($error != '') {
						$gd[4] = $error;
						$good = 0;
					}else{
						$gd[4] = 1;
					}
					
					$error = '';
					//��������� �����
					if(strlen($reg_d[9]) != 4 || $reg_d[9] != $_SESSION['code'])
					{
						$error = '������� ������ ��� ������������� ['.$reg_d[9].']'; $_POST['step'] = 3;
					}
					
					if($error != '') {
						$gd[5] = $error;
						$good = 0;
					}else{
						$gd[5] = 1;
					}
	
	if( $good == 1 ) {
		if( $reg_d[6] == 2 ) {
			$reg_d[6] = 1;
		}else{
			$reg_d[6] = 0;
		}
		//������������
		/*
		0 => htmlspecialchars(iconv('UTF-8', 'windows-1251', $_POST['login']),NULL,'cp1251'),
		1 => htmlspecialchars(iconv('UTF-8', 'windows-1251', $_POST['pass']),NULL,'cp1251'),
		2 => htmlspecialchars(iconv('UTF-8', 'windows-1251', $_POST['pass2']),NULL,'cp1251'),
		3 => (int)$_POST['dd'],
		4 => (int)$_POST['mm'],
		5 => (int)$_POST['yy'],
		6 => (int)$_POST['sex'],
		7 => (int)$_POST['rules'],
		8 => htmlspecialchars(iconv('UTF-8', 'windows-1251', $_POST['mail']),NULL,'cp1251'),
		9 => (int)$_POST['keycode']
		*/
		//������� ���������
		mysql_query('INSERT INTO `users` (`login`,`host_reg`,`pass`,`ip`,`ipreg`,`city`,`cityreg`,`room`,`timereg`,
		`activ`,`mail`,`bithday`,`sex`,`fnq`
		) VALUES (
			"'.mysql_real_escape_string($reg_d[0]).'",
			"'.mysql_real_escape_string(0+(int)$_POST['ref']).'",
			"'.mysql_real_escape_string(md5($reg_d[1])).'",
			"'.mysql_real_escape_string(IP).'",
			"'.mysql_real_escape_string(IP).'",
			"capitalcity",
			"capitalcity",
			"0",
			"'.time().'",			
			"1",
			"'.mysql_real_escape_string($reg_d[8]).'",
			"'.mysql_real_escape_string($reg_d[3].'.'.$reg_d[4].'.'.$reg_d[5]).'",
			"'.mysql_real_escape_string($reg_d[6]).'",
			"0"
		)');	
				
		$uid = mysql_insert_id();
		if( $uid > 0 ) {
				
			$text = '<b>'.$reg_d[0].'</b>, ���� � ��� �������� ����������� � ����������� ������, ��������� �� ��������� ������ - <a href=http://combatz.ru/library/noobguide/ target=_blank >www.combatz.ru/library/noobguide</a> ';
			mysql_query("INSERT INTO `chat` (`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`new`) VALUES ('capitalcity','0','','".$reg_d[0]."','".$text."','".time()."','6','0','1')");
	
			//������
			$re = $u->addItem(1,$uid,'|');
			if( $re > 0 ) {
				mysql_query('UPDATE `items_users` SET `gift` = "�����������" WHERE `id` = "'.$re.'" LIMIT 1');
			}
			//�����
			$re = $u->addItem(73,$uid,'|');
			if( $re > 0 ) {
				mysql_query('UPDATE `items_users` SET `gift` = "��������" WHERE `id` = "'.$re.'" LIMIT 1');
			}
			$re = $u->addItem(2133,$uid,'|sudba='.$reg_d[0].'|nosale=1|srok='.(86400*14).'');
				if( $re > 0 ) {
				mysql_query('UPDATE `items_users` SET `gift` = "����������" WHERE `id` = "'.$re.'" LIMIT 1');
			}
	
			
			$u->addItem(3199,$uid);
			$nast = 1001398;
			mysql_query('UPDATE `users` SET
			`host_reg` = "'.$nast.'"
			WHERE `id` = "'.mysql_real_escape_string($uid['id']).'" LIMIT 1');
			
			mysql_query('UPDATE `users` SET `online` = "'.time().'" WHERE `id` = "'.$uid.'" LIMIT 1');
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
			
			if(!setcookie('login',$reg_d[0], (time()+60*60*24*7) , '' , '.combatz.ru' ) || !setcookie('pass',md5($reg_d[1]), (time()+60*60*24*7) , '' , '.combatz.ru' )) {
				die('������ ���������� cookie.');
			}else{
				/*
				die('������� �� �����������!<br><script>function test(){ top.location.href="http://combatz.ru/buttons.php"; } setTimeout("test()",1000);</script>');
				*/
			}
			
			setcookie('login',$reg_d[0],time()+60*60*24*7,'',$c['host']);
			setcookie('pass',md5($reg_d[1]),time()+60*60*24*7,'',$c['host']);
			setcookie('login',$reg_d[0],time()+60*60*24*7);
			setcookie('pass',md5($reg_d[1]),time()+60*60*24*7);
			
			//header('location: http://combatz.ru/buttons.php');
		}
	}
	
	if( $good == 1 ) {
		$gd[6] = 1;
	}
	
	$rt .= '["'.$gd[0].'","'.$gd[1].'","'.$gd[2].'","'.$gd[3].'","'.$gd[4].'","'.$gd[5].'","'.$gd[6].'","'.$gd[7].'","'.$gd[8].'","'.$gd[9].'","'.$gd[10].'"]';
	//
	die($rt);
}

/* ������ ����������� */
$reg_id = microtime();
$reg_id = str_replace(' ','.',$reg_id);
$reg_id = str_replace('.','',$reg_id);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<title>����������� � ���� &laquo;����������� �����&raquo;</title>
<script type="text/javascript" src="scripts/jquery.js"></script>
<script type="text/javascript" src="scripts/psi.js"></script>
<link rel="stylesheet" href="styles/register.css?<?=time()?>" type="text/css" media="screen"/>
</head>

<body>
<div align="center" style="color:red;" id="errorreg"></div>
<center>
<!-- test window -->
<br><br>
<div>
<table style="padding-top:70px;" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="62"><table width="100%" height="62" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="129" class="psi_tlimg">&nbsp;</td>
        <td align="center" class="psi_tline">
        	<div class="psi_fix">
          	  <div class="psi_logo">&nbsp;</div>
            </div>
        </td>
        <td width="129" class="psi_trimg">&nbsp;</td>
      </tr>
      </table></td>
    </tr>
  <tr>
    <td>
    <table class="psi_mainin" width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="23" class="psi_mleft">&nbsp;</td>
        <td valign="top" width="682" height="401" class="psi_main_reg">
        <!-- main -->
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
        <form id="register_main" method="post" action="register.php">
        <input name="reg_id" id="reg_id" type="hidden" value="<?=$reg_id?>" />
        <div style="padding:20px;" align="right">
        <div style="padding-right:25px;">
        <center style="font-size:18px;"><img alt="����������� ������ ���������" src="images/reg_txt1.png" width="256" height="18"></center><br>
        <!-- -->
        <div style="padding-bottom:5px;">
        	<span style="display:inline-block;width:130px;">����� ���������</span> &nbsp; <script>psi.inputPrint('register_login<?=$reg_id?>','register_login<?=$reg_id?>',null,null,null,'psi_input1','width:191px;');</script>
            
            <span style="display:inline-block;width:15px;">
           	 <span id="login_error" style="display:none;" class='tip'><a tabindex="1"><em>?</em></a><span class='answer'><div id="login_error_text">&nbsp;</div></span></span>
        	</span>
        </div>
        <div style="padding-bottom:5px;">
        	<span style="display:inline-block;width:130px;">������</span> &nbsp; <script>psi.inputPrint('register_pass<?=$reg_id?>','register_pass<?=$reg_id?>',null,null,'password','psi_input1','width:191px;');</script>
            <span style="display:inline-block;width:15px;">
           	 <span id="pass_error" style="display:none;" class='tip'><a tabindex="1"><em>?</em></a><span class='answer'><div id="pass_error_text">&nbsp;</div></span></span>
        	</span>
        </div>
        <div style="padding-bottom:5px;">
        	<span style="display:inline-block;width:130px;">������ ��� ���</span> &nbsp; <script>psi.inputPrint('register_pass2<?=$reg_id?>','register_pass2<?=$reg_id?>',null,null,'password','psi_input1','width:191px;');</script>
            <span style="display:inline-block;width:15px;">
           	&nbsp;
            </span>
        </div>
        <div style="padding-bottom:5px;">
        	<span style="display:inline-block;width:130px;">��� ���</span> &nbsp;&nbsp;&nbsp;
            	<span style="padding-left:15px;">
                    <small class="cp radio1txt" id="register_sex<?=$reg_id?>block1" value="1">
					<script>psi.radioPring('register_sex<?=$reg_id?>','register_sex<?=$reg_id?>',true,null,'�������');</script></small>
                    &nbsp; &nbsp; &nbsp;
                    <small class="cp radio1txt" id="register_sex<?=$reg_id?>block2" value="2">
					<script>psi.radioPring('register_sex<?=$reg_id?>','register_sex<?=$reg_id?>',true,2,'�������');
                    psi.radioPress('register_sex<?=$reg_id?>',$('#register_sex<?=$reg_id?>_1'),2);
                    </script></small>
                     &nbsp; &nbsp;&nbsp; 
                </span>
            <span style="display:inline-block;width:15px;">
           	 <span id="sex_error" style="display:none;" class='tip'><a tabindex="1"><em>?</em></a><span class='answer'><div id="sex_error_text">&nbsp;</div></span></span>
        	</span>
        </div>
        <div style="padding-bottom:5px;">
        	<span style="display:inline-block;width:130px;">���� ��������</span> &nbsp;
			<div id="1register_dd<?=$reg_id?>" align="center" class="psi_input1_none psi_list" style="width:43px;">
			  <select name="register_dd<?=$reg_id?>" id="register_dd<?=$reg_id?>">
			    <?
				$i = 1;
				while( $i <= 31 ) {
					$j = $i;
					if( $j < 10 ) {
						$j = '0'.$j;	
					}
				?>
                <option value="<?=$i?>"><?=$j?></option>	
                <?
					$i++;
                }
				unset($i,$j);
				?>		  	
              </select>            
            </div>
            <div id="1register_mm<?=$reg_id?>" align="center" class="psi_input1_none psi_list" style="width:43px;">
			  <select name="register_mm<?=$reg_id?>" id="register_mm<?=$reg_id?>">
			    <?
				$i = 1;
				while( $i <= 12 ) {
					$j = $i;
					if( $j < 10 ) {
						$j = '0'.$j;	
					}
				?>
                <option value="<?=$i?>"><?=$j?></option>	
                <?
					$i++;
                }
				unset($i,$j);
				?>		  	
              </select> 
            </div>
            <div id="1register_yyyy<?=$reg_id?>" align="center" class="psi_input1_none psi_list" style="width:60px;">
			  <select name="register_yyyy<?=$reg_id?>" id="register_yyyy<?=$reg_id?>">
			    <?
				$i = date('Y') - 10;
				while( $i >= date('Y') - 80 ) {
					$j = $i;
				?>
                <option value="<?=$i?>"><?=$j?></option>	
                <?
					$i--;
                }
				unset($i,$j);
				?>		  	
              </select> 		  	
            </div>
            <span style="display:inline-block;width:15px;">
           	 <span id="bd_error" style="display:none;" class='tip'><a tabindex="1"><em>?</em></a><span class='answer'><div id="bd_error_text">&nbsp;</div></span></span>
        	</span>
        </div>
        <div style="padding-bottom:5px;">
        	<span style="display:inline-block;width:130px;">E-mail</span> &nbsp; <script>psi.inputPrint('register_mail<?=$reg_id?>','register_mail<?=$reg_id?>',null,null,null,'psi_input1','width:191px;');</script>
            <span style="display:inline-block;width:15px;">
           	 <span id="mail_error" style="display:none;" class='tip'><a tabindex="1"><em>?</em></a><span class='answer'><div id="mail_error_text">&nbsp;</div></span></span>
        	</span>
        </div>
        <div style="padding-bottom:5px;">
        	<span style="display:inline-block;width:130px;">��� � ��������</span> &nbsp;
			<img src="register.php?showcode=1" width="107" height="26" style="display:inline-block; vertical-align:bottom;">
            <script>psi.inputPrint('register_key<?=$reg_id?>','register_key<?=$reg_id?>',null,null,null,'psi_input1','width:81px;');</script>
            <span style="display:inline-block;width:15px;">
           	 <span id="key_error" style="display:none;" class='tip'><a tabindex="1"><em>?</em></a><span class='answer'><div id="key_error_text">&nbsp;</div></span></span>
        	</span>
        </div>
        <br>
        <div align="center" title="� �������� ��� ������� � ����������, � ���-�� �������� ��������� ���� �� E-mail">
        	<small class="cp" id="register_rules<?=$reg_id?>block"><script>psi.checkPring('register_rules<?=$reg_id?>','register_rules<?=$reg_id?>',true);</script> &nbsp; <img style="margin-bottom:-15px;" src="images/reg_txt2.png" alt="" width="270" height="35"></small>
            <span style="display:inline-block;width:15px;">
           	 <span id="rules_error" style="display:none;" class='tip'><a tabindex="1"><em>?</em></a><span class='answer'><div id="rules_error_text">&nbsp;</div></span></span>
        	</span>
        </div>
        <br>
        <div align="center">
        	<div onClick="psi.testForm();" class="psi_btn">&nbsp;</div>
        </div>
        <!-- -->
        </div>
        </div>
        </form>
    </td>
    <td style="border-left:1px solid #161d21;" width="243">&nbsp;</td>
  </tr>
  </table>
<!-- main -->	
        </td>
        <td width="23" class="psi_mright">&nbsp;</td>
      </tr>
      </table>
    </td>
    </tr>
  <tr>
    <td height="62"><table width="100%" height="62" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="129" class="psi_dlimg">&nbsp;</td>
        <td class="psi_dline">&nbsp;</td>
        <td width="129" class="psi_drimg">&nbsp;</td>
      </tr>
    </table></td>
    </tr>
</table>
<div style="width:660px;text-align:justify;">
	<small>
        <hr><br>
        ����������� ���� � ��� ���������� ������������� ������ ����, � ������� ���������������� ��� ����� ������ �� ����������� ������ ���. � ���� ���������� ���� �������� ����� ���������� �������� ���� ��������� ������ ���� ��� ��������� �Combats 2004-2009�, �������, ������, ����� ����������������� ���� ���������� ���.
        <br><br>
        � ���������� ���������� ���� ������ ����� ���������� ��������� � ����������� ����� ����������� ����������, ������� ������� ��� mmorpg ���� ��� ����� �������������!
        <br><br><hr>
    </small>
    <div style="float:left">
        <a href="http://combatz.ru/">�������</a> &nbsp; &nbsp; 
        <a href="http://events.combatz.ru/">�������</a> &nbsp; &nbsp; 
        <a href="http://forum.combatz.ru/">�����</a> &nbsp; &nbsp; 
        <a href="http://combatz.ru/library/UserAgreement">����������</a>
    </div>
    <div style="float:right">CombatZ &copy; 2014-<?=date('Y')?></div>
</div>
</div>
<!-- test window -->
</center>
<br><br>
<script>
psi.startTestingData(<?=$reg_id?>);
</script>
</body>
</html>
