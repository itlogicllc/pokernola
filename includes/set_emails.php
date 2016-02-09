<?php
	/////////////////////////////////////////////////////////////////////////////
	// DON'T FORGET TO CHANGE THIS TO TRUE WHEN UPLOADING TO PRODUCTION SERVER //
	/////////////////////////////////////////////////////////////////////////////
	$testing = false;
	/////////////////////////////////////////////////////////////////////////////
	
	$headers = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8\r\n";
	
	// If testing is true then use the localhost testing mail server
	// otherwise use the production server mail.
	if ($testing) {
		$admin_to = "xampp@localhost.com";
		$players_to = "xampp@localhost.com";
		$link = "http://localhost/pokernola/";
	} else {
		$admin_to = "info@pokernola.com";
		$players_to = "players@pokernola.com";
		$link = "http://www.pokernola.com/";
	}

	// Emails sent to players
	function player_emails($type, $to_array, $args_array = null) {
		global $players_to;
		global $link;
		global $headers;

		$to = $players_to;
		$body = "Hey $args_array[0]:";

		switch ($type) {
			case "password":
				$subject = "Forgot PokerNOLA Password";
				$body .= "<p>So, you forgot your PokerNOLA password did you?</p><p>No problem! Just click the link below or paste it into your browser's address bar. You will then be taken to a form where you can reset it.</p><p>" . $link . "password_reset.php?player_id=$args_array[1]&auth_code=$args_array[2]</p>";
				break;

			case "invitation":
				$subject = "Invitation to Join PokerNOLA";
				$body .= "<p>Good news! $args_array[1] would like to invite you to join PokerNOLA. New members are always welcome and we hope you accept this invitation to join us. To do so, simply click on or paste the link at the end of this email into your browser's address bar. You will be taken to a new player registration form. Fill it out, submit it and just like that, you will be a PokerNOLA member.</p><p>As soon as you are registered you will be able to log in, register for games, have your performance tracked, score points, get ranked and send invitations to others just like this one.</p><p>We would be happy to have you, so join today and good luck!</p><p>" . $link . "invitation_accept.php?player_id=$args_array[2]&invitation_code=$args_array[3]&invitation_id=$args_array[4]</p>";
				break;
			
			case "alternate_change":
				$subject = "Good News From PokerNOLA!";
				$body .= "<p>Good news, you're in! A player just unregistered from the $args_array[1] game starting at $args_array[2]. Since you were the next alternate in line, you just got bumped up and are now registered to play instead.</p><p>Please be courteous, if you will not be able to attend, please go to pokernola.com and unregister from the $args_array[1] game so that others will have the opportunity to play.<p>Thanks and good luck!</p>";
				break;

			case "distribution":
				$subject = "A Message From PokerNOLA";
				$body = "Dear player,";
				$body .= "<p>$args_array[0]</p>";
				break;

			default:
		}

		$body .= "<br /><br />Thanks for playing,<br />PokerNOLA<br /><img height='100' width='100' src='http://www.pokernola.com/images/pokernola_logo.png'><br /><a href='http://www.pokernola.com'>www.pokernola.com</a>";
		$body = wordwrap($body, 70);

		$bcc = "";
		foreach ($to_array as $value) {
			$bcc = $value . "," . $bcc;
		}

		$player_headers = $headers . "From: info@pokernola.com\r\n";
		$player_headers .= "Bcc:" . $bcc;

		mail($to, $subject, $body, $player_headers);
	}

	// Emails sent to the owner with system status messages
	function system_emails($type, $args_array = null) {
		global $admin_to;
		global $headers;
		
		$to = $admin_to;

		switch ($type) {
			case "password_request":
				$subject = "PokerNOLA Password Change Request";
				$body = "$args_array[0] just requested to change their password.";
				break;

			case "password_changed":
				$subject = "PokerNOLA Password Changed";
				$body = "$args_array[0] just changed their password.";
				break;

			case "invitation_sent":
				$subject = "PokerNOLA Invitation Sent";
				$body = "$args_array[0] just invited $args_array[1] $args_array[2] to join PokerNOLA.";
				break;

			case "invitation_accepted":
				$subject = "PokerNOLA Invitation Accepted";
				$body = "$args_array[0] $args_array[1] just accepted an invitation from $args_array[2] to join PokerNOLA.";
				break;

			case "invitation_deleted":
				$subject = "PokerNOLA Invitation Deleted";
				$body = "$args_array[0] just deleted an invitation sent to $args_array[1].";
				break;
			
			case "alternate_change":
				$subject = "PokerNOLA Alternate Change";
				$body = "$args_array[0] just got moved from the Alternates List to the Players List for the $args_array[1] game.";
				break;

			default:
		}

		$body = wordwrap($body, 70);

		$system_headers = $headers . "From: info@pokernola.com\r\n";

		mail($to, $subject, $body, $system_headers);
	}

	// Emails sent to the owner submitted from the contact form by players
	function contact_emails($from, $name, $body) {
		global $admin_to;
		
		$to = $admin_to;

		$subject = "Message From PokerNOLA Contact Form";

		$body = "Message from " . $name . ":\n\n" . $body;
		$body = wordwrap($body, 70);

		$contact_headers = "From: " . $from . "\r\n";

		mail($to, $subject, $body, $contact_headers);
	}
	