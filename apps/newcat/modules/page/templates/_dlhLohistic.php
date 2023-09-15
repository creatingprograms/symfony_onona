<? slot('js-euroset', true)?>
<div class="dlh-map" id="map">
	&nbsp;</div>
<script>
	var mapWidget = new MapWidget(147, "map", function() {
		alert('Точная стоимость доставки будет рассчитана в корзине')
	}, {
		'size': 'xs'
	});
	// запустить инициализацию виджета
	mapWidget.init();
</script>
