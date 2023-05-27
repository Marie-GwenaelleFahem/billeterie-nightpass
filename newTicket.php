<?php
require_once 'functions/functions.php';
$error = newTicket();
?><!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Ticket</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <h1>Création d'un nouveau Ticket</h1>
    <div class="option">
        <a class="button" href="dashboard.php">Dashboard</a>
    </div>
    <?php if($error): ?>
        <span><?= $error ?></span>
    <?php else: ?>
        <form method="POST">
            <label for="firstname">First Name:</label>
            <input type="text" name="firstname" id="firstname" placeholder="John">
            <label for="lastname">Last Name:</label>
            <input type="text" name="lastname" id="lastname" placeholder="Doe">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" placeholder="johndoe@email.fr">
            <input type="submit" value="Submit">
        </form>
    <?php endif; ?>
</body>
</html>