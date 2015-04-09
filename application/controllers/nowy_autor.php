<?php 



	class Nowy_autor extends CI_Controller
	{

		public function __construct()
		{
			parent::__construct();
			$this->load->model('autor');
			$this->lang->load('form_validation', 'polski');
			#require 'password.php';
		}

		public function index()
		{

			$data['title'] = "Nowy autor";
			$this->load->helper('url');
 			
 			$this->load->helper('form');
 			
 			$data['header'] = anchor("", "DexGram" );
 			$this->parse_nowy_autor_page($data);
		}

		public function zapisz() 
		{
			// utworz nowego autora w bazie danych
			$this->load->library('form_validation');
			$this->load->helper('url');
			$haslo = $this->input->post('haslo');
			$data['header'] = anchor("", "DexGram" );

			//validacja form
			$this->form_validation->set_rules('autor', 'lang:Login', 'required|callback_alpha_dash_space|is_unique[autorzy.autor]|xss_clean');
			$this->form_validation->set_rules('haslo', 'lang:haslo', 'required|callback_alpha_dash_space|xss_clean');
			if ($this->form_validation->run() == FALSE)
			{
				//nieudana walidacja 
				$this->parse_nowy_autor_page($data);
			}
			else 
			{
				//udana walidacja
				$autor = $this->input->post('autor');
				$passwordHash = password_hash($haslo, PASSWORD_DEFAULT);

				$nowy_autor = array 
				(
					'autor' => $autor,
					'hash'	=> $passwordHash,
					'uprawnienia' => "w"
				);

				// sprawdz czy istnieje autor
				if ( $this->autor->czy_autor_istnieje($autor) == False)
				{
					$this->autor->zapisz($nowy_autor);
					echo "utworzono nowego autora\n";
				}

				//zaloguj sie
				$dane_autora = $this->autor->pobierz_autora( $autor );
				if ( password_verify($haslo, $passwordHash))
				{
					echo "zostales zalogowany\n";
					$dane_sesji = array(
	                   'autor'  => $dane_autora[0]['autor'],
	                   'zalogowany' => TRUE,
	                   'uprawnienia' => $dane_autora[0]['uprawnienia'],
	                   'id_autora' => (int)$dane_autora[0]['id']
	               );
					$this->session->set_userdata($dane_sesji);
				}
				redirect('', 'refresh');
				echo "utworzono nowego autora\n";
			}
		}


		public function  alpha_dash_space($str)
		{
		    return ( ! preg_match("/^([-a-z0-9_ ])+$/i", $str)) ? FALSE : TRUE;
		} 		
		
		private function parse_nowy_autor_page($data)
		{
			$this->load->library('parser');
			$this->load->helper('form');

			$attributes = array('class' => 'form_vraper');
			$data['form_start'] = form_open('nowy_autor/zapisz"', $attributes);

			$this->parser->parse('head', $data);
			#$this->load->view('head', $data);
			$this->load->view('body_start');
			$this->parser->parse('header', $data);

			$this->parser->parse('nowy_autor_form', $data);

			$this->load->view('stopka');
			$this->load->view('body_end');

		}

	}

?>
