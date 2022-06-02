<?php
$message;
require __DIR__ . '/twilio/src/Twilio/autoload.php';
// Use the REST API Client to make requests to the Twilio REST API
use Twilio\Rest\client;
// Your Account SID and Auth Token from twilio.com/console
// $sid = 'ACb3547039006591cf9984f593f5e266fd';
$sid = 'AC46e23b5003cddcb504406f58f63fc21a';
// $token = '69acb3a8d5906291c0506957fdd77c00';
$token = '7fdd7a614f1b5cf323c6fb41711730e8';
$client = new Twilio\Rest\Client($sid, $token);
// Use the client to do fun stuff like send text messages!
$client->messages->create(
    // the number you'd like to send the message to
    '+243973472538',
    array(
        // A Twilio phone number you purchased at twilio.com/console
        // 'from' => '+18329811642',
        'from' => '+19793253624',
        // the body of the text message you'd like to send
        'body' => $message
    )
);
?>