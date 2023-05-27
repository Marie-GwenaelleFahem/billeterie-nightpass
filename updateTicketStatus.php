<?php
require_once 'functions/functions.php';
$error = updateTicketStatus();
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php if($error != NULL): ?>
        <span><?= $error ?></span>
    <?php else: ?>
        <span>Modification apporté au billet!</span>
    <?php endif ?>
</body>
</html>