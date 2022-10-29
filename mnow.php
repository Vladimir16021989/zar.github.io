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

			function send_mime_mail($name_from, // имя отправителя
							   $email_from, // email отправителя
							   $name_to, // имя получателя
							   $email_to, // email получателя
							   $data_charset, // кодировка переданных данных
							   $send_charset, // кодировка письма
							   $subject, // тема письма
							   $body // текст письма
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
			
			function send_mail($to,$to_name,$from = 'support@combats1.com',$name = '<b>Бойцовский Клуб</b> 2',$title,$text) {
				send_mime_mail($name,
					   $from,
					   $to_name,
					   $to,
					   'CP1251',  // кодировка, в которой находятся передаваемые строки
					   'KOI8-R', // кодировка, в которой будет отправлено письмо
					   $title,
					   $text); // \r\n
			}

$title = 'Бойцовского клуба - Администрация';
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
									<h3>Уважаемый(ая) {login}!</h3><br>
									<table width="100%" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td style="font-family: Arial; font-size:10px; line-height:12px; text-align:center; color:#888;">
												<br />
												Возвращайтесь в Бойцовский Клуб и двигайтесь к славе и могуществу! В этом письме мы сообщаем Вам что наш мир изменил внешность и название, в двух словах, что нового появится в нашем мире с выходом последнего обновления.<br /><br />
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
														Здравствуйте Уважаемый поклонник мира Бойцовского Клуба! 
														Рады сообщить, Вы стали одним из тех кто получил данное письмо и бонусы!
														<ul>													
														При регистрации Вас ожидает:
														<ul>
														<br><li>Абсолютно работоспособный сервер новейшего поколения без задержек и перебоев игры.</li>
														<br><li>Новая боевая система с учетом формул ББК2.</li>
														<br><li>Эпоха первого поколения ведения боя в &laquo;Башне смерти&raquo;, а так же старые добрые вещи с нами знакомого БК первого поколения.</li>
														<br><li>Креативная разработка геймплея проекта.</li>
														<br><li>Сдача предметов в Государственный магазин и Березку - 100%.</li>
														<br><li>Каждый день на проекте проводятся интересные эвенты.</li>
														<br><li>Уникальный подход к реконструкции всех пещер нашего мира : &laquo;Пещера тысячи проклятий&raquo;, &laquo;Бездна&raquo;, &laquo;Катакомбы&raquo;, &laquo;Подземелье Драконов&raquo;, а так же &laquo;Канализация&raquo;.</li>
														<br><li>500 рабочих квестов различных направлений: &laquo;Собиралки&raquo;, &laquo;Выбивалки&raquo;, &laquo;Убивалки&raquo;.</li>
														<br><li>Уникальный турнир &laquo;Арена Богов&raquo;. Турнир проводится каждый день в 21:00 по Мск, в турнире могут принять участия любые уровни кроме нулевого, для участия в турнире необходима любая из склонностей в начале турнира каждый участник заходит за свою склонность в которой проводиться трех-стороний поединок Свет - Тьма - Нейтралитет, в котором можно выиграть &laquo;+500% опыта&raquo;, а так же ресурсы которые можно будет обменять на ценные предметы.</li>
														<br><li>Специально для любителей "Казино" и азартных игр, в нашем проекте присутствует &laquo;&laquo;&laquo;Рулетка&raquo;&raquo;&raquo;, &laquo;&laquo;&laquo;БлэкДжек&raquo;&raquo;&raquo;, &laquo;&laquo;&laquo;Бильярд&raquo;&raquo;&raquo;.</li>
														<br><li>Излом хаоса, в который открыты врата для всех смельчаков.</li>
													    <br><li>Проффесиональная работа нашего персонала в обслуживании и поддержке в 24/7.</li>
													    <ul>
														<BR><BR>
														Регистрируйтесь прямо сейчас и получите в подарок свиток + 290 000 опыта, все екровые заклятия эликсиров 0/1, Статус VIP игрока - "VIP" на 3 дня, так же опереди всех и возьми 12-ый уровень первым и получи 100$. Или быть может ты безстрашен и рискнешь взять первым 13-ый уровень и получить 1000$? Спеши , гонка за уровнями уже началась.
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
																				
																				<td style="color:#fff; font-size:11px; font-weight:bold; font-family:Arial; font-style:italic; " align="center">ты уже знаешь, что это за день?</td>
																				
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
														<td style="color:#fff; font-size:11px; font-weight:bold; font-family:Arial; font-style:italic; " align="center">Это день жестоких битв и сражений! Спеши раскрыть новые просторы нашего мира !</td>
													</tr>
												</table>
											</td>
										</tr><tr>
											<td bgcolor="#778899">
												<table width="100%" border="0" cellspacing="0" cellpadding="10">
													<tr>
														<td align="left"> <a href="http://vk.cc/2idIpx"  style="color:#fff; font-size:11px; font-weight:bold; text-decoration:underline; font-family:Arial; font-style:italic;"><strong><target="_blank">Читать Форум игры</strong></a></td>
														<td align="right"> <a href="http://vk.cc/2idIpx"  style="color:#fff; font-size:11px; font-weight:bold; text-align:right; text-decoration:underline; font-family:Arial; font-style:italic;"><strong><target="_blank">Читать новости игры</strong></a></td>
													</tr>
												</table>
											</td>
										</tr></tr><tr>
					<td style="font-family: Arial; font-size:10px; line-height:12px; text-align:center; color:#888;">
						<br>	
					НЕ ПРОПУСТИ СВОЙ ШАНС <a href="http://vk.cc/2idIpx" style="color:#888888; text-decoration:underline;">Начни</a> прямо сейчас.<br />
					Copyright © 2014  <a href="http://vk.cc/2idIpx" style="color:#888888; text-decoration:underline; " target="_blank">
				  Бойцовский Клуб</a><br /><br /></td>
				</tr>
			</table>
			
		</td>
	</tr>
</table>';

$txt  .= '<br><br>С уважением, <br>';
$txt  .= 'Администрация Бойцовского Клуба';

$slf = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `all` WHERE `send` != "7" LIMIT 1'));
echo 'Осталось: '.$slf[0].' ящиков.';

if( isset($_GET['to']) ) {
	$sp = mysql_query('SELECT * FROM `all` WHERE `send` != "8" ORDER BY `id` DESC LIMIT 100');	
}else{
	$sp = mysql_query('SELECT * FROM `all` WHERE `send` != "8" ORDER BY `id` ASC LIMIT 100');	
}*/
/*while($pl = mysql_fetch_array($sp)) {					
	$txt22 = $txt;
	$txt22 = str_replace('{login}',$pl['login'],$txt22);
	$txt22 = str_replace('{mail}',$pl['email'],$txt22);	
	send_mail($pl['email'],'','noreaply@combats1.com','Бойцовский клуб - Администрация',$title,$txt22);
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