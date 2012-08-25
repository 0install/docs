<?php
require("config.php");

$uri = $_POST['uri'];
$body = $_POST['body'];

if (!isset($uri, $body)) {
  header("HTTP/1.1 400 Missing fields", true, 400);
  die("Missing fields in POST");
  exit(1);
}

require('Mail.php');

$recipient = "zero-install-bugs@lists.sourceforge.net";

$headers = array (
    'From' => 'bugs@0install.net',
    'To' => $recipient,
    'Subject' => "Bug in $uri",
);

$mail_object =& Mail::factory('smtp',
    array(
        'host' => 'prwebmail',
        'auth' => true,
        'username' => 'zero-install',
        'password' => $password,
        //'debug' => true, # uncomment to enable debugging
    ));

$result = $mail_object->send($recipient, $headers, $body);

if ($result === true) {
  echo("Bug report email sent");
} else {
  header("HTTP/1.1 500 Failed to send email: $result", true, 500);
  die($result);
}
