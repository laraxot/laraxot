<?php

declare(strict_types=1);
error_reporting(E_ALL);
ini_set('display_errors', true);

$base_url = 'https://manager.quaeris.it';
$login = '/api/user/login';
$addContact = '/api/quaeris/add-contact';
$email = 'info@veritas.it';
$pass = 'veritas123';

$ch = curl_init($base_url.$login);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$post = ['email' => $email, 'password' => $pass];

curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

$response = curl_exec($ch);
$json = json_decode($response);

$data = [
    'survey_pdf_id' => '12',
    'mobile_phone' => '321456789',
    'email' => 'test@email.com',
    'language' => 'it',
    'usesleft' => 1,

    'first_name' => 'Giacomo',
    'last_name' => 'Giocomo',
    'attribute_1' => '123',
    'attribute_2' => '123',
    'attribute_3' => '123',
];

$headers = [
    // 'Content-Type: application/json',  //error
    'Authorization: Bearer '.$json->token,
];

curl_setopt_array($ch, [
    CURLOPT_HTTPHEADER => $headers,
    CURLOPT_URL => $base_url.$addContact,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_VERBOSE => true,
    CURLOPT_POSTFIELDS => $data,
]);

$response = curl_exec($ch);

echo '<pre>'.print_r($response, true).'</pre>';

curl_close($ch);
