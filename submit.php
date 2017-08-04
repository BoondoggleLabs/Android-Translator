<?php
require_once "getStrings.php";

$lang = $_GET['lang'];

$email = $_POST['email_address'];
unset($_POST['email_address']);

foreach ($resources as $file => $resource) {
    if (!isset($resources[$file][$lang])) {
        $resources[$file][$lang] = new SimpleXMLElement("<?xml version=\"1.0\" encoding=\"utf-8\"?><resources ></resources>");
    }
}

foreach ($_POST as $formElementName => $translatedString){
    $formElementName = explode('-', $formElementName);
    $file = $formElementName[0].'.xml';
    $entry = $formElementName[1];

    if (find_node_by_name($resources[$file][$lang], $entry) == null && $translatedString != ""){
        $newRes = $resources[$file][$lang]->addChild("string", $translatedString);
        $newRes->addAttribute("name", $entry);
    }

    foreach($resources[$file][$lang] as $formElementName => $resNode) {
        if ($resNode['name'] == $entry) {
            $resNode[0] = $translatedString;
        }
    }
}

require '/mail/PHPMailerAutoload.php';

$mail = new PHPMailer();

// Setup if you don't want the servers mail() function

/*$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'smtp1.example.com;smtp2.example.com';  // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'user@example.com';                 // SMTP username
$mail->Password = 'secret';                           // SMTP password
$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 587;                                    // TCP port to connect to*/

$mail->setFrom('from@example.com', 'Mailer');
$mail->addAddress('youremail@thing.com', 'Joe User');     // Add a recipient
$mail->addReplyTo($email, 'Information');

foreach ($resources as $file => $langs){
    $xml = $resources[$file][$lang]->asXML();
    $mail->AddStringAttachment($xml, $file);
}
$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = 'Here is the subject';
$mail->Body    = 'This is the HTML message body <b>in bold!</b>';
$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

if(!$mail->send()) {
    $message = 'Message could not be sent.';
    $message .= 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    $message = 'Translation submitted!';
}



?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Translation submitted</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css">
<script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script>

</head>

<body>
<div class="mdl-grid mdl-cell--middle" style="padding-top:20px">
  <div class="mdl-cell mdl-cell--4-col"></div>
  <div class="mdl-cell mdl-cell--4-col mdl-card mdl-shadow--2dp" style="padding:20px"><div class=" mdl-typography--headline" style="padding-bottom:20px"><?=$message?></div>
             <p>Thanks for translating Forecaster! I'll add the translation into the app and release the new version as soon as I can. If you've included your email address, I'll let you know when it's going to be ready.</p>
             <p>Feel free to email me if you have any questions at: <a href="mailto:boondogglelabs@gmail.com">boondogglelabs@gmail.com</a></p></div>
  <div class="mdl-cell mdl-cell--4-col"></div>
</div>
</body>
</html>
