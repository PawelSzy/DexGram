
// var getJson = $.getJSON("baza_images.js", function(image_data){
// 	console.log("Json");
// });


//////////////////// CREATE URL helpers /////////////////////////////////////////////	
	var url_pliku_glownego ="index.php"
	var url_do_podstrony = url_pliku_glownego+"/index/index"
	var url_do_JSON = "index.php/"+"pobierz_info_o_obrazie_JSON" + "/index/";

	var page_url = window.location.href;
	var base_url =  page_url.substr(0, page_url.lastIndexOf(url_pliku_glownego));
	var url_subpage = page_url.substr(page_url.lastIndexOf(url_pliku_glownego) +url_pliku_glownego.length);
	var koncowka_url = page_url.substr(page_url.lastIndexOf(url_pliku_glownego))
	var podstrona = page_url.substr(page_url.lastIndexOf(url_do_podstrony)+url_do_podstrony.length+1)


//////////////////// End of CREATE URL helpers /////////////////////////////////////////////


//////////////////// Utworz stale /////////////////////////////////////////////
var iloscObrazowNaGlownej = 5;
//////////////////// Utworz stale /////////////////////////////////////////////

var utworz_div_obrazu = function(adres, alt, url) {
	var $link = $("<a></a>");
	$link.attr("href", url);
	
	var $obraz = $("<img>");
	$obraz.attr("src", adres).attr("class", "zdjecie_srednie");
	if ( !( typeof alt === "undefined" || alt === null )) {
		$obraz.attr("alt", alt);
	}
	
	$link.append($obraz);
	return "<div class='image_div'>"+$link.get(0).outerHTML+"</div>";	

	// return "<div class='image_div'>"+$obraz.get(0).outerHTML+"</div>";	
}

var wyswietl_obraz = function(row, data) {
	var url = base_url + url_do_podstrony +"/"+ data.nazwa_pliku;

	if (data.czy_lokalny === 1) {
		var adres_zdjecie = base_url +"image/"+ data.nazwa_pliku+ data.rozszerzenie;
	} else {
		var adres_zdjecie = data.url_pliku;
	};
	var div =  utworz_div_obrazu( adres_zdjecie, data.alt_text, url);

	$("#tresc").append(div);
};

var wyswietl_obrazy = function(data) {
	$.each(data,wyswietl_obraz);	
}




/************************************
Wyswietl obrazy przy zaladowaniu nowej strony
dla glownej strony 
i dla podstrony 

************************************/
$(document).ready(function(){
	// console.log("page_url", page_url);
	// console.log("base_url", base_url);
	// console.log("subpage "+url_subpage);
	// console.log("url_subpage", url_subpage);
	// console.log("koncowka_url", koncowka_url);
	// console.log("url_do_podstrony", url_do_podstrony);
	// console.log("podstrona", podstrona);

	if (koncowka_url == url_pliku_glownego || koncowka_url == url_pliku_glownego+"/" || koncowka_url == url_pliku_glownego + "/index" ){
		//glowna strona
		console.log("glowna_strona");

		$.getJSON(base_url + url_do_JSON, function(data){
		console.log(data);
		wyswietl_obrazy(data);
	}).fail(function( jqxhr, textStatus, error ) {
                    var err = textStatus + ', ' + error;
                    console.log( "Request Failed: " + err);
        });	

	/***********************************
	//Funkcja
	//Dodaj nowe obrazu przy skrolowaniu 
	//w dol 
	***********************************/
	
	$(window).scroll(function() {
	    if($(window).scrollTop() == $(document).height() - $(window).height()) {
	           // ajax call get data from server and append to the div
	    		console.log("scroll");
	    		var ilosc_nowych_obrazow = 5;
	    		
	    		var offset = iloscObrazowNaGlownej.toString(); 
	    		//console.log("offset"+offset);
	    		var getJSON_URL = base_url + url_do_JSON +"offset/" + offset +"/" + ilosc_nowych_obrazow.toString();
	    		console.log("JSURL "+getJSON_URL);
	    		$.getJSON(getJSON_URL, function(data){
	    			console.log(data);
	    			wyswietl_obrazy(data);
	    		});
	    		iloscObrazowNaGlownej += ilosc_nowych_obrazow;
	    };
	});


	} else {
		//podstrona
		console.log("podstrona");
		html_do_przeslania = base_url + url_do_JSON+ podstrona;
		$.getJSON(html_do_przeslania, function(data){
			document.title = data[0].nazwa_pliku;
			wyswietl_obrazy(data);
			//$.each(data, wyswietl_obrazy);

		}).fail(function( jqxhr, textStatus, error ) {
                    var err = textStatus + ', ' + error;
                    console.log( "Request Failed: " + err);
         });
	}

})
