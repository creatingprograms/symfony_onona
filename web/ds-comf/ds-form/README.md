#  DS Form

## Подключение

Скрипт формы подключается после jQuery (обычно в <head>):

```html
<script type="text/javascript" src="/ds-comf/ds-form/js/dsforms.js"></script> 
```

Корневую директорию (по умолчанию /ds-comf/ds-form/) всех файлов дистрибутива можно указать в файле js/dsforms.js, если требуется:

```js
dsformROOT = '/mydir/';
```


Минимальная версия фреймворка: 1.5. Если jQuery не подключен или версия ниже требуемой, то в консоли выводится ошибка:

```
Version jQuery < 1.5 or jQuery is not found!
```

Скрипт без конфликтов подключаем так:

```html
<script type="text/javascript" src="/ds-comf/lib/jquery-1.11.3.min.js"></script>
<script type="text/javascript">
    gKweri = $.noConflict(true);
</script>
<script type="text/javascript" src="/ds-comf/ds-form/js/dsforms.js"></script>
```

Далее пользуемся переменной gKweri, везде где необходима нужная нам версия.

## Инициализация скрипта

Для того, чтобы вывести форму в блоке, инициализируем элемент (группу элементов) как объект формы:

```js
$(document).ready(function(){
    $('.form-block').dsform({
        formID : 'id формы',
        modal : false,
    });
});
```

Чтобы назначить всплывающую форму по клику на элементе:

```js
$(document).ready(function(){
    $('.button').dsform({
        formID : 'id формы',
    });
});
```

### Быстрое создание кнопок и блоков.

Внутри скрипта есть надстройка, которая инициализирует все элементы имеющие атрибут data-dspopup-id, как кнопки
и имеющие class="ds-form" как блоки. Таким образом можно быстро создать требуемые формы вставив в нужном месте в контенте:

* для блоков

```html
<div id="id формы" class="ds-form"></div>

``` 

* для кнопок

```html
<button data-dspopup-id="id формы">Форма обратной связи</button>

``` 
Такой метод является более простым, но обладает рядом недостатков:

- не всегда есть возможность указать необходимые атрибуты, например админка может резать пустые div'ы или атрибут data-dspopup-id
- используются только минимальные настройки инициализации

### Настройки формы при инициализации

Полный перечень настроек:

* **formID**: *(строка)* - id формы назначаемой на элемент
* **modal**: *(флаг)* - использовать назначенный элемент, как кнопку для всплывающей формы, по умолчанию true
* **additionalClass**: *(строка)* - добавляет класс контейнеру в котором находится форма
* **config**: *(строка)* - строка в JSON формате, то же самое что и атрибут data-dsconfig
* **inputmask**: *(объект)* - объект формата {'имя поля': {объект настроек}}, для inputMask, см. "Подключаемые плагины"
* **dates**: *(объект)* - объект формата {'имя поля': {объект настроек}}, для dscalendar, см. "Подключаемые плагины"
* **showLoader**: *(флаг)* - определяет, показывать ли анимацию загрузки на месте "кнопки отправить", по умолчанию true
* **useFormStyler**: *(строка или флаг)* - определяет использовать ли плагин Form Styler для формы, по умолчанию false. Если указать true, то стилизует все возможные элементы, можно указать строку с селекторами, чтобы ограничить стилизацию
* **formstyler**: *(объект)* - настройки специфичные для плагина Form Styler, см. "Подключаемые плагины"
* **onLoad**: *(функция)* - функция по событию загрузки кода формы, срабатывает при обновлении формы, а именно:
	- при загрузке страницы у блоков
	- для всплывающих форм срабатывает каждый раз, когда происходит клик по новой кнопке для формы, например если сделать две кнопки которые будут открывать одну и ту же форму и нажимать их поочередно. При клике по одной и той же кнопке загрузка формы происходит только в первый раз.
	- при клике на элемент с классом repeatform, который перезагружает форму.
* **onShow**: *(функция)* - функция сработает по завершению анимации открытия модального окна.
* **onSuccess**: *(функция)* - функция сработает, если сервер сообщит об успешной отправке письма, например если письмо действительно отправилось
* **onFail**: *(функция)* - функция сработает, если в форме были ошибки заполнения полей
* **onClose**: *(функция)* - функция сработает по завершению анимации закрытия модального окна
* **onServerError**: *(функция)* - функция сработает, если сервер сообщит о неудачной отправке письма
* **animationspeed**: *(число)* - скорость анимации модального окна в миллисекундах, на всякий случай
* **closeonbackgroundclick**: *(флаг)* - определяет будет ли окно закрываться по клику на фоне, по умолчанию true
* **dismissmodalclass**: *(строка)* - можно переназначить класс кнопки закрытия, принимает именно className, а не селектор

Пример инициализации:

```js
$('.test-open').dsform({
    formID: 'feedback',
    additionalClass: 'my-class', 
    inputmask: {'phone': {placeholder: '#'}},
    dates: {'eventdate': '2.12.2015',
            'leavedate': 3,
            },
    config: '{"product": "' + $('h1').eq(0).text() + '"}',
    animationspeed: 0, 

    useFormStyler: 'select#stylish',

    onLoad: function () { 
        console.log('Загрузили');
    },
    onShow: function () {
        console.log('Открыли');
    },
    onSuccess: function () {
        console.log('Получилось!');
    },
    onFail: function () {
        console.log('Взрослых попроси заполнить');
    },
    onClose: function () {
        console.log('Закрыли');
    },
});
```



### Особенности объектной модели
Все формы будут вести себя независимо друг от друга, так как работают в контексте своих элементов. Инициализируем две кнопки, которые будут открывать одну и ту же форму:
```js
$('.btn').dsform({formID: 'sample'});

$('.btn-neat').dsform({
	formID: 'sample',
	additionalClass: 'neat',
	useFormstyler: true,
});

```
В данном примере при нажатии первой кнопки будет всплывать обычное модальное окно, при нажатии второй кнопки,
 несмотря на то что обе используют один и тот же контейнер, будет использоваться стилизация и добавляться класс.
Несколько одинаковых блоков будет проходить валидацию и отправляться независимо друг от друга, несмотря на одинаковые  атрибуты name у соответствующих полей.



## Автозаполнение полей

Автозаполнение полей происходит, если у элемента указывается атрибут data-dsconfig со значением в виде валидной JSON строки: 

```html
<button data-dspopup-id="primer" data-dsconfig="{'name':'Иннокентий','gorod':{'0':{'text':'Воронеж','value':'Voronez','select':''}, '1':{'text':'Урюпинск','value':'Urupinsk'}}}">ClickMe</button>
```

Преимущества такого способа это простота, доступность, возможность указать разные значения для схожих элементов. Недостатками являются статичность значений, админка может резать атрибут.

Использование параметра config при инициализации позволяет динамически определять содержимое для заполнения, например используя траверсинг:
```html
<div class="productunit">
	<div class="product-name">Макарошка</div>
	<div class="prices">
		100 руб. <button>Хочу!</button>
	</div>
</div>

<div class="productunit">
	<div class="product-name">Пюрешка</div>
	<div class="prices">
		200 руб. <button>Хочу!</button>
	</div>
</div>

<script>
$('.productunit .prices>button').each(function () {
	$(this).dsform({
	   formID: 'button',
	   config: '{"prod":"'+ $(this).parents('.productunit').find('.product-name').text() +'"}',
    });
});
</script>
```

Этот способ подходит если, например товары сделаны статикой, чтобы не прописывать атрибут вручную.

> **ВАЖНО!** Если значения для заполнения поля указано и в скрипте и атрибутом у элемента, атрибут будет приоритетным, причём следует учесть, что меняется вся JSON строка, а не только значение для конкретного поля.



## Подключаемые плагины

На данный момент скрипт автоматически подключает при необходимости другие скрипты, список которых возможно расширить. 

Перед подключением скрипт сначала пытается найти и использовать ранее подключённый плагин. Если требуемый плагин уже был подключен в консоли выводится информация: 

```
DSFORM: formstyler was loaded before
```

Для некоторых плагинов требуется раскомментировать css файл в папке стилей. Ссылки на документацию по плагинам можно получить, выполнив в консоли:

```js
$.dsform.uses()
```

> **ВАЖНО!** Плагины подключаются в конец head и могут не работать, если, например, переопределяется jQuery. Если скрипт формы подключен с помощью  noConflict, можно поменять аргумент jQuery самоисполняющейся функции в конце кода плагинов на gKweri.
> Также можно попробовать подключить скрипты самостоятельно в код страницы. 

### Form Styler

Плагин jQuery для изменения внешнего вида:

* input[type="radio"]
* input[type="checkbox"]
* input[type="file"]
* select

Подключается автоматически если указан параметр useFormStyler при инициализации формы. Значением должно быть true, 
если требуется использовать плагин для всех поддерживаемых типов полей, либо строка с селектором, если требуется использовать плагин только для конкретных элементов.
Также при инициализации можно указать дополнительные настройки в параметре formstyler, например:

```js
$('.btn').dsform({
	formID: 'sample',
	useFormstyler: true,
	formstyler: {
		onSelectOpened: function () { console.log('Select opened!'); },
		idSuffix: '-dsstyle'
	}
});

```

Подробная документация по плагину доступна на http://dimox.name/jquery-form-styler

#### Особенности

Плагин использует jQuery > 1.7. Если версия фреймворка ниже требуемой, то в консоли выводится сообщение, форма продолжит работать.

Версия в дистрибутиве немного изменена: у плагина есть баг — даже если у select есть выбранная опция, изначально выводилась заглушка "Выберите пункт". Убрана.


### Input Mask

Input Mask — форк плагина Masked Input. Подключается автоматически, если у поля input[type="text"] есть атрибут data-dsform-mask.

Атрибут с нужным значением необходимо указать в массиве attributs поля в шаблоне формы.

```php
'data-dsform-mask' => '+7 (999) 999-99-99',
```

Если атрибут у поля указан есть возможность указать опции плагина в параметре inputmask при инициализации:

```js
$('.btn').dsform({
	formID: 'sample',
	inputmask: {
		'serial': {
			mask: '999-AAA',
			placeholder: ' '
		}	
	}
});
```

Как видно из примера, есть даже возможность переназначить маску изначально указанную в атрибуте. Также плагин использует опции указанные, как атрибуты поля. 

Приоритетность опций такая (от низшего к высшему):
 
* атрибут data-dsform-mask (если атрибута нет, то следующие опции к полю не применятся)
* атрибут data-inputmask (поддерживается самим плагином)
* значения, определенные при инициализации в параметре inputmask

Плагин имеет очень много возможностей и настроек, подробная документация на https://github.com/RobinHerbots/jquery.inputmask

#### Особенности

Версия в дистрибутиве немного изменена, для поддержки jQuery 1.5 (методы .on() и .off() заменены на .bind() и .unbind()).

### DScalendar

Добавляет кнопку показывающую календарь для выбора даты к полю input[type="text"]. Подключается автоматически, если у поля существует атрибут data-dsform-date, в котором указывается формат вставляемой даты:


```php
'data-dsform-date' => '{D} {MMMMM} {YYYY} года', //выведет 5 Июля 2014 года
```

Псевдонимы данных:

- {D} - день без нуля
- {DD} - день с нулём
- {M} - месяц  без нуля
- {MM} - месяц с нулём
- {MMM} - месяц вида "янв" или "мар", с маленькой буквы
- {MMMM} - месяц вида "Сентябрь" с большой
- {MMMMM} - месяц  вида "Июня" в р.п. с большой
- {YY} - последние цифры года
- {YYYY} - год 

При инициализации можно указать начальную дату для поля, в формате 'имя поля':'настройка' : 

```js
$('.btn').dsform({
	formID: 'sample',
	dates: {
		'fieldname1': '05.07.1964',  // календарь будет установлен на это число
		'fieldname2': '00.12.1989', // будет установлено сегодняшнее число, но заданный месяц и год
		'fieldname3': -3              // сдвигает относительно текущей даты на указанное число месяцев
	}
});
```

#### Особенности

Скрипт был написан специально для формы и имеет минимум возможностей, работает без фреймворков.