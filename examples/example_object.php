<?php

if (file_exists('../source/ctcSerializer.php'))
	require_once('../source/ctcSerializer.php');
else
	die('ctcSerializer.php not found');

$example_object = new stdClass();
$example_object->one = 1;
$example_object->two = 2;
$example_object->three = 3;

$ctcSerializer = ctcSerializer::serialize($example_object);
echo htmlspecialchars($ctcSerializer);