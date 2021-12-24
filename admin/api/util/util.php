   
<?php
require_once dirname(__FILE__, 4) .  '/vendor/phpmailer/PHPMailer.php';
require_once dirname(__FILE__, 4) .  '/vendor/phpmailer/Exception.php';
require_once dirname(__FILE__, 4) .  '/vendor/phpmailer/SMTP.php';

require_once dirname(__FILE__, 2) . '/constant.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Util
{
	public function getFileExtension($file)
	{
		$path_parts = pathinfo($file);
		return $path_parts['extension'];
	}

	public function getFileType($extension) 
	{
		switch($extension) 
		{
			case 'jpg':
			case 'jpeg':
			case 'png':
				return 'image';
			default:
				return 'unknown';
		}
	}

	public function getFileSize($file)
	{
		return file_size($file);
	}

	public function isNotSupportedImage($image) {

		if (!file_exists($image['tmp_name'])) {
			return "Choose image file to upload";
		}

		$extension = $file_extension = pathinfo($image["name"], PATHINFO_EXTENSION);
		
		if (!in_array($extension, ALLOWED_EXTENTION)) {
			return "Invalid image. Supported format: " . implode(", ", ALLOWED_EXTENTION);
		}

		if ($image["size"] > MAX_SIZE) {
			return "Image size too large. Max size: " . round(MAX_SIZE/(1024*1024), 1) . "MB";
		}

		return false;
	}

	public function getImageDimen($image)
	{
		$dimen = array();
		
		try {
			$image_info = getimagesize($image);
			$dimen['width'] = $image_info[0];
			$dimen['height'] = $image_info[1];
		} catch (Exception $e) {
			$dimen['width'] = 0;
			$dimen['height'] = 0;
		}

		return $dimen;
	}
	
	public function reArrayFiles($files)
	{
		$file_ary = array();
		$file_count = count($files['name']);
		$file_key = array_keys($files);
		
		for($i=0; $i<$file_count; $i++)
		{
			foreach($file_key as $val)
			{
				$file_ary[$i][$val] = $files[$val][$i];
			}
		}
		return $file_ary;
	}

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

	public function sendUserQueryReplyEmail($receiver, $query, $reply) {
		$to = $receiver;
		$subject = "PSTUian | User Inquiry";

		$message = "
		<html>
		<head>
		<title>PSTUian | User Inquiry</title>
		</head>
		<body>
		<p>You: $query</p>
		<p>Admin: $reply</p>
		</body>
		</html>
		";

		$sender = "admin@pstuian.com";
		// Always set content-type when sending HTML email
		// $headers = "MIME-Version: 1.0" . "\r\n";
		// $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

		// More headers
		// $headers .= 'From: <admin@pstuian.com>' . "\r\n";
		// $headers .= 'Cc: myboss@example.com' . "\r\n";
		// return mail($to,$subject,$message,$headers);

		$result = $this->sendEmail($sender, $receiver, $subject, $message, true);
		return $result === '';
	}

	public function sendEmail($sender, $receiver, $title, $body, $isHTML = false) {
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
			$mail->isHTML($isHTML);                                  //Set email format to HTML
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