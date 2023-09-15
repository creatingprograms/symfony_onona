<?php
class CurlClient {
	private $curl;
	private $credentials;
	private $host;
	private $result;
	function __construct( $uname, $pass, $host, $shop_key){
		$this->host = $host;
		$this->curl = curl_init();
		curl_setopt( $this->curl , CURLOPT_POST, true);
		curl_setopt( $this->curl , CURLOPT_RETURNTRANSFER , TRUE);
		curl_setopt( $this->curl , CURLOPT_COOKIESESSION , TRUE);
		curl_setopt( $this->curl , CURLOPT_COOKIEJAR, "cookie.txt");
		curl_setopt( $this->curl , CURLOPT_COOKIEFILE, "cookie.txt");

		$this->credentials = array(
				'password' => $pass,
				'username' => $uname,
				'shopKey' => $shop_key );
		$this->authorize();
	}
	public function authorize(){
		$this->exec(array( 'sign/in?do=signInForm-submit' , $this->credentials ))->result();
	}
	public function exec($data){
		$GET = '';
		if( is_array($data) ){
			$GET = $data[0];
			curl_setopt( $this->curl , CURLOPT_POSTFIELDS, $data[1]);
		}else{
			$GET = $data;
		}
		curl_setopt( $this->curl , CURLOPT_COOKIESESSION , TRUE);
		curl_setopt( $this->curl , CURLOPT_URL, $this->host . '/' . $GET);
		$this->result = curl_exec( $this->curl );
		return $this;
	}
	public function json_result(){
		try{
			return json_decode($this->result, true);
		}catch(Exception $e){
			return false;
		}
	}
	public function result(){
		return $this->result;
	}
}


/**
#Example of use
$ApiC = new CurlClient( 'opupenko@gmail.com', 'temp123!' , 'http://80.252.242.212' );
print "GET ORDERS : ".$ApiC->exec( 'shop/api/Orders/get/new')."\n";
print "ADD ORDERS : ".$ApiC->exec( array(
			'shop/api/Orders/add/',
			array("json" =>
				json_encode(
					array(
						'Orders' => 'Some Orders'
					     )
					)
			     )
			)
		)."\n";
**/
