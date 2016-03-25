// This function is used to run the other functions that validate the profile form.
function validateProfileForm(objForm) {
	if (!passwordValidate(objForm)) {
		return false;
	}
	
	if (!emailChangeVerify(objForm)) {
		return false;
	}
	
	return true;
}

// This validates the password to be at least the minimum number characters passed to it
// and checks that the passwort in element1 matches the password in element2.
function passwordValidate(objForm) {
	var min_num = 6;
	var pass1 = objForm.password1.value;
	var pass2 = objForm.password2.value;
	var pass_num = objForm.password1.value.length;

	if (pass_num < min_num && pass_num > 0) {
		alert("Password must be at least " + min_num + " characters!");
		return false;
	}

	if (pass2 !== pass1) {
		alert("The passwords do not match!");
		return false;
	}
	
	return true;
}

// This checks to see if the email address has changed from the original. If it has
// the user is alerted that their new login will be the new email and confirm the change.
function emailChangeVerify(objForm) {
	var email_original = objForm.email_exist.value;
	var email_new = objForm.email.value;
	
	if (email_original !== email_new) {
		var answer = confirm("Changing your email address will also change your login email address! Are you sure you wish to change your email address?");

		if (answer === true) {
			  return true;
		} else {
			  return false;
		} 
	}
	
	return true;
}

// This function is first given the select menu object who's options are being set.
// Secondly, an array of all options is passed and finally, an array of options
// not to be included in the menu is passed. Each item in the first array is compaired
// to each item in the second array. If the item from the first array is found in
// the second array, that item is not included as an option in the given select object.
// If it is not found, then that item is written to the select object as an option
function setOptions(select_menu, main_array, subtract_array) {
	// Get the value of the currently selected item.
	var selected_value = select_menu.options[select_menu.selectedIndex].value;
	
	// Clear the select menu except for the first option.
	select_menu.length = 1;
	
	// Loop through all the values in the main array.
	for (i = 0; i < main_array.length; i++) {
		var found = 0;
		var string = main_array[i].value;
		var split_at = string.indexOf(",");
		var value = string.substring(0, split_at);
		var text = string.substring(split_at + 1);
		
		// If the subtract array is undefined, skip this.
		if (subtract_array !== undefined) {
			// Loop through the values in the subtract_array.
			for (i2 = 0; i2 < subtract_array.length; i2++) {
				var subtract_string = subtract_array[i2].value;
				var subtract_split_at = subtract_string.indexOf(",");
				var subtract_value;
				
				// If the value has a delimeter, split the value to get only the first
				// string before the delimeter. Otherwise, keep the value as it is.
				if (subtract_split_at > -1) {
					subtract_value = subtract_string.substring(0, subtract_split_at);
				} else {
					subtract_value = subtract_string;
				}
				
				// If the value equals the subtract value set found to 1.
				if (value === subtract_value) {
					found = 1;
					break;
				}
			}
		}
		
		// Write the main array values to the menu options only if
		// the value is not found in the subtract array.
		if (found === 0) {
			var option = document.createElement("option");
			option.value = value;
			option.text = text;

			select_menu.options.add(option);
		}
	}
	
	// Loop through the select menu options after they have been written.
	// If the value of the option equals the selected value saved at the
	// beginning of the script, set it to the selected option.
	for (i = 0; i < select_menu.options.length; i++) {
		if (select_menu.options[i].value === selected_value) {
			select_menu.selectedIndex = i;
			break;
		} 
	}
}

// This function will set the corresponding chip count text box to disabled or
// enabled deprnding on if the split chickbox is checked.
function setChipcountEnabled(split_type) {
	var check_box = document.getElementsByName('splits[]');
	var chip_count = document.getElementsByName('chipcounts[]');
 	
	for (i = 0; i < check_box.length; i++) {
		if (check_box[i].checked) {
			if (split_type === 1) {
				chip_count[i].style.visibility = "hidden";
			} else {
				chip_count[i].style.visibility = "visible";
				
				if (chip_count[i].value === "0") {
					chip_count[i].value = "";
					chip_count[i].placeholder = "Enter player's chip count here";
				}
			}
		} else {
			chip_count[i].style.visibility = "hidden";
			chip_count[i].value = "0";
			chip_count[i].placeholder = "";
		}
	}
}

// This function makes the preceeding checkbox visible if the currenr checkbox is clicked.
// If a checkbox is unchecked, all preceeding checkboxes are hidden. This is to force the user
// to input splits in the correct order.
function setSplitEnabled(index) {
	var check_box = document.getElementsByName('splits[]');
	var chip_count = document.getElementsByName('chipcounts[]');
	var i;
	
	if (check_box[index].checked) {
		check_box[index - 1].style.visibility = "visible";
	} else {
		i = 0;
		while (i < index) {
			check_box[i].style.visibility = "hidden";
			chip_count[i].style.visibility = "hidden";
			check_box[i].checked = false;
			chip_count[i].value = "";
			i++;
		}
	}
}

// This function calculates the sum of the chip counts and counts
// the number of splitting players then calculates the split percentage
// each player gets.
function setSplits(pay_3, pay_2, pay_1) {
	var check_box = document.getElementsByName('splits[]');
	var chip_count = document.getElementsByName('chipcounts[]');
	var original_splits = document.getElementsByName('original_splits[]');
	var split_count = 0;
	var chip_count_value = 0;
	var preceeding_chip_count = 0;
	var chip_count_sum = 0;
	var players_chip_count = 0;
	
	// Loop through all the check boxes.
	for (i = 0; i < check_box.length; i++) {
		// Hide all the chipcount text boxes
		chip_count[i].style.visibility = "hidden";
		
		// If the third place player is not checked to split, change their split_diff
		// back to it's default according to the settings
		if (check_box[i].checked === false && i === check_box.length - 3) {
			chip_count[i].value = pay_3;
		}
		
		// If the second place player is not checked to split, change their split_diff
		// back to it's default according to the settings
		if (check_box[i].checked === false && i === check_box.length - 2) {
			chip_count[i].value = pay_2;
		}
		
		// If the first place player is not checked to split, change their split_diff
		// back to it's default according to the settings
		if (check_box[i].checked === false && i === check_box.length - 1) {
			chip_count[i].value = pay_1;
		}
		
		// If a check box is checked count it as a splitting player.
		if (check_box[i].checked) {
			split_count = split_count + 1;
		}
		
		// If the associated chip count with the splitting player
		// has no value, set the chip count to 0. Otherwise, get 
		// the integer value of the given chip count value.
		if (chip_count[i].value === "") {
			chip_count_value = 0;
		} else {
			chip_count_value = parseInt(chip_count[i].value);
			// Make sure that a lower winner, preceeding chip count, does not have a larger
			// chip count than a higher winner.
			if (preceeding_chip_count > chip_count_value) {
				alert("Check you chip counts! You can not have a larger chip count for a lower winner.");
				return false;
			}
			preceeding_chip_count = chip_count_value;
		}
		
		// Sum all the chip_count values to get the total chip count sum.
		chip_count_sum = chip_count_sum + chip_count_value;
	}
	
	// If there is only one split_count, notify the user that a split must have at least
	// two players and exit the function.
	if (split_count === 1) {
		alert("You must have at least two players to split!");
		return false;
	}
	
	// If the chip count sum = 0, the the split is done evenly and set the
	// chip count sum to 1.
	if (chip_count_sum === 0) {
		chip_count_sum = 1;
		// For every check box that is checked, divide the chip count sum by
		// the split count to find the even percetage each splitting player gets.
		for (i = 0; i < check_box.length; i++) {
			if (check_box[i].checked) {
				chip_count[i].value = chip_count_sum / split_count;
			}
		}
	// If the chip count sum > 0, then the split is done by percentage.
	} else {
		// For every check box that is checked, divide the corresponding
		// chip count value by the total chip count sum to get the
		// percentage of each splitting player.
		for (i = 0; i < check_box.length; i++) {
			if (check_box[i].checked) {
				players_chip_count = parseInt(chip_count[i].value);
				// If the chip_count_sum is > 0 then the split is by percentage and no splitting players
				// can have a chip count of 0
				if (players_chip_count <= 0  || isNaN(players_chip_count)) {
					alert("All splitting players must have a chip count!");
					return false;
				}
				if (split_count === 2) {
					// If only 2 players are splitting, subtract 3rd place's percetage from
					// each of the chip counts to account for the percentage they get.
					chip_count[i].value = (players_chip_count - (players_chip_count * pay_3)) / chip_count_sum;
					// Save the original split percentage to the hidden field to use when splitting points.
					original_splits[i].value = players_chip_count / chip_count_sum;
				} else {
					chip_count[i].value = players_chip_count / chip_count_sum;
					// Save the original split percentage to the hidden field to use when splitting points.
					original_splits[i].value = players_chip_count / chip_count_sum;
				}
			}
		}
	}
	
	return true;
}

// This function forces the administrator to verify ending a season.
function getSeasonEndVerify() {
	var answer = confirm("By ending this season, no new games can be played until a new season is created and any statistics that were being calulated will stop as of today. Are you sure you wish to end this season?");

	if (answer === true) {
		  return true;
	} else {
		  return false;
	} 
}

// This function forces the administrator to verify creating a season.
function getSeasonCreateVerify() {
	var answer = confirm("Before creating this season, make sure all the settings are correct. Once a season is started it can not be edited. You will have to end this season and create a new one to make any changes. Are you sure this season is correct?");

	if (answer === true) {
		  return true;
	} else {
		  return false;
	} 
}

// This function checks if the number of players is less than or equal to the max allowed.
// If it is more than the allowed it forces the administrator to verify they want to override the setting.
function getPlayersOverVerify(objForm) {
	var playersNumber = parseInt(objForm.total_players.value);
	var playersMax = parseInt(objForm.max_players.value);
	
	if (playersNumber > playersMax) {
		var answer = confirm("There will be " + playersNumber + " players when only " + playersMax + " players are allowed! Are you sure you want to override this setting?");
		
		if (answer === true) {
			return true;
		} else {
			return false;
		} 
	}
	
	return true;
}

// This function disables the registrations buttons and displays a message while the request processes
function setRequestButton(objButton, objForm) {
	objButton.innerHTML = "Please Wait...";
	objButton.disabled = true;
	
	objForm.submit();
}

// This function disables the game status buttons and displays a message while the request processes
function setGameStatus(objForm) {
	document.getElementById("game_status_note").innerHTML = "Please Wait...";
	objForm.submit();
}

// This function verifies that the selected game should be deleted
function getGameDeleteVerify() {
	var answer = confirm("This will delete the selected game and all it's associated data! Are you sure you want to delete it?");

	if (answer === true) {
		return true;
	} else {
		return false;
	} 
}

// This function verifies that the selected player should be deleted
function getPlayerDeleteVerify() {
	var answer = confirm("This will delete the selected player and all their playing history! Are you sure you want to delete them?");

	if (answer === true) {
		return true;
	} else {
		return false;
	} 
}

var javascript_countdown = function () {
	var time_left = 10; //number of seconds for countdown
	var output_element_id = 'javascript_countdown_time';
	var keep_counting = 1;
	var no_time_left_message = 'Registration has ended';
 
	function countdown() {
		if(time_left < 2) {
			keep_counting = 0;
		}
 
		time_left = time_left - 1;
	}
 
	function add_leading_zero(n) {
		if(n.toString().length < 2) {
			return '0' + n;
		} else {
			return n;
		}
	}
 
	function format_output() {
		var hours, minutes, seconds;
		seconds = time_left % 60;
		minutes = Math.floor(time_left / 60) % 60;
		hours = Math.floor(time_left / 3600);
 
		seconds = add_leading_zero( seconds );
		minutes = add_leading_zero( minutes );
		hours = add_leading_zero( hours );
 
		return 'Registration ends in ' + hours + ':' + minutes + ':' + seconds;
	}
 
	function show_time_left() {
		document.getElementById(output_element_id).innerHTML = format_output();//time_left;
	}
 
	function no_time_left() {
		document.getElementById(output_element_id).innerHTML = no_time_left_message;
	}
 
	return {
		count: function () {
			countdown();
			show_time_left();
		},
		timer: function () {
			javascript_countdown.count();
 
			if(keep_counting) {
				setTimeout("javascript_countdown.timer();", 1000);
			} else {
				no_time_left();
			}
		},
		//Kristian Messer requested recalculation of time that is left
		setTimeLeft: function (t) {
			time_left = t;
			if(keep_counting == 0) {
				javascript_countdown.timer();
			}
		},
		init: function (t, element_id) {
			time_left = t;
			output_element_id = element_id;
			javascript_countdown.timer();
		}
	};
}();
