<?php
class Save extends Controller {
	// ---------------------------------------------------------------
	function index()
	{
		$this->load->helper('url');
		
		echo "<h1>Eduskunta: ‰‰nestyksen tallennus</h1>
<form name=\"form1\" method=\"post\" action=\"" . site_url("save/insert") . "\">
  <p><input type=\"reset\" name=\"reset\" value=\"Reset\"></p>
  
    <input name=\"url\" type=\"text\" id=\"url\" size=\"150\">
	<p>
  <label><input name=\"mode\" type=\"radio\" value=\"preview\"> 
Preview</label><br />
  <label><input name=\"mode\" type=\"radio\" value=\"save\" checked=\"checked\">
Save</label></p>
    <input type=\"submit\" name=\"Submit\" value=\"Submit\">
	
</p>
</form>";
	}
	// ---------------------------------------------------------------
	function insert()
	{
		/*
		$this->load->helper('html');
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('session');
		*/
		require_once "include/include_functions.php";
		require_once "simplehtmldom/simple_html_dom.php";
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

		// Hakee ja muuntaa URLin k‰ytett‰v‰‰n muotoon
		$url = $this->input->post('url');
		$url = str_replace("$", "\$", $url); // vaihdetaan $ -> \$
//		echo $url;

		// Hakee moden POSTista
		$mode = $this->input->post('mode');

		/*
		// ORIGINAL STYLE
		// Create DOM from URL or file
		$html = file_get_html($url);
		*/
		
		$curl = curl_init(); 
		curl_setopt($curl, CURLOPT_URL, $url);  
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);  
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);  
		$str = curl_exec($curl);  
		curl_close($curl);
		
		$html= str_get_html($str); 
		
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		// ETSII JA FORMATOI METADATAN
		// ƒƒNESTYS
		$aanestys_raw = $html->find('table.voteResults caption', 0);
		$aanestys_raw = $aanestys_raw->plaintext;
		
//		echo $aanestys_raw[0]; // debug
		
//		print_r ($aanestys_raw[0]); // debug

		$start = strpos($aanestys_raw, "ƒ‰nestys ");
		$start = $start + 9; // Lis‰t‰‰n needlen pituus
		$end = strpos($aanestys_raw, "\n", $start);
		$length = $end - $start;
		$aanestys = substr($aanestys_raw, $start, $length);
		$aanestys = trim($aanestys);
//		echo "<p>ƒƒNESTYS: $aanestys";
		unset($start);
		unset($end);

		// OTSIKKO
		$otsikko = $html->find('table.voteResults tbody td[colspan=3]', 0);
		$otsikko = $otsikko->plaintext;
		
		$otsikko = str_replace("<td colspan=\"3\">", "", $otsikko);
		$otsikko = str_replace("</td>", "", $otsikko);
		
//		$otsikko = str_replace("\r", "", $otsikko);
//		$otsikko = str_replace("\n", "", $otsikko);
//		$otsikko = str_replace("\t", "", $otsikko);
		
		$otsikko = trim($otsikko, " \t\r\n");
//		echo "<p>OTSIKKO: $otsikko";
		
		// KƒSITTELY
		$kasittely = $html->find('table.voteResults tbody td[colspan=3]', 1);
		$kasittely = $kasittely->plaintext;
		$kasittely = str_replace("<strong>", "", $kasittely);
		$kasittely = str_replace("</strong>", "", $kasittely);
		$kasittely = str_replace("<td colspan=\"3\">", "", $kasittely);
		$kasittely = str_replace("</td>", "", $kasittely);
		$kasittely = trim($kasittely);
//		echo "<p>KASITTELY: $kasittely";
		
		// ASETTELU
		$asettelu = $html->find('table.noTopBorder td', 0);
		$asettelu = $asettelu->plaintext;
		$asettelu = str_replace("<td>", "", $asettelu);
		$asettelu = str_replace("</td>", "", $asettelu);
		$asettelu = trim($asettelu);
//		echo "<p>ASETTELU: $asettelu";
		
		// ISTUNTO ja ƒƒNETYSTUNNISTE
		$istunto_link = $html->find('table.voteResults caption a', 0);
		$istunto = $istunto_link->href;
		$istunto_link = $istunto_link->plaintext;
		$istunto_link = trim($istunto_link);
//		echo "(" . $istunto_link . ")"; // debug

//		$start = strpos($istunto_link, ">") + 1;
//		$istunto_link = substr($istunto_link, $start);
//		$end = strpos($istunto_link, "<");
//		$istunto_link = substr($istunto_link, 0, $end);
//		$istunto_link = trim($istunto_link);
		
		$temp = explode("/", $istunto_link);
		
//		print_r ($temp); // debug
		$pvm = $temp[1]; // muodossa 19.02.2010
		unset($temp);
		$temp = explode(".", $pvm);
		$pvm = $temp[2] . $temp[1] . $temp[0]; // muodossa 20100219
		$pvm = trim($pvm);
		
//		echo "<p>PVM: $pvm";
		$vuosi = $temp[2];
		unset($temp);		

		$start = strpos($istunto, "PTK+");
		$start = $start + 4; // Lis‰t‰‰n needlen pituus
		$end = strlen($istunto);
		$length = $end - $start;
		$istuntokoodi = substr($istunto, $start, $length);
		$istuntokoodi = trim($istuntokoodi);
		$istuntokoodi = str_replace("/", "-", $istuntokoodi);
		
		$temp = explode("-", $istuntokoodi);
		$istunto = trim($temp[0]);
		
//		echo "<p>ISTUNTO: $istunto";
//		echo "<p>VUOSI: $vuosi";

		$aanestystunniste = "a" . $aanestys . "_" . $istuntokoodi; // Muotoa a2_11-2010
		$aanestystunniste = trim($aanestystunniste);
//		echo "<p>ƒƒNESTYSTUNNISTE: $aanestystunniste\n\n";

		// Kootaan tietokantaan menev‰ metadatataulu
		$db_meta = array(
			"aanestystunniste" => $aanestystunniste,
			"aanestys" => $aanestys,
			"vuosi" => $vuosi,
			"istunto" => $istunto,
			"otsikko" => $otsikko,
			"pvm" => $pvm,
			"kasittely" => $kasittely,
			"asettelu" => $asettelu 
		);
	
		// Korvataan umlautit kirjaimilla
		foreach ($db_meta as $keyx => $valuex)
		{
			$db_meta[$keyx] = uml_to_char($valuex);
		}
		
		// URL lis‰t‰‰n lopuksi, koska sille ei tehd‰ muunnosta
		$db_meta['url'] = $url;

			
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		// ETSI JA FORMATOI TULOKSET
//		echo "<pre>";
//		echo "\n X)" . microtime(TRUE);
		
		foreach($html->find('table.statistics td') as $aanestysval) // problem: returns object instead of array
		{
			$aanestys_arr[] = $aanestysval->plaintext . "\n\n";
		}

		// Siivotaan turhat solut pois
		foreach ($aanestys_arr as $k => $v)
		{
			if (strpos($v, "<td class=\"noBorder\">&nbsp;</td>") !== FALSE || strpos($v, "<td></td>") !== FALSE)
			{
				unset($aanestys_arr[$k]);
			}
		}

		$jaa = 0;
		$ei = 0;
		$poissa = 0;
		$tyhjaa = 0;
		
//		echo "<pre>";
//		print_r ($aanestys_arr);
//		echo "</pre>";


		foreach ($aanestys_arr as $key => $value)
		{
		//	$debug .= trim($value) . "\n";
			$value = trim($value);
			$value = str_replace("<td>", "", $value);
			$value = str_replace("</td>", "", $value);

			// kyseess‰ nimisolu
			if (strpos($value, "/") > 0)
			{
				$temp = explode("/", $value);
				$edustaja = trim($temp[0]);
				$puolue = trim($temp[1], " <");

				// jos puolueessa v‰lilyˆnti...
				if (strpos($puolue, " ") > 0)
				{
					// otetaan puoluenimest‰ mukaan vain alkuosa ekaan v‰lilyˆntiin asti
					$space = strpos($puolue, " ");
					$puolue = substr($puolue, 0, $space);
				}
//				echo "<pre>\n($puolue)</pre>"; // debug
			}
			
			// kyseess‰ valintasolu
			else
			{
				if ($value == "Jaa")
				{
					$valinta = "Jaa";
					$jaa++;
				}
				elseif ($value == "Ei")
				{
					$valinta = "Ei";
					$ei++;
				}
				elseif ($value == "Poissa")
				{
					$valinta = "Poissa";
					$poissa++;
				}
				elseif ($value == "Tyhj&auml;&auml;")
				{
					$valinta = "Tyhj‰‰";
					$tyhjaa++;
				}
			}
			
			// Kootaan parempi 2-tasoinen taulu tietokantaan tallennusta varten
			if (isset($edustaja) && isset($valinta))
			{
				$tulostaulu[] = array("aanestystunniste" => $aanestystunniste, "edustaja" => uml_to_char($edustaja), "valinta" => $valinta, "puolue" => $puolue);
	
				unset($edustaja);
				unset($valinta);
			}
		}
	
//		echo "\n 0)" . microtime(TRUE);



		/*	
		// Kokonaism‰‰r‰t
		echo "Jaa: " . $jaa . "<br />";
		echo "Ei: " . $ei . "<br />";
		echo "Poissa: " . $poissa . "<br />";
		echo "Tyhj‰‰: " . $tyhjaa . "<br />";
		*/


		// DEBUG
		/*
		echo "<pre>";
		print_r ($tulostaulu);
		echo "</pre>";
		*/
		
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		// TARKISTUKSET

		$error = 0; // Jos virheit‰ ei tarkistustenkaan j‰lkeen ole, t‰m‰n arvo on nolla.
		$msg = "";
		
		// edustajien m‰‰r‰
		if (count($tulostaulu) == 199) {
			$msg = $msg . "edustajien m‰‰r‰ ok<br />";
		}
		else
		{
			$msg = $msg . "edustajien m‰‰r‰ virheellinen<br/>";
			$error++;
		}
		
		// p‰iv‰m‰‰r‰n pituus
		if (strlen($db_meta['pvm']) == 8) {
			$msg = $msg . "p‰iv‰m‰‰r‰n pituus ok<br />";
		}
		else
		{
			$msg = $msg . "p‰iv‰m‰‰r‰n pituus virheellinen<br/>";
			$error++;
		}

		// vuosiluku
		if ($db_meta['vuosi'] == date("Y")) {
			$msg = $msg . "vuosiluku ok<br />";
		}
		else
		{
			$msg = $msg . "vuosi virheellinen (po. " . date("Y") . ")<br/>";
			$error++;
		}
		
		// edustajat
		foreach ($tulostaulu as $k1 => $v1)
		{
			// puoluenimen pituus
			if (strlen($v1['puolue']) > 4)
			{
				$msg = $msg . "edustajan " . $v1['edustaja'] . " puolue virheellinen<br/>";
				$error++;
			}
			// valinnan pituus
			if (strlen($v1['valinta']) > 6)
			{
				$msg = $msg . "edustajan " . $v1['edustaja'] . " valinta virheellinen<br/>";
				$error++;
			}
		}


		echo "\n" . $msg . "\n";
		echo "\n" . $error . " virhett‰\n";
			
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		// TALLENTAA TAI NƒYTTƒƒ ESIKATSELUN
		
		if ($mode == "save" && $error == 0)
		{
			// - - - - - - - - - - - - - - - - - - - - - - - - - - - -
			// Tallennus tietokantaan
				
			$this->load->database('edapi');
			$this->output->enable_profiler(TRUE);
			
			// tarkistetaan metadatataulusta onko aanestys jo kannassa
			$query = $this->db->get_where('edapi_meta', array('aanestystunniste' => $aanestystunniste), 1);
			if ($query->num_rows() > 0)
			{
				echo "<h2>T‰m‰n ‰‰nestyksen ($aanestystunniste) tiedot ovat jo tietokannassa</h2>";
			}
			// jos ei, tallennetaan
			else
			{
				/*
				// DEBUG
				// T‰m‰ korvaa ‰‰nestystunnisteen sanalla "temp" 
				$db_meta['aanestystunniste'] = "temp";
				foreach ($tulostaulu as $tk => $tv)
				{
					$tv['aanestystunniste'] = "temp";
					$tulostaulu[$tk] = $tv;
				}
								
				echo "<pre>";
				print_r ($tulostaulu);
				print_r ($db_meta);
				// DEBUG ENDS
				*/
				
//				echo "\n 1)" . microtime(TRUE);

				// jokaisen edustajan ‰‰nestys erikseen
				foreach ($tulostaulu as $key1 => $value1)
				{
					$this->db->insert('edapi_aanestykset', $value1); 
//					echo "\n n)" . microtime(TRUE);
				}

				// metadata
				$this->db->insert('edapi_meta', $db_meta);
//				echo "\n 2)" . microtime(TRUE);
				
				echo "<h2>Tietojen tallennus onnistui ($aanestystunniste)</h2>";

			}
		}
		else
		{
			// - - - - - - - - - - - - - - - - - - - - - - - - - - - -
			// Preview
		
			if ($error == 0)
			{
				echo "<h1>Preview</h1>";
			}
			else
			{
				echo "<h1>Virheit‰ tietojen haussa, tallennus keskeytetty!</h1>";
			}
		
			echo "<p>$url</p>";
			echo "<pre>";
			
			echo "aanestystunniste (" . umlauts($db_meta['aanestystunniste']) . ")\n";
			echo "aanestys (" . umlauts($db_meta['aanestys']) . ")\n";
			echo "vuosi (" . umlauts($db_meta['vuosi']) . ")\n";
			echo "istunto (" . umlauts($db_meta['istunto']) . ")\n";
			echo "otsikko (" . umlauts($db_meta['otsikko']) . ")\n";
			echo "pvm (" . umlauts($db_meta['pvm']) . ")\n";
			echo "url (" . $db_meta['url'] . ")\n"; // URLille ei tehd‰ muunnosta
			echo "kasittely (" . umlauts($db_meta['kasittely']) . ")\n";
			echo "asettelu (" . umlauts($db_meta['asettelu']) . ")\n";

//			print_r ($db_meta);
			print_r ($tulostaulu);
			echo "</pre>";
			
		}

	}
	// ---------------------------------------------------------------
}
?>

