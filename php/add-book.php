<?php
require_once 'config.php';
require_once 'db.php';

	//Auteur var
	if(!empty($_POST['NewauteurName'])){
		$auteurName = $_POST['NewauteurName'];
		$exist = FALSE;
	}
	else{
		$auteurName = $_POST['auteurName'];
		$exist = TRUE;
	}
		

	//Book var
	$bookName = $_POST['bookName'];
	$publishDate = $_POST['publishDate'];
	$ISBN = $_POST['ISBN'];
	$genre = $_POST['genre'];

	$db = connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
	$id_author = NULL;

	if($exist)
		$id_author = insertAuteur($db, $auteurName, $exist);
	else
		insertAuteur($db, $auteurName, $exist);
	
	insertLivre($db, $bookName, $publishDate, $ISBN, $genre, $id_author);

	$db->close();
	header("Location: /php/print.php");
	die;
?>