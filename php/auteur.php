<?php
require_once 'config.php';
require_once 'db.php';

$db = connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

$fulldata = fetchAuteur($db);

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
                    <th scope="col">ID de l'auteur</th>
                    <th scope="col">Nom de l'auteur</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if(count($fulldata) > 0):
                    foreach($fulldata as $data): ?>
                    <tr>
                        <td><?php echo $data->id_author; ?></td>
                        <td><?php echo $data->name; ?></td>
                        <td><a href="deleteAuteur.php?id=<?php echo $data->id_author;?>">Delete</a></td>
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
