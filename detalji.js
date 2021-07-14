req;

function promijeniBoju(moj_red_tablice){
	moj_red_tablice.style.backgroundColor = "#4CAF50";

}
function vratiBoju(moj_red_tablice){
	moj_red_tablice.style.backgroundColor="";
}

function loadXMLDoc(url){
	document.getElementById("detalj").style.visibility="visible";
	if(window.XMLHttpRequest){
		req=new XMLHttpRequest();
		
		}
	else{
		req=new ActiveXObject("Microsoft.XMLHTTP");
	}
	req.open("GET",url,true);
	req.send(null);
	document.getElementById("detalj").innerHTML='<img src="slike/loading.gif" alt="" />';
	req.onreadystatechange=function(){
		if(req.readyState==4){
			if(req.status==200){
				document.getElementById("detalj").innerHTML=req.responseText;
			}
			else alert("Nije vraceno 200ok!");
		}
	};
}














