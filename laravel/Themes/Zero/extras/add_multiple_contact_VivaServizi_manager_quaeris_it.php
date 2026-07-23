<?php

declare(strict_types=1);
error_reporting(E_ALL);
ini_set('display_errors', true);

$base_url = 'https://manager.quaeris.it';
$login = '/api/user/login';
$addContact = '/api/quaeris/add-contact-multi';
$email = 'lfranchini@vivaservizi.it';
$pass = 'franchinivivaservizi123';

$ch = curl_init($base_url.$login);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$post = ['email' => $email, 'password' => $pass];

curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

$response = curl_exec($ch);

$json = json_decode($response);

$data = [
    'a1' => [
        'survey_pdf_id' => '44',
        'email' => 'vair81@gmail.com',
        'mobile_phone' => '',
        'language' => 'it',
        'usesleft' => '1',

        'first_name' => '',
        'last_name' => '',
        'attribute_1' => '02.07.2024', // Data cr.
        'attribute_2' => 'ANCONA', // località
        'attribute_3' => '3791339157', // tel. mobile, non prendere in considerazione, utilizzare il campo mobile_phone
        'attribute_4' => 'E-mail', // canale_contatto
        'attribute_5' => 'Variaz. anagrafiche ', // motivo_contatto
        'attribute_6' => '', // Numero telefono
    ],
    'a2' => [
        'survey_pdf_id' => '44',
        'email' => '',
        'mobile_phone' => '3791339157',
        'language' => 'it',
        'usesleft' => '1',

        'first_name' => '',
        'last_name' => '',
        'attribute_1' => '02.07.2024', // Data cr.
        'attribute_2' => 'ANCONA', // località
        'attribute_3' => '3791339157', // tel. mobile, non prendere in considerazione, utilizzare il campo mobile_phone
        'attribute_4' => 'E-mail', // canale_contatto
        'attribute_5' => 'Variaz. anagrafiche ', // motivo_contatto
        'attribute_6' => '', // Numero telefono
    ],
    // 'a3'=>[
    //   'survey_pdf_id' => '44',
    //   'email' => 'davide.vaira@quaeris.it',
    //   'language' => 'it',
    //   'usesleft' => '1',

    //   'first_name' => '',
    //   'last_name' => '',
    //   'attribute_1' => '02.07.2024', // Data cr.
    //   'attribute_2' => 'ANCONA', // località
    //   'attribute_3' => '3791339157', // tel. mobile
    //   'attribute_4' => 'E-mail', // canale_contatto
    //   'attribute_5' => 'Variaz. anagrafiche ', // motivo_contatto
    //   'attribute_6' => '', // Numero telefono
    // ]

];

$headers = [
    'Authorization: Bearer '.$json->data->token,
    'Content-type: multipart/form-data',
];

curl_setopt_array($ch, [
    CURLOPT_HTTPHEADER => $headers,
    CURLOPT_URL => $base_url.$addContact,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_VERBOSE => true,
    CURLOPT_POSTFIELDS => ['data' => json_encode($data)],
]);

$response = curl_exec($ch);

echo '<pre>'.print_r($response, true).'</pre>';

curl_close($ch);

function curl_postfields_flatten($data, $prefix = '')
{
    if (! is_array($data)) {
        return $data;
    }

    $output = [];
    foreach ($data as $key => $value) {
        $final_key = $prefix ? "{$prefix}[{$key}]" : $key;
        if (is_array($value)) {
            $output += curl_postfields_flatten($value, $final_key);
        } else {
            $output[$final_key] = $value;
        }
    }

    return $output;
}
