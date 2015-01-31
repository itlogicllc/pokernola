<?php
//$testing = true;
$testing = false;

$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-type:text/html;charset=UTF-8\r\n";

if($testing) {
	$to = "xampp@localhost.com";
	$link = "http://localhost/pokernola/";
} else {
	$to = "players@pokernola.com";
	$link = "http://www.pokernola.com/";
}

function player_emails($to_array, $arg_array, $type) {
	global $to;
	global $link;
	global $headers;
	
	$body = sprintf("Hey %s,", $arg_array[0]);
	
	switch($type) {
		case "password":
			$subject = "Forgot Poker NOLA Password";
			$body .= sprintf("<p>So, you forgot your Poker NOLA password did you?</p><p>No problem! Just click the link below or paste it into your browser's address bar. You will then be taken to a form where you can reset it.</p><p>" . $link . "reset_password.php?player_id=%s&auth_code=%s</p>", $arg_array[1], $arg_array[2], $arg_array[3], $arg_array[4], $arg_array[5]);
			break;
		
		case "invitation":
			$subject = "Invitation to Join Poker NOLA";
			$body .= sprintf("<p>Good news! %s would like to invite you to join Poker NOLA. New members are always welcome and we hope you accept this invitation to join us. To do so, simply click on or paste the link at the end of this email into your browser's address bar. You will be taken to a new player registration form. Fill it out, submit it and just like that, you will be a Poker NOLA member.</p><p>As soon as you are registered you will be able to log in, register for games, have your performance tracked, score points, get ranked and send invitations to others just like this one.</p><p>We would be happy to have you, so join today and good luck!</p><p>" . $link . "player_add.php?player_id=%s&invitation_code=%s&invitation_id=%s</p>", $arg_array[1], $arg_array[2], $arg_array[3], $arg_array[4], $arg_array[5]);
			break;
		
		default:
	}
	
	$body .= "<br /><br />Thanks for playing,<br />Poker NOLA<br /><img height='100' width='100' src='http://www.pokernola.com/images/pokernola_logo.png'><br /><a href='http://www.pokernola.com'>www.pokernola.com</a>";
	$body = wordwrap($body, 70);
	
	$bcc = "";
	foreach($to_array as $value) {
		$bcc = $value . "," . $bcc;
	}
	
	$player_headers = $headers . "From: info@pokernola.com\r\n" ;
	$player_headers .= "Cc:" . $bcc;
	
	mail($to, $subject, $body, $player_headers);
}

function system_emails($arg_array, $type) {
	global $to;
	global $headers;
	
	switch($type) {
		case "password_request":
			$subject = "Poker NOLA Password Change Request";
			$body = sprintf("%s just requested to change their password.", $arg_array[0], $arg_array[1], $arg_array[2], $arg_array[3], $arg_array[4], $arg_array[5]);
			break;
		
		case "password_changed":
			$subject = "Poker NOLA Password Changed";
			$body = sprintf("%s just changed their password.", $arg_array[0], $arg_array[1], $arg_array[2], $arg_array[3], $arg_array[4], $arg_array[5]);
			break;
		
		case "invitation_sent":
			$subject = "Poker NOLA Invitation Sent";
			$body = sprintf("%s just invited %s %s to join Poker NOLA.", $arg_array[0], $arg_array[1], $arg_array[2], $arg_array[3], $arg_array[4], $arg_array[5]);
			break;
		
		case "invitation_accepted":
			$subject = "Poker NOLA Invitation Accepted";
			$body = sprintf("%s %s just accepted an invitation from %s to join Poker NOLA.", $arg_array[0], $arg_array[1], $arg_array[2], $arg_array[3], $arg_array[4], $arg_array[5]);
			break;
		
		case "invitation_deleted":
			$subject = "Poker NOLA Invitation Deleted";
			$body = sprintf("%s just deleted an invitation sent to %s.", $arg_array[0], $arg_array[1], $arg_array[2], $arg_array[3], $arg_array[4], $arg_array[5]);
			break;
		
		default:
	}
	
	$body = wordwrap($body, 70);
	
	$system_headers = $headers . "From: info@pokernola.com\r\n";
	
	mail($to, $subject, $body, $system_headers);
}

function contact_emails($from, $name, $body) {
	global $to;
	
	$subject = "Message From Poker NOLA Contact Form";
	
	$body = "Message from " . $name . ":\n\n" . $body;
	$body = wordwrap($body, 70);
	
	$contact_headers = "From: " . $from . "\r\n";
	
	mail($to, $subject, $body, $contact_headers);
}