<?php

$myArray = array(
    'one',
    'name' => 'Test',
    'description' => 'This is a test description',
    'created_at' => date('Y-m-d H:i:s'),
    'updated_at' => date('Y-m-d H:i:s'),
);
echo $myArray[0] . "\n"; // Outputs: one
echo $myArray['name'] . "\n"; // Outputs: Test
echo $myArray['description'] . "\n"; // Outputs: This is a test description
