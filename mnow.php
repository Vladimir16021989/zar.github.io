<?php
/*define('GAME',true);
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
include('_incl/__config.php');
include('_incl/class/__db_connect.php');
include('_incl/class/__filter_class.php');
include('_incl/class/__chat_class.php');
$u = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `login`="'.mysql_real_escape_string($_COOKIE['login']).'" AND `pass`="'.mysql_real_escape_string($_COOKIE['pass']).'" LIMIT 1'));
if($u['admin'] == 0)
{
	die('Fuck off!');
}

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
			
			function send_mail($to,$to_name,$from = 'support@combats1.com',$name = '<b>���������� ����</b> 2',$title,$text) {
				send_mime_mail($name,
					   $from,
					   $to_name,
					   $to,
					   'CP1251',  // ���������, � ������� ��������� ������������ ������
					   'KOI8-R', // ���������, � ������� ����� ���������� ������
					   $title,
					   $text); // \r\n
			}

$title = '����������� ����� - �������������';
$txt   = '	
<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#dddddd">
	<tr>
		<td align="center" valign="top">
			
			<table width="600" cellspacing="0" cellpadding="0">
				<tr>
					<td>
						<table width="100%" border="0" cellspacing="0" cellpadding="10" bgcolor="#ffffff">
							<tr>
								<td>
									<br>
									<h3>���������(��) {login}!</h3><br>
									<table width="100%" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td style="font-family: Arial; font-size:10px; line-height:12px; text-align:center; color:#888;">
												<br />
												������������� � ���������� ���� � ���������� � ����� � ����������! � ���� ������ �� �������� ��� ��� ��� ��� ������� ��������� � ��������, � ���� ������, ��� ������ �������� � ����� ���� � ������� ���������� ����������.<br /><br />
											</td>
										</tr>
										<tr>
											<td style="font-size:0; line-height:0;"><a href="http://vk.cc/2idIpx" target="_blank">
											<img width="100%" src="http://new.ombats.com/ui/u1000000_1397372970.jpg" border="0" alt="" />
											</a></td>
										</tr>
										<tr>
											<td bgcolor="#778899">
												<table width="100%" border="0" cellspacing="0" cellpadding="10">
													<tr>
														<td style="color:#fff; font-size:14px; font-weight:bold; font-family:Arial; font-style:italic; " align="left">
														������������ ��������� ��������� ���� ����������� �����! 
														���� ��������, �� ����� ����� �� ��� ��� ������� ������ ������ � ������!
														<ul>													
														��� ����������� ��� �������:
														<ul>
														<br><li>��������� ��������������� ������ ��������� ��������� ��� �������� � �������� ����.</li>
														<br><li>����� ������ ������� � ������ ������ ���2.</li>
														<br><li>����� ������� ��������� ������� ��� � &laquo;����� ������&raquo;, � ��� �� ������ ������ ���� � ���� ��������� �� ������� ���������.</li>
														<br><li>���������� ���������� �������� �������.</li>
														<br><li>����� ��������� � ��������������� ������� � ������� - 100%.</li>
														<br><li>������ ���� �� ������� ���������� ���������� ������.</li>
														<br><li>���������� ������ � ������������� ���� ����� ������ ���� : &laquo;������ ������ ���������&raquo;, &laquo;������&raquo;, &laquo;���������&raquo;, &laquo;���������� ��������&raquo;, � ��� �� &laquo;�����������&raquo;.</li>
														<br><li>500 ������� ������� ��������� �����������: &laquo;���������&raquo;, &laquo;���������&raquo;, &laquo;��������&raquo;.</li>
														<br><li>���������� ������ &laquo;����� �����&raquo;. ������ ���������� ������ ���� � 21:00 �� ���, � ������� ����� ������� ������� ����� ������ ����� ��������, ��� ������� � ������� ���������� ����� �� ����������� � ������ ������� ������ �������� ������� �� ���� ���������� � ������� ����������� ����-�������� �������� ���� - ���� - �����������, � ������� ����� �������� &laquo;+500% �����&raquo;, � ��� �� ������� ������� ����� ����� �������� �� ������ ��������.</li>
														<br><li>���������� ��� ��������� "������" � �������� ���, � ����� ������� ������������ &laquo;&laquo;&laquo;�������&raquo;&raquo;&raquo;, &laquo;&laquo;&laquo;��������&raquo;&raquo;&raquo;, &laquo;&laquo;&laquo;�������&raquo;&raquo;&raquo;.</li>
														<br><li>����� �����, � ������� ������� ����� ��� ���� ����������.</li>
													    <br><li>���������������� ������ ������ ��������� � ������������ � ��������� � 24/7.</li>
													    <ul>
														<BR><BR>
														��������������� ����� ������ � �������� � ������� ������ + 290 000 �����, ��� ������� �������� ��������� 0/1, ������ VIP ������ - "VIP" �� 3 ���, ��� �� ������� ���� � ������ 12-�� ������� ������ � ������ 100$. ��� ���� ����� �� ���������� � �������� ����� ������ 13-�� ������� � �������� 1000$? ����� , ����� �� �������� ��� ��������.
														</td>
													</tr>
												</table>
											</td>
										</tr>
										<tr>
											<td style="font-size:0; line-height:0;">#</td>
										</tr>
										<tr>
											<td>
												<table width="100%" border="0" cellspacing="0" cellpadding="0">
													<tr>
														<td valign="top">
															
															<table width="100%" border="0" cellspacing="0" cellpadding="0">
																
																<tr>
																	<td bgcolor="#778899">
																		<table width="100%" border="0" cellspacing="0" cellpadding="10" >
																			<tr>
																				
																				<td style="color:#fff; font-size:11px; font-weight:bold; font-family:Arial; font-style:italic; " align="center">�� ��� ������, ��� ��� �� ����?</td>
																				
																			</tr>
																		</table>
																	</td>																
																</tr>																
										<tr>
											<td style="font-size:0; line-height:0;">#</td>
										</tr>										
												<tr>
											<td bgcolor="#778899">
												<table width="100%" border="0" cellspacing="0" cellpadding="10">
													<tr>
														<td style="color:#fff; font-size:11px; font-weight:bold; font-family:Arial; font-style:italic; " align="center">��� ���� �������� ���� � ��������! ����� �������� ����� �������� ������ ���� !</td>
													</tr>
												</table>
											</td>
										</tr><tr>
											<td bgcolor="#778899">
												<table width="100%" border="0" cellspacing="0" cellpadding="10">
													<tr>
														<td align="left"> <a href="http://vk.cc/2idIpx"  style="color:#fff; font-size:11px; font-weight:bold; text-decoration:underline; font-family:Arial; font-style:italic;"><strong><target="_blank">������ ����� ����</strong></a></td>
														<td align="right"> <a href="http://vk.cc/2idIpx"  style="color:#fff; font-size:11px; font-weight:bold; text-align:right; text-decoration:underline; font-family:Arial; font-style:italic;"><strong><target="_blank">������ ������� ����</strong></a></td>
													</tr>
												</table>
											</td>
										</tr></tr><tr>
					<td style="font-family: Arial; font-size:10px; line-height:12px; text-align:center; color:#888;">
						<br>	
					�� �������� ���� ���� <a href="http://vk.cc/2idIpx" style="color:#888888; text-decoration:underline;">�����</a> ����� ������.<br />
					Copyright � 2014  <a href="http://vk.cc/2idIpx" style="color:#888888; text-decoration:underline; " target="_blank">
				  ���������� ����</a><br /><br /></td>
				</tr>
			</table>
			
		</td>
	</tr>
</table>';

$txt  .= '<br><br>� ���������, <br>';
$txt  .= '������������� ����������� �����';

$slf = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `all` WHERE `send` != "7" LIMIT 1'));
echo '��������: '.$slf[0].' ������.';

if( isset($_GET['to']) ) {
	$sp = mysql_query('SELECT * FROM `all` WHERE `send` != "8" ORDER BY `id` DESC LIMIT 100');	
}else{
	$sp = mysql_query('SELECT * FROM `all` WHERE `send` != "8" ORDER BY `id` ASC LIMIT 100');	
}*/
/*while($pl = mysql_fetch_array($sp)) {					
	$txt22 = $txt;
	$txt22 = str_replace('{login}',$pl['login'],$txt22);
	$txt22 = str_replace('{mail}',$pl['email'],$txt22);	
	send_mail($pl['email'],'','noreaply@combats1.com','���������� ���� - �������������',$title,$txt22);
	mysql_query('UPDATE `all` SET `send` = 8 WHERE `id` = "'.$pl['id'].'" LIMIT 1');
	unset($txt22);
}*/
	/*$txt22 = $txt;
	$txt22 = str_replace('{login}','test',$txt22);
	$txt22 = str_replace('{mail}','test',$txt22);	
	send_mail('dits@qip.ru','','support@combats1.com','test','test2','test3');*/
	//mysql_query('UPDATE `all` SET `send` = 8 WHERE `id` = "'.$pl['id'].'" LIMIT 1');
	/*unset($txt22);*/
/*if( isset($GET['to'])) {
	echo '<script>function test(){top.location="/mnow.php?to=1";} setTimeout("test()",250);</script>';
}else{
	echo '<script>function test(){top.location="/mnow.php";} setTimeout("test()",250);</script>';
}*/
?>