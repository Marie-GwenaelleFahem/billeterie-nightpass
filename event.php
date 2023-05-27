<?php
require_once 'functions/functions.php';
[$error, $event] = event();
$tickets = afficherTickets();
?><!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $event["event_name"] ?></title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <div class="option">
        <a class="button" href="dashboard.php">Dashboard</a><a class="button" href="newTicket.php?eventID=<?= $event["event_id"] ?>">Créer un ticket</a>
    </div>
        <?php if($error != NULL):?>
            <span><?= $error ?></span>
        <?php else: ?>
            <section>
                <img height=100 width=100 src="<?= $event["event_img"] ?>" alt="Event logo">
                <h2><?= $event["event_name"] ?></h2>
                <h3><?= $event["event_speaker"] ?></h3>
                <div>
                    <span><?= $event["event_location"] ?></span><span><?= $event["event_date"] ?></span><span><?= $event["event_hour"] ?></span>
                </div>
                <p><?= $event["event_descr"] ?></p>
                <a href="updateEvent.php?id=<?= $event["event_id"] ?>">MODIFY</a>
                <a href="deleteEvent.php?id=<?= $event["event_id"] ?>">DELETE</a>
            </section>
            <section>
                <h2>CURRENT TICKETS de <?= $event["event_name"] ?> : <?= count($tickets) ?>/<?= $event["event_maxtickets"] ?></h2>
                <table>
                    <tr>
                        <th>Prénom</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Qr Code</th>
                    </tr>
                    <?php foreach ($tickets as $ticket) :?>
                        <tr style="cursor: pointer;" onclick="window.location='ticket.php?ticket_tokenPub=<?= $ticket['ticket_tokenPub'] ?>'">
                            <td><?= $ticket['ticket_firstname'] ?></td>
                            <td><?= $ticket['ticket_lastname'] ?></td> 
                            <td><?= $ticket['ticket_mail'] ?></td> 
                            <td><?= $ticket['ticket_status'] ?></td>
                            <td><img src="qrcode/id=<?= $ticket['ticket_id'] ?>&ticket_tokenPub=<?= $ticket['ticket_tokenPub']; ?>.png" alt="QR code"></td>
                            <td><a href="updateTicket.php?ticket_id=<?= $ticket['ticket_id'] ?>">UPDATE</a></td>
                            <td><a href="deleteTicket.php?ticket_id=<?= $ticket['ticket_id'] ?>">DELETE</a></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </section>
    <?php endif; ?>
</body>
</html>