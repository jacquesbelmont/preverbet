<?php

// definir o URL da API da Betfair
$url = 'https://api.betfair.com/exchange/betting/json-rpc/v1';

// definir o token de acesso
$token = 'hIKZdYDEScQAgXAH';

// definir a carga JSON-RPC para a solicitação
$request = array(
  'jsonrpc' => '2.0',
  'method' => 'SportsAPING/v1.0/listEvents',
  'params' => array(
    'filter' => array(
      'eventTypeIds' => array(1), // 1 = futebol
      'marketCountries' => array('GB'), // Reino Unido
      'marketTypeCodes' => array('MATCH_ODDS'), // correspondências com odds
      'marketStartTime' => array(
        'from' => date('c', strtotime('yesterday')), // ontem
        'to' => date('c', strtotime('today')), // hoje
      ),
    ),
    'sort' => 'FIRST_TO_START',
    'maxResults' => 5, // 5 jogos
    'marketProjection' => array('RUNNER_DESCRIPTION'),
  ),
  'id' => 1,
);

// codificar a carga JSON-RPC como uma string JSON
$request_json = json_encode($request);

// configurar a conexão cURL
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  'X-Application: ' . $token,
  'Content-Type: application/json',
));
curl_setopt($ch, CURLOPT_POSTFIELDS, $request_json);

// enviar a solicitação e receber a resposta
$response_json = curl_exec($ch);
$response = json_decode($response_json, true);

// imprimir a resposta
print_r($response);

// fechar a conexão cURL
curl_close($ch);
