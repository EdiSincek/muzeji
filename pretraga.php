<?php
	error_reporting(E_ALL);
	ini_set("allow_url_include",true);
	set_time_limit(100);
	include_once('funkcije.php');
	$dom=new DOMDocument();
	$dom->load('podaci.xml');
	$xp=new DOMXPath($dom);
	$xp->registerNamespace('php','http://php.net/xpath');
	$upit=upit();
	$results=$xp->query($upit);

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<title> Pretraživanje muzeja </title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link rel="stylesheet" type="text/css" href="dizajn.css">
		<link rel="stylesheet" href="https://unpkg.com/leaflet@1.5.1/dist/leaflet.css"/>
		<script src="https://unpkg.com/leaflet@1.5.1/dist/leaflet.js"></script>
		<script type="text/javascript" src="detalji.js"></script>
	</head>
	
	<body>
	
	<div class="zaglavlje">
		<h1 class="naslov"><b>Rezultati pretraživanja</b></h1>
		<a href="index.html"><img class="skulptura" src="slike/skulptura.png" alt="slika" width="200" height="200"></a>
	</div>
	
	<!--   ............................................................................................ -->
	
	<div class="navigacija">
		<ul class="lista">
			
					<li class="izborRez"><a class="link" href="index.html" target="_blank"><b>POČETNA STRANICA</b></a></li> 	
					
					<li class="izborRez"><a class="link" href="obrazac.html" target="_blank"><b>PRETRAŽIVANJE MUZEJA</b></a></li> 
						
					<li class="izborRez"><a class="link" href="podaci.xml" target="_blank"><b> PODACI </b></a> </li>
						
					<li class="izborRez"><a class="link" href="http://www.fer.unizg.hr/predmet/otvrac" target="_blank"><b>STRANICA PREDMETA OR </b></a></li> 
					
					<li class="izborRez"><a class="link"  href="http://www.fer.hr" target="_blank"><b>SJEDIŠTE FER-a</b></a></li> 		
					
					<li class="izborRez"><a class="link" href="mailto:@fer.hr" target="_blank"><b>EMAIL AUTORA</b></a></li>

		</ul>
	</div>
	
	<!--   ............................................................................................ -->
	
	<div id="detalj" class="detalji">
		<br/>
		<br/>
		<br/>
		<br/>
		<br/>
	</div>
	
	<!--   ............................................................................................ -->
	
	<div class="tijeloRez">
		<table class="rezultati">
			<tr>
				<th class="rezultati"> Ime </th>
				<th class="rezultati"> Adresa </th>
				<th class="rezultati"> Godina osnivanja </th>
				<th class="rezultati"> Sazetak </th>
				<th class="rezultati"> Akcija </th>
			</tr>
			
			<?php
				foreach($results as $rez){
					$id = $rez->getAttribute('id');
					$array=array();
					$array[]=getWikimediaJsonArray($id);
					$source=getImageSource($array);
					$coor=getCoordinates($array);
					if($coor!='greska'){
						$coor1=explode(',',$coor);
						$lat1=$coor1[0];
						$lon1=$coor1[1];
					}
					else{
						$lat1=0;
						$lon1=0;
					}
					
					$sazetak=getExtract($array);
					$sazetak=mb_strimwidth($sazetak,0,40,"...");
					$adresa=getMediaWikiAddress($id);
					$coorNom=getNominatimCoordinates($adresa);
					if($coorNom!='greska'){
						$coor2=explode(',',$coorNom);
						$lat2=$coor2[0];
						$lon2=$coor2[1];
					}
					else{
						$lat2=0;
						$lon2=0;
					}
					$ime = getEl('ime',$rez);
					
	

		
					
					
			?>
			<tr onmouseover="promijeniBoju(this)" onmouseout="vratiBoju(this)">
			<td id="demo" class="rezultati"> <?php echo getEl('ime',$rez); ?> </td>
			<td id="adresa" class="adresa"> <?php echo $adresa;?> </td>
			<td class="koordinate"> <?php echo getEl('god_osnivanja',$rez);?></td>
			<td class="sazetak"> <?php echo $sazetak; ?></td>			
			<td class="rezultati"> <?php $url="http://localhost/lab5/detalji.php?id="."".$rez->getAttribute("id")."&show=simple";?>
									<button id="detaljiBtn" href="#" onClick="loadXMLDoc('<?php echo $url?>'); generirajKartu('<?php echo $lat1?>','<?php echo $lon1?>','<?php echo $lat2?>','<?php echo $lon2?>','<?php echo $ime?>','<?php echo $adresa?>');">Detalji</button><br/> </td>

			</tr>
			
			<?php
				}
			?>
		</table>
	</div>
	
	<!--   ............................................................................................ -->
	<div id="map" class="map">			
			
	</div>
	<button id="gumb" class="closeBtn">Zatvori detalje </button>
	
	<script>
		var marker1={},marker2={};
		var polyline={};
		var map = L.map('map').setView([50.84673,4.35247],12);
		var OpenStreetMap_Mapnik = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
									maxZoom: 19,
										attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
											}).addTo(map);
		
		function generirajKartu(lat1,lon1,lat2,lon2,ime,adresa){
			document.getElementById("map").style.visibility="visible";
			document.getElementById("gumb").style.visibility="visible";
			map.setView([lat1,lon1],8);
			if((lat1==0&&lon1==0) | (lat2==0&&lon2==0) )alert("Greska s koordinatama");
			else{
				if(marker1!=undefined) map.removeLayer(marker1);
				if(marker2!=undefined) map.removeLayer(marker2);
				if(polyline!=undefined) map.removeLayer(polyline);
				marker1=L.marker([lat1,lon1]).addTo(map);
				marker2=L.marker([lat2,lon2]).addTo(map);
				const coordinates=[
					[lat1, lon1],
					[lat2, lon2]
				];
				const configObject={ color:'red'};
				polyline= L.polyline(coordinates,configObject).addTo(map);
				map.fitBounds(polyline.getBounds());
				marker1.bindPopup('Wikimedia<br/>Ime:'+ime+ '<br/>Širina: '+lat1+'<br/>Dužina: '+lon1+'<br/>Adresa: '+adresa).openPopup();
				marker2.bindPopup('Nominatim<br/>Ime:'+ime+ '<br/>Širina: '+lat2+'<br/>Dužina: '+lon2+'<br/>Adresa: '+adresa).openPopup();
			
			}
			
		
			
		}
		var btn=document.getElementById("gumb");
		btn.addEventListener("click",function(){
			document.getElementById("map").style.visibility="hidden";
			document.getElementById("detalj").style.visibility="hidden";
			document.getElementById("gumb").style.visibility="hidden";
		});
		
		
		
	</script>
	
	</body>
</html>
		