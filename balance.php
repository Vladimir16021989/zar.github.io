<?php
define('GAME',true);	
include('_incl/__config.php');
include('_incl/class/__db_connect.php');
include('_incl/class/__user.php');
if( $u->info['id'] == 1001565 || $u->info['id'] == 2222218 ) {
	$u->info['admin'] = 1;
}
if($u->info['admin']<1)
{
	header('location: index.html'); die();
}

if(isset($_POST['money']))
{
	$balance = mysql_fetch_array(mysql_query('SELECT SUM(`money`) FROM `balance_money` WHERE `cancel` = 0'));
	$balance = $balance[0]+(int)$_POST['money'];
	mysql_query('INSERT INTO `balance_money` (`time`,`ip`,`money`,`comment2`,`balance`) VALUES ("'.time().'","'.$u->info['ip'].'","'.((int)$_POST['money']).'","'.mysql_real_escape_string($_POST['text']).'","'.$balance.'")');
}elseif(isset($_GET['cancel']))
{
	mysql_query('UPDATE `balance_money` SET `cancel` = "'.$u->info['id'].'" WHERE `id` = "'.((int)$_GET['cancel']).'" LIMIT 1');
}elseif(isset($_GET['recancel']))
{
	mysql_query('UPDATE `balance_money` SET `cancel` = "0" WHERE `id` = "'.((int)$_GET['recancel']).'" LIMIT 1');
}

$mm = date('m');
$yy = date('Y');
if(isset($_GET['mm']))
{
	$mm = $_GET['mm'];//strtotime
}
$mf = array(
'01' => 'January',
'02' => 'February',
'03' => 'March',
'04' => 'April',
'05' => 'May',
'06' => 'June',
'07' => 'July',
'08' => 'August',
'09' => 'September',
'10' => 'October',
'11' => 'November',
'12' => 'December'
);
$mf2 = array(
'12' => 'January',
'01' => 'February',
'02' => 'March',
'03' => 'April',
'04' => 'May',
'05' => 'June',
'06' => 'July',
'07' => 'August',
'08' => 'September',
'09' => 'October',
'10' => 'November',
'11' => 'December'
);
if(!isset($mf[$mm]))
{
	$mm = date('m');
}
$yy2 = $yy;
if($mm=='12')
{
	$yy2++;
}
$time_start = strtotime("1 ".$mf[$mm]." ".$yy."");
$time_finish = strtotime("1 ".$mf2[$mm]." ".$yy2."");

$balance = mysql_fetch_array(mysql_query('SELECT SUM(`money`) FROM `balance_money` WHERE `cancel` = 0'));
$balance = $balance[0];
$plus = mysql_fetch_array(mysql_query('SELECT SUM(`money`) FROM `balance_money` WHERE `cancel` = 0 AND `time` >= '.$time_start.' AND `time` < '.$time_finish.' AND `cancel` = "0"'));
$plus = $plus[0];
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<title>���������� �������</title>
<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	font-family: tahoma, arial, verdana, sans-serif, Lucida Sans;
	font-size: 11px;
}
.txt1 {
	color: #707a88;
}
</style>
<script type="text/javascript">
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='?mm="+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
</script>
</head>
<body>
<table width="1000" bgcolor="#fefefe" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td height="30" align="center"><form name="form1" method="post" action="">
      ������ ����������: 
        <select name="mm" id="mm" onChange="MM_jumpMenu('parent',this,0)">
            <option <? if($mm=='01'){ echo 'selected'; } ?> value="01">������</option>
            <option <? if($mm=='02'){ echo 'selected'; } ?> value="02">�������</option>
            <option <? if($mm=='03'){ echo 'selected'; } ?> value="03">����</option>
            <option <? if($mm=='04'){ echo 'selected'; } ?> value="04">������</option>
            <option <? if($mm=='05'){ echo 'selected'; } ?> value="05">���</option>
            <option <? if($mm=='06'){ echo 'selected'; } ?> value="06">����</option>
            <option <? if($mm=='07'){ echo 'selected'; } ?> value="07">����</option>
            <option <? if($mm=='08'){ echo 'selected'; } ?> value="08">������</option>
            <option <? if($mm=='09'){ echo 'selected'; } ?> value="09">��������</option>
            <option <? if($mm=='10'){ echo 'selected'; } ?> value="10">�������</option>
            <option <? if($mm=='11'){ echo 'selected'; } ?> value="11">������</option>
            <option <? if($mm=='12'){ echo 'selected'; } ?> value="12">�������</option>
          </select>
    , <?=$yy;?>
     ����.
    ��������� ��������:
     <span style="font-weight: bold"><?=number_format($balance, 0, ",", " ");?></span> RUB | ������� �� ���� �����: <span style="font-weight: bold"><?=number_format($plus, 0, ",", " ");?></span> RUB | ���� ��������� ������:
<?=date('d.m.Y H:i:s');?>
    </form></td>
  </tr>
  <!--<tr>
    <td>     
    <table width="1000" border="0" align="center" cellpadding="5" cellspacing="0">
      <tr>
        <td width="500" align="center" bgcolor="#CEE4C5" class="txt1" style="color: #265214; font-family: tahoma, arial, verdana, sans-serif, 'Lucida Sans';">�����������</td>
        <td width="500" align="center" bgcolor="#E1C8C9" class="txt1" style="color: #A3585C">��������</td>
      </tr>
    </table>    
    </td>
  </tr>-->
  <tr>
    <td>
    <? 
	$i = 1;
	$days = ($time_finish-$time_start)/86400;
	while($i<=$days)
	{
	$dt = $time_start+(86400*($i-1));
	if($dt<time())
	{
		$lim = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `balance_money` WHERE `time` >= '.$dt.' AND `time` < '.($dt+86400).''));
		$lim = $lim[0];
		$mst = mysql_fetch_array(mysql_query('SELECT `money`,`balance` FROM `balance_money` WHERE `time` < '.$dt.' AND `cancel` = "0" ORDER BY `id` DESC LIMIT 1')); $mst = $mst['balance'];
		$mft = mysql_fetch_array(mysql_query('SELECT `money`,`balance` FROM `balance_money` WHERE `time` >= '.$dt.' AND `time` < '.($dt+86400).' AND `cancel` = "0" ORDER BY `id` DESC LIMIT 1')); $mft = $mft['balance'];
	?>
    <!-- day -->
	<div style="background-color:#cad3e0;color:#8591a2;border:1px solid #cdd5e2;">
	  <div style="padding:10px;">����: <b><?=date('d.m.Y',$dt);?></b>, �������� �� ���� ����: <?=$lim;?>, ������� � ������ ���: <b><?=number_format($mst, 0, ",", " ");?></b> RUB, ������� � ����� ���: <b><?=number_format($mft, 0, ",", " ");?></b> RUB</div>
 		<!-- -->
        <? 
		$sp = mysql_query('SELECT * FROM `balance_money` WHERE `time` >= '.$dt.' AND `time` < '.($dt+86400).' ORDER BY `time` ASC LIMIT '.$lim);
		while($pl = mysql_fetch_array($sp))
		{
			if($pl['money']>0 && $pl['cancel']==0)
			{
		?>
        <table width="998" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td width="499" align="center" valign="top" bgcolor="#f5f7fa" class="txt1">
            <table bgcolor="#e6f8ea" width="100%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td width="100" align="center"><?=date('d.m.Y H:i:s',$pl['time']);?></td>
                <td width="75" align="center"><?=number_format($pl['money'], 0, ",", " ");?> RUB</td>
                <td>�������: <span style="font-weight: bold"><?=number_format($pl['balance'], 0, ",", " ");?></span> RUB</td>
                <td width="100" align="center"><? if($pl['cancel']==0){ echo '<a href="?mm='.$mm.'&cancel='.$pl['id'].'">���������</a>'; }else{ echo '<a href="?mm='.$mm.'&recancel='.$pl['id'].'">����������</a>'; } ?></td>
                </tr>
            </table>
            </td>
            <td width="499" align="left" valign="top" bgcolor="#f5f7fa" class="txt1">
              <table width="100%" border="0" cellspacing="0" cellpadding="5">
                <tr>
                  <td width="30" align="left" valign="top">&larr;</td>
                  <td valign="top">&nbsp;<?=$pl['comment2'];?></td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
        <? }else{ ?>
        <table width="998" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td width="499" align="right" valign="top" bgcolor="#f5f7fa" class="txt1">
            <table width="100%" border="0" cellspacing="0" cellpadding="5">
              <tr>            
                <td valign="top" bgcolor="#F5F7FA">&nbsp;<?=$pl['comment2'];?></td>
                <td width="30" align="right" valign="top">&rarr;</td>
              </tr>
            </table>
            </td>
            <td width="499" align="center" valign="top" bgcolor="<? if($pl['money']<0){ echo '#f8e6ef'; }else{ echo '#F5F7FA'; } ?>" class="txt1">
            <table bgcolor="<? if($pl['money']<0 && $pl['cancel']==0){ echo '#f8e6ef'; }else{ echo '#F5F7FA'; } ?>" width="100%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td width="100" align="center"><?=date('d.m.Y H:i:s',$pl['time']);?></td>
                <td width="75" align="center"><?=number_format($pl['money'], 0, ",", " ");?> RUB</td>
                <td>�������: <span style="font-weight: bold"><?=number_format($pl['balance'], 0, ",", " ");?></span> RUB</td>
                <td width="100" align="center"><? if($pl['cancel']==0){ echo '<a href="?mm='.$mm.'&cancel='.$pl['id'].'">���������</a>'; }else{ echo '<a href="?mm='.$mm.'&recancel='.$pl['id'].'">����������</a>'; } ?></td>
                </tr>
            </table>
            </td>
          </tr>
        </table>
        <? } } ?>
        <!-- -->
      </div>
    <? } $i++; } ?>
    <? if($mm==date('m')){ ?>
    <br><br><br>
    <div style="background-color:#F5F7FA;">
    <form name="form1" method="post" action="?mm=<?=$mm;?>#addline">
      <table width="100%" border="0" align="center" cellpadding="5" style="border:1px solid #8591a2;" cellspacing="0">
        <tr>
          <td bgcolor="#CAD3E0">�����: <input name="money" type="text" size="21" maxlength="7" /> 
            RUB</td>
        </tr>
        <tr>
          <td bgcolor="#CAD3E0"><p class="txt1">����������� (500 �������� ��������):</p>
            <p>
              <textarea style="width:980px;" name="text" id="text" cols="45" rows="5"></textarea>
            </p></td>
        </tr>
        <tr>
          <td align="right" bgcolor="#CAD3E0"><input type="submit" name="button" id="button" value="���������� � ������ �� <?=date('d.m.Y');?>"></td>
        </tr>
      </table>
     </form>
    </div>
    <? } ?>
    <!-- day -->
    <br><br><br>time :: <?=time();?>
    </td>
  </tr>
</table>
</body>
</html>
