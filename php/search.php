<?php
require_once 'config.php';
require_once 'db.php';

$db = connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

$fulldata = search($db);

?>
<html>
    <head>
        <title>Liste livres</title>
    </head>
    <body>
        <h1>Liste des Livres</h1>
        <table class="table-primary">
            <thead>
                <tr>
                    <th>ID du livre</th>
                    <th>Nom du livre</th>
                    <th>Date de parution</th>
                    <th>ISBN</th>
                    <th>Genre</th>
                    <th>Nom</th>
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
    </body>
</html>