<?php
require('../lib/Iconspring.class.php');

$icons = glob('../data/in/*.{jpg,gif,png}',GLOB_BRACE);
$data = array();

foreach ($icons as $i) {
	$icon = new Iconspring($i, '../data/out/'.basename($i).'/');

	// Das althergebrachte Favicon (für Bookmarks und das kleine Logo oben im Browser-Tab) wird in den Größen 16×16 und 32×32px benötigt. Der Dateiname ist i.d.R. favicon.ico.
	$icon->build('favicon.ico', 'shortcut icon', 32);
	// Für Geräte mit älterem iOS (also dem Betriebssystem für iPad und iPhone) werden die Größen 72×72, 114×114 und 144×144px benötigt. Der Dateiname ist dabei i.d.R. apple-touch-icon.png.
	$icon->build('apple-touch-icon-72x72.png', 'apple-touch-icon', 72);
	$icon->build('apple-touch-icon-114x114.png', 'apple-touch-icon', 114);
	$icon->build('apple-touch-icon-144x144.png', 'apple-touch-icon', 144);
	// Für das neuere iOS 7 werden nun 76×76, 120×120 und 152×152px benötigt, der Dateiname bleibt gleich (siehe Apples Anleitung für Apple Touch-Icons).
	$icon->build('apple-touch-icon.png', 'apple-touch-icon', 76);
	$icon->build('apple-touch-icon-120x120.png', 'apple-touch-icon', 120);
	$icon->build('apple-touch-icon-152x152.png', 'apple-touch-icon', 152);
	// Für Chrome bzw Android empfiehlt sich das Logo in 128×128px, am Besten als PNG.
	$icon->build('favicon.png', NULL, 128);
	// Das neu hinzugekommene Firefox OS erwartet das Logo in 60×60px, bizarrerweise aber mit einem 8px Rand. Den Aufbau und Einbau beschreibt Mozilla in seiner Anleitung für Firefox OS Icons.
	$icon->build('firefox-os-icon.png', NULL, 60, 60, 8);
	// Und nicht zuletzt erlaubt Windows 8.1 Desktop-Kacheln, die in den Größen 70×70, 150×150, 310×150 und 310×310px daherkommen, und ebenfalls gerne als PNG geliefert werden möchten. Das genaue Vorgehen beschreibt Microsoft in seiner Anleitung für neue Internet Explorer 11 Features, und liefert netterweise auch einen Wizard zur Erstellung von Windows 8.1-Kacheln.
	$icon->build('windows-small-icon.png', NULL, 70);
	$icon->build('windows-medium-icon.png', NULL, 150);
	$icon->build('windows-big-icon.png', NULL, 310);
	$icon->build('windows-semi-big-icon.png', NULL, 310, 150);
	// Offen ist aktuell noch die Frage, wie Icon-Größen für das Windows Phone aussehen, hier gehen die Meinungen gerade noch weit auseinander - und in Ermangelung eines Testgeräts konnte ich dazu selber noch keine Forschung anstellen.
	$icon->saveHtml();
	$icon->zip();
	$data[$i] = $icon->returnHtml();;
}

require('../template/index.html');