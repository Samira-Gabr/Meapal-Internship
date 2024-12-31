<?php
if (isset($_POST['data'])) {
    $data = $_POST['data'];

    if (is_string($data) && preg_match('/^[a-zA-Z0-9:{}"\[\],]+$/', $data)) {
        $safeData = json_decode($data, true); //استخدمنا json_decode for safe deserialization

        if (json_last_error() === JSON_ERROR_NONE) {
            echo "Deserialized data: ";
            print_r($safeData);
        } else {
            echo "Invalid data format.";
        }
    } else {
        echo "Invalid input detected.";
    }
}
// JSON is safer as it doesn’t allow complex objects or executable code
// Use functions like json_last_error() to handle decoding errors

?>
