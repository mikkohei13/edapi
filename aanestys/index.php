
<?php

/*
��nestyskone; etusivu
Mikko Heikkinen 8-9/2010

��nestyskone vertaa k�ytt�j�n ��nestysvalintoja kansanedustajien valintoihin
ja n�ytt�� edustajat osuvuusj�rjestyksess�.

Varsinainen vertailu tapahtuu sivulla tulos.php. T�ll� sivulla on lomake,
joka l�hett�� tiedot sopivassa muodossa GET-muuttujina tulos.php:lle.
Tulos.php-sivua voi k�ytt�� my�s l�hett�m�ll� sille ��nestysten tiedot vastaavassa muodossa
muulla tavalla (toisella lomakkella, linkin avulla...).

*/
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>��nestyskone - biomi.org</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="http://www.biomi.org/eduskunta/simple_biomi.css" rel="stylesheet" type="text/css" title="normal" media="screen" />
<meta name="title" content="��nestyskone" />
<meta name="description" content="Ketk� kansanedustajat ovat ��nest�neet mielipiteesi mukaan?" />
<link rel="image_src" href="http://www.biomi.org/tools/aanestys/eduskunta.jpg" / >
<style type="text/css">

table
{
	border: 0;
	border-collapse:collapse;
}
table td
{
	border: 0;
	margin: 0;
	padding: 5px;
	vertical-align: top;
}
.row-aanestys
{
	background-color: #eee;
}
.cell-ei
{
	background-color: #CF8C8C;
	background-color: #F4D1D5;
	text-align: center;
}
.row-aanestys .cell-ei
{
	background-color: #A67070;
	background-color: #E3C2C6;
}

.cell-jaa
{
	background-color: #B8CF8C;
	background-color: #E5F2CF;
	text-align: center;
}
.row-aanestys .cell-jaa
{
	background-color: #93A670;
	background-color: #D8E4C3;
}

.cell-tyhjaa
{
	background-color: #8CBACF;
	background-color: #D1E8F6;
	text-align: center;
}
.row-aanestys .cell-tyhjaa
{
	background-color: #7095A6;
	background-color: #C3D9E4;
}

input:checked
{
	padding: 2px;
}
.cell-tyhjaa input:checked
{
	background-color: cyan;
}
.cell-jaa input:checked
{
	background-color: green;
}
.cell-ei input:checked
{
	background-color: red;
}
.row-spacer
{
	height: 5px;
}
#button
{
text-align: right;
margin-right: 50px;
}
.cell-aanestys p
{
margin: 0;
}
.cell-aanestys p.aname
{
margin-bottom: 0.3em;	
}
#eduskuntaimage {
border: 1px solid #999;
}
#share {
float: right;
width: 150px;
padding: 0 5px;
background-color: #D1E8F6;
margin: 0 0 5px 5px;
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

<p id="bread"><a href="http://www.biomi.org/">biomi.org</a> &gt; <a href="http://www.biomi.org/eduskunta/eduskunta.html">Eduskunta</a> &gt; ��nestyskone</p>


<div id="share">
<p><a name="fb_share" type="button_count" href="http://www.facebook.com/sharer.php">Share</a><script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script></p>
</div>

<h1>��nestyskone - ketk� kansanedustajat ovat ��nest�neet mielipiteesi mukaan?</h1>

<p>T�m�n "��nestyskoneen" avulla voit verrata kansanedustajien ��nestysvalintoja omiin mielipiteisiisi. Kun valitset miten olisit itse ��nest�nyt ao. ��nestyksiss�, vertaa kone valintojasi kansanedustajiin ja esitt�� heid�t sopivuusj�rjestyksess�. Mukaan on otettu valikoima tunnettuja ja keskustelua her�tt�neit� ��nestyksi� 2000-luvulta, painottaen kuluvaa vuotta.</p>

<p><strong>Pisteytys:</strong> Edustajalle annetaan yksi piste jokaisesta valinnasta, joka vastaa omaasi. Vastakkaisesta valinnasta annetaan yksi miinuspiste ja tyhj�n ��nest�misest� puolikas miinuspiste. Huomaa ett� mik�li teet valintoja usean vaalikauden ajalta, pitk��n virassa olleet edustajat voivat saada enemm�n pisteit� kuin tuoreemmat edustajat.</p>

<p>��nestystulokset on alunperin haettu Eduskunnan verkkosivuilta <a href="http://www.biomi.org/eduskunta/eduskunta.html">��nestysrajapinnan</a> avulla. (Nopeamman toimivuuden saavuttamiseksi kone hakee tulokset kuitenkin suoraan ��nestystietokannasta k�ytt�m�tt� apuna itse XML-rajapintaa.)</p>


<?php

$aanestykset['a10_77-2010'][0] = "Valtioneuvoston periaatep��t�s 6. p�iv�n� toukokuuta 2010 <strong>Fennovoima Oy:n hakemukseen ydinvoimalaitoksen</strong> rakentamisesta";
$aanestykset['a10_77-2010'][1] = "http://www.eduskunta.fi/triphome/bin/thw.cgi/trip/?\${html}=aax/aax4000&\${base}=aanestysu&aanestysvpvuosi=2010&istuntonro=77&pj_kohta=1&aanestysnro=10&\${snhtml}=aax/aaxeiloydy";
$aanestykset['a10_77-2010'][2] = "1.7.2010";

$aanestykset['a9_77-2010'][0] = "Valtioneuvoston periaatep��t�s <strong>Posiva Oy:n hakemukseen k�ytetyn ydinpolttoaineen loppusijoituslaitoksen</strong> rakentamisesta";
$aanestykset['a9_77-2010'][1] = "http://www.eduskunta.fi/triphome/bin/thw.cgi/trip/?\${html}=aax/aax4000&\${base}=aanestysu&aanestysvpvuosi=2010&istuntonro=77&pj_kohta=1&aanestysnro=9&\${snhtml}=aax/aaxeiloydy";
$aanestykset['a9_77-2010'][2] = "1.7.2010";

$aanestykset['a7_77-2010'][0] = "Valtioneuvoston periaatep��t�s <strong>Teollisuuden Voima Oyj:n hakemukseen ydinvoimalaitosyksik�n</strong> rakentamisesta";
$aanestykset['a7_77-2010'][1] = "http://www.eduskunta.fi/triphome/bin/thw.cgi/trip/?\${html}=aax/aax4000&\${base}=aanestysu&aanestysvpvuosi=2010&istuntonro=77&pj_kohta=1&aanestysnro=7&\${snhtml}=aax/aaxeiloydy";
$aanestykset['a7_77-2010'][2] = "1.7.2010";

$aanestykset['a1_53-2009'][0] = "Hallituksen esitys laiksi <strong>rekister�idyst� parisuhteesta annetun lain 9 �:n muuttamisesta</strong>";
$aanestykset['a1_53-2009'][1] = "http://www.eduskunta.fi/triphome/bin/thw.cgi/trip/?\${html}=aax/aax4000&\${base}=aanestysu&aanestysvpvuosi=2009&istuntonro=53&pj_kohta=2&aanestysnro=1&\${snhtml}=aax/aaxeiloydy";
$aanestykset['a1_53-2009'][2] = "15.5.2009";

$aanestykset['a3_15-2010'][0] = "Hallituksen esitys laiksi <strong>ymp�rist�nsuojelulain muuttamisesta</strong>";
$aanestykset['a3_15-2010'][1] = "http://www.eduskunta.fi/triphome/bin/thw.cgi/trip/?\${html}=aax/aax4000&\${base}=aanestysu&aanestysvpvuosi=2010&istuntonro=15&pj_kohta=2&aanestysnro=3&\${snhtml}=aax/aaxeiloydy";
$aanestykset['a3_15-2010'][2] = "26.2.2010";

$aanestykset['a1_55-2010'][0] = "Hallituksen esitys <strong>Pallas-Yll�stunturin kansallispuistosta annetun lain muuttamisesta</strong>";
$aanestykset['a1_55-2010'][1] = "http://www.eduskunta.fi/triphome/bin/thw.cgi/trip/?\${html}=aax/aax4000&\${base}=aanestysu&aanestysvpvuosi=2010&istuntonro=55&pj_kohta=3&aanestysnro=1&\${snhtml}=aax/aaxeiloydy";
$aanestykset['a1_55-2010'][2] = "21.5.2010";

$aanestykset['a87_74-2010'][0] = "Hallituksen esitys laeiksi <strong>arvonlis�verolain muuttamisesta</strong> ja arvonlis�verolain v�liaikaisesta muuttamisesta";
$aanestykset['a87_74-2010'][1] = "http://www.eduskunta.fi/triphome/bin/thw.cgi/trip/?\${html}=aax/aax4000&\${base}=aanestysu&aanestysvpvuosi=2010&istuntonro=74&pj_kohta=3&aanestysnro=87&\${snhtml}=aax/aaxeiloydy";
$aanestykset['a87_74-2010'][2] = "24.6.2010";

$aanestykset['a4_69-2009'][0] = "Hallituksen esitys <strong>yliopistolaiksi</strong> ja siihen liittyviksi laeiksi";
$aanestykset['a4_69-2009'][1] = "http://www.eduskunta.fi/triphome/bin/thw.cgi/trip/?\${html}=aax/aax4000&\${base}=aanestysu&aanestysvpvuosi=2009&istuntonro=69&pj_kohta=3&aanestysnro=4&\${snhtml}=aax/aaxeiloydy";
$aanestykset['a4_69-2009'][2] = "16.6.2009";

$aanestykset['a1_18-2009'][0] = "Hallituksen esitys <strong>s�hk�isen viestinn�n tietosuojalain</strong> ja er�iden siihen liittyvien lakien muuttamisesta";
$aanestykset['a1_18-2009'][1] = "http://www.eduskunta.fi/triphome/bin/thw.cgi/trip/?\${html}=aax/aax4000&\${base}=aanestysu&aanestysvpvuosi=2009&istuntonro=18&pj_kohta=1&aanestysnro=1&\${snhtml}=aax/aaxeiloydy";
$aanestykset['a1_18-2009'][2] = "4.3.2009";

$aanestykset['a1_66-2008'][0] = "Hallituksen esitys <strong>Lissabonin sopimuksen</strong> hyv�ksymisest� ja laiksi sen lains��d�nn�n alaan kuuluvien m��r�ysten voimaansaattamisesta";
$aanestykset['a1_66-2008'][1] = "http://www.eduskunta.fi/triphome/bin/thw.cgi/trip/?\${html}=aax/aax4000&\${base}=aanestysu&aanestysvpvuosi=2008&istuntonro=66&pj_kohta=1&aanestysnro=1&\${snhtml}=aax/aaxeiloydy";
$aanestykset['a1_66-2008'][2] = "11.6.2008";

$aanestykset['a1_100-2005'][0] = "Hallituksen esitys laeiksi <strong>tekij�noikeuslain ja rikoslain</strong> 49 luvun muuttamisesta";
$aanestykset['a1_100-2005'][1] = "http://www.eduskunta.fi/triphome/bin/thw.cgi/trip/?\${html}=aax/aax4000&\${base}=aanestysu&aanestysvpvuosi=2005&istuntonro=100&pj_kohta=1&aanestysnro=1&\${snhtml}=aax/aaxeiloydy";
$aanestykset['a1_100-2005'][2] = "5.10.2005";

$aanestykset['a1_66-2002'][0] = "Valtioneuvoston periaatep��t�s 17 p�iv�n� tammikuuta 2002 <strong>Teollisuuden Voima Oy:n hakemukseen ydinvoimalaitosyksik�n rakentamisesta</strong>";
$aanestykset['a1_66-2002'][2] = "http://www.eduskunta.fi/triphome/bin/thw.cgi/trip/?\${html}=aax/aax4000&\${base}=aanestysu&aanestysvpvuosi=2002&istuntonro=66&pj_kohta=1&aanestysnro=1&\${snhtml}=aax/aaxeiloydy";
$aanestykset['a1_66-2002'][2] = "24.5.2002";

$aanestykset['a1_105-2001'][0] = "Hallituksen esitys <strong>laiksi virallistetusta parisuhteesta</strong>";
$aanestykset['a1_105-2001'][1] = "http://www.eduskunta.fi/triphome/bin/thw.cgi/trip/?\${html}=aax/aax4000&\${base}=aanestysu&aanestysvpvuosi=2001&istuntonro=105&pj_kohta=1&aanestysnro=1&\${snhtml}=aax/aaxeiloydy";
$aanestykset['a1_105-2001'][2] = "28.9.2001";


function getlink($id)
{
	$id = str_replace("a", "", $id);
	$id = str_replace("_", "-", $id);
	
	$pieces = explode("-", $id);
	
	$ret = "http://www.eduskunta.fi/triphome/bin/thw.cgi/trip/?\\${html}=aax/aax4000&\\${base}=aanestysu&aanestysvpvuosi=" . $pieces[2] . "&istuntonro=" . $pieces[1] . "&pj_kohta=1&aanestysnro=" . $pieces[0] . "&\\${snhtml}=aax/aaxeiloydy";
	return $ret;
}

?>

<form name="form1" id="form1" method="get" action="tulos.php">
<table>
  <tr class="row-header">
    <td class="cell-aanestys">&nbsp;</td>
    <td class="cell-jaa">Jaa</td>
    <td>&nbsp;</td>
    <td class="cell-tyhjaa">Ei merkityst�</td>
    <td>&nbsp;</td>
    <td class="cell-ei">Ei</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <?php
	foreach ($aanestykset as $key => $value)
	{
		// muutetaan & -> &amp;
		$value[1] = str_replace("&", "&amp;", $value[1]);
		echo "
	  <tr class=\"row-aanestys\">
		<td class=\"cell-aanestys\">
		<p class=\"aname\">$value[0]</p>
		<p class=\"meta\">" . $value[2] . " <a href=\"http://www.biomi.org/tools/eduskunta/aanestys/$key\">T�m�n ��nestyksen tulokset</a> &amp; <a href=\"" . $value[1] . "\" target=\"_blank\">lis�tietoa Eduskunnan sivuilla</a></p>
		</td>
		<td class=\"cell-jaa\"><input name=\"selects[$key]\" type=\"radio\" class=\"rad\" value=\"Jaa\" /></td>
		<td>&nbsp;</td>
		<td class=\"cell-tyhjaa\"><input name=\"selects[$key]\" type=\"radio\" class=\"rad\" value=\"ei merkityst�\" checked=\"checked\" /></td>
		<td>&nbsp;</td>
		<td class=\"cell-ei\"><input name=\"selects[$key]\" type=\"radio\" class=\"rad\" value=\"Ei\" /></td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	  </tr>
	  <tr class=\"row-spacer\">
		<td class=\"cell-aanestys\"></td>
		<td class=\"cell-jaa\"></td>
		<td></td>
		<td class=\"cell-tyhjaa\"></td>
		<td></td>
		<td class=\"cell-ei\"></td>
		<td></td>
		<td></td>
	  </tr>";
	}
  ?>
<tr><td colspan="7">
	<p id="button"><input name="" type="submit" value="Hae edustajat" /></p>
</td></tr>  
</table>





</form>

<p class="clear: both;">&nbsp;</p></div><!-- content ends -->

<div id="footer">
<p><a href="http://www.biomi.org/mikko/">Mikko Heikkinen</a> &#8226; <a href="http://www.biomi.org/">http://www.biomi.org</a> &#8226; <a href="/sekalaista/palaute.html">palaute</a>  </p>
</div>

</body>

</html>

