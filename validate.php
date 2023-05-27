<?php 
require_once 'functions/functions.php';
[$error, $ticket] = checkTicket();
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>validate</title>
</head>
<body>
    <?php if($error != NULL): ?>
        <span><?= $error ?></span>
    <?php else: ?>
        <span>Ticket authentique de <?= $ticket['ticket_firstname'] . " " . $ticket['ticket_lastname'] ?></span>
    <?php endif; ?>
</body>
</html>