<?php

require_once 'config.php';
require_once 'db.php';
session_start();

$id = $_GET['id'];      // get the id of the book to be added to the cart

$db = connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

try {
    addToCart($id);
} catch (Exception $e) {
    echo 'Failed to add book to the database: ' . $e->getMessage();
}

header("Location: ../index.php");
die;

