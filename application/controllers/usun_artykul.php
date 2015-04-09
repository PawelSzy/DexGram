<?php
	class Usun_artykul extends CI_Controller
	{
		public function __construct()
		{
			parent::__construct();
			$this->load->model('obrazy');
			$this->load->helper('url');
		}

		public function index( $id )
		{
			$this->obrazy->usun_obraz( $id );
			echo "Usunieto artykul";
			redirect('', 'refresh');
			echo "Usunieto artykul";
		}
	}
