<?php 

/**
* 
*/
class Obrazy extends CI_Model
{
	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function pobierz_obrazy( $ilosc_obrazow=0 )
	{

		
		$this->db->order_by("data", "desc"); 
		if ( $ilosc_obrazow === 0 ) {
			$query = $this->db->get('obrazy');
		} else {
			$query = $this->db->get('obrazy', $ilosc_obrazow);
		}
		
		$return_array = $this->dodaj_autora_do_tabeli( $query->result_array() );
		return $return_array;	
	}

	public function pobierz_obraz( $nazwa_obrazu ) 
	{

		$this->db->where('nazwa_pliku',urldecode($nazwa_obrazu));
		$query = $this->db->get('obrazy');
		$return_array = $this->dodaj_autora_do_tabeli( $query->result_array() );
		return $return_array;	
	} 

	public function pobierz_id_autora_obrazu ( $nazwa_obrazu )
	{
		$this->db->where('tytul',urldecode($nazwa_obrazu));
		$this->db->select('id_autora');
		$query = $this->db->get('obrazy');

		return $query->result_array()[0]['id_autora'];
	}

	public function pobierz_tytuly()
	{
		$this->db->order_by("data", "desc"); 
		$this->db->select('tytul, autor_id');
		$query = $this->db->get('artykuly');

		$return_array = $this->dodaj_autora_do_tabeli( $query->result_array() );
		return $return_array;		
	}	


	public function zapisz( $dane )
	{
		$this->db->insert('obrazy', $dane);
		return $this->db->insert_id();
	}

	public function zmien_dane($dane)
	{
		$stary_tytul = urldecode(  $dane['stary_tytul'] );
		$autor_id =  $dane['obraz']['autor_id'];
		$where_array = array('autor_id' => $autor_id, 'tytul' => $stary_tytul);
		$this->db->where($where_array);
		$this->db->update('obrazy', $dane['obraz']);
	}

	public function usun_obraz( $id ) 
	{
		$this->db->delete( 'obrazy', array( 'nazwa_pliku' => $id )); 
	}


	public function zamien_id_na_autora( $id )
	{
		$this->db->select('autor');
		$this->db->where( 'id', $id );
		$query = $this->db->get('autorzy');
		return $query->result_array()[0]['autor']  ;
	}

	private function dodaj_autora_do_tabeli( $tabela )
	{
		//dodaje do tabeli autora tresci na podstawie id_autora
		$new_table =  array();
		foreach ($tabela as $row) 
		{
			
			$row['autor'] = $this->zamien_id_na_autora( $row['id_autora']); 
			array_push($new_table, $row);
		}
		
		return $new_table;
	}


}	

?>

