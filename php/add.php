<?php
require_once 'config.php';
require_once 'db.php';

session_start();
if (!isset($_SESSION['username'])) {
	header('Location: login.php');
	exit();
}

$db = connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
$sql = "Select name from auteur";
$res = mysqli_query($db, $sql);  // create a query for already existing auteur

?>

<!DOCTYPE html>
<html>

<head>
	<title>Ajouter un Livre</title>
	<link rel="stylesheet" type="text/css" href="../css/bootstrap.css" />
</head>

<body>

	<div class="container-fluid">
		<div class="row col-md-6 col-md-offset-3">
			<div class="panel panel-primary">
				<div class="panel-heading text-center">
					<h1>Ajout livre</h1>
				</div>
				<div class="panel-body">
					<form action="add-book.php" method="post">
						<div class="form-group">
							<label for="bookName">Nom du livre</label>
							<input type="text" class="form-control" id="bookName" name="bookName" required="required" />
						</div>
						<div class="form-group">
							<label for="publishDate">Date de parution</label>
							<input type="text" class="form-control" id="publishDate" name="publishDate" required="required" />
						</div>
						<div class="form-group">
							<label for="ISBN">ISBN</label>
							<input type="text" class="form-control" id="ISBN" name="ISBN" required="required" />
						</div>
						<div class="form-group">
							<label for="genre">Genre</label>
							<input type="text" class="form-control" id="genre" name="genre" required="required" />
						</div>
						<div class="form-group">
							<label for="auteurName">Auteur</label>
							<select name="auteurName">
								<option disable selected value> -- Choisir un auteur existant -- </option>
								<?php while ($rows = mysqli_fetch_array($res)) {
								?>
									<option value="<?php echo $rows['name']; ?>"> <?php echo $rows['name']; ?> </option>
								<?php
								}
								?>
							</select>
						</div>
						<div class="input-auteur">
							<label for="NewauteurName"></label>
							<input type="text" class="form-control" id="NewauteurName" name="NewauteurName" placeholder="Remplir si nouveau auteur" />
						</div>
						<div class="button-perso">
							<input type="submit" class="btn btn-primary" value="Ajouter le livre" />
					</form>
					<form action="../index.php" method="post">
						<input type="submit" class="btn btn-primary" value="Retour vers la page d'acceuil" />
					</form>
				</div>
				</form>
			</div>
		</div>
	</div>
	</div>
</body>

</html>