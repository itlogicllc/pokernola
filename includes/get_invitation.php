<?php

	// Select all the invitations
	$invitations_query = "SELECT *, CONCAT(invitation_first,' ',invitation_last) AS full_name
								 FROM invitations
								 ORDER BY invitation_id DESC";

	$records = mysqli_query($db_connect, $invitations_query);

	while ($record = mysqli_fetch_array($records)) {
		$invitations_array[] = $record;
	}
	
	mysqli_free_result($records);
	
	// Refresh the recordset by rerunning the query and resetting the invitations_array
	// to the new recordset. Use after database transactions.
	function invitations_refresh() {
		global $db_connect;
		global $invitations_query;

		$records = mysqli_query($db_connect, $invitations_query);

		while ($record = mysqli_fetch_array($records)) {
			$invitations_array[] = $record;
		}

		$GLOBALS['invitations_array'] = $invitations_array;
		
		mysqli_free_result($records);
	}
	
	// Returns an array of all invitations
	function invitations_all() {
		global $invitations_array;
		
		return $invitations_array;
	}

	// Returns an array of a pending invitation when the player_id,
	// the player who sent the invitation, and the invitation code
	// match the record in the database.
	function invitations_invitation($player_id, $invitation_code) {
		global $invitations_array;

		for ($i = 0; $i <= count($invitations_array) - 1; $i++) {
			if ($invitations_array[$i]['player_id'] == $player_id && $invitations_array[$i]['invitation_code'] == $invitation_code) {
				return $invitations_array[$i];
			}
		}
		
		return false;
	}

	// Returns an array of invitation sent by the given player
	// based on their player_id.
	function invitations_invited($player_id) {
		global $invitations_array;

		for ($i = 0; $i <= count($invitations_array) - 1; $i++) {
			if ($invitations_array[$i]['player_id'] == $player_id) {
				$invitation_array[] = $invitations_array[$i];
			}
		}
		
		return $invitation_array;
	}
	
	// Checks to see if this given email has already been sent an invitation.
	// If it has 1 is returned for true, otherwise 0 is returned fo false.
	function invitations_email_duplicate($email) {
		global $invitations_array;

		for ($i = 0; $i <= count($invitations_array) - 1; $i++) {
			if ($invitations_array[$i]['invitation_email'] == $email) {
				return 1;
			}
		}
		
		return 0;
	}

	// Returns the array of an invitation that matches the given email that
	// the invitation was sent to.
	function invitations_by_email($email) {
		global $invitations_array;

		for ($i = 0; $i <= count($invitations_array) - 1; $i++) {
			if ($invitations_array[$i]['invitation_email'] == $email) {
				return $invitations_array[$i];
			}
		}
		
		return false;
	}

	// Returns the array of an invitation that matches the given invitation_id.
	function invitations_by_id($invitation_id) {
		global $invitations_array;

		for ($i = 0; $i <= count($invitations_array) - 1; $i++) {
			if ($invitations_array[$i]['invitation_id'] == $invitation_id) {
				return $invitations_array[$i];
			}
		}
		
		return false;
	}
	