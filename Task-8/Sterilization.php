<?php
//Sterilization
$array = array('name' => 'John', 'age' => '25', 'country' => 'USA');
$serialized_data = serialize($array);
echo "Serialized Data: " . $serialized_data . "\n";
?>
