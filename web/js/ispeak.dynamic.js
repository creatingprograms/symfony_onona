/**
 * ispeak.dynamic.js
 * Динамические манипуляции с iSpeakVideo.
 * Библиотека для вставки видео на страницу и расширенного управления
 * ходом воспроизведения.
 * ТОЛЬКО ДЛЯ ИСПОЛЬЗОВАНИЯ С УСЛУГАМИ iSpeakVideo!
 * 
 * @author Дмитрий Соколов
 * @version 0.1.2 [DOW-RESET]
 */

/**
 * Основной объект для работы с API iSpeakVideo
 */
ISpeakVideo = (function() {
	
	var _trim = function(str) {
		return str.replace(/^\s\s*/, '').replace(/\s\s*$/, '');
	};
	
	var _getNode = function(element) {
		if (!element)
			return null;
			
		// Грязная проверка на DOM-элемент
		if (typeof element.parentNode == 'undefined') {
			var node = document.getElementById(element);
			
			if (typeof node == 'undefined')
				throw 'В документе не найден указанный элемент: #' + element;
			
			if (!_test_flash) {
				node.style.display = 'none';
			}
			
			return node;
		}
		
		// Элемент уже готов к возврату
		return element;
	};
	
	var _createDefaultNode = function(width, height) {
		var node = document.createElement('div');
		node.style.width = width + 'px';
		node.style.height = height + 'px';
		node.style.position = 'fixed';
		node.style.right = '0px';
		node.style.bottom = '0px';
		
		return node;
	};
	
	// Хренов IE заставляет писать всю эту дрянь
	var _createUglyObjectNode = function(url, width, height) {
		var node_id = 'tmp_' + new Date().getTime() + Math.round(Math.random() * 10000);
		
		var obj = '<object';

		// Хак для хрома
		if (/(webkit)[ \/]([\w.]+)/.test(navigator.userAgent.toLowerCase())) {
			obj += ' type="application/x-shockwave-flash"';
		}

		obj += ' id="' + node_id + '"';
		obj += ' width="' + width + '"';
		obj += ' height="' + height + '"';
		obj += ((window.ActiveXObject)? ' classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" data="' + url + '"' : '');
		obj += '>';

		obj += '<param name="movie" value="' + url + '">';
		obj += '<param name="wmode" value="transparent">';
		obj += '<param name="AllowScriptAccess" value="always">';

		obj += '<embed src="' + url + '" type="application/x-shockwave-flash" width="' + width + '" height="' + height + '" wmode="transparent" AllowScriptAccess="always"></embed>';
		obj += '</object>';
		
		return obj;
	};
	
	var _getCookieByName = function(name) {
		var cookies = document.cookie.split(';');
		
		for (var i = 0; i < cookies.length; i++) {
			var name_val = cookies[i].split('=');
			
			if (_trim(name_val[0]) == name) {
				return name_val[1];
			}
		}
		
		return '';
	};
	
	var _getPlayCount = function(url, dayLimit) {
		
		var count = 0;
		var plays = _getCookieByName('plays');

		if (plays === '' || _is_year(url, dayLimit))
			return 0;

		var files = plays.split('|');
		
		for (var i = 0; i < files.length; i++) {
			var file_count = files[i].split('$');
			
			if (_trim(file_count[0]) == url)
				count = parseInt(file_count[1], 10);
		}
		
		return count;
	};
		
	var _setPlayCount = function(url, count, dayLimit) {
		var filesInCookie = [url + '$' + count];
		var plays = _getCookieByName('plays');
		
		var files = (plays === '') ? [] : plays.split('|');
		
		for (var i = 0; i < files.length; i++) {
			var file_count = files[i].split('$');
			
			if (_trim(file_count[0]) != url)
				filesInCookie.push(files[i]);
		}
		
		var eDate = new Date();
		eDate.setDate(eDate.getDate() + dayLimit);
		document.cookie = 'plays=' + filesInCookie.join('|') + '; expires=' + eDate.toUTCString();
	};
	
	var _incrementCount = function(url, dayLimit) {
		_setPlayCount(url, _getPlayCount(url, dayLimit) + 1, dayLimit);
	};
	
	var _setLastPlay = function (url, lastPlay, dayLimit) {
		var filesInCookie = [url + '$' + lastPlay.toUTCString()];
		var plays = _getCookieByName('play_dates');
		
		var files = plays.split('|');
		
		for (var i = 0; i < files.length; i++) {
			var file_count = files[i].split('$');
			
			if (_trim(file_count[0]) != url)
				filesInCookie.push(files[i]);
		}

		var eDate = new Date();
		eDate.setDate(eDate.getDate() + dayLimit);
		document.cookie = 'play_dates=' + filesInCookie.join('|') + '; expires=' + eDate.toUTCString();
	};
	
	var _getLastPlay = function(url) {
		var played = new Date(0);
		var plays = _getCookieByName('play_dates');
		
		if (plays === '')
			return new Date(0);
		
		var files = plays.split('|');
		
		for (var i = 0; i < files.length; i++) {
			var file_count = files[i].split('$');
			
			if (_trim(file_count[0]) == url)
				played = new Date(file_count[1]);
		}
		
		return played;
	};
	
	/* Cookie (expired?) */
	
	var _is_year = function(url, dayLimit){
		var now = new Date(),
			now_ms = now.getTime(),
			earlier = _getLastPlay(url),
			earlier_ms = earlier.getTime(),
			delta = now_ms - earlier_ms;
		
		if (delta > 86400000 * dayLimit) { /* > 1 day ? */
			return true;
		} else {
			return false;
		}
	};
	
	var _currentSpeaker = null;
	
	/* Test flash player */
	
	_test_flash = (function() {
		var flash = false;
		if (typeof navigator.plugins != 'undefined' && typeof navigator.plugins["Shockwave Flash"] == 'object') {
			flash = true;
		} else if (typeof window.ActiveXObject != 'undefined') {
			try {
				var a = new ActiveXObject("ShockwaveFlash.ShockwaveFlash");
				if (a) {
					flash = true;
				}
			}
			catch(e) {}
		}
		return flash;
	})();
	
	
	return {


		/**
		 * Создаёт объект видеоспикера, далее служащий для управления видео на странице.
		 * @param {String} url Адрес swf-файла (!) с нужным видео.
		 * @param {Number} width Ширина видео
		 * @param {Number} height Высота видео
		 * @returns {Object} Объект видеоспикера
		 */

		getSpeaker: function(url, width, height) {
			
			var swfURL = (typeof url != 'undefined')? url.toString() : '';
			var	videoWidth = (typeof width !== 'undefined') ? (!!~width.toString().indexOf('%')) ? width : parseInt(width, 10) : 350;
			var	videoHeight = (typeof height !== 'undefined') ? (!!~height.toString().indexOf('%')) ? height : parseInt(height, 10) : 350;
			var	bindedNode = null;
			var loadedFlash = null;
			var temporaryNode = false;
			var playLimit = 0;
			var dayLimit = 1;
			var stopCallback = null;
			
			var speaker = {
				/**
				 * Привязывает видео к элементу на странице
				 * @param element Идентификатор объекта на странице или прямая ссылка на DOM-ноду.
				 */
				bindTo: function(element) {
					if (temporaryNode && bindedNode !== null) {
						document.body.removeChild(bindedNode);
						temporaryNode = false;
					}
					
					bindedNode = _getNode(element);
					ISpeakVideo.stopOnPage();
					_currentSpeaker = this;
				},
				/**
				 * Лимитирует количество воспроизведений этого видео из этого браузера
				 * Вторым параметром указывается длительность блокировки в днях (по умолчанию 1 день)
				 */
				limit: function(count, days) {
					playLimit = parseInt(count, 10);
					if (days) dayLimit = parseInt(days, 10);
				},
				isPlay: function() {
					// Нужно ли воспроизводить вообще?
					if (playLimit > 0 && _getPlayCount(swfURL, dayLimit) >= playLimit) {
						return false;
					}
					return true;
				},
				/**
				 * Воспроизводит видео со спикером. Если заданы лимиты, то проверяет их.
				 * Если не указана нода для вставки, то создаёт наложенную сверху.
				 */
				play: function() {
					// Нужно ли воспроизводить вообще?
					if (!this.isPlay()) {
						if (!!stopCallback) stopCallback();
						return;
					}
					
					if (!_test_flash) {
						return;
					}
					
					ISpeakVideo.stopOnPage();
					_currentSpeaker = this;
					
					if (typeof bindedNode == 'undefined' || bindedNode === null) {
						// Вызван метод без привязки к документу, будет создан элемент по умолчанию
						temporaryNode = true;
						bindedNode = _createDefaultNode(videoWidth, videoHeight);
					} else {
						temporaryNode = false;
					}
					
					// Плеер уже создан?
					if (loadedFlash !== null)
						return;		// В будущем планируем поддержку вызова методов из флэшки
					
					var code = _createUglyObjectNode(swfURL, videoWidth, videoHeight);
					bindedNode.innerHTML = code;
					loadedFlash = bindedNode.firstChild;
					
					if (temporaryNode)
						document.body.appendChild(bindedNode);
						
					_incrementCount(swfURL, dayLimit);
					_setLastPlay(swfURL, new Date(), dayLimit);
				},
				stop: function() {
					
					if (loadedFlash !== null) {
						if (typeof loadedFlash.StopPlay != 'undefined') {
							try {
								loadedFlash.StopPlay();
							}
							catch(e) {}
						}

						bindedNode.parentNode.removeChild(bindedNode);
						if (stopCallback !== null)
							stopCallback(true);
					}
					
					if (temporaryNode && bindedNode !== null) {
						document.body.removeChild(bindedNode);
						bindedNode = null;
						temporaryNode = false;
					}
					
					loadedFlash = null;
				},
				setStopCallback: function(callback) {
					if ((typeof callback).toLowerCase() !== 'function' && callback !== null) {
						return;
					}
					
					stopCallback = callback;
				}
			};



			if (swfURL.toLowerCase().indexOf('.swf') < 0 || videoWidth <= 0 || videoHeight <= 0) {
				throw 'Некорректные параметры для видеоспикера (SWF ' + speaker.swfURL + ', ' + speaker.videoWidth + 'x' + speaker.videoHeight + ' пикс.)';
			}
			
			return speaker;
		},

		Random: function(array, num){
			num = Math.floor(Math.random() * array.length);
			var speaker = ISpeakVideo.getSpeaker(num, width, height);
		},


		/**
		* Возвращает НОВОСТНОГО спикера: особый вид спикеров, сбрасывающих счётчик просмотров по критерию.
		* Новостной спикер наследуется от обычного спикера и поддерживает те же методы.
		* @param {String} url Адрес swf-файла (!) с нужным видео.
		* @param {Number} width Ширина видео
		* @param {Number} height Высота видео
		* @returns {Object} Объект видеоспикера
		*/
		getNewsSpeaker: function(url, width, height) {
			var speaker = ISpeakVideo.getSpeaker(url, width, height);
			
			var _dayOfWeek = -1;
			
			var _checkDOW = function() {
				//  Получаем текущий день недели
				var today = new Date();
				today.setHours(0, 0, 0);
				
				// Получаем последний просмотр
				var lastSeen = _getLastPlay(url);
				lastSeen.setHours(0, 0, 0);
				
				// День недели для последнего просмотра
				var lastDay = lastSeen.getDay();
				if (lastDay === 0)
					lastDay = 7;
				
				// Теперь: если прошло больше недели, то всяко разрешить просмотр
				var dayInterval = Math.floor((today.getTime() - lastSeen.getTime()) / (1000 * 60 * 60 * 24));
				if (dayInterval >= 7) {
					return true;
				}
				
				// Если прошло меньше недели, но дата всё-таки в прошлом и настал (либо прошёл) требуемый день
				// недели...
				var normWeekday = (lastDay + dayInterval) % 7;
				if (dayInterval > 0 && normWeekday >= _dayOfWeek) {
					return true;
				}
				
				return false;
			};
					
			// Расширяем объект спикера
			
			/**
			 * Сбрасывает число просмотров после наступления указанного дня недели
			 * (либо более позднего за ним)
			 */
			speaker.resetOnDOW = function(day) {
				if (day < 1 || day > 7)
					return;
				
				_dayOfWeek = day;
			};
			
			// Перегрузка функции воспроизведения
			var _oldPlay = speaker.play;
			speaker.play = function() {
				// Тут проверка на необходимость сброса числа просмотров
				if (_checkDOW()) {
					// Сбрасываем просмотры
					_setPlayCount(url, 0, 1);
				}
				
				_oldPlay.call(this);
			};
			
			return speaker;
		},
				
		/**
		 * Останавливает спикеров на странице
		 */
		stopOnPage: function() {
			if (_currentSpeaker !== null)
				_currentSpeaker.stop();
		}
	};
	
})();

// Функция, вызываемая флэшкой. Не трогать.
function hideWhenFinished() {
	ISpeakVideo.stopOnPage();
}
