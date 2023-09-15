<?php

return array(
	'mail'  => array(
        'to_email'   => array('morozova@onona.ru','atroshenko@onona.ru', 'ays@onona.ru', 'povar@onona.ru'),
		'subject'    => 'Запрос с формы Быстрый заказ',
	),
	'configform' => array(
        /* HTML код */
        array(
        'type'      => 'freearea',
        'container' => false,
        'value'     => '<div class="form-head">Быстрый заказ</div>',
        ),

        /* Однострочный текст */
        array(
        'type'      => 'input',
        'error'     => 'Поле "Наименование товара" заполнено некорректно',
        'formail'   => 1,
        'name_mail' => 'Наименование товара',
        'attributs' => array(
                            'id'          => 'product_name',
                            'name'        => 'product_name',
                            'type'        => 'text',
                            'placeholder' => '',
                            'value'       => '',
                            'pattern'     => '',
                            'readonly' => 'readonly',
                        ),
        ),


        /* Однострочный текст */
        array(
        'type'      => 'input',
        'label'     => 'Ваше имя',
        'error'     => 'Поле "Ваше имя" заполнено некорректно',
        'formail'   => 1,
        'name_mail' => 'Имя',
        'attributs' => array(
                            'id'          => 'field-id203412',
                            'name'        => 'field-name203412',
                            'type'        => 'text',
                            'placeholder' => '',
                            'value'       => '',
                            'pattern'     => '',
                        ),
        ),

        /* Однострочный текст */
        array(
        'type'      => 'input',
        'label'     => 'Ваш телефон (*)',
        'error'     => 'Поле "Ваш телефон" заполнено некорректно',
        'formail'   => 1,
        'name_mail' => 'Телефон',
        'attributs' => array(
                            'id'          => 'field-id238580',
                            'name'        => 'field-name238580',
                            'type'        => 'text',
                            'placeholder' => '+ 7 (___) ___-__-__',
                            'value'       => '',
                            'data-dsform-mask' => '+7 (999) 999-99-99',
                            'required'    => 'required',
                            'pattern'     => '^\+?[\d,\-,(,),\s]+$',
                        ),
        ),

		/*--Ваше сообщение--*/
		array(
			'type'      => 'textarea',
			'container' => true,
			'label'     => 'Комментарий',
			'error'     => 'Поле "Комментарий" заполнено некорректно!',
			'formail'   => 1,
			'name_mail' => 'Комментарий',
			'attributs' => array(
								'name'        => 'message',
								'type'        => 'text',
								'rows'        => '8',
								'cols'        => '46',
								'value'       => '',
								'placeholder' => '',
								'value'       => '',
						   ),
			),


        /*--Кнопка--*/
        array(
        'type'      => 'input',
        'class'     => 'buttonform',
        'attributs' => array(
                            'type'  => 'submit',
                            'value' => 'Отправить заказ',
                        ),
        ), 

        /*--Блок ошибок--*/
        array(
        'type'      => 'freearea',
        'container' => false,
        'value'     => '<div class="error_form"></div>',
        ),
    ),
);
