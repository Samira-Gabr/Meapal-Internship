<?php

function ActionXml ($action, $posts = []) {
    $xmlData = New SimpleXMLElement('<request/>');
    $xmlData->addChild('action', $action);
    foreach ($posts as $key => $value) {
        $xmlData->addChild($key, $value);
    }
    return $xmlData->asXML();
}

function SendCurl ($url, $xmlData, $headers = []) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}

function ActionXml2 ($action, $data = []) {
    $xmlData = new SimpleXMLElement('<request/>');
    $xmlData->addChild('action', $action);
    foreach ($data as $key => $value) {
    $xmlData->addChild($key, $value);
    }
    return $xmlData->asXML();
}

function GetData($action, $data = []) {
    $xmlData = New SimpleXMLElement('<request/>');
    $xmlData->addChild('action', $action);
    foreach ($data as $key => $value ) {
        $xmlData->addChild($key, $value);
    }
    return $xmlData->asXML();
}

?>