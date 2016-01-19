<?php (isset($_GET['message'])) ? $messages[] = get_message($_GET['message']) : NULL; ?>
<?php if (!empty($messages)) { ?>
	<div class="ui-body ui-body-a ui-corner-all">
	<?php foreach ($messages as $message) { echo $message; } ?>
	</div>
	<br />
<?php } ?>