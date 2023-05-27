<?php
require_once 'functions/functions.php';
$events = dashboard();
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | NIGHTPASS</title>
    <link rel="stylesheet" href="./css/dashboard.css">
    <link rel="stylesheet" href="./css/style-header.css">
</head>
<body>
    <section>
        <div>
            <h1>EVENT</h1>
            <div class="line"></div>
        </div>
        <?php foreach($events as $event) : ?>
        <div class="event">
            <div class="f1">
                <img src="<?= $event["event_img"] ?>" alt="Logo de l'event" class="imgLogoEvent">
                <div class="f1-1">
                    <h2><a href="event.php?id=<?= $event['event_id'] ?>"><?= $event["event_name"] ?></a></h2>
                    <span><?= $event["event_speaker"] ?></span>
                </div>
            </div>
            <div class="f2">
                <div class="details">
                    <img src="./assets/icons/map-pin.svg" alt="icône lieu">
                    <span><?= $event["event_location"] ?></span>
                </div>
                <div class="details">
                    <img src="./assets/icons/calendar.svg" alt="icône calendrier">
                    <span><?= $event["event_date"] ?></span>
                </div>
                <div class="details">
                    <img src="./assets/icons/clock.svg" alt="icône horloge">
                    <span><?= $event["event_hour"] ?></span>
                </div>
            </div>
            <div class="manage">
                <button class="edit">
                    <img src="./assets/icons/modify.svg" alt="">
                    <a href="updateEvent.php?id=<?= $event["event_id"] ?>"><span>EDIT</span></a>
                </button>
                <button class="delete">
                    <img src="./assets/icons/delete.svg" alt="">
                    <a href="deleteEvent.php?id=<?= $event["event_id"] ?>"><span>DELETE</span></a>
                </button>
            </div>
        </div>
        <?php endforeach; ?>
    </section>
    <div class="new-event">
        <button class="add-event">
        <a class="button" href="newEvent.php"><img src="./assets/icons/add.svg" alt="bouton pour ajouter un évenement"></a>
        </button>
    </div>
</body>
</html>