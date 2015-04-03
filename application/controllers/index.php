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

			?>
 			<script type="text/javascript">
 				var js_base_url = '<?php echo base_url() ; //musi byc zaladowany "url_helper" aby uzyc tej funckji?>';	
 			</script>
			<?php			
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
			define("ILOSC_ARTYKULOW_NA_GLOWNEJ", 5);

			$data['title'] = "LightCMS Pawel test";
 			$data['content'] = "";
 			$nazwy_obrazow = ['obraz.jpg','zielony.jpg'];
 			foreach ($nazwy_obrazow as $obraz) {
	 			$adres_obrazu = base_url('image/');
	 			$adres_obrazu .="/".$obraz;
				$data['content'] .= $this->utworz_div_obrazu($adres_obrazu);
 			}

 

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

var utworz_div_obrazu = function(adres) {
	return "<div class='image_div'>"+"<img src="+"'"+adres+"'"+"class ='zdjecie_srednie'>"+"</div>";	
}

var wyswietl_obrazy = function(key, data) {
	var wyswietl_obraz = function(row, data) {
		console.log( utworz_div_obrazu( js_base_url + data.nazwa_pliku ));
	};
	console.log(data);
	$.each(data,wyswietl_obraz);	
}

$(document).ready(function(){
	console.log("ready");
	console.log(js_base_url);
	//js_base_url musi byc zdefiniowane w srodku php controlera - aby skorzystac 
	// helpera base_url()
	$.getJSON(js_base_url +"js/baza_images.js", function(data){
		$.each(data, wyswietl_obrazy);
	}).fail(function( jqxhr, textStatus, error ) {
                    var err = textStatus + ', ' + error;
                    console.log( "Request Failed: " + err);
              });

	console.log("ready2");
})

</script>
