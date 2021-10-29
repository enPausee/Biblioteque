<?php
require_once 'config.php';
require_once 'db.php';

$db = connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

$fulldata = fetchall($db);

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Liste livres</title>
        <link rel="stylesheet" type="text/css" href="../css/bootstrap.css"/>
    </head>
    <body>
        <h1>Liste des Livres</h1>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">ID du livre</th>
                    <th scope="col">Nom du livre</th>
                    <th scope="col">Date de parution</th>
                    <th scope="col">ISBN</th>
                    <th scope="col">Genre</th>
                    <th scope="col">Auteur</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if(count($fulldata) > 0):
                    foreach($fulldata as $data): ?>
                    <tr>
                        <td><?php echo $data['id']; ?></td>
                        <td><?php echo $data['book_name']; ?></td>
                        <td><?php echo $data['publish_date']; ?></td>
                        <td><?php echo $data['ISBN']; ?></td>
                        <td><?php echo $data['genre']; ?></td>
                        <td><?php echo $data['name']; ?></td>
                        <td><a href="delete.php?id=<?php echo $data['id'];?>">Delete</a></td>
                        <td><a href="../php/edit.php?id=<?php echo $data['id'];?>">Edit</a></td>
                    </tr>
                <?php endforeach;
                else: ?>
                    <tr>
                        <td colspand="6">Nous n'avons pas pu récupérer les livres</td>
                    </tr>
                <?php endif?>
            </tbody>
        </table>
        <form action="../index.php" method="post">
                <input type="submit" class="btn btn-primary" value="Retour vers ajout"/>
        </form>
    </body>
</html>