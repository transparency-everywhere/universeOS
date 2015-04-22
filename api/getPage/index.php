<?php
$url = $_POST['url'];

$c = curl_init($url);
curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
//curl_setopt(... other options you want...)

if (curl_error($c))
    die(curl_error($c));

echo curl_exec($c);