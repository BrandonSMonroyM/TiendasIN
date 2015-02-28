<!DOCTYPE html>
<html lang="es-CO">
<head>
	<meta charset="UTF-8">
	<title>Crear Subdominio</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>

	<header>
		<img alt="WebPuro" src="http://webpuro.net/web/images/MOCKUP-WP-LOGO-PRINCIPAL.png">
	</header>

	<?php
	###############################################################
	# cPanel Subdomains Creator 1.1
	###############################################################
	# Visit http://www.zubrag.com/scripts/ for updates
	###############################################################
	#
	# Can be used in 3 ways:
	# 1. just open script in browser and fill the form
	# 2. pass all info via url and form will not appear
	# Sample: cpanel_subdomains.php?cpaneluser=USER&cpanelpass=PASSWORD&domain=DOMAIN&subdomain=SUBDOMAIN
	# 3. list subdomains in file. In this case you must provide all the defaults below
	#
	# Note: you can omit any parameter, except "subdomain".
	# When omitted, default value specified below will be taken
	###############################################################

	// cpanel user
	define('CPANELUSER','user');

	// cpanel password
	define('CPANELPASS','pass');

	// name of the subdomains list file.
	// file format may be 1 column or 2 columns divided with semicilon (;)
	// Example for two columns:
	//   rootdomain1;subdomain1
	//   rootdomain1;subdomain2
	// Example for one columns:
	//   subdomain1
	//   subdomain2
	define('INPUT_FILE','domains.txt');

	// cPanel skin (mainly "x")
	// Check http://www.zubrag.com/articles/determine-cpanel-skin.php
	// to know it for sure
	define('CPANEL_SKIN','x');

	// Default domain (subdomains will be created for this domain)
	// Will be used if not passed via parameter and not set in subdomains file
	define('DOMAIN','');


	/////////////// END OF INITIAL SETTINGS ////////////////////////
	////////////////////////////////////////////////////////////////

	function getVar($name, $def = '') {
	  if (isset($_REQUEST[$name]) && ($_REQUEST[$name] != ''))
		return $_REQUEST[$name];
	  else 
		return $def;
	}

	$cpaneluser=getVar('cpaneluser', CPANELUSER);
	$cpanelpass=getVar('cpanelpass', CPANELPASS);
	$cpanel_skin = getVar('cpanelskin', CPANEL_SKIN);

	if (isset($_REQUEST["subdomain"])) {
	  // get parameters passed via URL or form, emulate string from file 
	  $doms = array( getVar('domain', DOMAIN) . ";" . $_REQUEST["subdomain"]);
	  if (getVar('domain', DOMAIN) == '') die("<p>Debe especificar el nombre de dominio</p>

	<footer>
		<h5>© 2015-2016 all rights reserved Desing By <a target='_Blank' href='http://webpuro.net'>WebPuro</a></h5>
	</footer>

	  	");
	}
	else {
	  // open file with domains list
	  $doms = @file(INPUT_FILE);
	  if (!$doms) {
		// file does not exist, show input form
		echo "
	<p>No se puede encontrar el archivo de entrada con la información subdominios. Está bien si no va a crear subdominios de archivo.<br>
	Consejo: campo vacío dejar de utilizar el valor predeterminado que ha especificado en el código del script.<br></p>
	<form method='post'>
	<table>
		<tr>
			<td>Subdominio:</td>
			<td><input type='text' name='subdomain'></td>
		</tr>
		<tr>
			<td>Dominio:</td>
			<td><input type='text' name='domain'></td>
		</tr>
		<tr>
			<td>cPanel Usuario:</td>
			<td><input type='text' name='cpaneluser'></td>
		</tr>
		<tr>
			<td>cPanel Contraseña:</td>
			<td><input type='password' name='cpanelpass'></td>
		</tr>
		<tr>
			<td>cPanel Skin:</td>
			<td><input type='text' name='cpanelskin'></td>
		</tr>
		<tr>
			<td></td>
			<td><input type='submit' value='Crear Subdominio'></td>
		</tr>
	</table>
	 
	</form>

	<footer>
		<h5>© 2015-2016 all rights reserved Desing By <a target='_Blank' href='http://webpuro.net'>WebPuro</a></h5>
	</footer>

	";
		die();
	  }
	}

	// create subdomain
	function subd($host,$port,$ownername,$passw,$request) {

	  $sock = fsockopen('localhost',2082);
	  if(!$sock) {
		print('Socket error');
		exit();
	  }

	  $authstr = "$ownername:$passw";
	  $pass = base64_encode($authstr);
	  $in = "GET $request\r\n";
	  $in .= "HTTP/1.0\r\n";
	  $in .= "Host:$host\r\n";
	  $in .= "Authorization: Basic $pass\r\n";
	  $in .= "\r\n";
	 
	  fputs($sock, $in);
	  while (!feof($sock)) {
		$result .= fgets ($sock,128);
	  }
	  fclose( $sock );

	  return $result;
	}

	foreach($doms as $dom) {
	  $lines = explode(';',$dom);
	  if (count($lines) == 2) {
		// domain and subdomain passed
		$domain = trim($lines[0]);
		$subd = trim($lines[1]);
	  }
	  else {
		// only subdomain passed
		$domain = getVar('domain', DOMAIN);
		$subd = trim($lines[0]);
	  }
	  // http://[domainhere]:2082/frontend/x/subdomain/doadddomain.html?domain=[subdomain here]&rootdomain=[domain here]
	  $request = "/frontend/$cpanel_skin/subdomain/doadddomain.html?rootdomain=$domain&domain=$subd";
	  $result = subd('localhost',2082,$cpaneluser,$cpanelpass,$request);
	  $show = strip_tags($result);
	  echo $show;
	}

	?>
	
</body>
</html>