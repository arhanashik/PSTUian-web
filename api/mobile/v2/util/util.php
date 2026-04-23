<?php
require_once dirname(__FILE__, 5) .  '/vendor/phpmailer/PHPMailer.php';
require_once dirname(__FILE__, 5) .  '/vendor/phpmailer/Exception.php';
require_once dirname(__FILE__, 5) .  '/vendor/phpmailer/SMTP.php';

require_once dirname(__FILE__, 2) . '/constant.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Util
{
	public function getHash($str, $extra) {
		return sha1($str.$extra);
	}

	/**
	 * Function to get the client ip address
	 *
	 * @return string The Ip address
	 */
	public function getIp(): string {
		if(isset($_SERVER['HTTP_CLIENT_IP'])) {
			return $_SERVER['HTTP_CLIENT_IP'];
		}
		if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			return $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		return $_SERVER['REMOTE_ADDR'];
	}

	public function isValidEmail(string $email) : bool
	{
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			return false;
		}

		//Get host name from email and check if it is valid
		$email_host = array_slice(explode("@", $email), -1)[0];

		// Check if valid IP (v4 or v6). If it is we can't do a DNS lookup
		if (!filter_var($email_host,FILTER_VALIDATE_IP, [
			'flags' => FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE,
		])) {
			//Add a dot to the end of the host name to make a fully qualified domain name
			// and get last array element because an escaped @ is allowed in the local part (RFC 5322)
			// Then convert to ascii (http://us.php.net/manual/en/function.idn-to-ascii.php)
			$email_host = idn_to_ascii($email_host.'.');

			//Check for MX pointers in DNS (if there are no MX pointers the domain cannot receive emails)
			if (!checkdnsrr($email_host, "MX")) {
				return false;
			}
		}

		return true;
	}

	// receivers should be an  array of email addresses
	public function sendPasswordResetEmail($receiver, $reset_link) {
		$to = $receiver;
		$subject = "PSTUian | Reset password";

		$reset_link_anchor = "<a href='$reset_link'>Reset Password Link</a>";

		$message = "
		<html>
		<head>
		<title>PSTUian | Reset password</title>
		</head>
		<body>
		<p>Please reset your password from following link. The link is valid for next 1 day.</p>
		$reset_link_anchor
		<p>If the above link does not work, please use this one:</p>
		$reset_link
		</body>
		</html>
		";

		$sender = "admin@pstuian.com";
		// Always set content-type when sending HTML email
		// $headers = "From: <$sender>\r\n";
		// $headers .= "Reply-To: $sender\r\n";
		// $headers .= "Return-Path: $sender\r\n";
		// $headers .= "CC:\r\n";
		// $headers .= "BCC:\r\n";
		// $headers .= "MIME-Version: 1.0\r\n";
		// $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		// $headers .= "X-Priority: 3\r\n";
		// $headers .= "X-Mailer: PHP". phpversion() ."\r\n";
		// return mail($to,$subject,$message,$headers);

		$result = $this->sendEmail($sender, $receiver, $subject, $message);
		return $result === '';
	}

	public function sendVerificationEmail($receiver, $verification_link) {
		$to = $receiver;
		$subject = "PSTUian | Email Verification";

		$verification_link_anchor = "<a href='$verification_link'>Verfication Link</a>";

		$message = "
		<html>
		<head>
		<title>PSTUian | Email Verification</title>
		</head>
		<body>
		<p>Thank you for using PSTUian.</p>
		<p>Please verify your email by clicking on the following link.</p>
		$verification_link_anchor
		<p>If the above link does not work, please use this one:</p>
		$verification_link
		</body>
		</html>
		";

		$sender = "admin@pstuian.com";
		// Always set content-type when sending HTML email
		// $headers = "From: <$sender>\r\n";
		// $headers .= "Reply-To: $sender\r\n";
		// $headers .= "Return-Path: $sender\r\n";
		// $headers .= "CC:\r\n";
		// $headers .= "BCC:\r\n";
		// $headers .= "MIME-Version: 1.0\r\n";
		// $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		// $headers .= "X-Priority: 3\r\n";
		// $headers .= "X-Mailer: PHP". phpversion() ."\r\n";
		// return mail($to, $subject, $message, $headers);

		$result = $this->sendEmail($sender, $receiver, $subject, $message);
		return $result === '';
	}

	public function sendEmail($sender, $receiver, $title, $body) {
		//Create an instance; passing `true` enables exceptions
		$mail = new PHPMailer(true);

		try {
			//Server settings
			// $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
			// $mail->isSMTP();                                            //Send using SMTP
			// $mail->Host       = 'smtp.example.com';                     //Set the SMTP server to send through
			// $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
			// $mail->Username   = 'user@example.com';                     //SMTP username
			// $mail->Password   = 'secret';                               //SMTP password
			// $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
			// $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

			//Recipients
			$mail->setFrom($sender, 'PSTUian');
			// $mail->addAddress('joe@example.net', 'Joe User');     //Add a recipient
			$mail->addAddress($receiver,);               //Name is optional
			$mail->addReplyTo($sender, 'PSTUian');
			// $mail->addCC('cc@example.com');
			// $mail->addBCC('bcc@example.com');

			//Attachments
			// $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
			// $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

			//Content
			$mail->isHTML(true);                                  //Set email format to HTML
			$mail->Subject = $title;
			$mail->Body    = $body;
			// $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

			$mail->send();
			return '';
		} catch (Exception $e) {
			return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
		}
	}
}