<?php 



	class Zaloguj extends CI_Controller
	{

		public function __construct()
		{
			parent::__construct();
			$this->load->model('autor');
			#require 'password.php';
		}

		public function index()
		{
			$data['title'] = "Zaloguj";
			$this->load->helper('url');
 			
 			$this->load->helper('form');
 			
 			$data['header'] = anchor("", "DexGram" );
 			$this->parse_zaloguj_page($data);

		}

		public function logowanie() 
		{
			$this->load->library('form_validation');
			$this->lang->load('form_validation', 'polski');
			$this->load->helper('url');
			$data['header'] = anchor("", "DexGram" );

			$haslo = $this->input->post('haslo');
			$autor = $this->input->post('autor');



			//validacja form
			$list_autorow = $this->autor->lista_autorow();

			$this->form_validation->set_rules('autor', 'lang:Login', 'required|xss_clean');
			$this->form_validation->set_rules('haslo', 'lang:haslo', 'required|xss_clean');
			if ($this->form_validation->run() == FALSE)
			{
				//nieudana walidacja
				$this->parse_zaloguj_page($data);
			}
			else
			{
				//udana walidacja
				$dane_autora = $this->autor->pobierz_autora( $autor );
				if ( empty( $dane_autora )) 
				{
						echo "Login nie istnieje, wpisz ponownie wlasciwy login";
						$this->parse_zaloguj_page($data);
				}
				else 
				{
					//autor istnieje sprawdz haslo
					$passwordHash = $dane_autora[0]['hash'];
					if ( password_verify($haslo, $passwordHash))
					{
						echo "zostales zalogowany";
						$dane_sesji = array(
		                   'autor'  => $dane_autora[0]['autor'],
		                   'zalogowany' => TRUE,
		                   'uprawnienia' => $dane_autora[0]['uprawnienia'],
		                   'autor_id' => (int)$dane_autora[0]['id']
		               );
						$this->session->set_userdata($dane_sesji);	
						#var_dump( $dane_sesji['autor_id'] );
						redirect('', 'refresh');
					}
					else 
					{
						echo "nieudane logowanie, zle haslo";
						$this->parse_zaloguj_page($data);
					}
				}	
			}	
		}

		private function parse_zaloguj_page($data)
		{
			$this->load->library('parser');
			$this->load->helper('form');

			//<form class="form_vraper" action="../index.php/zaloguj/logowanie" method='post' accept-charset="utf-8">

			$attributes = array('class' => 'form_vraper');
			$data['form_start'] = form_open('zaloguj/logowanie', $attributes);

			$this->parser->parse('head', $data);
			#$this->load->view('head', $data);
			$this->load->view('body_start');
			$this->parser->parse('header', $data);

			$this->parser->parse('zaloguj', $data);

			$this->load->view('stopka');
			$this->load->view('body_end');
		}
		
	}

?>
