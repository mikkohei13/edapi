<style type="text/css">
body {
font-family: Arial, Helvetica, sans-serif;
font-size: 14px;
}
a {
padding: 5px;
background-color: #D1E8F6;
background-color: #fff;
margin-left: -10px;
}

</style>
	
<?php

$url = $_GET['url'];
$url = urlencode($url);

echo "<a href=\"shortlink.php?url=$url\">Tee lyhytlinkki näihin tuloksiin</a>";


?>