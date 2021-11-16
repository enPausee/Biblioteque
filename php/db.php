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

    if ($db->connect_error) {
        die("Cannot connect to the database:" . $db->connect_error . "\n" . $db->connect_errno);
    }

    return $db;
}

function getBook($db, $id)
{
    $query = "SELECT * FROM livre l JOIN auteur a WHERE l.auteur_id = a.id_author AND id = $id";
    $result = $db->query($query);

    if ($result->num_rows > 0) {
        $book = $result->fetch_assoc();
        return $book;
    } else {
        return null;
    }
}

function addToCart($id)
{
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    if (empty($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id] = 1;
    } else if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]++;
    }
}

function removeFromCart($id, $nbrLess)
{
    if (!empty($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id] -= $nbrLess;
        if ($_SESSION['cart'][$id] <= 0) {
            unset($_SESSION['cart'][$id]);
        }
    }
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

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_object()) {
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

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_object()) {
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

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_array()) {
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
    if ($already_exist == true) {
        $listeAuteur = fetchAuteur($db);
        if (count($listeAuteur) > 0) {
            foreach ($listeAuteur as $auteur) {
                if ($auteur->name == $auteurName) {
                    $id = $auteur->id_author;
                    return $id;
                }
            }
        }
    }

    $sql = "INSERT INTO `auteur`(`name`) VALUES ('$auteurName');";
    $result = $db->query($sql);

    if (!$result) {
        throw new Exception('Cannot insert into database');
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
    if ($id_author == null) {
        $author_id = $db->insert_id;
    }    //get the last id
    else {
        $author_id = $id_author;
    }
    try {
        $sql = "INSERT INTO `livre`(`book_name`, `publish_date`, `ISBN`, `genre`, `auteur_id`) VALUES ('$bookName','$publishDate', '$ISBN', '$genre', $author_id);";
        $result = $db->query($sql);
    } catch (Exception $e) {
        throw new Exception('Cannot insert into database');
    }
    return $db;
}

/**
 * Delete livre
 * @param mysqli $db
 * @param $id
 * @throws Exception
 */
function deleteLivre(mysqli $db, $id)
{
    $sql = "DELETE FROM `livre` WHERE id ='" . $id . "';";
    $result = $db->query($sql);
    if (!$result) {
        throw new Exception('Cannot delete livre');
    }
}

/**
 * Delete auteur
 * @param mysqli $db
 * @param $id
 * @throws Exception
 */
function deleteAuteur(mysqli $db, $id)
{
    $sql = "DELETE FROM `auteur` WHERE id_author ='" . $id . "';";
    try {
        $result = $db->query($sql);
    } catch (Exception $e) {
        throw new Exception('Cannot delete auteur');
    }
}
/**
 * Search with name into database
 * @param mysqli $db
 * @return $listeAuteur
 */
function searchName(mysqli $db)
{
    $search = '';
    if (isset($_POST['search'])) {
        $search = $_POST['search'];
    }

    $sql = "SELECT * FROM livre l JOIN auteur a WHERE l.auteur_id = a.id_author AND l.book_name LIKE '" . "%" . $search . "%" . "';";

    $result = $db->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_array()) {
            $listeAuteur[] = $row;
        }
    }
    if (isset($listeAuteur)) {
        return $listeAuteur;
    } else {
        return [];
    }
}

/**
 * Search with id into database
 * @param mysqli $db
 * @return $listeAuteur
 */
function searchID(mysqli $db, $id)
{
    $sql = "SELECT * FROM livre l JOIN auteur a WHERE l.auteur_id = a.id_author AND l.id = " . $id . ";";

    $result = $db->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_array()) {
            $listeAuteur[] = $row;
        }
    }
    if (isset($listeAuteur)) {
        return $listeAuteur;
    } else {
        return [];
    }
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
function updateRecord(mysqli $db, $auteurName, $bookName, $publishDate, $ISBN, $genre, $id)
{
    $auteurID = getAuteurId($db, $auteurName);
    if (!checkAuteur($db, $auteurName)) {
        $sql = "INSERT INTO `auteur`(`name`) VALUES ('$auteurName');";
        $result = $db->query($sql);
        $auteurID = getAuteurId($db, $auteurName);
        $sql = "UPDATE `livre` SET `book_name`= '$bookName', `publish_date` = '$publishDate', `ISBN` = '$ISBN', `genre` = '$genre', `auteur_id`='$auteurID' WHERE `id` = $id";
        $result = $db->query($sql);
    } else {
        $sql = "UPDATE `livre` SET `book_name`= '$bookName', `publish_date` = '$publishDate', `ISBN` = '$ISBN', `genre` = '$genre', `auteur_id`= '$auteurID' 	WHERE `id` = $id";
        $result = $db->query($sql);
        if (!$result) {
            throw new Exception('Cannot update livre');
        }
    }
}

function checkAuteur($db, $auteurName)
{
    $listeAuteur = fetchAuteur($db);

    if (count($listeAuteur) > 0) {
        foreach ($listeAuteur as $auteur) {
            if ($auteur->name == $auteurName) {       //need fix
                return true;
            }
        }
    }
    return false;
}

function getAuteurId($db, $auteurName)
{
    $listeAuteur = fetchAuteur($db);

    if (count($listeAuteur) > 0) {
        foreach ($listeAuteur as $auteur) {
            if ($auteur->name == $auteurName) {       //need fix
                return $auteur->id_author;
            }
        }
    }
    return false;
}

function nombreLivre($db, $data)
{
    $sql = "SELECT COUNT(*) FROM livre WHERE auteur_id =" . $data->id_author . ";";
    $result = $db->query($sql);
    $row = $result->fetch_row();
    return $row[0];
}
