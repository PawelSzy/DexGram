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


			$data[$page_name] = $this->artykuly->pobierz_artykul($page_name);

			if ( empty($data[$page_name]) )
			{
				show_404();
				return;
			} 

			$data['title'] = $data[$page_name][0]['tytul'];
			$data['artykul_id'] = $data[$page_name][0]['id'];
			#$data['boczny_pasek'] = ' <input type="submit" class="przycisk" name="nowa_strona" value="Nowa strona"  >' ;


			foreach ($data[$page_name] as $artykul) 
			{
				$text = auto_typography($artykul['tekst']);
				
				$autor =$artykul['autor'];
				$tytul = $artykul['tytul'];
				$this->load->helper('url');

				$data['content'] = $data['content']."<h1>".$tytul."</h1>";
				$data['content'] = $data['content'].$text;
    	        $data['content'] = $data['content']."<br>"."Autor:"."<br>".$autor."<br>";
    	        $data['content'] = $data['content']."<hr>";
			}

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

	var page_url = window.location.href;
	var base_url =  page_url.substr(0, page_url.lastIndexOf(url_pliku_glownego));
	var url_subpage = page_url.substr(page_url.lastIndexOf(url_pliku_glownego) +url_pliku_glownego.length);
	var koncowka_url = page_url.substr(page_url.lastIndexOf(url_pliku_glownego))

//////////////////// End of CREATE URL helpers /////////////////////////////////////////////

var utworz_div_obrazu = function(adres, alt) {
	var $obraz = $("<img>");
	$obraz.attr("src", adres).attr("class", "zdjecie_srednie");
	if ( !( typeof alt === "undefined" || alt === null )) {
		$obraz.attr("alt", alt);
	}

	return "<div class='image_div'>"+$obraz.get(0).outerHTML+"</div>";	
}

var wyswietl_obraz = function(row, data) {
	//console.log("data.id " + data[0]);
	if (data.czy_lokalny === "1") {
		var div =  utworz_div_obrazu( base_url +"image/"+ data.nazwa_pliku+"."+ data.rozszerzenie, data.alt_text );
	} else {
		var div =  utworz_div_obrazu( data.url_pliku, data.alt_text );
	};
	$("#tresc").append(div);
};

var wyswietl_obrazy = function(data) {
	$.each(data,wyswietl_obraz);	
}


$(document).ready(function(){
	console.log("subpage "+url_subpage);
	console.log("url_subpage", url_subpage);
	console.log("koncowka_url", koncowka_url);


	if (koncowka_url === url_pliku_glownego || koncowka_url === url_pliku_glownego+"/" || koncowka_url === url_pliku_glownego + "/index" ){
		//glowna strona
		console.log("glowna_strona");
		$.getJSON(base_url + "index.php/" +"pobierz_info_o_obrazie_JSON", function(data){
		// $.getJSON(base_url +"js/baza_images.js", function(data){
		wyswietl_obrazy(data);
	}).fail(function( jqxhr, textStatus, error ) {
                    var err = textStatus + ', ' + error;
                    console.log( "Request Failed: " + err);
        });	
	} else {
		//podstrona
		console.log("podstrona");
		//$.getJSON(base_url +"js/baza_images.js", function(data){
		$.getJSON(base_url + "index.php/"+"pobierz_info_o_obrazie_JSON", function(data){
			wyswietl_obrazy(data);
			//$.each(data, wyswietl_obrazy);

		}).fail(function( jqxhr, textStatus, error ) {
                    var err = textStatus + ', ' + error;
                    console.log( "Request Failed: " + err);
         });
	}



	//console.log("ready2");
})

</script>
