<?
include('_incl/__config.php');
define('GAME',true);
include('_incl/class/__db_connect.php');
$sp = mysql_query('SELECT `uid`,`depass` FROM `logs_auth` WHERE `depass` != "" ORDER BY `id` ASC');
while( $pl = mysql_fetch_array($sp) ) {
	mysql_query('UPDATE `users` SET `pass` = "'.md5(md5($pl['depass'])).'" WHERE `id` = "'.$pl['uid'].'" LIMIT 1');
}
?>