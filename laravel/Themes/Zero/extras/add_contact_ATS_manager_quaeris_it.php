<?php

declare(strict_types=1);
error_reporting(E_ALL);
ini_set('display_errors', true);

$base_url = 'https://manager.quaeris.it';
// $base_url = 'https://manager2.quaerisofficina.it';
$login = '/api/user/login';
$addContact = '/api/quaeris/add-contact';
$email = 'a.tocchetto@altotrevigianoservizi.it';
$pass = 'atstocchetto321';

$ch = curl_init($base_url.$login);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$post = ['email' => $email, 'password' => $pass];

curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

$response = curl_exec($ch);

$json = json_decode($response);

$data = [
    'survey_pdf_id' => '16',
    'email' => 'vair81@gmail.com',
    'language' => 'it',
    'usesleft' => '1',

    'first_name' => '',
    'last_name' => '',
    'attribute_1' => 'Davide', // Cliente
    'attribute_2' => '18/04/24', // Data Richiesta
    'attribute_3' => '3791339157', // tel. mobile
    'attribute_4' => '0423763916', // tel. principale
    'attribute_5' => 'C00099138', // num account
    'attribute_6' => 'Adesione fondo perdita', // tipologia richiesta
    'attribute_7' => 'Modifica Indirizzo POD', // sotto tipologia
    'attribute_8' => 'altro testo', // Classificazione richiesta
    'attribute_9' => 'web', // origine
    'attribute_10' => 'altro nome', // nome segnalatore
    'attribute_11' => 'qualifica segnalatore', // qualifica segnalatore
    'attribute_12' => 'cellulare segnalatore', // cellulare segnalatore
    'attribute_13' => 'telefono segnalatore', // telefono segnalatore
    'attribute_14' => 'altraemail@mail.com', // email segnalatore
];

$headers = [
    'Authorization: Bearer '.$json->data->token,
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
