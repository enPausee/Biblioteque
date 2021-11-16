<?php

require_once 'db.php';

session_start();

if (isset($_POST['pseudo']) && strlen($_POST['pseudo']) > 2) {
    $_SESSION['username'] = $_POST['pseudo'];
    header("Location: ../index.php");
}
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="../css/bootstrap.css"/>
    </head>

    <body>
        <div class="container-fluid">
            <div class="row col-md-6 col-md-offset-3">
                <div class="panel panel-primary">
                    <div class="panel-heading text-center">
                        <h1>Login</h1>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <form method="post" action="login.php">
                                <label for="pseudo">Pseudo</label>
                                <input type="text" name="pseudo" id="pseudo" required>
                                <input class="button" type="submit" value="Se connecter">
                            </form>
                            <?php if (isset($_POST['pseudo']) && strlen($_POST['pseudo']) <= 2) {
                                    echo "Nom trop court";
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>