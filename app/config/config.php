<?php

define('CONF_DEBUG',          FALSE);
define('CONF_FIELD_IMAGE',    'image');
define('CONF_FIELD_ICONS',    'icons');
define('CONF_FIELD_GRAVITY',  'gravity');
define('CONF_PATH_APP',       realpath(__DIR__.'/..').'/');
define('CONF_PATH_WEB',       realpath(__DIR__.'/../..').'/');
define('CONF_PATH_TEMPLATE',  realpath(__DIR__.'/../template').'/');
define('CONF_PATH_DOWNLOAD',  'download/');
define('CONF_SESSION_DATA',   'uploaded');

// see http://css-tricks.com/favicon-quiz/
$CONF_ICONS = array(
	array('favicon.ico', 'shortcut icon', 48),
	array('apple-touch-icon-ios6-57x57.png', 'apple-touch-icon', 57),
	array('apple-touch-icon-ios6-72x72.png', 'apple-touch-icon', 72),
	array('apple-touch-icon-ios6-114x114.png', 'apple-touch-icon', 114),
	array('apple-touch-icon-ios6.png', 'apple-touch-icon', 144),
	array('apple-touch-icon-60x60.png', 'apple-touch-icon', 60),
	array('apple-touch-icon-76x76.png', 'apple-touch-icon', 76),
	array('apple-touch-icon-120x120.png', 'apple-touch-icon', 120),
	array('apple-touch-icon.png', 'apple-touch-icon', 152),
	array('favicon.png', NULL, 196),
	array('firefox-os-icon.png', NULL, 60, 60, 8),
	array('tile-small.png', NULL, 128),
	array('tile-medium.png', NULL, 270),
	array('tile.png', NULL, 558),
	array('tile-wide.png', NULL, 558, 270),
);

$CONF_FORM_ICONS_DEFAULT = array(
	'favicon.ico',
	'apple-touch-icon.png',
	'favicon.png',
	'firefox-os-icon.png',
	'tile.png',
	'tile-wide.png',
);

$CONF_FORM_GRAVITY = array(
	'NorthWest' => 'Top-left',
	'North'     => 'Top',
	'NorthEast' => 'Top-right',
	'West'      => 'Left',
	'Center'    => 'Center',
	'East'      => 'Right',
	'SouthWest' => 'Bottom-left',
	'South'     => 'Bottom',
	'SouthEast' => 'Bottom-right',
);