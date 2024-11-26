<?php
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

$config = require 'config.php';
$email_account = $config['email_account'];
$password = $config['gmail_app_password'];

$mail = new PHPMailer(true);

try {
  // Server settings
  $mail->SMTPDebug = 0;
  $mail->isSMTP();
  $mail->Host = 'smtp.gmail.com';
  $mail->SMTPAuth = true;
  $mail->Username = $email_account;
  $mail->Password = $password;
  $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
  $mail->Port = 587;

  // Sanitize and validate inputs
  if($_POST['formType'] === 'contact') {
    $firstName = htmlspecialchars($_POST['first-name'], ENT_QUOTES, 'UTF-8');
    $lastName = htmlspecialchars($_POST['last-name'], ENT_QUOTES, 'UTF-8');
    $name = $firstName . " " . $lastName;
    $message = htmlspecialchars($_POST['message'], ENT_QUOTES, 'UTF-8');
  } else {
    $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
    $giftAidConsent = $_POST['giftAid'];
    $commsConsent = $_POST['commsConsent'];
  }
  $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    throw new Exception('Invalid email format.');
  }

  if (preg_match('/[\r\n]/', $firstName) || preg_match('/[\r\n]/', $lastName)) {
    throw new Exception('Invalid input detected.');
  }

  // Sender and recipient information
  $mail->setFrom($email, $name);
  $mail->addAddress('gemjoyben@hotmail.com', 'Dumbutu Website Form');

  // Email content
  $reminder = 'REMINDER:<br>' . '1) Scammers often use website forms to harvest genuine email addresses (expecting an auto-reply). Enquiries that do not make sense can be ignored, and are not a risk to your data.<br>2) Links sent in these emails should be treated with the same care and caution as links sent in any other email.';

  $mail->isHTML(true);

  if ($_POST['formType'] === 'contact') {
    $mail->Subject = 'WSDL Contact Form';
    $mail->Body = 'DUMBUTU WEBSITE -<br>CONTACT FORM SUBMISSION:<br>------------------------<br>Sender Name: ' . $name . '<br>Email: ' . $email . '<br>Message: ' . $message . '<br><br>------------------------<br>' . $reminder;
  } else {
    $mail->Subject = 'WSDL Donation Form';
    $mail->Body = 'DUMBUTU WEBSITE -<br>DONATION FORM SUBMISSION:<br>------------------------<br>Sender Name: ' . $name . '<br>Email: ' . $email . '<br>Gift Aid: ' . $giftAidConsent . "<br>Communications Consent: " . $commsConsent . '<br><br>------------------------<br>' . $reminder;
  }

  if (!$mail->send()) {
    throw new Exception('Message could not be sent. Mailer Error: ' . $mail->ErrorInfo);
  } else {
    echo 'Message has been sent!';
  }
} catch (Exception $e) {
  echo 'Error: ' . $e->getMessage();
}