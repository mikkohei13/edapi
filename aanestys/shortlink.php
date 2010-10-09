<style type="text/css">
div {
width: 200px;
text-align: center;
font-family: Arial, Helvetica, sans-serif;
font-size: 16px;
}
p {
margin: 0;
}
a:link {
color: #009;
}
#selite {
font-size: 12px;
}
</style>

<?php

include_once "../../../bitly_apikey.php";

$url = $_GET['url'];

$url = urlencode($url);

$url = "http://api.bit.ly/v3/shorten?login=mikkohei13&apiKey=$bitly_apikey&longUrl=" . $url . "&format=txt";

if (!function_exists('curl_init')) {
	echo "CURL not installed";
}
else
{
	$curl = curl_init(); 
	curl_setopt($curl, CURLOPT_URL, $url);  
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);  
	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);  
	$content = curl_exec($curl);  
	curl_close($curl);
	
	$short_url = $content;
//	echo "short_url: " . $short_url . "\n";

	$qrcode_url = "http://chart.apis.google.com/chart?chs=200x200&cht=qr&chl=$short_url";

	echo "<div>";
	echo "<p>Lyhytlinkki n‰ihin tuloksiin:<br /><a href=\"$short_url\" target=\"_top\">$short_url</a></p>";
	echo "<img src=\"$qrcode_url\" alt=\"\" width=\"200\" height=\"200\" />";
	echo "<p id=\"selite\">Osoite viivakoodina k‰nnykk‰‰n tai muuhun viivakoodeja lukevaan laitteeseen. </p>";
	echo "<div>";
}



?>