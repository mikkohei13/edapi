
<?php

/*
Äänestyskone
Mikko Heikkinen 8-9/2010

Vertaa käyttäjän äänestysvalintoja kansanedustajien valintoihin ja näyttää edustajat osuvuusjärjestyksessä.
*/

// How to Get the Current Page URL
// http://www.webcheatsheet.com/PHP/get_current_page_url.php
function curPageURL() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Äänestyskone; tulokset</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="http://www.biomi.org/eduskunta/simple_biomi.css" rel="stylesheet" type="text/css" title="normal" media="screen" />
<meta name="title" content="Äänestyskone; tulokset" />
<meta name="description" content="Ketkä kansanedustajat ovat äänestäneet mielipiteesi mukaan?" />
<link rel="image_src" href="http://www.biomi.org/tools/aanestys/eduskunta.jpg" / >
<style type="text/css">
#tulostaulu {
	border-collapse: collapse;
	float: left;
	margin: 0 2em 2em 0;
}

#tulostaulu th {
	border: 1px solid #B8CF8C;
	padding: 4px;
	background-color: #B8CF8C;
	text-align: left;
}

#tulostaulu th[title^="Kertoo"] {

}

#tulostaulu th span {
	border-bottom: 1px dashed #333;
}

#tulostaulu th.pistemaararyhma {
	background-color: #eee;
}

#tulostaulu td {
	border: 1px solid #ccc;
	padding: 4px;
}
#footer {
clear: both;
}
#share {
width: 250px;
height: 400px;
float: right;
}
#share div {
background-color: #D1E8F6;
border: 1px solid #D1E8F6;
border-left: 5px solid #D1E8F6;
border-right: 5px solid #D1E8F6;
}
#share iframe {
width: 250px;
height: 350px;
border: 0;
margin: 0;
}
#startover {
background-color: #D1E8F6;
padding: 5px;
}
</style>
<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
</script>
<script type="text/javascript">
_uacct = "UA-112739-1";
urchinTracker();
</script>
</head>

<body>
<div id="content">

<p id="bread"><a href="http://www.biomi.org/">biomi.org</a> &gt; <a href="http://www.biomi.org/eduskunta/eduskunta.html">Eduskunta</a> &gt; <a href="./">Äänestyskone</a> &gt; Tulokset</p>

<h1>Tulokset</h1>

<?php
require_once "../../../username.php";
include_once "include_puoluekanta.php";

// -----------------------------------------------------------------------------------------------
// ESIVALMISTELUT

$selects = $_GET['selects'];

/*
Tietoturvatarkistukset tehdään jäljempänä, ennen inputin käyttämistä tietokantahauissa
Oletettu input selects%5Ba10_77-2010%5D=Ei&selects%5Ba7_77-2010%5D=Ei&selects%5Ba9_77-2010%5D=ei+merkityst%E4&selects%5Ba1_53-2009%5D=Jaa&selects%5Ba3_15-2010%5D=Jaa&selects%5Ba1_55-2010%5D=Ei&selects%5Ba87_74-2010%5D=Jaa&selects%5Ba1_18-2009%5D=Ei&selects%5Ba1_66-2008%5D=Jaa&selects%5Ba1_100-2005%5D=Ei&selects%5Ba1_105-2001%5D=Jaa
jonka tuloksena Array tyyliä:
Array
(
    [a10_77-2010] => Ei
    [a9_77-2010] => ei merkitystä
    [a1_53-2009] => Jaa
)
*/


// Tutkitaan onko käyttäjä tehnyt lomakkeella valintoja (=valinnut ainakin yhden muun valinnan kuin "ei merkitystä")
if (in_array("Ei", $selects) === FALSE && in_array("Jaa", $selects) === FALSE)
{
	echo "<p>Et tehnyt yhtään Jaa/Ei -valintaa. Ole hyvä ja palaa takaisin.</p>";
	echo "</body></html>";
	exit;
}

$count = count($selects);

// Kytkeydytään tietokantaan
$conn = mysql_connect('mysql7.nebula.fi', $username, $password);
//mysql_set_charset("latin1", $conn); // ei toimi PHP 5.1:lla

if (!$conn) {
    die('Tietokantayhteys epäonnistui: ' . mysql_error());
}

// -----------------------------------------------------------------------------------------------
// TIETOKANTAHAUT JA TULOSTEN KÄSITTELY

// Käydään läpi äänestykset yksi kerrallaan
foreach ($selects as $aanestystunniste => $toivottuvalinta)
{
	if ($toivottuvalinta == "Jaa" || $toivottuvalinta == "Ei")
	{
		if ($toivottuvalinta == "Jaa")
		{
			$ei_toivottuvalinta = "Ei";
		}
		else
		{
			$ei_toivottuvalinta = "Jaa";
		}
		
		// Tietoturva
		// $select:in value:ta ei tarvitse tarkistaa, sillä sitä käytetään vain vertailussa.
		if (!preg_match("/^[a-zA-Z0-9_-]+$/", $aanestystunniste))
		{
			echo "Virhe (invalid character)";
			exit;
		}
		
		$sql = "SELECT edustaja, valinta FROM biomiorg.edapi_aanestykset WHERE aanestystunniste = '" . $aanestystunniste . "';";
		
		$result = mysql_query($sql, $conn);
		
		// Käydään läpi edustajat yksi kerrallaan
		while ($edustaja = mysql_fetch_assoc($result))
		{
				/* ---------------------------------
				Tiedot tulevat tietokannasta muodossa
				Array
				(
					[edustaja] => Ahde Matti
					[valinta] => Ei
				)
				--------------------------------- */
			
			// Edustaja äänesti "oikein"
			if ($edustaja['valinta'] == $toivottuvalinta)
			{
	//			echo "\n" . $edustaja['edustaja'] . " +1"; // debug
				$tulos[$edustaja['edustaja']]['pisteet'] = $tulos[$edustaja['edustaja']]['pisteet'] + 1; // lisätään 1
			}
			// Edustaja äänesti "väärin"
			elseif ($edustaja['valinta'] == $ei_toivottuvalinta)
			{
	//			echo "\n" . $edustaja['edustaja'] . " -1"; // debug
				$tulos[$edustaja['edustaja']]['pisteet'] = $tulos[$edustaja['edustaja']]['pisteet'] - 1; // vähennetään 1
			}
			// Edustaja äänesti Tyhjää
			elseif ($edustaja['valinta'] == "Tyhjää")
			{
	//			echo "\n" . $edustaja['edustaja'] . " äänesti tyhjää"; // debug
	//			echo "\n" . $edustaja['edustaja'] . " -0,5"; // debug
				$tulos[$edustaja['edustaja']]['pisteet'] = $tulos[$edustaja['edustaja']]['pisteet'] - 0.5;
				$tulos[$edustaja['edustaja']]['tyhjaa'] = $tulos[$edustaja['edustaja']]['tyhjaa'] + 1;
			}
			// Edustaja oli poissa
			elseif ($edustaja['valinta'] == "Poissa")
			{
	//			echo "\n" . $edustaja['edustaja'] . " oli poissa"; // debug
				$tulos[$edustaja['edustaja']]['pisteet'] = $tulos[$edustaja['edustaja']]['pisteet'] + 0;
				$tulos[$edustaja['edustaja']]['poissa'] = $tulos[$edustaja['edustaja']]['poissa'] + 1;
			}
			
			// Laskee summan kuinka monen äänestyksen aikana ko. henkilö oli kansanedustajana
			$tulos[$edustaja['edustaja']]['edustajana'] = $tulos[$edustaja['edustaja']]['edustajana'] + 1;
			
	//		echo $edustaja['edustaja'] . " mukana äänestyksessä, valitsi " . $edustaja['valinta'] . " (count so far " . $tulos[$edustaja['edustaja']]['edustajana'] . ")\n"; // debug
		}
		
		// Tyhjennetään muuttujat seuraavaa äänestystä varten
		unset($where);
		unset($query_edustajat);
		unset($aanestys);
		unset($aanestystunniste);
		unset($toivottuvalinta);
		unset($ei_toivottuvalinta);
	}
}

	/* ---------------------------------
	Tuloksena $tulos-taulukko muotoa
	Array
	(
		[Ahde Matti] => Array
			(
				[pisteet] => -1
				[edustajana] => 3
			)

		[Matikainen-Kallström Marjo] => Array
			(
				[pisteet] => 1
				[edustajana] => 3
			)
	...
	    [Kasvi Jyrki] => Array
        (
            [pisteet] => 0.5
            [edustajana] => 3
            [tyhjaa] => 1
            [poissa] => 1
        )
	...
	--------------------------------- */

// Kopioidaan pisteet toiseen tauluun sorttausta varten
foreach ($tulos as $k => $v) {
    $pisteet[$k] = $v['pisteet']; 
}
//array_multisort($pisteet, SORT_DESC, $tulos); // mitä tämä tekee??
arsort($pisteet);

	/* ---------------------------------
	Tuloksena $pisteet-taulukko muotoa
	Array
	(
		[Salolainen Pertti] => 3
		[Pulliainen Erkki] => 3
		[Pekkarinen Mauri] => 2
		...	
	--------------------------------- */

// TODO: tarkista ovatko tulokset oikein
// print_r ($pisteet);

// -----------------------------------------------------------------------------------------------
// HTML:n TULOSTUS

// Metadata
echo "\n\n<p>Tehdyt äänestysvalinnat: ";
$html = "";
foreach ($selects as $k1 => $v1)
{
	$html .= "<a href=\"http://www.biomi.org/tools/eduskunta/aanestys/$k1\">$k1</a> ($v1), ";
}
$html = trim($html, ", ");
echo $html;
echo "</p>\n";


if (strpos($_SERVER['HTTP_REFERER'], "www.biomi.org/tools/aanestys/"))
{
	// Käyttäjä tullut valintalomakkeelta
	echo "<p>Jos haluat muuttaa valintoja, palaa takaisin selaimen back/takaisin -napilla. <strong id=\"startover\"><a href=\"./\">Tai nollaa valinnat ja aloita alusta</a></strong>.</p>\n";
}
else
{
	// Käyttäjä tullut ulkopuolelta suoraan tuloksiin
	echo "<p><strong id=\"startover\"><a href=\"./\">Nollaa valinnat ja aloita alusta</a></strong></p>\n";
}


// Tulostaulukko
echo "<table id=\"tulostaulu\">\n";

// Otsikkorivi
echo "<tr id=\"otsikko\">\n
	<th>Edustaja</th>\n
	<th>Pisteet</th>\n
	<th title=\"Kertoo kuinka monta kertaa ko. henkilö on ollut kansanedustaja-aikanaan poissa äänestyksestä tai äänestänyt tyhjää\"><span>Poissaolot ja tyhjät</span></th>\n
	<th title=\"Kertoo kuinka monen äänestyksen aikana ko. henkilö on ollut kansanedustajana\"><span>Edustuskerrat</span></th>\n
</tr>\n";

$pistemaararyhma = "";

// Käydään edustajat läpi yksi kerrallaan
foreach($pisteet as $nimi => $pistemaara)
{
	$teksti = "";
	$mukanateksti = "";

	/*
	if (isset($tiedot['poissa']))
		$teksti = $teksti . "poissa " . $tiedot['poissa'] . " kertaa,";
		
	if (isset($tiedot['tyhjaa']))
		$teksti = $teksti . "äänesti tyhjää " . $tiedot['tyhjaa'] . " kertaa";
		
	if ($tiedot['edustajana'] < $count)
		$mukanateksti = "kansanedustajana " . $tiedot['edustajana'] . " äänestyksessä";
		
	$teksti = trim($teksti, ",");
	$teksti = str_replace(",", ",<br />", $teksti);
	*/

	// Kootaan lisätietoteksti
	if (isset($tulos[$nimi]['poissa']))
	{
		if ($tulos[$nimi]['poissa'] > 1)
			$teksti .= "poissa " . $tulos[$nimi]['poissa'] . " kertaa, ";
		else
			$teksti .= "poissa " . $tulos[$nimi]['poissa'] . " kerran, ";
	}
	if (isset($tulos[$nimi]['tyhjaa']))
	{
		if ($tulos[$nimi]['tyhjaa'] > 1)
			$teksti .= "äänesti tyhjää " . $tulos[$nimi]['tyhjaa'] . " kertaa";
		else
			$teksti .= "äänesti tyhjää " . $tulos[$nimi]['tyhjaa'] . " kerran";
	}
	$teksti = trim($teksti, ", ");
	
	// Ryhmittely pistemäärittäin
	if ($pistemaararyhma !== $pistemaara)
	{
		$pistemaararyhma = $pistemaara;
		echo "<tr><th colspan=\"4\" class=\"pistemaararyhma\">$pistemaara pistettä</th></tr>\n";
	}

	// Muutetaan pistemäärän desimaalipiste pilkuksi
	$pistemaara_pilkku = str_replace(".", ",", $pistemaara);
	
	// Tulostetaan tiedot
	echo "<tr>\n";
	echo "	<td><a href=\"http://www.biomi.org/tools/eduskunta/edustaja/$nimi\">$nimi (" . $puoluekanta[$nimi] . ")</a></td>\n";
	echo "	<td><strong class=\"pisteet\">$pistemaara_pilkku</strong></td>\n";
	echo "	<td>$teksti &nbsp;</td>\n";
	echo "	<td>" . $tulos[$nimi]['edustajana'] . "</td>\n";

	echo "</tr>\n";
	
}

echo "</table>";


// Lyhytlinkki, Facebook yms.
$this_url = curPageURL();
$this_url = urlencode($this_url);
$iframe_url ="shortlink_start.php?url=$this_url";

echo "<div id=\"share\">\n";
?>
<div>	
<p><a name="fb_share" type="button_count" href="http://www.facebook.com/sharer.php">Share</a><script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script> Jaa tulokset Facebookiin</p>
</div>
<?php
echo "<iframe src=\"$iframe_url\" frameborder=\"0\" border=\"0\">\n
  <p><a href=\"$iframe_url\">Tee lyhytlinkki näihin tuloksiin</a>.</p>\n
</iframe>\n";
echo "</div>\n";

// Suljetaan tietokantayhteys
mysql_close($conn);

?>
	
<p class="clear: both;">&nbsp;</p></div><!-- content ends -->

<div id="footer">
<p><a href="http://www.biomi.org/mikko/">Mikko Heikkinen</a> &#8226; <a href="http://www.biomi.org/">http://www.biomi.org</a> &#8226; <a href="/sekalaista/palaute.html">palaute</a>  </p>
</div>

</body>

</html>
