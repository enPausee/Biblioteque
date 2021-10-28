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
function insertAuteur(mysqli $db, $auteurName, $exist)
{
	if($exist == TRUE)
	{
		$fulldata = fetchAll($db);
		if(count($fulldata) > 0){
			foreach($fulldata as $data){
				if($data['name'] == $auteurName){
					$id = $data['id_author'];
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
	if($id_author != NULL)
		$author_id = $id_author;
	else
		$author_id = $db->insert_id;   //get the last id

	$sql = "INSERT INTO `livre`(`book_name`, `publish_date`, `ISBN`, `genre`, `auteur_id`) VALUES ('$bookName','$publishDate', '$ISBN', '$genre', $author_id);";
	$result = $db->query($sql);
	if(!$result){
		throw new Excepetion('Cannot insert into database');
	}

	return $db;
}

/**
 * Delete record
 * @param mysqli $db
 * @param $id
 * @throws Exception
 */
function deleteRecord(mysqli $db, $id){
	$sql = "DELETE FROM `livre` WHERE id ='".$id."';";
	$result = $db->query($sql);
	if(!$result){
		throw new Exception('Cannot delete livre');
	}
}

/**
 * Search into database
 * @param mysqli $db
 * @return $fulldata
 */
function search(mysqli $db){
	$search = $_POST['search'];

	$sql = "SELECT * FROM livre l JOIN auteur a WHERE l.auteur_id = a.id_author AND l.book_name LIKE '"."%".$search."%"."';";

	$result = $db->query($sql);

	if($result->num_rows > 0)
	{
		while($row = $result->fetch_array())
		{
			$fulldata[] = $row;
		}
	}
	return $fulldata;
}

function uptdateRecord(mysqli $db, $auteurName, $bookName, $publishDate, $ISBN, $genre, $id){
	$sql =  "UPDATE `auteur` SET `name`= '$auteurName' WHERE `id_author` = $id";
	$result = $db->query($sql);
	if(!$result){
		throw new Exception('Cannot update auteur');
	}
	
	$sql = "UPDATE `livre` SET `book_name`= '$bookName', `publish_date` = '$publishDate', `ISBN` = '$ISBN', `genre` = '$genre' WHERE `id` = $id";
	$result = $db->query($sql);
	if(!$result){
		throw new Exception('Cannot update livre');
	}
}
?>