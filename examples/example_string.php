<?php

if (file_exists('../source/ctcSerializer.php'))
	require_once('../source/ctcSerializer.php');
else
	die('ctcSerializer.php not found');

$example_string = 'one two three';

$ctcSerializer = ctcSerializer::serialize($example_string);
echo htmlspecialchars($ctcSerializer);