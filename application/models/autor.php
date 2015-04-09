<?php 

/**
* 
*/
class Autor extends CI_Model
{
	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function pobierz_autora( $autor ) 
	{

		$this->db->where( 'autor', $autor );
		$query = $this->db->get('autorzy');
		return $query->result_array();
	} 

	public function zapisz($dane)
	{
		$this->db->insert('autorzy', $dane);
		return $this->db->insert_id();
	}

	public function czy_autor_istnieje( $autor )
	{
		$this->db->where('autor', $autor);
		$this->db->select('autor');
		$query = $this->db->get('autorzy');
		$autor = $query->result_array();
		return ( empty( $autor ) ? False : True );
	}

	public function lista_autorow() 
	{
		$this->db->select('autor');
		$query = $this->db->get('autorzy');
		$tablica=  $query->result_array();
		$return_array = array();
		foreach ($tablica as $key => $value) 
		{
			$return_array[] = $value['autor'];
		}	
		return $return_array;	
	}
}	

?>

