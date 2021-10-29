<?php
require_once 'config.php';
/**
 * Connect to the databe
 * @param $dbHOST
 * @param $dbUsername
 * @param $dbPassword
 * @param $dbName
 * @return mysqli
 */
function connect($dbHOST, $dbUsername, $dbPassword, $dbName)
{
	$db = new mysqli($dbHOST, $dbUsername, $dbPassword, $dbName);

	if($db->connect_error){
		die("Cannot connect to the database:" . $db->connect_error . "\n" . $db->connect_errno);
	}

	return $db;
}

/**
 * Fetch Auteur
 * @param mysqli $db
 * @return array
 */
function fetchAuteur(mysqli $db)
{
	$data = [];

	$sql = 'SELECT * FROM `auteur`';

	$result = $db->query($sql);

	if($result->num_rows > 0)
	{
		while($row = $result->fetch_object())
		{
			$data[] = $row;
		}
	}
	return $data;
}

/**
 * Fetch Livre
 * @param mysqli $db
 * @return array
 */
function fetchLivre(mysqli $db)
{
	$data = [];

	$sql = 'SELECT * FROM `livre`';

	$result = $db->query($sql);

	if($result->num_rows > 0)
	{
		while($row = $result->fetch_object())
		{
			$data[] = $row;
		}
	}
	return $data;
}

/**
 * Fetch All
 * @param mysqli $db
 * @return array
 */
function fetchAll(mysqli $db)
{
	$data = [];

	$sql = 'SELECT * FROM livre JOIN auteur WHERE livre.auteur_id = auteur.id_author;';

	$result = $db->query($sql);

	if($result->num_rows > 0)
	{
		while($row = $result->fetch_array())
		{
			$data[] = $row;
		}
	}
	return $data;
}

/**
 * Insert a author into the database
 * @param mysqli $db
 * @param array $record
 */
function insertAuteur(mysqli $db, $auteurName, $already_exist)
{
	if($already_exist == TRUE)
	{
		
		$listeAuteur = fetchAuteur($db);
		if(count($listeAuteur) > 0){
			foreach($listeAuteur as $auteur){
				if($auteur->name == $auteurName){
					$id = $auteur->id_author;
					return $id;
				}
			}
		}
	}
		
	$sql = "INSERT INTO `auteur`(`name`) VALUES ('$auteurName');";
	$result = $db->query($sql);

	if(!$result){
		throw new Excepetion('Cannot insert into database');
	}

	return $db;
}

/**
 * Insert a book into the database
 * @param mysqli $db
 * @param array $record
 * @throws Exception
 */
function insertLivre(mysqli $db, $bookName, $publishDate, $ISBN, $genre, $id_author)
{
	if($id_author == NULL)
		$author_id = $db->insert_id;	//get the last id
	else
		$author_id = $id_author;   
	$sql = "INSERT INTO `livre`(`book_name`, `publish_date`, `ISBN`, `genre`, `auteur_id`) VALUES ('$bookName','$publishDate', '$ISBN', '$genre', $author_id);";
	$result = $db->query($sql);
	if(!$result){
		throw new Excepetion('Cannot insert into database');
	}

	return $db;
}

/**
 * Delete livre
 * @param mysqli $db
 * @param $id
 * @throws Exception
 */
function deleteLivre(mysqli $db, $id){
	$sql = "DELETE FROM `livre` WHERE id ='".$id."';";
	$result = $db->query($sql);
	if(!$result){
		throw new Exception('Cannot delete livre');
	}
}

/**
 * Delete auteur
 * @param mysqli $db
 * @param $id
 * @throws Exception
 */
function deleteAuteur(mysqli $db, $id){
	$sql = "DELETE FROM `auteur` WHERE id_author ='".$id."';";
	$result = $db->query($sql);
	if(!$result){
		throw new Exception('Cannot delete auteur');
	}
}
/**
 * Search with name into database
 * @param mysqli $db
 * @return $listeAuteur
 */
function searchName(mysqli $db){
	$search = '';
	$search = $_POST['search'];

	$sql = "SELECT * FROM livre l JOIN auteur a WHERE l.auteur_id = a.id_author AND l.book_name LIKE '"."%".$search."%"."';";

	$result = $db->query($sql);

	if($result->num_rows > 0)
	{
		while($row = $result->fetch_array())
		{
			$listeAuteur[] = $row;
		}
	}
	return $listeAuteur;
}

/**
 * Search with id into database
 * @param mysqli $db
 * @return $listeAuteur
 */
function searchID(mysqli $db, $id){
	if(empty($id))
		$id = $_POST['searchID'];

	$sql = "SELECT * FROM livre l JOIN auteur a WHERE l.auteur_id = a.id_author AND l.id = ".$id.";";

	$result = $db->query($sql);

	if($result->num_rows > 0)
	{
		while($row = $result->fetch_array())
		{
			$listeAuteur[] = $row;
		}
	}
	return $listeAuteur;
}

/**
 * Update book
 * @param mysqli $db
 * @param $auteurName
 * @param $ bookName
 * @param $publishTable
 * @param $ISBN
 * @param $genre
 * @param $id
 */
function updateRecord(mysqli $db, $auteurName, $bookName, $publishDate, $ISBN, $genre, $id){

	$auteurID = getAuteurId($db, $auteurName);
	if(!checkAuteur($db,$auteurName)){
		$sql = "INSERT INTO `auteur`(`name`) VALUES ('$auteurName');";
		$result = $db->query($sql);
		$auteurID = getAuteurId($db, $auteurName);
		$sql = "UPDATE `livre` SET `book_name`= '$bookName', `publish_date` = '$publishDate', `ISBN` = '$ISBN', `genre` = '$genre', `auteur_id`='$auteurID' WHERE `id` = $id";
		$result = $db->query($sql);
	}
	else{
		$sql = "UPDATE `livre` SET `book_name`= '$bookName', `publish_date` = '$publishDate', `ISBN` = '$ISBN', `genre` = '$genre', `auteur_id`= '$auteurID' 	WHERE `id` = $id";
		$result = $db->query($sql);
		if(!$result){
			throw new Exception('Cannot update livre');
		}
	}
}

function checkAuteur($db, $auteurName){

	$listeAuteur = fetchAuteur($db);

	if(count($listeAuteur) > 0){
		foreach($listeAuteur as $auteur){
			if($auteur->name == $auteurName)       //need fix
			{
				return TRUE;
			}
		}
	}
	return FALSE;
}

function getAuteurId($db, $auteurName){
	$listeAuteur = fetchAuteur($db);

	if(count($listeAuteur) > 0){
		foreach($listeAuteur as $auteur){
			if($auteur->name == $auteurName)       //need fix
			{
				return $auteur->id_author;
			}
		}
	}
	return FALSE;
}
?>