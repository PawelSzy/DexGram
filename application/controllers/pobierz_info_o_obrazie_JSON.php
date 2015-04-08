<?php 


	class pobierz_info_o_obrazie_JSON extends CI_Controller
	{
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
			if ( $page_name == ""){
				define("ILOSC_OBRAZOW_NA_GLOWNEJ", 5);
				$this->load->model('obrazy');

	 			$obrazy = $this->obrazy->pobierz_obrazy(ILOSC_OBRAZOW_NA_GLOWNEJ);
	 			echo  json_encode( $obrazy );				
			} else {
				$this->load->model('obrazy');

	 			$obrazy = $this->obrazy->pobierz_obraz($page_name);
	 			echo  json_encode( $obrazy );
			}


		}
	}
?>	
