<?php
require('../config/config.php');
require('../lib/vendor/small-php-helpers/Form.class.php');
require('../lib/vendor/small-php-helpers/Messages.class.php');
require('../lib/vendor/small-php-helpers/toolshed.php');
require('../lib/Iconspring.class.php');

// --------------------------------------------
// Initial variablesa
session_start();
$messages = new Messages();
$messages->restoreFromSession();
$template = 'index.html';

// --------------------------------------------
// Logic
if (0) {
	$template = 'uploaded.html';
}
if (!empty($_FILES[CONF_FIELD_IMAGE])) {
	$success = TRUE;
	try {
		$icon = new Iconspring($_FILES[CONF_FIELD_IMAGE]['tmp_name'], CONF_PATH_DOWNLOAD.md5($_FILES[CONF_FIELD_IMAGE]['name']).'/',CONF_PATH_WEB);

		foreach ($CONF_ICONS as $i) {
			$success &= $icon->build($i[0],$i[1],$i[2],!empty($i[3]) ? $i[3] : $i[2], !empty($i[4]) ? $i[4] : 0);
		}
		if ($success) {
			$icon->saveHtml();
			$icon->zip();
			$template = 'uploaded.html';
			$messages->addSuccessMessage('Converted images');
			$messages->storeInSession();
			header('Location: '.returnCompleteUrl($_SERVER['SCRIPT_NAME']), TRUE, 303);
			exit();
		}
		else {
			$messages->addSuccessMessage('Error converting images', FALSE);
		}
	} catch (Exception $e) {
		$messages->addSuccessMessage($e->getMessage(), FALSE);
	}
}

// --------------------------------------------
// Output
header($messages->buildhttpStatusCode());
require(CONF_PATH_TEMPLATE.$template);
