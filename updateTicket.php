<?php
require_once "functions/functions.php";
[$error, $ticket] = updateTicket();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Ticket</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <h1>Modifier un ticket</h1>
    <?php if($error != NULL): ?>
        <span><?= $error ?></span>
    <?php else: ?>
        <form method="POST">
            <label>Prénom : </label>
            <input type="text" name="ticket_firstname" value="<?= $ticket['ticket_firstname'] ?>"><br>
            <label>Nom : </label>
            <input type="text" name="ticket_lastname" value="<?= $ticket['ticket_lastname'] ?>"><br>
            <label>Email : </label>
            <input type="email" name="ticket_mail" value="<?= $ticket['ticket_mail'] ?>"><br>
            <fieldset>
                <legend>Statut :</legend>
                <input name="ticket_status" type="radio" value=0 <?php if($ticket['ticket_status'] == 0){echo("checked");}?>>
                <label>En attente</label>
                <input name="ticket_status" type="radio" value=1 <?php if($ticket['ticket_status'] == 1){echo("checked");}?>>
                <label>Validé</label>
                <input name="ticket_status" type="radio" value=2 <?php if($ticket['ticket_status'] == 2){echo("checked");}?>>
                <label>Décliné</label>
                <input name="ticket_status" type="radio" value=3 <?php if($ticket['ticket_status'] == 3){echo("checked");}?>>
                <label>Utilisé</label>
            </fieldset>
            <input type="submit" name="submit" value="Modifier">
        </form>
    <?php endif; ?>
</body>
</html>