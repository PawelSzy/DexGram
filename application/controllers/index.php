<?php 

include 'wyswietl_tresc_trait.php';

	class Index extends CI_Controller
	{
		use wyswietl_tresc_trait { wyswietl_tresc as private; utworz_log_block as private; utworz_boczne_przyciski as private;}

		public function __construct()
		{
			parent::__construct();
			$this->load->library('session');
			#$this->load->model('artykuly');
			$this->load->helper('typography');
			$this->load->helper('url');
			$this->load->helper('html');	
		}

		public function index($page_name ="")
		{
			//wybor czy pojedyncza strona
			//czy glowna strona z najnowszymi artykulami
			if ( $page_name =="" )
			{
				$this->main_page();
				 
			}
			else {

				$this->wyswietl_strone($page_name);
			}


		}

		private function wyswietl_strone($page_name ="") 
		{
 			$data['content'] = "";


/*			$data[$page_name] = $this->artykuly->pobierz_artykul($page_name);

			if ( empty($data[$page_name]) )
			{
				show_404();
				return;
			} */

			// $data['title'] = $data[$page_name][0]['tytul'];
			// $data['artykul_id'] = $data[$page_name][0]['id'];
			// #$data['boczny_pasek'] = ' <input type="submit" class="przycisk" name="nowa_strona" value="Nowa strona"  >' ;


			// foreach ($data[$page_name] as $artykul) 
			// {
			// 	$text = auto_typography($artykul['tekst']);
				
			// 	$autor =$artykul['autor'];
			// 	$tytul = $artykul['tytul'];
			// 	$this->load->helper('url');

			// 	$data['content'] = $data['content']."<h1>".$tytul."</h1>";
			// 	$data['content'] = $data['content'].$text;
   //  	        $data['content'] = $data['content']."<br>"."Autor:"."<br>".$autor."<br>";
   //  	        $data['content'] = $data['content']."<hr>";
			// }

			$this->wyswietl_tresc( $data); //zaladowany trait wyswietl tresc
		}

		private function main_page() 
		{
			define("ILOSC_OBRAZOW_NA_GLOWNEJ", 5);

			$data['title'] = "DexGram Pawel test";
 			$data['content'] = "";


 			// $nazwy_obrazow = ['obraz.jpg','zielony.jpg'];
 			// foreach ($nazwy_obrazow as $obraz) {
	 		// 	$adres_obrazu = base_url('image/');
	 		// 	$adres_obrazu .="/".$obraz;
				// $data['content'] .= $this->utworz_div_obrazu($adres_obrazu);
 			// }

 

			// $artykuly = $this->artykuly->pobierz_artykuly(ILOSC_ARTYKULOW_NA_GLOWNEJ);

			// foreach ($artykuly as $artykul) 
			// {
			// 	$text = auto_typography($artykul['tekst']);
				
			// 	$autor =$artykul['autor'];
			// 	$tytul = $artykul['tytul'];

			// 	$data['content'] = $data['content']."<h1>".anchor("/index/index/".$tytul, $tytul )."</h1>";
			// 	$data['content'] = $data['content'].$text;
   //  	        $data['content'] = $data['content']."<br>"."Autor:"."<br>".$autor."<br>";
   //  	        $data['content'] = $data['content']."<br>"."Link: ".anchor("/index/index/".$tytul, $tytul );
   //  	        $data['content'] = $data['content']."<hr>";
			// }

			// $data['content'] = $data['content']."<br>".anchor("/pokaz_liste", "Pokaz wszystkie artykuly");

			
			$this->wyswietl_tresc($data); //zaladowany trait wyswietl tresc

		}

		private function utworz_div_obrazu($adres) 
		{
			return "<div class='image_div'>"."<img src="."'".$adres."'"."class ='zdjecie_srednie'>"."</div>";
		}


	}

?>			
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js" >
</script>
<script type="text/javascript">
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
		wyswietl_obrazy(data);
	}).fail(function( jqxhr, textStatus, error ) {
                    var err = textStatus + ', ' + error;
                    console.log( "Request Failed: " + err);
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

</script>
