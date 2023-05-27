<?php

// La BDD
// CREATE DATABASE billetterie;

// USE billetterie;

// CREATE TABLE events (
//   event_id INT AUTO_INCREMENT PRIMARY KEY,
//   event_name VARCHAR(255) NOT NULL,
//   event_location VARCHAR(255) NOT NULL,
//   event_date DATE NOT NULL,
//   event_hour TIME NOT NULL,
//   event_speaker VARCHAR(255) NOT NULL,
//   event_descr TEXT NOT NULL,
//   event_img VARCHAR(255) NOT NULL,
//   event_maxtickets INT NOT NULL
// );

// CREATE TABLE tickets (
//   ticket_id INT AUTO_INCREMENT PRIMARY KEY,
//   event_id INT NOT NULL,
//   ticket_firstname VARCHAR(255) NOT NULL,
//   ticket_lastname VARCHAR(255) NOT NULL,
//   ticket_mail VARCHAR(255) NOT NULL,
//   ticket_status TINYINT NOT NULL DEFAULT 0 CHECK (ticket_status IN (0, 1, 2, 3)),
//   ticket_tokenPub VARCHAR(255) UNIQUE NOT NULL,
//   ticket_tokenPri VARCHAR(255) UNIQUE NOT NULL,
//   ticket_tokenUse VARCHAR(255) UNIQUE NOT NULL,
//   ticket_generation_date DATETIME DEFAULT CURRENT_TIMESTAMP,
//   FOREIGN KEY (event_id) REFERENCES events(event_id)
// );

// CONNEXION A LA BDD
// MAMP
function bddBillet() {
    $moteur = "mysql";
    $hote = "localhost";
    $port = 8889;
    $nomBdd = "billetterie";
    $nomUtilisateur = "root";
    $motDePasse = "root";
    $pdo = new PDO(
        "$moteur:host=$hote:$port;dbname=$nomBdd", 
        $nomUtilisateur, 
        $motDePasse
    );
    // Renturn la BDD billetterie
    return $pdo;
}

// XAMP
function bddBilletXAMP() {
    $moteur = "mysql";
    $hote = "localhost";
    $nomBdd = "billetterie";
    $nomUtilisateur = "root";
    $motDePasse = "";
    $pdo = new PDO(
        "$moteur:host=$hote;dbname=$nomBdd", 
        $nomUtilisateur, 
        $motDePasse
    );
    return $pdo;
}

// ----------------------------------------- EVENTS -----------------------------------------

// DASHBOARD & READ ALL EVENT
function dashboard() {
    // NE PAS OUBLIER DE CHECK LE LOGIN (Louisan)
    // CONNEXION A LA BDD
    $pdo = bddBillet();
    // SELECTIONNER ET RETURN TOUT LES EVENTS
    $requete = $pdo->prepare("SELECT * FROM events");
    $requete->execute();
    $events = $requete->fetchAll(PDO::FETCH_ASSOC);
    return $events;
}

// ------------------------------------------------------------------

// CREATE EVENT
function newEvent(){
    $error = NULL;
    // NE PAS OUBLIER DE CHECK LE LOGIN (Louisan)
    // ENVOYER LES DONNÉES EN POST & REDIRIGER SI LES DONNÉES SAISI SONT VALIDES
    $methode = filter_input(INPUT_SERVER, "REQUEST_METHOD");
    if ($methode == "POST"){
        try {
            // CONNEXION A LA BDD
            $pdo = bddBillet();
            // RECUPERATION DES DONNÉES EN POST
            $event_name = filter_input(INPUT_POST, "name");
            $event_location = filter_input(INPUT_POST, "location");
            $event_date = filter_input(INPUT_POST, "date");
            $event_hour = filter_input(INPUT_POST, "hour");
            $event_speaker = filter_input(INPUT_POST, "speaker");
            $event_descr = filter_input(INPUT_POST, "descr");
            $event_img = filter_input(INPUT_POST, "img", FILTER_VALIDATE_URL);
            $event_maxtickets = filter_input(INPUT_POST, "maxtickets", FILTER_VALIDATE_INT);
            // AJOUTE DE L'EVENT A LA BDD & REDIRECTION AU DASHBOARD
            $requete = $pdo->prepare("INSERT INTO events (event_name, event_location, event_date, event_hour, event_speaker, event_descr, event_img, event_maxtickets) VALUES (:event_name, :event_location, :event_date, :event_hour, :event_speaker, :event_descr, :event_img, :event_maxtickets)");
            $requete->execute([
                ":event_name" => $event_name, 
                ":event_location" => $event_location, 
                ":event_date" => $event_date, 
                ":event_hour" => $event_hour, 
                ":event_speaker" => $event_speaker, 
                ":event_descr" => $event_descr, 
                ":event_img" => $event_img, 
                ":event_maxtickets" => $event_maxtickets
            ]);
            // METTRE LE BON LIEN
            header("Location: dashboard.php");
            exit();
        } catch (Exception $error){
            // UNE ERREUR DANS LE PROCESSUS DE CREATION D'UN EVENT (DONNÉES || BDD || REQUÊTES...)
            $error = "Impossible de créer l'événement, merci de saisir des données valides!";
            http_response_code(400);
        }
    }
    return $error;
}

// ------------------------------------------------------------------

// READ UN EVENT
function event() {
    $error = NULL;
    // NE PAS OUBLIER DE CHECK LE LOGIN (Louisan)
    // CONNEXION A LA BDD
    $pdo = bddBillet();
    // SELECTIONNER & RETURN TOUTES LES INFOS DE L'EVENT SUIVANT SON ID
    $event_id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
    $requete = $pdo->prepare("SELECT * FROM events WHERE event_id = :event_id");
    $requete->execute([
        ":event_id" => $event_id
    ]);
    $event = $requete->fetch(PDO::FETCH_ASSOC);
    // VERIFIE SI L'ID CORRESPOND A UN ID D'EVENT VALIDE
    if(!$event){
        $error = "Affichage de l'event impossible : ID invalide";
        http_response_code(401);
    }
    return [$error, $event];
}

// ------------------------------------------------------------------

// UPDATE EVENT
function updateEvent() {
    // NE PAS OUBLIER DE CHECK LE LOGIN (Louisan)
    $error = NULL;
    $event = NULL;
    // ENVOYER LES DONNÉES EN POST & REDIRIGER
    $methode = filter_input(INPUT_SERVER, "REQUEST_METHOD");
    if ($methode == "POST"){
        try{
            // CONNEXION A LA BDD
            $pdo = bddBillet();
            // RECUPERATION DES DONNÉES
            $event_name = filter_input(INPUT_POST, "name");
            $event_location = filter_input(INPUT_POST, "location");
            $event_date = filter_input(INPUT_POST, "date");
            $event_hour = filter_input(INPUT_POST, "hour");
            $event_speaker = filter_input(INPUT_POST, "speaker");
            $event_descr = filter_input(INPUT_POST, "descr");
            $event_img = filter_input(INPUT_POST, "img",  FILTER_VALIDATE_URL);
            $event_maxtickets = filter_input(INPUT_POST, "maxtickets", FILTER_VALIDATE_INT);
            $event_id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
            // UPDATE LES INFOS LIÉS A L'EVENT DANS LA BDD & REDIRIGE VERS LE DASHBOARD
            $requete = $pdo->prepare("UPDATE events SET event_name = :event_name, event_location = :event_location, event_date = :event_date, event_hour = :event_hour, event_speaker = :event_speaker, event_descr = :event_descr, event_img = :event_img, event_maxtickets = :event_maxtickets WHERE event_id = :event_id");
            $requete->execute([
                ":event_name" => $event_name, 
                ":event_location" => $event_location, 
                ":event_date" => $event_date, 
                ":event_hour" => $event_hour, 
                ":event_speaker" => $event_speaker, 
                ":event_descr" => $event_descr, 
                ":event_img" => $event_img, 
                ":event_maxtickets" => $event_maxtickets,
                ":event_id" => $event_id
            ]);
            // METTRE LE BON LIEN
            header("Location: dashboard.php");
            exit();
        } catch (Exception $error){
            // UNE ERREUR DANS LE PROCESSUS DE MODIFICATION D'UN EVENT (DONNÉES || BDD || REQUÊTES...)
            $error = "L'event n'a pas pue être modifié, merci de réessayer et de saisir des données valides!";
            http_response_code(400);
            return [$error, $event];
        }
    } elseif($methode == "GET") {
        // RÉCUPÉRER L'ID DE L'EVENT ENVOYÉ EN GET (event.php?id=...)
        $event_id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
        // CONNEXION A LA BDD
        $pdo = bddBillet();
        // RETURN LES INFOS LIÉ A L'ID DE L'EVENT
        $requete = $pdo->prepare("SELECT event_name, event_location, event_date, event_hour, event_speaker, event_descr, event_img, event_maxtickets FROM events WHERE event_id = :event_id");
        $requete->execute([
            ":event_id" => $event_id
        ]);
        $event = $requete->fetch(PDO::FETCH_ASSOC);
        // VERIFIE SI L'ID CORRESPOND A UN ID D'EVENT VALIDE
        if(!$event){
            $error = "Modification de l'event impossible : ID invalide";
            http_response_code(401);
        }
        return [$error, $event];
    }
}

// ------------------------------------------------------------------

// DELETE EVENT
function deleteEvent() {
    // NE PAS OUBLIER DE CHECK LE LOGIN (Louisan)
    // RÉCUPÉRER L'ID DE L'EVENT ENVOYÉ EN GET (event.php?id=...)
    $id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
    // CONNEXION A LA BDD
    $pdo = bddBillet();
    // DELETE L'EVENT DE LA BDD & REDIRIGE VERS LE DASHBOARD
    $requete = $pdo->prepare("DELETE FROM events WHERE event_id = :id");
    $requete->execute([
        ":id" => $id
    ]);
    // METTRE LE BON LIEN
    header("Location: dashboard.php");
    exit();
}

// ----------------------------------------- TICKETS -----------------------------------------

// READ ALL TICKET
function afficherTickets() {
    // NE PAS OUBLIER DE CHECK LE LOGIN (Louisan) // DOUBLE SECU LIMITE USELESS
    // CONNEXION A LA BDD
    $pdo = bddBillet();
    // RÉCUPÉRER L'ID DE L'EVENT ENVOYÉ EN GET (event.php?id=...)
    $event_id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
    // SELECTIONNER ET RETURN LES INFOS DES TICKETS LIÉS A L'EVENT DU BONNE ID
    $requete = $pdo->prepare("SELECT ticket_id, ticket_firstname, ticket_lastname, ticket_mail, ticket_status, ticket_tokenPub FROM tickets WHERE event_id = :event_id");
    $requete->execute([
        ':event_id' => $event_id
    ]);
    $tickets = $requete->fetchAll(PDO::FETCH_ASSOC);
    return $tickets;
}

// ------------------------------------------------------------------

// CREATE TICKET
function newTicket() {
    // NE PAS OUBLIER DE CHECK LE LOGIN (Louisan)
    $error = NULL;
    // ENVOYER LES DONNÉES EN POST & REDIRIGER
    $methode = filter_input(INPUT_SERVER, "REQUEST_METHOD");
    if ($methode == "POST"){
        // CONNEXION A LA BDD
        $pdo = bddBillet();
        // RÉCUPÉRER L'ID DE L'EVENT ENVOYÉ EN GET (event.php?eventID=...)
        $event_id = filter_input(INPUT_GET, "eventID", FILTER_VALIDATE_INT);
        // RÉCUPÉRÉ LE NOMBRE DE TICKETS DE L'EVENT ET SON NOMBRE MAX
        $requete = $pdo->prepare("SELECT event_maxtickets FROM events WHERE event_id = :event_id");
        $requete->execute([
            ':event_id' => $event_id
        ]);
        $event_maxtickets = $requete->fetch(PDO::FETCH_ASSOC);
        $requete = $pdo->prepare("SELECT ticket_id FROM tickets WHERE event_id = :event_id");
        $requete->execute([
            ':event_id' => $event_id
        ]);
        $nb_tickets = $requete->fetchAll(PDO::FETCH_ASSOC);
        if($event_maxtickets['event_maxtickets'] > count($nb_tickets)){
            // RECUPERATION DES DONNÉES
            $ticket_firstname = filter_input(INPUT_POST, "firstname");
            $ticket_lastname = filter_input(INPUT_POST, "lastname");
            $ticket_mail = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
            $ticket_status = 0;
            // EXEMPLE D'AJOUT FUTUR : token avec un timestamp pour réduire la chance de doublon : bin2hex(random_bytes (11)) . dechex(time());
            $ticket_tokenPub = bin2hex(random_bytes (15));
            $ticket_tokenPri = strtoupper(bin2hex(random_bytes (5)));
            $ticket_tokenUse = strtoupper(bin2hex(random_bytes (15)));
            // AJOUTE DE LE TICKET A LA BDD & REDIRECTION A L'EVENT
            $requete = $pdo->prepare("INSERT INTO tickets (event_id, ticket_firstname, ticket_lastname, ticket_mail, ticket_tokenPub, ticket_tokenPri, ticket_tokenUse, ticket_status) VALUES (:event_id, :ticket_firstname, :ticket_lastname, :ticket_mail, :ticket_tokenPub, :ticket_tokenPri, :ticket_tokenUse, :ticket_status)");
            $requete->execute([
                ':event_id' => $event_id,
                ':ticket_firstname' => $ticket_firstname,
                ':ticket_lastname' => $ticket_lastname,
                ':ticket_mail' => $ticket_mail,
                ':ticket_tokenPub' => $ticket_tokenPub,
                ':ticket_tokenPri' => $ticket_tokenPri,
                ':ticket_tokenUse' => $ticket_tokenUse,
                ':ticket_status' => $ticket_status
            ]);
            // GENERATIONS DES QRCODES 
            $requete = $pdo->prepare("SELECT * FROM tickets WHERE ticket_tokenPub = :ticket_tokenPub");
            $requete->execute([
                ':ticket_tokenPub' => $ticket_tokenPub
            ]);
            $ticket = $requete->fetch(PDO::FETCH_ASSOC);
            createQrCode($ticket);
            sendMail($ticket);
            // METTRE LE BON LIEN
            header("Location: event.php?id=$event_id");
            exit();
        } else {
            $error = "Nombre max de tickets pour l'événement atteint";
            http_response_code(401);
        }
    }
    return $error;
}

// CREATE QRCODE
function createQrCode($ticket) {
    require_once('phpqrcode/qrlib.php');
    // MODIFIER L'URL SUIVANT SON HÉBERGEUR && LE DOSSIER RACINE DU PROJET 
    //$url_Pub = 'http://localhost:8080/validate.php?ticket_tokenPub=' . $ticket['ticket_tokenPub'];
    //$url_Use = 'http://localhost:8080/consume.php?ticket_tokenUse=' . $ticket['ticket_tokenUse'];
    $url_Pub = 'http://localhost:8888/billetterie_finale/validate.php?ticket_tokenPub=' . $ticket['ticket_tokenPub'];
    $url_Use = 'http://localhost:8888/billetterie_finale/consume.php?ticket_tokenUse=' . $ticket['ticket_tokenUse'];
    $filepath = "qrcode/" . "id=" . $ticket['ticket_id'] . "&ticket_tokenPub=" . $ticket['ticket_tokenPub'] . ".png";
    QRcode::png($url_Pub, $filepath, QR_ECLEVEL_Q, 4);
    $filepath = "qrcode/" . "id=" . $ticket['ticket_id'] . "&ticket_tokenUse=" . $ticket['ticket_tokenUse'] . ".png";
    QRcode::png($url_Use, $filepath, QR_ECLEVEL_Q, 4);
}

// SEND MAIL AFTER CREATE A TICKET
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
function sendMail($ticket) {
    ob_start();
    require_once "PHPMailer-master/src/PHPMailer.php";
    require_once "PHPMailer-master/src/SMTP.php";
    require_once "PHPMailer-master/src/Exception.php";

    $pdo = bddBillet();
    $event_id = $ticket['event_id'];
    $requete = $pdo->prepare("SELECT event_name, event_location, event_hour, event_date  FROM events WHERE event_id = :event_id");
    $requete->execute([
        ':event_id' => $event_id
    ]);
    $event = $requete->fetch(PDO::FETCH_ASSOC);
    $ticket_id = $ticket["ticket_id"];
    $ticket_firstname = $ticket["ticket_firstname"];
    $ticket_lastname = $ticket["ticket_lastname"];
    $ticket_mail = $ticket["ticket_mail"];
    $ticket_tokenPri = $ticket["ticket_tokenPri"];
    $event_name = $event["event_name"];
    $event_location = $event["event_location"];
    $event_hour = date('h:i A', strtotime($event["event_hour"]));
    $event_date = new DateTime($event["event_date"]);
    $event_date = $event_date->format('d F Y');

    // URL vers la page 'getTicket.php'
    $url = "http://localhost:8888/exercicesphp/9.%20sessionssql/getTicket.php";

    $mail = new PHPMailer(true);
    // Paramètres du serveur SMTP
    $mail -> SMTPDebug = SMTP::DEBUG_SERVER;
    // $mail -> isSMTP();
    $mail -> Host = 'localhost';
    // $mail -> SMTPAuth = true;
    // $mail -> Username = '';
    // $mail -> Password = '';
    // $mail -> SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail -> Port = 1025;

    // Paramètres du mail
    $mail->setFrom('no-reply@nightpass.fr', 'NightPass');
    $mail->addAddress($ticket_mail, $ticket_firstname . " " . $ticket_lastname);
    $mail -> CharSet = 'UTF-8';

    // Contenu du mail
    $mail->isHTML(true);
    $mail->Subject = 'NightPass invites you to '. $event_name;
    $mail->Body = "<!DOCTYPE html>
    <html lang='fr'>
        <head>
        <meta charset='UTF-8'>
        <title>Invitation to ...</title>
        <style>
            body {
            font-family: Arial, sans-serif;
            font-size: 16px;
            line-height: 1.5;
            color: #333;
            background-color: #f2f2f2;
            }
            .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            }
            h1, h2, h3, h4, h5, h6 {
            font-family: inherit;
            font-weight: 700;
            line-height: 1.2;
            margin-top: 0;
            margin-bottom: 1rem;
            }
            a {
            color: #007bff;
            text-decoration: underline;
            }
            img {
            max-width: 50px;
            height: auto;
            }
        </style>
        </head>
        <body>
        <div class='container'>
            <h1>Invitation to the $event_name</h1>
            <p>Dear $ticket_firstname $ticket_lastname,</p>
            <p>We are pleased to invite you to our event taking place on <strong>$event_date</strong> at <strong>$event_hour</strong> in our premises located in <strong>$event_location</strong>.</p>
            <p>To retrieve your ticket and confirm your attendance to the event, please click on the following link and enter your secret code: $ticket_tokenPri</p>
            <p><a href='$url'>$event_name</a></p>
            <p>We are looking forward to welcoming you to our event and sharing our passion with you.</p>
            <p>Best regards,</p>
            <p>NightPass</p>
            <img src='https://imagizer.imageshack.com/img922/9315/GgzsJ9.png' alt='Logo NightPass'>
        </div>
        </body>
    </html>";
    // AltBody est utilisé pour les clients qui ne supportent pas le HTML
    $mail->AltBody = "Dear $ticket_firstname $ticket_lastname, \n
    We are pleased to invite you to our event taking place on $event_date at $event_hour in our premises located in $event_location \n
    To retrieve your ticket and confirm your attendance to the event, please click on the following link and enter your secret code: $ticket_tokenPri \n
    $url \n
    We are looking forward to welcoming you to our event and sharing our passion with you. \n
    Best regards, \n
    NightPass";

    $mail->send();
    ob_end_clean();
}

// ------------------------------------------------------------------

// READ UN TICKET
function ticket() {
    // NE PAS OUBLIER DE CHECK LE LOGIN (Louisan)
    $error = NULL;
    $ticket = NULL;
    $event = NULL;
    // CONNEXION A LA BDD
    $pdo = bddBillet();
    // RECUPERE LE TOKEN_PUB EN GET
    $ticket_tokenPub = filter_input(INPUT_GET, "ticket_tokenPub");
    // SELECTIONNER LES INFORMATIONS DU TICKET
    $requete = $pdo->prepare("SELECT ticket_id, event_id, ticket_firstname, ticket_lastname, ticket_mail, ticket_status, ticket_tokenPub, ticket_tokenUse FROM tickets WHERE ticket_tokenPub = :ticket_tokenPub");
    $requete->execute([
        ':ticket_tokenPub' => $ticket_tokenPub
    ]);
    $ticket = $requete->fetch(PDO::FETCH_ASSOC);
    // VERIFICATION SI OUI OU NON LE TICKET EXISTE
    if(!$ticket){
        $error = "Ticket introuvable : Token Publique invalide";
        http_response_code(401);
    } else {
        // SELECTIONNER LES INFORMATIONS DE L'EVENT LIÉ AU TICKET
        $requete = $pdo->prepare("SELECT event_name, event_location, event_date, event_hour, event_img FROM events WHERE event_id = :event_id");
        $requete->execute([
            ":event_id" => $ticket['event_id']
        ]);
        $event = $requete->fetch(PDO::FETCH_ASSOC);
    }
    return [$error, $ticket, $event];
}

// ------------------------------------------------------------------

// RESEND TICKET'S MAIL
function resendTicketMail(){
    $ticket_id = filter_input(INPUT_GET, "ticket_id");
    $pdo = bddBillet();
    $ticket_tokenPri = strtoupper(bin2hex(random_bytes (5)));
    $requete = $pdo->prepare("UPDATE tickets SET ticket_tokenPri = :ticket_tokenPri WHERE ticket_id = :ticket_id");
    $requete->execute([
        ':ticket_tokenPri' => $ticket_tokenPri,
        ':ticket_id' => $ticket_id
    ]);
    $requete = $pdo->prepare("SELECT * FROM tickets WHERE ticket_id = :ticket_id");
    $requete->execute([
        ':ticket_id' => $ticket_id
    ]);
    $ticket = $requete->fetch(PDO::FETCH_ASSOC);
    sendMail($ticket);
    $event_id = $ticket['event_id'];
    // METTRE LE BON LIEN
    header("Location: event.php?id=$event_id");
    exit();
}

// ------------------------------------------------------------------

// UPDATE TICKET
function updateTicket() {
    // NE PAS OUBLIER DE CHECK LE LOGIN (Louisan)
    $error = NULL;
    $ticket = NULL;
    // ENVOYER LES DONNÉES EN POST & REDIRIGER
    $methode = filter_input(INPUT_SERVER, "REQUEST_METHOD");
    if ($methode == "POST"){
        // CONNEXION A LA BDD
        $pdo = bddBillet();
        // RECUPERATION DE L'ID DE L'EVENT POUR LA REDIRECTION
        $ticket_id = filter_input(INPUT_GET, "ticket_id", FILTER_VALIDATE_INT);
        // RECUPERATION DES DONNÉES
        $ticket_firstname = filter_input(INPUT_POST, "ticket_firstname");
        $ticket_lastname = filter_input(INPUT_POST, "ticket_lastname");
        $ticket_mail = filter_input(INPUT_POST, "ticket_mail", FILTER_VALIDATE_EMAIL);
        $ticket_status = filter_input(INPUT_POST, "ticket_status", FILTER_VALIDATE_INT);
        $requete = $pdo->prepare("SELECT event_id FROM tickets WHERE ticket_id = :ticket_id");
        $requete->execute([
            ":ticket_id" => $ticket_id
        ]);
        $event_id = $requete->fetch(PDO::FETCH_ASSOC)['event_id'];
        // UPDATE LES INFOS LIÉS AU TICKET DANS LA BDD & REDIRIGE VERS L'EVENT ASSOCIÉ
        $requete = $pdo->prepare("UPDATE tickets SET ticket_firstname = :ticket_firstname, ticket_lastname = :ticket_lastname, ticket_mail = :ticket_mail, ticket_status = :ticket_status WHERE ticket_id = :ticket_id");
        $requete->execute([
            ":ticket_firstname" => $ticket_firstname, 
            ":ticket_lastname" => $ticket_lastname, 
            ":ticket_mail" => $ticket_mail,
            ":ticket_status" => $ticket_status,
            ":ticket_id" => $ticket_id
        ]);
        // METTRE LE BON LIEN
        header("Location: event.php?id=$event_id");
        exit();
    } elseif ($methode == "GET" ) {
        // CONNEXION A LA BDD
        $pdo = bddBillet();
        // RÉCUPÉRER L'ID DU TICKET ENVOYÉ EN GET (ticket_id=...)
        $ticket_id = filter_input(INPUT_GET, "ticket_id", FILTER_VALIDATE_INT);
        // RETURN LES INFOS LIÉ A L'ID DU TICKET
        $requete = $pdo->prepare("SELECT ticket_id, ticket_firstname, ticket_lastname, ticket_mail, ticket_status FROM tickets WHERE ticket_id = :ticket_id");
        $requete->execute([
            ":ticket_id" => $ticket_id
        ]);
        $ticket = $requete->fetch(PDO::FETCH_ASSOC);
        if(!$ticket){
            $error = "Ticket introuvable : ID invalide";
            http_response_code(401);
        }
        return [$error, $ticket];
    }
}

// ------------------------------------------------------------------

// DELETE TICKET
function deleteTicket() {
    // NE PAS OUBLIER DE CHECK LE LOGIN (Louisan)
    // RÉCUPÉRER L'ID DU TICKET ENVOYÉ EN GET (ticket_id=...)
    $ticket_id = filter_input(INPUT_GET, "ticket_id", FILTER_VALIDATE_INT);
    // CONNEXION A LA BDD
    $pdo = bddBillet();
    // RÉCUPÉRER L'ID DE L'EVENT AU TICKET ASSOCIÉ POUR LA REDIRECTION && LES TOKEN (PUB & USE) POUR LA SUPRESSION DES QRCODES
    $requete = $pdo->prepare("SELECT event_id, ticket_tokenPub, ticket_tokenUse FROM tickets WHERE ticket_id = :ticket_id");
    $requete->execute([
        ":ticket_id" => $ticket_id,
    ]);
    $ticket = $requete->fetch(PDO::FETCH_ASSOC);
    $ticket_tokenPub = $ticket['ticket_tokenPub'];
    $ticket_tokenUse = $ticket['ticket_tokenUse'];
    $event_id = $ticket['event_id'];
    // DELETE LE TICKET DE LA BDD & REDIRIGE VERS L'EVENT ASSOCIÉ
    $requete = $pdo->prepare("DELETE FROM tickets WHERE ticket_id = :ticket_id");
    $requete->execute([
        ":ticket_id" => $ticket_id
    ]);
    // SUPPRIME LES QRCODES LIÉS (ATTENTIONS AUX LIENS RELATIFS !!!)
    unlink("qrcode/id=$ticket_id&ticket_tokenPub=$ticket_tokenPub.png");
    unlink("qrcode/id=$ticket_id&ticket_tokenUse=$ticket_tokenUse.png");
    // METTRE LE BON LIEN
    header("Location: event.php?id=$event_id");
    exit();
}

// ------------------------------------------------------------------

// RÉCUPÉRATION DU TICKET
function getTicket(){
    $error = NULL;
    $ticket = NULL;
    $event = NULL;
    // ENVOYER LES DONNÉES EN POST
    $methode = filter_input(INPUT_SERVER, "REQUEST_METHOD");
    if ($methode == "POST"){
        // CONNEXION A LA BDD
        $pdo = bddBillet();
        // VERIFICATION DU TOKEN PRIVÉ
        $ticket_tokenPri = filter_input(INPUT_POST, "ticket_tokenPri");
        $requete = $pdo->prepare("SELECT ticket_id, event_id, ticket_firstname, ticket_lastname, ticket_mail, ticket_status, ticket_tokenPub, ticket_tokenUse, ticket_tokenPri FROM tickets WHERE ticket_tokenPri = :ticket_tokenPri");
        $requete->execute([
            ":ticket_tokenPri" => $ticket_tokenPri
        ]);
        $ticket = $requete->fetch(PDO::FETCH_ASSOC);
        if(!$ticket){
            $error = "Ticket introuvable : le code lié au billets est incorrect";
            http_response_code(401);
        } else {
            // TOKEN PRIVÉ VALIDE RÉCUPÉRATION DES DERNIÈRES INFORMATIONS
            $requete = $pdo->prepare("SELECT event_name, event_location, event_date, event_hour, event_img FROM events WHERE event_id = :event_id");
            $requete->execute([
                ":event_id" => $ticket['event_id']
            ]);
            $event = $requete->fetch(PDO::FETCH_ASSOC);
        }
    }
    return [$error, $ticket, $event, $methode];
}

// ------------------------------------------------------------------

// UPDATE TICKET STATUS
function updateTicketStatus(){
    $error = NULL;
    // Récupération des informations en GET & POST
    $ticket_status = filter_input(INPUT_GET, "status", FILTER_VALIDATE_INT);
    $ticket_tokenPri = filter_input(INPUT_POST, "ticket_tokenPri");
    // VERIFIER QUE LE TOKEN PRIVÉ EST ENVOYÉ EN POST
    if(!$ticket_tokenPri){
        $error = "Aucun billet de sélectionné";
        http_response_code(400);
    } else {
        $pdo = bddBillet();
        $requete = $pdo->prepare("SELECT ticket_status FROM tickets WHERE ticket_tokenPri = :ticket_tokenPri");
        $requete->execute([
            ":ticket_tokenPri" => $ticket_tokenPri
        ]);
        $ticket = $requete->fetch(PDO::FETCH_ASSOC);
        // VERIFIER SI LE STATUT DU TICKET N'A PAS DÉJÀ ÉTÉ MODIFIÉ
        if($ticket['ticket_status'] != 0){
            $error = "Ticket status déjà modifié impossible de re-modifier son status";
            http_response_code(401);
        } else {
            $requete = $pdo->prepare("UPDATE tickets SET ticket_status = :ticket_status WHERE ticket_tokenPri = :ticket_tokenPri");
            $requete->execute([
                ":ticket_status" => $ticket_status,
                ":ticket_tokenPri" => $ticket_tokenPri
            ]);
        }
    }
    return $error;
}

// ------------------------------------------------------------------

// CHECK TICKET
function checkTicket(){
    $error = NULL;
    $ticket_tokenPub = filter_input(INPUT_GET, "ticket_tokenPub");
    $pdo = bddBillet();
    $requete = $pdo->prepare("SELECT ticket_firstname, ticket_lastname FROM tickets WHERE ticket_tokenPub = :ticket_tokenPub");
    $requete->execute([
        ":ticket_tokenPub" => $ticket_tokenPub
    ]);
    $ticket = $requete->fetch(PDO::FETCH_ASSOC);
        if(!$ticket){
            $error = "Faux ticket";
            http_response_code(401);
        }
    return [$error, $ticket];
}

// ------------------------------------------------------------------

// USE TICKET
function useTicket(){
    $error = NULL;
    $ticket = NULL;
    $ticket_tokenUse = filter_input(INPUT_GET, "ticket_tokenUse");
    $pdo = bddBillet();
    $requete = $pdo->prepare("SELECT ticket_firstname, ticket_lastname, ticket_status FROM tickets WHERE ticket_tokenUse = :ticket_tokenUse");
    $requete->execute([
        ":ticket_tokenUse" => $ticket_tokenUse
    ]);
    $ticket = $requete->fetch(PDO::FETCH_ASSOC);
    if(!$ticket){
        $error = "ERREUR : Billet introuvable...";
        http_response_code(401);
    } else {
        if($ticket['ticket_status'] == 0){
            $error = "ERREUR : Billet en attente de validation...";
            http_response_code(401);
        } elseif($ticket['ticket_status'] == 2){
            $error = "ERREUR : Billet décliné...";
            http_response_code(401);
        } elseif($ticket['ticket_status'] == 3){
            $error = "ERREUR : Billet déjà validé...";
            http_response_code(401);
        } else {
            $requete = $pdo->prepare("UPDATE tickets SET ticket_status = 3 WHERE ticket_tokenUse = :ticket_tokenUse");
            $requete->execute([
                ":ticket_tokenUse" => $ticket_tokenUse
            ]);
        }
    }
    return [$error, $ticket];
}