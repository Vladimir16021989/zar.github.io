<?php

/* Защита от урона, расчет */
function zago($v) {
	$r = 0;
	$r = (1-( pow(0.5, ($v/250) ) ))*100;		
	return $r;
}

/* Расчет урона */
function yron($level, $level2, $type, $min_yron, $max_yron, $min_bron, $max_bron, $vladenie, $power_yron, $power_krit, $zashita) {
	
	//Параметры для возврвата
	$r = array('min' => 0, 'max' => 0, 'type' => $type);
	
	$p = array(
		'Y'		=> 0,
		'B'		=> array(0 => 0, 1 => 0, 'rnd' => false),
		'L'		=> $level,
		'W'		=> array($min_yron, $max_yron, 'rnd' => false), //минимальный урон //максимальный урон добавочный
		'U'		=> $vladenie, //владение оружием
		'M'		=> $power_yron, //можность урона
		'K'		=> $power_krit, //мощность крита
		'S'		=> 0  //влияние статов на проф.урон
		/*
			(S) - влияние наших статов на профильный урон
			Колющий: S = Сила * 0,3 + Ловкость * 0,7
			Рубящий: S = Сила * 0,5 + Ловкость * 0,25 + Интуиция * 0,25
			Дробящий: S = Сила * 1
			Режущий: S = Сила * 0,3 + Интуиция * 0,7
		*/
	);
	
	//Выставление параметров		
		$r['bron'] = array($min_bron, $max_bron); //Броня зоны куда бьем
		$r['bron']['rnd'] = rand($r['bron'][0],$r['bron'][1]);
		
		$r['za'] = $zashita; //Защита от урона
		
		//Остальные расчеты	
		$p['B'][0] = round((ceil($st1['s1']*1.4)/1.25)+2);
		$p['B'][1] = round(5+ceil(0.4+($p['B'][0]-0)/0.9)/1.25);	
		$p['B']['rnd'] = rand($p['B'][0],$p['B'][1]);
		
		$p['W']['rnd'] = rand($p['W'][0],$p['W'][1]);
		
		
		
		
		
	//Расчет типа урона			
		//колющий
			 	if($r['type'] == 1) {		$p['S'] = $st1['s1'] * 0.3 + $st['s2'] * 0.7;
		//рубящий
			}elseif($r['type'] == 2) {		$p['S'] = $st1['s1'] * 0.5 + $st['s2'] * 0.25 + $st['s3'] * 0.25;
		//дробящий
			}elseif($r['type'] == 3) {		$p['S'] = $st1['s1'] * 1;
		//режущий
			}elseif($r['type'] == 4) {		$p['S'] = $st1['s1'] * 0.3 + $st['s2'] * 0.7;	}
		
	//Обычный урон
		$r['min'] = ($p['B'][0]+$p['L']+$p['S']+$p['W'][0]*(1+0.07*$p['U']))*(1+$p['M']/100);
		$r['max'] = ($p['B'][1]+$p['L']+$p['S']+$p['W'][1]*(1+0.07*$p['U']))*(1+$p['M']/100);
	//Критический урон
		$r['Kmin'] = ($p['B'][0]+$p['L']+$p['S']+$p['W'][0]*(1+0.07*$p['U']))*(1+$p['M']/100)*2*(1+$p['K']/100);
		$r['Kmax'] = ($p['B'][1]+$p['L']+$p['S']+$p['W'][1]*(1+0.07*$p['U']))*(1+$p['M']/100)*2*(1+$p['K']/100);
		
		$r['min'] = floor($r['min']);
		$r['max'] = floor($r['max']);
		$r['Kmin'] = floor($r['Kmin']);
		$r['Kmax'] = floor($r['Kmax']);
	
	//Расчет брони
		//для обычного
		$r['min_abron'] = round($r['min']/2.3636);
		$r['max_abron'] = round($r['max']/2.3636);
		$r['min'] -= $r['bron']['rnd'];
		$r['max'] -= $r['bron']['rnd'];
		if($r['min'] < $r['min_abron']) {
			$r['min'] = $r['min_abron'];
		}
		if($r['max'] < $r['max_abron']) {
			$r['max'] = $r['max_abron'];
		}
		//для крита
		$r['Kmin_abron'] = round($r['Kmin']/3);
		$r['Kmax_abron'] = round($r['Kmax']/3);
		$r['Kmin'] -= $r['bron']['rnd'];
		$r['Kmax'] -= $r['bron']['rnd'];
		if($r['Kmin'] < $r['Kmin_abron']) {
			$r['Kmin'] = $r['Kmin_abron'];
		}
		if($r['Kmax'] < $r['Kmax_abron']) {
			$r['Kmax'] = $r['Kmax_abron'];
		}
	
	//Расчет защиты
		$r['min'] -= floor($r['min']/100*zago($r['za']));
		$r['max'] -= floor($r['max']/100*zago($r['za']));
		$r['Kmin'] -= floor($r['Kmin']/100*zago($r['za']));
		$r['Kmax'] -= floor($r['Kmax']/100*zago($r['za']));
		
	return $r;
}

		
?>