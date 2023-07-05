<?php
/**
 * ROND残高確認プログラム
 */

const URL_API = 'https://api.polygonscan.com/api';                      //polygon APIのURL
const CONTRACT_ADDRESS = '0x204820B6e6FEae805e376D2C6837446186e57981';  //RONDトークンのコントラクトアドレス
const TARGET_ADDRESS = '0x18EBDB05963E562921fdd24420c8cF27dbaa1F3E';    //チェック対象アドレス
const API_KEY = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';                   //polygon APIのapikey

$settings = [
    'module' => 'account',
    'action' => 'tokenbalance',
    'contractaddress' => CONTRACT_ADDRESS,
    'address' => TARGET_ADDRESS,
    'tag' => 'latest',
    'apikey' => API_KEY
];
$response = [
    'status' => 0,
    'balance' => 0,
    'code' => 0,
    'message' => ''
];
$params = array_map(
    function ($k, $v) {
        return sprintf("%s=%s", $k, $v);
    },
    array_keys($settings),
    array_values($settings)
);
$url = sprintf("%s?%s", URL_API, implode('&', $params));

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
$res = curl_exec($curl);
$curlErrno = curl_errno($curl);
if ($curlErrno) {
    $curlError = curl_error($curl);
    $response['status'] = 2;
    $response['message'] = curl_error($curl);
}
curl_close($curl);

$json = json_decode($res, true);
if (isset($json['status'])) {
    $response['status'] = (int)$json['status'];
    $response['balance'] = round((float)$json['result'] / 1000000000000000000, 6);
}
print(Date("Y-m-d H:i:s")) . "\n";
print_r($response);
