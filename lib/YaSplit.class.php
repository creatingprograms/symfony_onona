<?
class YaSplit{
  private $merchId;
  private $apiKey;
  private $apiBaseLink;
  private $logState;
  
  public function __construct($merchId, $apiKey, $isTest){
    $this->apiKey = $apiKey;
    $this->merchId = $merchId;
    $this->apiBaseLink = 'https://' . ($isTest ? 'sandbox.' : '') . 'pay.yandex.ru/api/merchant/';
    $this->logState = $isTest;
  }

  public function setLogState($state){
    $this->logState = $state;
  }

  public function getLink($data){
    if ($this->logState) $this->log('getLink', $data);
    $result = $this->sendRequest(json_encode($data), 'v1/orders');
    
    $response = json_decode($result);
    if($response->status == 'fail') return false;

    return $response->data->paymentUrl;
  }

  private function sendRequest($data, $url, $method = 'POST'){
    $curl = curl_init();
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
        CURLOPT_POSTFIELDS => $data,
        CURLOPT_HTTPHEADER => [
          'Content-Type: application/json',
          'Authorization: Api-Key ' . $this->apiKey,
        ],
      ]
    );
    $response = curl_exec($curl);

    curl_close($curl);

    if($this->logState) $this->log('curl_response', json_decode($response));

    return $response;
  }

  private function log($place, $data){
    $logData = "\n--------------------------------------------------\n" . date('d.m.Y H:i:s') . "\n";
    $logData.= $place . "\n";
    $logData.= print_r($data, true);

    file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/../split.log', $logData, FILE_APPEND);
  }
}