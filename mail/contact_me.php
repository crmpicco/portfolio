<?php

/**
 * Contact Me - Mail script to send email to me with SwiftMailer
 *
 * @author Craig R Morton <crmpicco@aol.co.uk>
 * @date   19-May-2015
 */

require_once '../swiftmailer/lib/swift_required.php';

// Check for empty fields
if (empty($_POST['name']) ||
    empty($_POST['email']) ||
    empty($_POST['phone']) ||
    empty($_POST['message']) ||
    !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)
) {
    echo "No arguments Provided!";

    return false;
}

// no real need to sanitise since there is no DB
$name          = $_POST['name'];
$email_address = $_POST['email'];
$phone         = $_POST['phone'];
$message       = $_POST['message'];

// Create the email and send the message
$email_body = "You have received a new message from your website contact form.\n\n" . "Here are the details:\n\nName: $name\n\nEmail: $email_address\n\nPhone: $phone\n\nMessage:\n$message";

$transporter = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
    ->setUsername('ayrshireminiscontact@gmail.com')
    ->setPassword('Gv7vhEoot6lqLS6hKbok');

$mailer  = Swift_Mailer::newInstance($transporter);
$message = Swift_Message::newInstance('Portfolio Enquiry')
    ->setFrom(array('ayrshireminiscontact@gmail.com' => 'CRMPicco Portfolio Enquiry'))
    ->setTo(array('crmpicco@aol.co.uk' => 'A name'))
    ->setBody($email_body);

$result = $mailer->send($message);

// write the contact info to a flat file since outbound comms seems to be disabled on AwardSpace :(
$fp = fopen('email-contacts.txt', 'a');
fwrite($fp, $name . '|' . $email_address . '|' . $phone . '|' . $_POST['message'] . PHP_EOL);
fwrite($fp, str_repeat('=', 50) . PHP_EOL);
fclose($fp);

return true;
?>