<?php 
	class Nowa_strona extends CI_Controller
	{

		public function __construct()
		{
			parent::__construct();
			$this->load->model('obrazy');
			$this->lang->load('form_validation', 'polski');
			$this->load->helper('url');
			$this->load->library('parser');

		}

		public function index($page_name ="")
		{
		
			//Jesli nie przekazujemy danych do funckji opcja tworzenia nowej strony
			if ( $this->session->userdata('autor') === false )
			{
				//Uzytkownik niezalogowany
				redirect('/zaloguj/', 'refresh');
			}


			$data['wyswietl_blad'] = "";
			//przekazana wartosc (nazwa artykulu) do funkcji - edytuj wybrany dokumeny
			if( $page_name !== "" ){
				$artykuly = $this->artykuly->pobierz_artykul( $page_name);
				$data['tytul_artykulu'] = $artykuly[0]['tytul'];
				$data['tekst'] = $artykuly[0]['tekst'];
				#var_dump($data['tytul_artykulu']);
				#var_dump($data['tekst']);
			} else {
				//utworz nowy artykul
				$data['opis_obrazu'] ="";
				//$data['tekst'] ="";
			}

			$data['title'] = "Nowa strona";

			$this->parse_page($data, $page_name);
		}

		public function zapisz($page_name="") 
		{
			//Zasada nazwa przekazywana do funckji to nazwa artukulu
			$this->load->library('form_validation');
			$this->load->database();

			$this->load->helper('url');


			if ( $this->session->userdata('autor') === false )
			{
				//Uzytkownik niezalogowany
				redirect('/zaloguj/', 'refresh');
			}	
			else 
			{
				// Uzytkownik zalogowany
             

				$obraz = array 
				(

					'id_autora' => $this->session->userdata('autor_id'), 
					'alt_text' => $this->input->post('opis')

				);


				//validacja form
				if ( $page_name == "") {
					//nowy artykul
					$this->form_validation->set_rules('opis', 'lang:tytul', 'required|callback_alpha_dash_space|xss_clean');
				} else {
					//istniejacy artykul
					//$this->form_validation->set_rules('tytul', 'lang:tytul', 'required|callback_alpha_dash_space|xss_clean|');
				}

				if ($this->form_validation->run() == FALSE)
				{
					//nieudana walidacja
					$this->parse_page($obraz, $page_name);
					#redirect('../index.php/edytuj/index/'.urldecode($page_name), 'refresh');
				}	
				else 
				{			
					//Udana walidacja danych
					if ( $page_name !== "" )
					{	//zmien dane w isniejacej stronie

						// if ( !$this->czy_autor_artykulu($obraz['tytul'] ))
						// { 
						// 	$dane['wyswietl_blad'] = "tylko autor artykulu moze go modyfikować";
						// 	$dane['tytul_artykulu'] = $artykul['tytul'];
						// 	$dane['tekst'] =  $artykul['tekst'];							
						// 	$this->parse_page($dane, $page_name);
						// 	return;	
						// }		
						// $dane['obraz'] = $obraz;
						// $dane['stary_tytul'] = $page_name;
						// $this->obrazy->zmien_dane($dane);				
						// echo "informacja zapisana";
						// redirect('', 'refresh');
					}
					else 
					{	// utworz nowa strone
						$zapisz_plik_config = $this->upload_data();
						//var_dump($zapisz_plik_config);
						//return;
						$dane_zapis_pliku = array 
						(
							'url_pliku' 	=> base_url()."image/".$zapisz_plik_config["upload_data"]['client_name'],
							'id_autora' 	=> $this->session->userdata('autor_id'), 
							'nazwa_pliku' 	=> $zapisz_plik_config["upload_data"]['raw_name'],
							"czy_lokalny"	=> 1,
							"rozszerzenie" 	=> $zapisz_plik_config["upload_data"]['file_ext']
							//'tekst'	=> $this->input->post('tresc')
						);
						$obraz = array_merge($obraz, $dane_zapis_pliku);

						$this->obrazy->zapisz($obraz);
						echo "informacja zapisana";
						redirect('', 'refresh');	




						// Array
						// (
						//     [file_name]    => mypic.jpg
						//     [file_type]    => image/jpeg
						//     [file_path]    => /path/to/your/upload/
						//     [full_path]    => /path/to/your/upload/jpg.jpg
						//     [raw_name]     => mypic
						//     [orig_name]    => mypic.jpg
						//     [client_name]  => mypic.jpg
						//     [file_ext]     => .jpg
						//     [file_size]    => 22.2
						//     [is_image]     => 1
						//     [image_width]  => 800
						//     [image_height] => 600
						//     [image_type]   => jpeg
						//     [image_size_str] => width="800" height="200"
						// )


					}
				}
			}	
		}

		public function  alpha_dash_space($str)
		{
			//pomocniczny return True gdy string zawiera tylko liczby, litery albo spacje
		    return ( ! preg_match("/^([-a-z0-9_ ])+$/i", $str)) ? FALSE : TRUE;
		} 

		private function czy_autor_artykulu($tytul)
		{
			//sprawdcza czy zalogowany uzytkownik jest autorem artykulu
			$id_autora = (int)$this->artykuly->pobierz_id_autora_artykulu($tytul);
			return( $id_autora == $this->session->userdata('autor_id') ) ? TRUE : FALSE;
		}

		private function upload_data() 
		{
			//wpisza plik na serwer
			$zapisz_plik_config =  array(
              'upload_path' 	=> './image/',
              //'upload_url'      => base_url()."image/",
              'allowed_types'   => "gif|jpg|png|jpeg",
              'overwrite'       => FALSE,
              'max_size'        => "1000KB",
              'max_height'      => "768",
              'max_width'       => "1024",
              'remove_spaces'	=> TRUE  
            );

            $this->load->library('upload', $zapisz_plik_config);

	 		if ( ! $this->upload->do_upload())
			{
				$error = array('error' => $this->upload->display_errors());
				echo $error;
				//$this->load->view('upload_form', $error);
				return;
			}
			else
			{
				$data = array('upload_data' => $this->upload->data());
				return $data;
				//$this->load->view('upload_success', $data);
			}  			
		}

		private function parse_page($data, $page_name)
		{
			$this->load->helper('url');
 			$this->load->helper('form');
 			$data['przycisk_zapisz_akcja_do_wykonania'] = base_url()."index.php/nowa_strona/zapisz/".$page_name;
 			
 			//utworz przycisk edytuj
 			$data['header'] = anchor("", "LightCMS" );

			$this->parser->parse('head', $data);
			$this->load->view('body_start');
			$this->parser->parse('header', $data);
			//**************************************************//
			$this->parser->parse('nowa_strona_form', $data);
			//**************************************************//
			$this->load->view('stopka');
			$this->load->view('body_end');
		}
	}

?>
