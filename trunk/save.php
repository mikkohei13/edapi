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
		require_once "include/include_functions.php";
		require_once "simplehtmldom/simple_html_dom.php";
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

		// Hakee ja muuntaa URLin k‰ytett‰v‰‰n muotoon
		$url = $this->input->post('url');
		$url = str_replace("$", "\$", $url); // vaihdetaan $ -> \$

		// Hakee moden POSTista
		$mode = $this->input->post('mode');

		$curl = curl_init(); 
		curl_setopt($curl, CURLOPT_URL, $url);  
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);  
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);  
		$str = curl_exec($curl);  
		curl_close($curl);
		
		$html= str_get_html($str); 
		
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		// ETSII JA FORMATOI METADATAN
		
		// ƒ‰nestys
		$aanestys_raw = $html->find('table.voteResults caption', 0);
		$aanestys_raw = $aanestys_raw->plaintext;
		
		$start = strpos($aanestys_raw, "ƒ‰nestys ");
		$start = $start + 9; // Lis‰t‰‰n needlen pituus
		$end = strpos($aanestys_raw, "\n", $start);
		$length = $end - $start;
		$aanestys = substr($aanestys_raw, $start, $length);
		$aanestys = trim($aanestys);
		unset($start);
		unset($end);

		// Otsikko
		$otsikko = $html->find('table.voteResults tbody td[colspan=3]', 0);
		$otsikko = $otsikko->plaintext;
		
		$otsikko = str_replace("<td colspan=\"3\">", "", $otsikko);
		$otsikko = str_replace("</td>", "", $otsikko);
		
		$otsikko = trim($otsikko, " \t\r\n");
		
		// K‰sittely
		$kasittely = $html->find('table.voteResults tbody td[colspan=3]', 1);
		$kasittely = $kasittely->plaintext;
		$kasittely = str_replace("<strong>", "", $kasittely);
		$kasittely = str_replace("</strong>", "", $kasittely);
		$kasittely = str_replace("<td colspan=\"3\">", "", $kasittely);
		$kasittely = str_replace("</td>", "", $kasittely);
		$kasittely = trim($kasittely);
		
		// Asettelu
		$asettelu = $html->find('table.noTopBorder td', 0);
		$asettelu = $asettelu->plaintext;
		$asettelu = str_replace("<td>", "", $asettelu);
		$asettelu = str_replace("</td>", "", $asettelu);
		$asettelu = trim($asettelu);
		
		// Istuntolink
		$istunto_link = $html->find('table.voteResults caption a', 0);
		$istunto = $istunto_link->href;
		$istunto_link = $istunto_link->plaintext;
		$istunto_link = trim($istunto_link);
		
		$temp = explode("/", $istunto_link);
		
		// P‰iv‰m‰‰r‰
		$pvm = $temp[1]; // muodossa 19.02.2010
		unset($temp);
		$temp = explode(".", $pvm);
		$pvm = $temp[2] . $temp[1] . $temp[0]; // muodossa 20100219
		unset($temp);
		$pvm = trim($pvm);
		
		// Istunto
		$start = strpos($istunto, "PTK+");
		$start = $start + 4; // Lis‰t‰‰n needlen pituus
		$end = strlen($istunto);
		$length = $end - $start;
		$istuntokoodi = substr($istunto, $start, $length);
		$istuntokoodi = trim($istuntokoodi);
		$istuntokoodi = str_replace("/", "-", $istuntokoodi);
		
		$temp = explode("-", $istuntokoodi);
		$istunto = trim($temp[0]);
		$vuosi = trim($temp[1]);
		
		// ƒ‰nestystunniste
		$aanestystunniste = "a" . $aanestys . "_" . $istuntokoodi; // Muotoa a2_11-2010
		$aanestystunniste = trim($aanestystunniste);

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
		foreach ($db_meta as $key2 => $value2)
		{
			$db_meta[$key2] = uml_to_char($value2);
		}
		
		// URL lis‰t‰‰n lopuksi, koska sille ei tehd‰ umlaut-muunnosta
		$db_meta['url'] = $url;

			
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		// ETSI JA FORMATOI TULOKSET
		
		// prosessointiajan seurantaa...
		echo "<pre>";
		echo "\n X)" . microtime(TRUE);
		
		// Hakee tulostaulukon solut
		foreach($html->find('table.statistics td') as $aanestysval) // returns object
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

		// Alkuarvot
		$jaa = 0;
		$ei = 0;
		$poissa = 0;
		$tyhjaa = 0;
		
		// K‰yd‰‰n tulostaulukko l‰pi
		foreach ($aanestys_arr as $key => $value)
		{
			$value = trim($value);
			$value = str_replace("<td>", "", $value);
			$value = str_replace("</td>", "", $value);

			// kyseess‰ NIMISOLU
			if (strpos($value, "/") > 0)
			{
				$temp = explode("/", $value);
				$edustaja = trim($temp[0]);
				$puolue = trim($temp[1], " <");

				// jos puolueessa v‰lilyˆnti...
				if (strpos($puolue, " ") > 0)
				{
					// otetaan puoluenimest‰ mukaan vain alkuosa 1. v‰lilyˆntiin asti
					$space = strpos($puolue, " ");
					$puolue = substr($puolue, 0, $space);
				}
			}
			
			// kyseess‰ VALINTASOLU
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
			
			// Kootaan parempi 2-ulotteinen taulu tietokantaan tallennusta varten
			if (isset($edustaja) && isset($valinta))
			{
				$tulostaulu[] = array("aanestystunniste" => $aanestystunniste, "edustaja" => uml_to_char($edustaja), "valinta" => $valinta, "puolue" => $puolue);
	
				unset($edustaja);
				unset($valinta);
			}
		}
	
		// prosessointiajan seurantaa...
		echo "\n 0)" . microtime(TRUE);
		
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		// TALLENTAA TAI NƒYTTƒƒ ESIKATSELUN
		
		if ($mode == "save")
		{
			// - - - - - - - - - - - - - - - - - - - - - - - - - - - -
			// Tallennus tietokantaan
				
			$this->load->database('edapi');
			$this->output->enable_profiler(TRUE);
			
			// tarkistetaan metadatataulusta onko k‰sitelt‰v‰ ‰‰nestys jo tallennettu...
			$query = $this->db->get_where('edapi_meta', array('aanestystunniste' => $aanestystunniste), 1);
			if ($query->num_rows() > 0)
			{
				echo "<h2>T‰m‰n ‰‰nestyksen ($aanestystunniste) tiedot ovat jo tietokannassa</h2>";
			}
			// ...jos ei, tallennetaan
			else
			{
				// prosessointiajan seurantaa...
				echo "\n 1)" . microtime(TRUE);

				// INSERT jokaiselle edustajalle erikseen
				foreach ($tulostaulu as $key1 => $value1)
				{
					$this->db->insert('edapi_aanestykset', $value1); 
					echo "\n n)" . microtime(TRUE);
				}

				// INSERT metadata toiseen tauluun
				$this->db->insert('edapi_meta', $db_meta);
				
				// prosessointiajan seurantaa...
				echo "\n 2)" . microtime(TRUE);
				
				echo "<h2>Tietojen tallennus onnistui ($aanestystunniste)</h2>";
			}
		}
		else
		{
			// - - - - - - - - - - - - - - - - - - - - - - - - - - - -
			// Preview
			
			echo "<h3>Preview</h3>";
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

			print_r ($tulostaulu);
			echo "</pre>";
			
		}

	}
	// ---------------------------------------------------------------
}
?>

