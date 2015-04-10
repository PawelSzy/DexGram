


<div class="container" >
	<div>
	{wyswietl_blad}
	</div>
	<form class="form_vraper" action='{przycisk_zapisz_akcja_do_wykonania}' method='post' accept-charset="utf-8"  enctype="multipart/form-data">
		<?php echo validation_errors();?>
		Tytul obrazu:<br>	
		<input type="text" name="opis" class="small_form" value='{opis_obrazu}' max="255" required><br><br>
		Pobierz plik:<br>
		
		<input type="file" name="userfile"  value="upload" />
		<br><br>
		<input type="submit" value="Zapisz">
		<br>
		<br>
	</form>
</div>	