<?php
require_once 'functions/functions.php';
[$error, $event] = updateEvent();
?><!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Event</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <h1>Modification d'un nouvelle événement</h1>
    <div class="option">
        <a class="button" href="dashboard.php">Dashboard</a>
    </div>
    <?php if($error != NULL):?>
            <span><?= $error ?></span>
    <?php else: ?>
        <form method="POST">
            <label for="name">Name :</label>
            <input id="name" name="name" type="text" placeholder="Mon Event" value="<?= $event["event_name"] ?>" required>
            <label for="location">Location :</label>
            <input id="location" name="location" type="text" placeholder="Paris" value="<?= $event["event_location"] ?>" required>
            <label for="date">Date :</label>
            <input id="date" name="date" type="date" value="<?= $event["event_date"] ?>" required>
            <label for="hour">Hour :</label>
            <input id="hour" name="hour" type="time" value="<?= $event["event_hour"] ?>" required>
            <label for="speaker">Speaker :</label>
            <input id="speaker" name="speaker" type="text" placeholder="Julien the crackito" value="<?= $event["event_speaker"] ?>" required>
            <label for="descr">Description :</label>
            <textarea id="descr" name="descr" type="text" placeholder="Bla bla julien ze téme <3" required><?= $event["event_descr"] ?></textarea>
            <label for="img">Img :</label>
            <input id="img" name="img" type="text" placeholder="https://image.com" value="<?= $event["event_img"] ?>" required>
            <label for="maxtickets">Max places :</label>
            <input id="maxtickets" name="maxtickets" type="number" placeholder="10" value="<?= $event["event_maxtickets"] ?>" required>
            <input type="submit" name="submit" value="Modifier l'event" />
        </form>
    <?php endif; ?>
</body>
</html>