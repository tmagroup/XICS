<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//Load composer's autoloader
require 'smtp/vendor/autoload.php';

$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
try {
    //Server settings
    $mail->SMTPDebug = 2;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'sslout.df.eu';                   // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'xics@xics.de';                 // SMTP username
    $mail->Password = 'xics2019';                           // SMTP password
   // $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
	$mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
    //$mail->Port = 587;                                    // TCP port to connect to
	$mail->Port = 465;                                    // SSL port to connect to
        //$mail->Port = 995;  
		//$mail->Port = 25;   
		// SSL port to connect to

    //Recipients
    $mail->setFrom('xics@xics.de','Optimus');
    $mail->addAddress('pramodranpariya@gmail.com', 'Pramod Ranpariya');     // Add a recipient
    //$mail->addAddress('contact@example.com');               // Name is optional
    //$mail->addReplyTo('info@example.com', 'Information');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');

    //Attachments
    //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Subject line goes here';
    $mail->Body    = 'Body text goes here';
    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
}
?>