<?php
require_once 'config.php';
require_once 'db.php';

session_start();
if (!isset($_SESSION['username'])) {
	header('Location: login.php');
	exit();
}

$db = connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if (isset($_POST['update'])) {
	$auteurName = $_POST['auteurName']; //Auteur var
	$bookName = $_POST['bookName']; //Book var
	$publishDate = $_POST['publishDate'];
	$ISBN = $_POST['ISBN'];
	$genre = $_POST['genre'];
	$id = $_GET['id'];
}


// Edit
if (isset($_POST['update']) && !empty($auteurName) && !empty($bookName) && !empty($publishDate) && !empty($ISBN) && !empty($genre)) {
	updateRecord($db, $auteurName, $bookName, $publishDate, $ISBN, $genre, $id);
	header("Location: /php/print.php");
	die;
}

if (isset($_POST['update']) && (empty($auteurName) || empty($bookName) || empty($publishDate) || empty($ISBN) || empty($genre))) {

	echo '<script>alert("Veuillez remplir tous les champs");</script>';
}

?>

<!DOCTYPE html>
<html>

<head>
	<title>Modifier un Livre</title>
	<link rel="stylesheet" type="text/css" href="../css/bootstrap.css" />
</head>

<body>
	<div class="container-fluid">
		<div class="row col-md-6 col-md-offset-3">
			<div class="panel panel-primary">
				<div class="panel-heading text-center">
					<h1>Modifie un livre</h1>
				</div>
				<div class="panel-body">
					<form method="post">
						<div class="form-group">
							<label for="bookName" aria-required="true">Nouveau nom du livre</label>
							<input type="text" class="form-control" id="bookName" name="bookName" />
						</div>
						<div class="form-group">
							<label for="publishDate" aria-required="true">Nouvelle date de parution</label>
							<input type="text" class="form-control" id="publishDate" name="publishDate" />
						</div>
						<div class="form-group">
							<label for="ISBN" aria-required="true"> Nouveau ISBN</label>
							<input type="text" class="form-control" id="ISBN" name="ISBN" />
						</div>
						<div class="form-group">
							<label for="genre" aria-required="true"> Nouveau Genre</label>
							<input type="text" class="form-control" id="genre" name="genre" />
						</div>
						<div class="form-group">
							<label for="auteurName" aria-required="true"> Nouveau Nom de l'auteur</label>
							<input type="text" class="form-control" id="auteurName" name="auteurName" />
						</div>
						<div class="button-perso">
							<input type="submit" class="btn btn-primary btn-small" name="update" value="Publier les modifications" />
					</form>
					<form action="print.php" method="post">
						<input type="submit" class="btn btn-primary btn-small" value="Retour au livres" />
					</form>
				</div>
			</div>
		</div>
	</div>
	</div>
</body>

</html>