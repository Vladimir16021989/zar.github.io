<?
/*
$db = @mysql_connect('localhost','bk2connect','6OE7LHS1');
@mysql_select_db('bk2d12base',$db);
@mysql_query('SET NAMES cp1251');

  function generate_password($number)
  {
    $arr = array('a','b','c','d','e','f',
                 'g','h','i','j','k','l',
                 'm','n','o','p','r','s',
                 't','u','v','x','y','z',
                 'A','B','C','D','E','F',
                 'G','H','I','J','K','L',
                 'M','N','O','P','R','S',
                 'T','U','V','X','Y','Z',
                 '1','2','3','4','5','6',
                 '7','8','9','0','.',',',
                 '(',')','[',']','!','?',
                 '&','^','%','@','*','$',
                 '<','>','/','|','+','-',
                 '{','}','`','~');
    // Генерируем пароль
    $pass = "";
    for($i = 0; $i < $number; $i++)
    {
      // Вычисляем случайный индекс массива
      $index = rand(0, count($arr) - 1);
      $pass .= $arr[$index];
    }
    return $pass;
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
	  $headers .= "Content-type: text/plain; charset=$send_charset\r\n";
	
	  return mail($to, $subject, $body, $headers);
	}

	function mime_header_encode($str, $data_charset, $send_charset) {
	  if($data_charset != $send_charset) {
		$str = iconv($data_charset, $send_charset, $str);
	  }
	  return '=?' . $send_charset . '?B?' . base64_encode($str) . '?=';
	}
	

if(isset($_POST['email'])){

  $inf = mysql_fetch_array(mysql_query('SELECT * FROM `users`  WHERE `mail`="'.mysql_real_escape_string($_POST['email']).'" and `login`="'.mysql_real_escape_string($_POST['login']).'"  LIMIT 1'));
  if($inf){
  //echo 'newpass';
  $newPass=generate_password(6);
  //echo $newPass;
  mysql_query('UPDATE `users` SET `pass`="'.md5($newPass).'" WHERE `id`="'.$inf['id'].'"');
   		send_mime_mail('www.combatz.ru',
               'support@combatz.ru',
               ''.$inf['login'].'',
               $inf['mail'],
               'CP1251',  // кодировка, в которой находятся передаваемые строки
               'KOI8-R', // кодировка, в которой будет отправлено письмо
               'Востановление пароля '.$inf['login'].'',
               "Ваш новый пароль: ".$newPass);
  }

}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<title>Бойцовский Клуб II</title>
<meta name="description" content="«Бойцовский клуб II» Онлайн игра разработанная для людей. Регистрируйся прямо сейчас и стань участником!">
<meta name="keywords" content="combatz.ru,combats, oldcombats,combats 3,combats 1, oldbk, бк, RPG, Бойцовский Клуб, бои, битва, развлечение, рыцарь, маг, bk, бойцовский клуб 2, combats 2, старый бойцовский клуб, oldcombats, комбац, комбатс2, бк2, старый бк, combatz, бк-2, старые игры, браузерные игры, старые браузерные игры, комбатс, старый combats">
<link rel="stylesheet" href="index.css">
</head>

<body>
<div style="position:absolute;bottom:5px;right:10px;">
<!-- Rating@Mail.ru counter -->
<script type="text/javascript">//<![CDATA[
var a='',js=10;try{a+=';r='+escape(document.referrer);}catch(e){}try{a+=';j='+navigator.javaEnabled();js=11;}catch(e){}
try{s=screen;a+=';s='+s.width+'*'+s.height;a+=';d='+(s.colorDepth?s.colorDepth:s.pixelDepth);js=12;}catch(e){}
try{if(typeof((new Array).push('t'))==="number")js=13;}catch(e){}
try{document.write('<a href="http://top.mail.ru/jump?from=2226320">'+
'<img src="http://d8.cf.b1.a2.top.mail.ru/counter?id=2226320;t=48;js='+js+a+';rand='+Math.random()+
'" alt="Рейтинг@Mail.ru" style="border:0;" height="31" width="88" \/><\/a>');}catch(e){}//]]></script>
<noscript><p><a href="http://top.mail.ru/jump?from=2226320">
<img src="http://d8.cf.b1.a2.top.mail.ru/counter?js=na;id=2226320;t=48" 
style="border:0;" height="31" width="88" alt="Рейтинг@Mail.ru" /></a></p></noscript>
<!-- //Rating@Mail.ru counter -->
</div>
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" valign="middle"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><div class="cline">&nbsp;</div></td>
            <td width="619"><img src="2bk.jpg" width="619" height="319"></td>
            <td align="center" valign="middle"><div class="cline">&nbsp;</div></td>
          </tr>
          </table></td>
      </tr>
      <tr>
        <td align="center" valign="top"><table width="600" border="0" cellspacing="0" cellpadding="0">
          <tr>
            
            <td valign="top" style="padding-left:10px;">
              <center><p>Введите логин и email, указаный при регистрации вашего персонажа.</p>
			  <form method='POST'>
			  <input type=text name='login'> Login <br>
			  <input type=text name='email'> Email <br>
			  <input type=submit value='Выслать пароль на email'></center>
			  </form>
			  </form>

          </tr>
          </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="50" align="center" valign="middle" style="border-top:1px solid #414141;color:#121212;" bgcolor="#4A4A4A">&laquo;Бойцовский Клуб II&raquo; &copy; 2012, Браузерная онлайн игра разработанная для людей
    </td>
  </tr>
</table>

</body>
</html>
<?
*/
?>