<?php
require_once 'functions/functions.php';
[$error, $ticket, $event, $methode] = getTicket();
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Get a ticekt</title>
</head>
<body>
    <?php if($error != NULL): ?>
        <span><?= $error ?></span>
    <?php endif; ?>

    <?php if(($methode == "GET") || ($methode == "POST" && $error != NULL)): ?>
        <form method="POST">
            <label for="ticket">Ticket Code : </label>
            <input id="ticket" name="ticket_tokenPri" type="text" placeholder="0000000000">
            <input type="submit" value="get my ticket">
        </form>
    <?php elseif($methode == "POST" && $error == NULL): ?>
        <img src="qrcode/id=<?= $ticket['ticket_id'] ?>&ticket_tokenPub=<?= $ticket['ticket_tokenPub']; ?>.png" alt="Ticket Token Pub QRCode">
        <img src="qrcode/id=<?= $ticket['ticket_id'] ?>&ticket_tokenUse=<?= $ticket['ticket_tokenUse']; ?>.png" alt="Ticket Token Use QRcode">
        <img src="<?= $event["event_img"] ?>" alt="Logo Event">
        <span><?= $event["event_name"] ?></span>
        <span><?= $event["event_location"] ?></span>
        <span><?= $event["event_date"] ?></span>
        <span><?= $event["event_hour"] ?></span>
        <span><?= $ticket["ticket_firstname"] ?></span>
        <span><?= $ticket["ticket_lastname"] ?></span>
        <span><?= $ticket["ticket_mail"] ?></span>
        <span><?= $ticket["ticket_status"] ?></span>
        <form action="updateTicketStatus.php?status=1&ticket=<?= $ticket["ticket_tokenPub"] ?>" method="POST">
            <input type="hidden" name="ticket_tokenPri" value="<?= $ticket["ticket_tokenPri"] ?>">
            <input type="submit" value="VALIDER MA PRESENCE">
        </form>
        <form action="updateTicketStatus.php?status=2&ticket=<?= $ticket["ticket_tokenPub"] ?>" method="POST">
            <input type="hidden" name="ticket_tokenPri" value="<?= $ticket["ticket_tokenPri"] ?>">
            <input type="submit" value="DECLINER MA PRESENCE">
        </form>
        <button onclick="print();">Imprimer</button>
    <?php endif; ?>
</body>
</html>