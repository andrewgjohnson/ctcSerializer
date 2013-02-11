<?php

if (file_exists('../source/ctcSerializer.php'))
	require_once('../source/ctcSerializer.php');
else
	die('ctcSerializer.php not found');

$example_multi = array();
$example_multi['example_array'] = array('Example','Array');
$example_multi['example_string'] = 'Example String';
$example_multi['example_objects'] = array();
$example_multi['example_objects'][0] = new stdClass();
$example_multi['example_objects'][0]->id = 1;
$example_multi['example_objects'][0]->text = 'Example';
$example_multi['example_objects'][1] = new stdClass();
$example_multi['example_objects'][1]->id = 2;
$example_multi['example_objects'][1]->text = 'Object';

$ctcSerializer = ctcSerializer::serialize($example_multi);
echo htmlspecialchars($ctcSerializer);