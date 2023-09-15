<?
// https://api-stg.ozonru.me/principal-integration-api/swagger/index.html

class OzonDelivery{
  private $baseUrl;
  private $key;
  private $clientId;
  private $token;

  protected $data;

  public function __construct($clientId, $key, $isTest=false){
    $this->isTest = $isTest;
    if($isTest) {
      $this->baseUrl='https://api-stg.ozonru.me/principal-integration-api';
      $this->authUrl='https://api-stg.ozonru.me/principal-auth-api/connect/token';
      $this->clientId='ApiTest_11111111-1111-1111-1111-111111111111';
      $this->key='SRYksX3PBPUYj73A6cNqbQYRSaYNpjSodIMeWoSCQ8U=';
    }
    else {
      $this->baseUrl='https://api.ozon.ru/principal-integration-api';
      $this->authUrl='https://api.ozon.ru/principal-auth-api/connect/token';
      $this->clientId=$clientId;
      $this->key=$key;
    }
  }

  private function getPvzType($pvz){
    if ($pvz->objectTypeName=='Постомат') return 'Постамат';
    // if ($pvz->objectTypeName=='Самовывоз')
    return '';
  }

  private function mailLog($message){
    if($this->isTest) die('<pre>'.print_r($message, true));
    else mail("aushakov@interlabs.ru","Проблема импорта на synergetic.ru", $message,"From: synergetic@ch52.ru");
  }

  private function cleanCity($city){
    $wordToRemove=[
      ' город',
      ' поселок',
    ];
    $newName=explode(',', $city)[0];
    $newName=str_ireplace($wordToRemove, '', $newName);

    return trim($newName);
  }

  private function createRequest($mode, $method='get', $data=''){

    if(!$this->token) $this->getToken();
    // die($this->baseUrl.$mode);

    $url=$this->baseUrl.$mode;
    if($method=='get') {
      if(!empty($data))
        $url.='?'.http_build_query($data);
      unset($data);

    }

    $curl=curl_init($url);
    $opts=[
      CURLOPT_HTTPHEADER => [
          // 'Accept: application/json',
          'authorization: Bearer '.$this->token,
      ],
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FAILONERROR => true,
    ];
    if($method!='get'){
      $opts[CURLOPT_CUSTOMREQUEST] = 'POST';
    }
    if(strlen($data)){
      $opts[CURLOPT_POSTFIELDS] = $data;
      $opts[CURLOPT_HTTPHEADER][] = 'Content-Type: application/json';
    }
    curl_setopt_array(
      $curl, $opts
    );
    $this->data = curl_exec($curl);
    $this->curl_error = curl_error($curl);

    // if($mode=='/v1/delivery/calculate') die (''.print_r([$url, $this->curl_error], true));

    curl_close($curl);
  }

  private function getToken(){

    $curl=curl_init();
    $curlOpts=[
      CURLOPT_URL => $this->authUrl,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 20,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => 'grant_type=client_credentials&client_id='. urlencode($this->clientId).'&client_secret='.urlencode($this->key),
      CURLOPT_HTTPHEADER =>'content-type: application/x-www-form-urlencoded',
    ];
    curl_setopt_array( $curl, $curlOpts );
    $data = curl_exec($curl);

    $curl_error = curl_error($curl);
    curl_close($curl);
    if(mb_strlen($data)) {
      $response=json_decode($data);
      if(isset($response->access_token)) $this->token=$response->access_token;
    }

  }

  public function getPickpointList($isDebug=false){

    $this->createRequest('/v1/delivery/variants?payloadIncludes.includeWorkingHours=false&payloadIncludes.includePostalCode=true');

    $this->data=json_decode($this->data);
    $this->data=$this->data->data;
    foreach ($this->data as $key => $value) {
      if($value->objectTypeName=='Курьерская') unset($this->data[$key]);
      if($value->isCashForbidden) unset($this->data[$key]);
      if(!$value->cardPaymentAvailable) unset($this->data[$key]);
    }
    if ($isDebug) die('array_size is '.sizeof($this->data));

    return $this->data;
  }

  public function getFromPlaces(){//Показывает откуда можно доставлять
    $this->createRequest('/v1/delivery/from_places', 'get');
    return json_decode($this->data);
  }

  public function getVariantsByIds($ids){//Список вариантов по их идентификаторам
    $data='{"ids":['.implode(', ', $ids).']}';
    // echo '<pre>';var_dump($data); echo '</pre>';
    $this->createRequest('/v1/delivery/variants/byids', 'post', $data);
    $this->data=json_decode($this->data);
    // echo '<pre>';var_dump($this->data); echo '</pre>';
    // echo '<pre>';var_dump($this->curl_error); echo '</pre>';
    if(is_object($this->data) && !empty($this->data->data)) {
      foreach ($this->data->data as $point) {
        $points[$point->id]=$point;//json_decode(json_encode($point));
      }
      return $points;
    }
    else return false;
  }

  public function getVariantsTable($address, $types, $debug=false){
    $from=$this->getFromPlaces();
    // var_dump($from);

    $ids=$this->getVariants($address, $types, $debug);
    $points=$this->getVariantsByIds($ids->deliveryVariantIds);
    // die('<pre>!'.__LINE__.'|'.print_r($this->getVariantsByIds($ids->deliveryVariantIds), true).'~~');
    foreach ($ids->deliveryVariantIds as $key => $id) {
      $obj=$this->getDeliveryPrice($from->places[0]->id, $id);
      if(is_object($obj) && isset($obj->amount)) $table[$id]['price']=$obj->amount;
      $obj=$this->getDeliveryTime($from->places[0]->id, $id);
      if(is_object($obj) && isset($obj->days)) $table[$id]['days']=$obj->days;
      if(!empty($points[$id])){
        $table[$id]['detail']=$points[$id];
      }
      // break;
    }
    return $table;
    // die ('<pre>!'.__LINE__.'|'.print_r($table, true));
  }

  public function getDeliveryPrice($from, $to, $weight=5000){//Стоимость доставки
    $data=[
      "deliveryVariantId" => $to,
      "weight" => $weight,
      "fromPlaceId" => $from
    ];
    $this->createRequest('/v1/delivery/calculate', 'get', $data);
    // die ('<pre>!'.__LINE__.'|'.print_r([$data, $this->data], true));
    return json_decode($this->data);
  }

  public function getDeliveryTime($from, $to){//Срок доставки
    $data=[
      "deliveryVariantId" => $to,
      "fromPlaceId" => $from
    ];
    // $data='{"deliveryVariantId":'.$to.', "fromPlaceId":'.$from.'}';
    $this->createRequest('/v1/delivery/time', 'get', $data);
    // die ('<pre>!'.__LINE__.'|'.print_r([$data, $this->data], true));

    return json_decode($this->data);
  }

  public function getVariants($address, $type, $debug=false){
    if(!empty($type)){

      foreach ($type as $value) {
        $types[]='"'.$value.'"';
      }

      $strType='"deliveryTypes":['.implode(', ', $types).'],';
      // die($strType);
    }
    else $strType='"deliveryType":"Courier",';

    $data='{'.$strType.'"address":"'.$address.'","radius":50,"packages":[{"count":1,"dimensions":{"weight":1,"length":1,"height":1,"width":1},"price":100}]}';
    // die($data);
    $this->createRequest('/v1/delivery/variants/byaddress/short', 'post', $data);

    $this->data=json_decode($this->data);
    return $this->data;

  }

  public function getCourierAvaible($address, $debug=false){

    $data='{"deliveryType":"Courier","address":"'.$address.'","radius":50,"packages":[{"count":1,"dimensions":{"weight":1,"length":1,"height":1,"width":1},"price":100}]}';
    /*
      $data=[
        "deliveryType" => "Courier",
        "address" => $address,
        "radius"=> 0,
        "packages" => [
          "count" => 1,
          "dimensions" => [
            "weight" => 1,
            "length" => 1,
            "height" => 1,
            "width" => 1,
          ],
          "price" => 100,
        ],
      ];
      // json_encode превращает packages в объект, а нужен массив
    */

    $this->createRequest('/v1/delivery/variants/byaddress', 'post', $data);

    $this->data=json_decode($this->data);
    // if($debug) var_dump($this->data);
    $this->data=$this->data->data;
    $ids=[];
    foreach ($this->data as $key => $value) {
      $ids[]=$value->id;
      // if($value->objectTypeName!='Курьерская') unset($this->data[$key]);
    }
    return [
      'available' => sizeof($this->data) > 0,
      'ids' => implode('|', $ids),
    ];
    // die('<hr><hr><hr>');
  }

}
?>
