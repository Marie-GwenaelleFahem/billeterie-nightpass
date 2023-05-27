<?php
require_once 'functions/functions.php';
[$error, $ticket, $event] = ticket();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php if($error != NULL):?>
        <span><?= $error ?></span>
    <?php else: ?>
        <img src="qrcode/id=<?= $ticket['ticket_id'] ?>&ticket_tokenPub=<?= $ticket['ticket_tokenPub']; ?>.png" alt="Ticket Token Pub QRCode">
        <img src="qrcode/id=<?= $ticket['ticket_id'] ?>&ticket_tokenUse=<?= $ticket['ticket_tokenUse']; ?>.png" alt="TIcket Token Use QRcode">
        <img src="<?= $event["event_img"] ?>" alt="Logo Event">
        <span><?= $event["event_name"] ?></span>
        <span><?= $event["event_location"] ?></span>
        <span><?= $event["event_date"] ?></span>
        <span><?= $event["event_hour"] ?></span>
        <span><?= $ticket["ticket_firstname"] ?></span>
        <span><?= $ticket["ticket_lastname"] ?></span>
        <span><?= $ticket["ticket_mail"] ?></span>
        <span><?= $ticket["ticket_status"] ?></span>
        <a href="resendTicket.php?ticket_id=<?= $ticket['ticket_id'] ?>">RESEND A NEW CODE</a>
        <a href="updateTicket.php?ticket_id=<?= $ticket['ticket_id'] ?>">UPDATE</a>
        <a href="deleteTicket.php?ticket_id=<?= $ticket['ticket_id'] ?>">DELETE</a>
    <?php endif; ?>
</body>
</html>