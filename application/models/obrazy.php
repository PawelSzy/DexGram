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
		$this->db->where('nazwa_pliku',urldecode($nazwa_obrazu));
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

	public function usun_obraz( $nazwa_pliku ) 
	{
		
		

		//usun plik z folderu image
		$this->load->helper("file");
		$this->load->helper("url");
		$path = $this->podaj_path_do_foldera_image();
		$rozszerzenie = $this->podaj_rozszerzenie_pliku($nazwa_pliku);
		$path_do_pliku = $path."/".$nazwa_pliku.$rozszerzenie;
		
		// var_dump($path_do_pliku);
		if(unlink($path_do_pliku)) {
		     echo 'Usunieto obraz ';
		     //usun z bazy danych
		     $this->db->delete( 'obrazy', array( 'nazwa_pliku' => $nazwa_pliku )); 
		}
		else {
		     echo 'errors occured';
		}
	}


	public function zamien_id_na_autora( $id )
	{
		$this->db->select('autor');
		$this->db->where( 'id', $id );
		$query = $this->db->get('autorzy');
		return $query->result_array()[0]['autor']  ;
	}

	private function podaj_path_do_foldera_image()
	{
		$root = $_SERVER['DOCUMENT_ROOT'];
		$script_name = 	$_SERVER['SCRIPT_NAME'];
		$folder_path =  str_replace("/index.php", '', $script_name); 
		$image_path = "/image";
		$path = $root.$folder_path.$image_path;
		return $path;

	}

	private function podaj_rozszerzenie_pliku($nazwa_obrazu)
	{
		$this->db->where('nazwa_pliku',urldecode($nazwa_obrazu));
		$this->db->select('rozszerzenie');
		$query = $this->db->get('obrazy');

		return $query->result_array()[0]['rozszerzenie'];		
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

