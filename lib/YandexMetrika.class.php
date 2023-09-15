<?php
  class YandexMetrika{
    private $token;
    private $logState;
    private $apiBaseLink;
    private $metrikaId;

    public function __construct($token, $metrikaId, $isTest){
      $this->token = $token;
      $this->metrikaId = $metrikaId;
      $this->logState = $isTest;
      $this->apiBaseLink = 'https://api-metrika.yandex.net/stat/v1/';
    }

    public function setLogState($state){
      $this->logState = $state;
    }

    public function getOrders($date1, $date2){
      $data = [
        'ids' => $this->metrikaId,
        'date1' => $date1,
        'date2' => $date2,
        'accuracy' => 'full',
        'limit' => '1000',
        'dimensions' => 'ym:s:purchaseID, ym:s:<attribution>TrafficSource, ym:s:<attribution>SourceEngine',
        'metrics' => 'ym:s:ecommerceRevenue'
      ];
      $res = $this->sendRequest($data, 'data', 'GET');

      return json_decode($res);
    }

    private function sendRequest($data, $url, $method = 'POST') {
      $curl = curl_init();

      if($method == 'GET' && !empty($data)) {
        $url .= "?" . http_build_query($data);
      }
      else curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

      curl_setopt_array(
        $curl,
        [
          CURLOPT_URL => $this->apiBaseLink . $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => $method,
          CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: OAuth ' . $this->token,
          ],
        ]
      );
      $response = curl_exec($curl);

      curl_close($curl);

      if ($this->logState) $this->log('curl_response', json_decode($response));

      return $response;
    }
    private function log($place, $data){
      $logData = "\n--------------------------------------------------\n" . date('d.m.Y H:i:s') . "\n";
      $logData.= $place . "\n";
      $logData.= print_r($data, true);

      file_put_contents(__DIR__ . '/../metrika.log', $logData, FILE_APPEND);
    }

  }
