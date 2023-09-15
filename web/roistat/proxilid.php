<?php

function logi($val,$name,$fileName = null){
	$statusLog = 1;
	if($statusLog > 0){
		$data = date('Y-m-d H:i:s');
		$result = "\n[$name ($data)]\n".print_r($val,true);
		file_put_contents($fileName!=null?$fileName:'test.txt', $result."\n##########################################\n" , FILE_APPEND);
	}
}
//logi($form,'Все данные из формы',$_SERVER["DOCUMENT_ROOT"].'/bitrix/roistat/log/form.txt');

function dumpi ($var, $label = '')
{
	if($var === NULL){
		$str = "NULL";
	}
	elseif($var === FALSE){
		$str = "FALSE";
	}
	else{
		$str = json_encode(print_r ($var, true));
	}
	echo "<script>console.group('".$label."');console.log('".$str."');console.groupEnd();</script>";
}

/*Запись доп.полей в комментарий*/

function comm($params,$inputs){
	$comment = '';
	foreach($inputs as $key=>$val){
		if(array_key_exists($key, $params) && !empty($params[$key])){
			switch($key){
				case "products":
					$comment.= "{$params[$key]}\n";
					break;
				default:
					$comment.= "$val: {$params[$key]} \n";
					break;
			}
		}
	}
	return $comment;
}

function proxilid($params){
	$pole = array(
		'products' => 'Товары',
		'comment'  => 'Комментарий',
		'tema'     => 'Тема сообщения',
		'message'  => 'Сообщение',
		'timeCall' => 'Удобное время звонка',
		'delivery' => 'Способ доставки',
		'payment'  => 'Способ оплаты',
	);
	$comment = comm($params,$pole);
	$title = "Заявка с сайта ({$params['form_name']})";

	$roistatData = array(
		'roistat' => isset($_COOKIE['roistat_visit']) ? $_COOKIE['roistat_visit'] : 'no-cookie',
		'key'     => 'MTgyNDEwOjE0NzIyMzowMDcxOTk5MGQyNTJlY2VjMDhlZjdlMTEyNTNlNjQzOA==', // Ключ для интеграции с CRM, указывается в настройках интеграции с CRM.
		'title'   => $title, // Название сделки
		'name'    => !empty($params['name']) ? $params['name'] : null, // Имя
		'comment' => $comment,
		'email'   => !empty($params['email']) ? $params['email'] : null, // Email клиента
		'phone'   => !empty($params['phone']) ? $params['phone'] : null, // Номер телефона клиента
		'fields' => array(
			'orderCreationMethod' => !empty($params['form_name']) ? $params['form_name'] : '',
			'utmSource'           => '{utmSource}',
			'utmMedium'           => '{utmMedium}',
			'utmCampaign'         => '{utmCampaign}',
			'utmTerm'             => '{utmTerm}',
			'utmContent'          => '{utmContent}'
		)
	);

	if(!empty($params['sex'])){
		$roistatData['fields']['sex'] = $params['sex'] == 'm' ? 'Мужской':'Женский';
	}

	if(!empty($params['birthday'])){
		$roistatData['fields']['birthday'] = "{$params['birthday']['day']}.{$params['birthday']['month']}.{$params['birthday']['year']}";
	}

	if(!empty($params['city'])){
		$roistatData['fields']['city'] = $params['city'];
	}


	$result = '';
	if((!empty($params['email']) || !empty($params['phone'])) && isset($_COOKIE['roistat_visit'])){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, null);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, null);
		curl_setopt($ch, CURLOPT_URL,'https://cloud.roistat.com/api/proxy/1.0/leads/add?' . http_build_query($roistatData));
		$result = curl_exec($ch);
		curl_close($ch);
		//unset($roistatData['key']);
		$logic = array('params'=>$params,'result'=>$result,'roistatData'=>$roistatData);
		logi($logic,'Все данные из формы',__DIR__."/log/form.txt");
	}
}