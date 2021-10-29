<?php
require_once 'config.php';
require_once 'db.php';

	//Auteur var
	if(!empty($_POST['NewauteurName'])){
		$auteurName = $_POST['NewauteurName'];
		$already_exist = FALSE;
	}
	else{
		$auteurName = $_POST['auteurName'];
		$already_exist = TRUE;
	}

	//Book var
	$bookName = $_POST['bookName'];
	$publishDate = $_POST['publishDate'];
	$ISBN = $_POST['ISBN'];
	$genre = $_POST['genre'];

	$db = connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
	$id_author = NULL;

	if($already_exist == TRUE){
		$id_author = insertAuteur($db, $auteurName, $already_exist);
	}	
	else{
		insertAuteur($db, $auteurName, $already_exist);
	}
	echo $id_author;
	insertLivre($db, $bookName, $publishDate, $ISBN, $genre, $id_author);

	$db->close();
	header("Location: print.php");
	die;
?>