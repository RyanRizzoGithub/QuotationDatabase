<!-- 
This is the home page for Final Project, Quotation Service. 
It is a PHP file because later on you will add PHP code to this file.

File name quotes.php 
    
Authors: Rick Mercer and Ryan Rizzo
-->

<!DOCTYPE html>
<html>
<head>
<title>Quotation Service</title>
<link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body onload="showQuotes()">

<h1>Quotation Service</h1>
<a href="./addQuote.html" ><button>Add Quote</button></a>

<div id="quotes"></div>

<script>
var element = document.getElementById("quotes");
function showQuotes() {
	var ajax = new XMLHttpRequest();
	ajax.open("GET", "controller.php?todo=getQuotes", true);
	ajax.send();
			
	ajax.onreadystatechange = function () {
		if(ajax.readyState == 4 && ajax.status == 200) {
			var str = ajax.responseText;
			element.innerHTML = str;
		}
	};
}

</script>

</body>
</html>