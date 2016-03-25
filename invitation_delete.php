<?php
	require('../db_connections/pokernola.php');
	require('includes/set_page.php');
	require('includes/get_invitation.php');
	require('includes/set_emails.php');
	
	$page_access_type = 'player';
	set_page_access($page_access_type);

	// Make sure the query string has an invitation_id value.
	// If not redirect to access denied.
	if (!empty($_GET['invitation_id'])) {
		$invitation_id = trim($_GET['invitation_id']);

		$invitation = invitations_by_id($invitation_id);

		// Check the invitation_id in the database against the invitation_id
		// in the query srtring. If they don't match redirect to access denied.
		if (!$invitation) {
			header("Location: access_denied.php?message=unauthorized");
			exit();
		}
	} else {
		header("Location: access_denied.php?message=unauthorized");
		exit();
	}

	// Get the details of the player who sent the invitation.
	$player = players_by_id($invitation['player_id']);

	// Delete the invitation from the database.
	$query = "DELETE FROM invitations
				 WHERE invitation_id='$invitation_id'";

	$db_action = mysqli_query($db_connect, $query);

	// Email the owner a system message that the invter just deleted the invitation to the invitee.
	system_emails("invitation_deleted", array($player['full_name'], $invitation['full_name']));

	// Refresh the page
	header("Location: player_profile.php?player_id=" . $player_id);
	exit();