<?php
	function get_message($type, $args_array = null) {
		switch ($type) {
			case "invalid_credentials":
				$message = "<p class='alert'>Invalid email or password!</p>";
				break;

			case "email_exists":
				$message = "<p class='alert'>The email $args_array[0] already exists! Please try again.</p>";
				break;
			
			case "email_not_exists":
				$message = "<p class='alert'>The email address $args_array[0] does not exist! Please try again.</p>";
				break;

			case "updated":
				$message = "<p class='info'>Updated successfully!</p>";
				break;
			
			case "not_updated":
				$message = "<p class='alert'>Sorry, the update failed! Please try again. If the problem persists, please contact Poker NOLA using the contact form under Tools</p>";
				break;
			
			case "unauthorized":
				$message = "<p class='alert'>You are not authorized for this action! If you pasted a link you received into your browser's address bar, make sure you pasted the entire link. Otherwise, report to your local police station and surrender yourself as a cyber criminal.</p>";
				break;
			
			case "password_reset":
				$message = "<p class='info'>You have successfully reset your password! You may now log in with your new credentials.</p>";
				break;
			
			case "password_email":
				$message = "<p class='info'>An email with instruction for resetting your password has been sent to $args_array[0]. Don't forget to check your spam folder if it's not in your inbox. It could take up to 24 hours to receive.</p>";
				break;
				
			case "invitation_pending":
				$message = "<p class='info'>A previous invitation was already sent to $args_array[0] and is still pending. That previous invitation was just sent again.</p>";
				break;
			
			case "invitation_email":
				$message = "<p class='info'>You have just sent an email invitation for $args_array[0] at $args_array[1] to join Poker NOLA.</p>";
				break;
			
			case "welcome":
				$message = "<p class='info'>Welcome to Poker NOLA! You are now officially a member and can log in with your new credentials.</p>";
				break;
			
			case "contact_sent":
				$message = "<p class='info'>You've just sent your message to Poker NOLA from $args_array[0] at $args_array[1]. We will respond ASAP!</p>";
				break;
			
			case "distribution_sent":
				$message = "<p class='info'>Your email distribution has been sent to $args_array[0]</p>";
				break;
			
			case "must_login":
				$message = "<p class='alert'>You must be logged in to access this page! Please log in and try again.</p>";
				break;
			
			case "admin_only":
				$message = "<p class='alert'>This is an ADMIN ONLY page! You are not authorized to proceed.</p>";
				break;
			
			case "season_ended":
				$message = "<p class='alert'>The latest season has ended! You must create a new season before creating any new games.</p>";
				break;
			
			default:
		}
		
		return $message;
	}
	