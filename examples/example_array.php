<?php

if (file_exists('../source/ctcSerializer.php'))
	require_once('../source/ctcSerializer.php');
else
	die('ctcSerializer.php not found');

$example_array = array(
	'one' => 1,
	'two' => 2,
	'three' => 3
);

$ctcSerializer = ctcSerializer::serialize($example_array);
echo htmlspecialchars($ctcSerializer);