<?php
require_once 'php/config.php';
require_once 'php/db.php';

session_start();
$db = connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
$sql = "Select name from auteur";
$res = mysqli_query($db, $sql);  // create a query for already existing auteurs
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
    <title>Home Page</title>
</head>

<body>
    <div class="container-fluid">
        <div class="row col-md-6 col-md-offset-3">
            <div class="panel panel-primary">
                <div class="panel-heading text-center">
                    <span> <?php if (isset($_SESSION['username'])) echo "Bienvenue " . $_SESSION['username'] . "!"; ?></span>
                    <h1>Acceuil</h1>
                    <div class="nav-heading">
                        <form action="php/cart.php">
                            <input type="submit" class="btn btn-info" value="Panier">
                        </form>
                        <?php if (isset($_SESSION['username'])) { ?>
                            <form action="php/logout.php" method="POST">
                                <input type="submit" class="btn btn-info" value="Log out">
                            </form>
                        <?php } else { ?>
                            <form action="php/login.php">
                                <input type="submit" class="btn btn-info" value="Login">
                            </form>
                        <?php } ?>
                    </div>
                </div>
                <form class="navbar-perso" action="php/search.php" method="POST">
                    <input class="form-control me-2" type="search" placeholder="Rechercher un titre" aria-label="Search" name="search">
                    <input class="form-control searchID" type="number" placeholder="Recherche via ID" aria-label="Search" name="searchID">
                    <button class="btn btn-primary" type="submit">Search</button>
                </form>
                <!--Partie ajout au panier des Livres -->
                <div class="panel-body text-center">
                    <div class="row-center">
                        <?php
                        $sql = "SELECT * FROM livre l JOIN auteur a WHERE l.auteur_id = a.id_author";
                        $res = mysqli_query($db, $sql);
                        while ($rows = mysqli_fetch_array($res)) {
                        ?>
                            <div class="container-fluid col-md-6">
                                <div class="vitrine-item">
                                    <img src="https://via.placeholder.com/100x100" class="card-img-top" alt="...">
                                    <h5 class="card-title"><?php echo $rows['book_name']; ?></h5>
                                    <p class="card-text"><?php echo $rows['name']; ?></p>
                                    <a href="php/add-to-cart.php?id=<?php echo $rows['id']; ?>" class="btn btn-primary">Ajouter au panier</a>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                    <!-- Partie button de navigation -->
                </div>
                <div class=panel-footer>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="panel-body">
                                <form action="php/auteur.php" method="POST">
                                    <input type="submit" class="btn btn-primary btn-small" value="Voir les auteurs" />
                                </form>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="panel-body">
                                <form action="php/print.php" method="POST">
                                    <input type="submit" class="btn btn-primary btn-small" value="Voir les livres" />
                                </form>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="panel-body">
                                <form action="php/add.php" method="POST">
                                    <input type="submit" class="btn btn-primary btn-small" value="Ajouter un livre" />
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
</body>

</html>