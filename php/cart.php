<?php

require_once 'config.php';
require_once 'db.php';

session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$db = connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.css" />
    <title>Panier</title>
</head>

<body>
    <h1>Panier</h1>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Nom du livre</th>
                <th scope="col">Date de parution</th>
                <th scope="col">ISBN</th>
                <th scope="col">Genre</th>
                <th scope="col">Auteur</th>
                <th scope="col">Quantité</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($_SESSION['cart'] != null) {
                foreach ($_SESSION['cart'] as $book_id => $quantity) {
                    $book = getBook($db, $book_id);
                    echo '<tr>';
                    echo '<td>' . $book['book_name'] . '</td>';
                    echo '<td>' . $book['publish_date'] . '</td>';
                    echo '<td>' . $book['ISBN'] . '</td>';
                    echo '<td>' . $book['genre'] . '</td>';
                    echo '<td>' . $book['name'] . '</td>';
                    echo '<td>' . $quantity . '</td>';
                    echo '<td>' ?><a href="remove-from-cart.php?id=<?php echo $book['id']; ?>">Moins</a></td>
                <?php
                }
            } else { ?>
                <tr>
                    <td colspand="6">Vous n'avez rien dans votre panier</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <form action="../index.php" method="post">
        <input type="submit" class="btn btn-primary" value="Retour vers la page d'acceuil" />
    </form>
</body>

</html>