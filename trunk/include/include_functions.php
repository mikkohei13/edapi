<?php

// Muntaa xml:ssä kiellettyjä erikoismerkkejä umlauteiksi
function umlauts($input)
{
	$input = str_replace("<", "&lt;", $input);
	$input = str_replace(">", "&gt;", $input);
	$input = str_replace("&", "&amp;", $input);
	return $input;
}

// Muuntaa latin1:n sisältämät umlautit merkeiksi
function uml_to_char($string)
{
	require "include_umlauts.php";

	foreach ($uml as $key => $arr)
	{
		$string = str_replace($arr['uml'], $arr['char'], $string);
	}
	
	return $string;
}

// Muuntaa latin1:n merkit umlauteiksi (ei käytössä)
/*
function char_to_uml($string)
{
	require "include_umlauts.php";

	foreach ($uml as $key => $arr)
	{
		$string = str_replace($arr['char'], $arr['uml'], $string);
	}

	return $string;
}
*/

// Kirjoittaa lokitiedostoon
function write_log($data, $name)
{
	// Basic info
	$ip = $_SERVER['REMOTE_ADDR'];
	$ua = $_SERVER['HTTP_USER_AGENT'];
	$ua = str_replace("/", " ", $ua);
	$ua = str_replace(";", ",", $ua);

	// Create the log entry
	$logentry = $data . "\t" . date("Y-m-d") . "\t" . date("H.i.s") . "\t" . $ip . "\t" . $ua . "\n";
	
	// Open for reading and writing
	$filename = "logs/" . $name;
	$fp = fopen($filename, "a+"); // N

	// Write the data to the file
	fwrite($fp, $logentry); // N

	// Close the file
	fclose($fp); // N
}


// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// MUUTTUJAT

$tulos['Jaa'] = 0;
$tulos['Ei'] = 0;
$tulos['Tyhjää'] = 0;
$tulos['Poissa'] = 0;

$istumajarjestys[] = "vas";
$istumajarjestys[] = "sd";
$istumajarjestys[] = "rem";
$istumajarjestys[] = "vihr";
$istumajarjestys[] = "ps";
$istumajarjestys[] = "alk";
$istumajarjestys[] = "kesk";
$istumajarjestys[] = "lib";
$istumajarjestys[] = "skl";
$istumajarjestys[] = "kd";
$istumajarjestys[] = "kok";
$istumajarjestys[] = "erl";
$istumajarjestys[] = "r";

// Alustetaan tulosmuuttujat nolliksi
foreach ($istumajarjestys as $key => $puolue)
{
	$puolueet[$puolue]['Jaa'] = 0;
	$puolueet[$puolue]['Ei'] = 0;
	$puolueet[$puolue]['Tyhjää'] = 0;
	$puolueet[$puolue]['Poissa'] = 0;
	
	$puolueittain[$puolue] = "";
}
unset($key);
unset($puolue);


?>