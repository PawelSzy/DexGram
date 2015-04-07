<?php 

include 'wyswietl_tresc_trait.php';

	class pobierz_info_o_obrazie_JSON extends CI_Controller
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

		public function index ($page_name ="")
		{
			define("ILOSC_OBRAZOW_NA_GLOWNEJ", 5);
			$this->load->model('obrazy');

 			$obrazy = $this->obrazy->pobierz_obrazy(ILOSC_OBRAZOW_NA_GLOWNEJ);
 			echo  json_encode( $obrazy );
		}
	}
?>	
