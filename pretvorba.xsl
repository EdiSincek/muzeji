<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
	<xsl:output method="xml" indent="yes" doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd" doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN"/>
	<xsl:template match="/">
	<html xmlns="http://www.w3.org/1999/xhtml">

	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
		<title> Podaci </title>
		<link rel="stylesheet" type="text/css" href="dizajn.css"></link>
	</head>

	<body>
		<div class="zaglavlje">
			<h1 class="naslov"><b>Podaci</b></h1>
			<a href="index.html"><img class="skulptura" src="slike/skulptura.png" alt="slika" width="200" height="200"></img></a>
		</div>
	
		<!--   ............................................................................................ -->
	
		<div class="navigacija">
				<ul class="lista">
						<li class="izbor"><a class="link" href="index.html" target="_blank"><b>POČETNA STRANICA</b></a></li> 	
					
						<li class="izbor"><a class="link" href="obrazac.html" target="_blank"><b>PRETRAŽIVANJE MUZEJA</b></a></li>

						<li class="izbor"><a class="link" href="podaci.xml"><b> PODACI</b></a> </li>
						
						<li class="izbor"><a class="link" href="http://www.fer.unizg.hr/predmet/otvrac" target="_blank"><b>STRANICA PREDMETA OR</b></a></li> 
					
						<li class="izbor"><a class="link"  href="http://www.fer.hr" target="_blank"><b>SJEDIŠTE FER-a</b></a></li> 		
						
						<li class="izbor"><a class="link" href="mailto:es50806@fer.hr" target="_blank"><b>EMAIL AUTORA</b></a></li>
					
						
				</ul>
		</div>

		<!--   ............................................................................................ -->
		<div class="tijelo">	
			<table class="podaci">
				<tr class="podaci">
					<th> Ime </th>
					<th> Lokacija </th>
					<th> Godina osnutka </th>
					<th> Izložbenih primjeraka </th>
					<th> Posjeta godišnje(2019) </th>
					<th> Kontakt </th>
				</tr>
				<xsl:for-each select="/muzeji/muzej">
				<tr>
					<td class="podaci"> <xsl:value-of select="ime" /> </td>
					<td class="podaci"> <xsl:value-of select="lokacija/grad"/> </td>
					<td class="podaci"> <xsl:value-of select="god_osnivanja"/> </td>
					<td class="podaci"> <xsl:value-of select="predmeti"/> </td>
					<td class="podaci"> <xsl:value-of select="posjeta_god"/> </td>
					<td class="podaci"> <xsl:element name="a"> <xsl:attribute name="href"> <xsl:value-of select="kontakt[@vrsta='website']"/> </xsl:attribute> <xsl:value-of select="kontakt[@vrsta='website']"/></xsl:element> 
						 <br/> <xsl:value-of select="kontakt[@vrsta='email']"/></td>
				
				</tr>
				</xsl:for-each>
			</table>
					
		
		</div>	
		<!--   ............................................................................................ -->
		<div class="podnozje">
			<p class="podnozje"> Autor: Edi Šincek </p>
		</div>
	
	</body>
	</html>
</xsl:template>
</xsl:stylesheet>
	