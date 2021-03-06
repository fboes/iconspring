<?php
require('app/config/config.php');
require(CONF_PATH_APP.'lib/vendor/small-php-helpers/Form.class.php');
require(CONF_PATH_APP.'lib/vendor/small-php-helpers/Messages.class.php');
require(CONF_PATH_APP.'lib/vendor/small-php-helpers/toolshed.php');
require(CONF_PATH_APP.'lib/Iconspring.class.php');

// --------------------------------------------
// Initial variablesa
session_start();
$messages = new Messages();
$messages->restoreFromSession();
$template = 'index.html';
$data = array();

$CONF_FORM_ICONS = array();
foreach ($CONF_ICONS as $k => &$i) {
	if (empty($i[3])) {
		$i[3] = $i[2];
	}
	$CONF_FORM_ICONS[$i[0]] = $i[0]. ' ('.$i[2].'×'.$i[3].'px)';
}
unset($i);

// --------------------------------------------
// Logic
if (isset($_GET['success'])) {
	$template = 'uploaded.html';
	if (!empty($_SESSION[CONF_SESSION_DATA])) {
		$data = $_SESSION[CONF_SESSION_DATA];
		unset($_SESSION[CONF_SESSION_DATA]);
	}
}
elseif (!empty($_FILES[CONF_FIELD_IMAGE])) {
	$success = TRUE;
	try {
		$icon = new Iconspring($_FILES[CONF_FIELD_IMAGE]['tmp_name'], CONF_PATH_DOWNLOAD.md5($_FILES[CONF_FIELD_IMAGE]['name']).'/',CONF_PATH_WEB);

		if (defined('CONF_DEBUG') && CONF_DEBUG) {
			$icon->moveOriginalImage();
		}


		if (empty($_POST[CONF_FIELD_GRAVITY]) || !in_array($_POST[CONF_FIELD_GRAVITY], array_keys($CONF_FORM_GRAVITY))) {
			$_POST[CONF_FIELD_GRAVITY] = current(array_keys($CONF_FORM_GRAVITY));
		}
		foreach ($CONF_ICONS as $key => $i) {
			if (in_array($i[0], $_POST[CONF_FIELD_ICONS])) {
				$success &= $icon->build($i[0],$i[1],$i[2],!empty($i[3]) ? $i[3] : $i[2], !empty($i[4]) ? $i[4] : 0, !empty($i[4]) ? $i[4] : 0, $_POST[CONF_FIELD_GRAVITY]);
			}
		}
		if ($success) {
			$icon->saveHtml();
			$icon->zip();
			$template = 'uploaded.html';
			$_SESSION[CONF_SESSION_DATA] = (object)array(
				'html'   => $icon->returnHtml(),
				'zip'    => $icon->getZipFilename(),
				'images' => $icon->getImageFilenames(),
			);
			$messages->addSuccessMessage('Converted images');
			$messages->storeInSession();
			if (!CONF_DEBUG) {
				header('Location: '.returnCompleteUrl($_SERVER['SCRIPT_NAME']).'?success', TRUE, 303);
				exit();
			}
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
