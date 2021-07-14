<?php 
	
	function lower($string){
		return "translate(" .$string. ", 'ABCDEFGHIJKLMNOPQRSTUVWXYZŠĐČĆŽ', 'abcdefghijklmnopqrstuvwxyzšđčćž')";
	}
	
	
	function upit(){
		$upit = array();
		if(!empty($_REQUEST['ime'])) $upit[] = 'contains(' . lower('ime'). ', "'.mb_strtolower($_REQUEST['ime']).'")'; 
		if(!empty($_REQUEST['god_osnivanja'])) $upit[]='god_osnivanja<'.$_REQUEST['god_osnivanja'];
		if(!empty($_REQUEST['posjeta_god'])) $upit[]='posjeta_god>'.$_REQUEST['posjeta_god'];
		if(!empty($_REQUEST['predmeti'])) $upit[]='predmeti>'.$_REQUEST['predmeti'];
		if(!empty($_REQUEST['drzava'])) $upit[]='contains('.lower('lokacija/drzava').',"'.mb_strtolower($_REQUEST['drzava']).'")';
		if(!empty($_REQUEST['grad'])) $upit[]='contains('.lower('lokacija/grad').',"'.mb_strtolower($_REQUEST['grad']).'")';
		if(!empty($_REQUEST['kontinent'])) $upit[]='contains('.lower('lokacija/kontinent').',"'.mb_strtolower($_REQUEST['kontinent']).'")';
		if(!empty($_REQUEST['tip1'])) $upit[]='contains(karakteristike/@tip1'.',"'.mb_strtolower($_REQUEST['tip1']).'")';
		$xpath = implode(" and ",$upit);
		if(!empty($xpath)) return "/muzeji/muzej[" .$xpath . "]";
		else return "/muzeji/muzej";
	}
	
	
	function getEl($ime,$el){
		return $el->getElementsByTagName($ime)->item(0)->nodeValue;
	}
	
	function getWikimediaJsonArray($id){
		$id=urlencode($id);
		$url='https://en.wikipedia.org/api/rest_v1/page/summary/'.$id;
		$json=file_get_contents($url);
		$array[]=(json_decode($json,true));
		return $array;
	}
	
	function getImageSource($array){
		if((isset($array[0][0]['originalimage']['source']))) return $array[0][0]['originalimage']['source'];
		return 'Greška,nema slike';
		
	}
	function getCoordinates($array){
		if((isset($array[0][0]['coordinates']['lat'])) && (isset($array[0][0]['coordinates']['lon']))){
			$lat=round($array[0][0]['coordinates']['lat'],4);
			$lon=round($array[0][0]['coordinates']['lon'],4);
			$coordinates=$lat. ' , '. $lon;
			return $coordinates;
		}
		return 'greska';
		
		
	}
	
	function getExtract($array){
		if(isset($array[0][0]['extract'])){
			return $array[0][0]['extract'];
		}
		return 'Greska, nema sazetka!';
	}
	
	function getMediaWikiAddress($id){
		$id=urlencode($id);
		$url='https://en.wikipedia.org/w/api.php?action=query&prop=revisions&rvprop=content&rvsection=0&titles='.$id.'&format=json';
		$json=file_get_contents($url);
		$array[]=json_decode($json,true);
		$var=$array[0]['query']['pages'];
		$var2=$var[key($var)]['revisions'][0]['*'];
		$var2=preg_replace('/\s+/',' ',$var2);
		$var2=strstr((strstr($var2,'location')),'=');
		$var2=explode("<ref",$var2);
		$var2=$var2[0];
		$var2=explode("|",$var2);
		$var2=$var2[0];
		$var2=str_replace(array('[',']','=','{{postcode','{{flag',', SE postcode area'),'',$var2);
		return $var2;
	}
	
	function getNominatimCoordinates($adresa){
		$options = array('http' => array('user_agent' => 'es50806@fer.hr'));
		$context = stream_context_create($options);
		$adresa=urlencode($adresa);
		$url='https://nominatim.openstreetmap.org/search?q='.$adresa.'&format=xml';
		$xml=file_get_contents($url,false,$context);
		if($xml == false) return 'greška';
		$simpleXml = new SimpleXMLElement($xml);
		
		if($simpleXml->count()>0){	
			$lat = $simpleXml->place[0]->attributes()->{'lat'};
			$lon = $simpleXml->place[0]->attributes()->{'lon'};
			$lat=round($lat,4);
			$lon=round($lon,4);
			$coor=$lat.' , '.$lon;
			return $coor;
		}
		else return 'greska';
		

	}
?>