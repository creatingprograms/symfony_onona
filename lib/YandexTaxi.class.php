<?php
  class YandexTaxi {
    const   API_BASE='https://b2b.taxi.yandex.net/b2b/cargo/integration';
    private $token;
    private $baseLat;
    private $baseLon;
    private $baseAddr;
    private $basePhone;
    private $baseEmail;
    private $baseName;
    private $pickupCode;

    public function  __construct($token, $basePoint=false, $params){
      $this->token=$token;
      if(is_array($basePoint)){
        $this->baseLat=1*$basePoint[0];
        $this->baseLon=1*$basePoint[1];
      }
      elseif(sizeof($coords=explode('|', $basePoint))==2){
        $this->baseLat=1*$coords[0];
        $this->baseLon=1*$coords[1];
      }
      else{
        $this->baseLat=55.755819;
        $this->baseLon=37.617644;
      }
      $this->baseAddr=$params['full_address'];
      $this->basePhone=$this->cleanPhone($params['phone']);
      $this->baseEmail=$params['email'];
      $this->baseName=$params['name'];
      $this->pickupCode = $params['pickup_code'] ? $params['pickup_code'] : '456456';
    }

    public function getClaimStatus($orderId){//Получение состояния заявки
      $url='/v2/claims/info?claim_id='.$orderId;
      $data=false;
      return $this->sendPost($url, $data);
    }

    public function claimCancel($orderId){//Отмена ранее созданной заявки
      $url='/v1/claims/cancel?claim_id='.$orderId;
      $claim=$this->getClaimStatus($orderId);
      $data=[
        "cancel_state" => "free",
        "version"=> 1*$claim->version,
      ];
      return $this->sendPost($url, $data);
    }

    public function claimAccept($orderId){//Принять ранее созданную заявку
      $url='/v1/claims/accept?claim_id='.$orderId;
      $claim=$this->getClaimStatus($orderId);
      $data=[
        "version"=> 1*$claim->version,
      ];
      return $this->sendPost($url, $data);
    }

    public function getTarifs($point){//Получение доступных тарифов для точки
      $url='/v1/tariffs';
      $data=[
        "start_point" => [ $this->baseLon, $this->baseLat ]
      ];

      return $this->sendPost($url, $data);

    }

    public function getDeliveryPrice($point, $products){//Получение стоимости доставки
      $url='/v1/check-price';
      foreach ($products as $product) {
        $items[]=[
          'quantity' => 1*$product['quantity'],
          /*'size' =>[
            'height' => 0.05,
            'length'=> 0.15,
            'width'=> 0.1,
          ],*/
          'weight' => 1*$product['weight']
        ];
      }

      $data=[
        'items'=>$items,
        'requirements' => [
          "pro_courier"=> false,
          "taxi_class"=> "courier"
        ],
        "route_points" => [
          ['coordinates'=>[$this->baseLon, $this->baseLat]],
          ['coordinates'=>[1*$point['lon'], 1*$point['lat']]]
        ],
        "skip_door_to_door"=> false
      ];

      return $this->sendPost($url, $data);
    }

    public function createClaim($point, $products, $userData){//Создает заявку
      $url='/v2/claims/create?request_id='.uniqid();
      $i=1;
      foreach ($products as $product) {// Готовим массив с товарами
        $items[]=[
          'quantity' => 1*$product['quantity'],
          "pickup_point" => 1, //точка приема
          "droppof_point" => 2,//Точка сброса
          "cost_currency" => "RUB",
          "title" => $product['name'] ? $product['name'] : 'Товар №'.$i++,
          "cost_value" => ''.$product['price_final'],
          'weight' => 1*$product['weight']
        ];
      }

      $data=[
        "client_requirements" => [
          "taxi_class" => "courier"
        ],
        "emergency_contact" => [
          "name" => $this->baseName,
          "phone" => $this->basePhone
        ],
        "items"=> $items,
        "route_points" => [
          [
            "address" => [
              "coordinates" => [$this->baseLon, $this->baseLat],
              "fullname" => $this->baseAddr
            ],
            "contact" => [
              "email" => $this->baseEmail,
              "name" => $this->baseName,
              "phone" => $this->basePhone
            ],
            "pickup_code" => $this->pickupCode,
            "point_id" => 1,
            "type" => "source",
            "visit_order" => 1
          ],
          [
            "address" => [
              "coordinates" => [1*$point['lon'], 1*$point['lat']],
              "fullname" => $point['full']
            ],
            "contact" => [
              "email" => $userData['email'],
              "name" => $userData['name'],
              "phone" => $this->cleanPhone($userData['phone'])
            ],
            "point_id" => 2,
            "type" => "destination",
            "visit_order" => 2
          ],
        ],
        "skip_act" => false,
        "skip_door_to_door" => false
      ];

      return $this->sendPost($url, $data);
    }

    private function cleanPhone($phone){
      return str_replace([' ', '(', ')', '-'], '', $phone);
    }

    private function sendPost($url, $data){
      // var_dump($data);echo '<br>';
      // die('----------------');
      $curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_URL => self::API_BASE.$url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => array(
          'Content-Type: application/json',
          'Accept-Language: ru',
          'Authorization: Bearer '.$this->token
        ),
      ));

      $response = curl_exec($curl);

      curl_close($curl);
      return json_decode($response);
    }

  }
?>
