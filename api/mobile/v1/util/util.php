   
<?php
require_once dirname(__FILE__) . '/../constant.php';

class Util
{
	public function getHash($str, $extra) {
		return sha1($str.$extra);
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

		$reset_link_anchor = "<a href='$reset_link'>$reset_link</a>";

		$message = "
		<html>
		<head>
		<title>PSTUian | Reset password</title>
		</head>
		<body>
		<p>Please reset your password from following link. The link is valid for next 1 day.</p>
		$reset_link_anchor
		</body>
		</html>
		";

		$sender = "admin@pstuian.com";
		// Always set content-type when sending HTML email
		$headers = "From: <$sender>\r\n";
		$headers .= "Reply-To: $sender\r\n";
		$headers .= "Return-Path: $sender\r\n";
		$headers .= "CC:\r\n";
		$headers .= "BCC:\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		$headers .= "X-Priority: 3\r\n";
		$headers .= "X-Mailer: PHP". phpversion() ."\r\n";

		return mail($to,$subject,$message,$headers);
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
		$headers = "From: <$sender>\r\n";
		$headers .= "Reply-To: $sender\r\n";
		$headers .= "Return-Path: $sender\r\n";
		$headers .= "CC:\r\n";
		$headers .= "BCC:\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		$headers .= "X-Priority: 3\r\n";
		$headers .= "X-Mailer: PHP". phpversion() ."\r\n";

		return mail($to, $subject, $message, $headers);
	}
}