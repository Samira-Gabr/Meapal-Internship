//Deserialization
<?php
$serialized_data   = 
'a:3:{s:4:"name";s:4:"John";s:3:"age";i:25;s:7:"country";s:3:"USA";}';
$unserialized_data = unserialize($serialized_data);
echo "Unserialized Data:";
print_r($unserialized_data);
?>
