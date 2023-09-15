<? if(sizeof($products)) :?>
	<? if(isset($texts['v-block-mod'])) :?>
		<div class="v-block-wrapper <?= $texts['v-block-mod'] ?>">
	<? endif ?>
	<section class="v-block wrapper <?= isset($texts['v-block-mod']) ? $texts['v-block-mod'] : ''?>">
		<div class="v-block-plate">
			<h2><?=$texts['h2']?></h2>
		</div>
		<? if(isset($texts['timer'])) :?>
			<div class="dopinfo-action-timer">
				<div class="dopinfo-action-timer-head">Успей купить!</div>
				<span class="js-countdown dopinfo-action-timer-timer" data-until="<?=$texts['timer']?>"></span>
				<div class="dopinfo-action-timer-text">осталось до конца акции</div>
			</div>
		<? endif ?>
		<div class="v-block-wrap-slider -typeSlider">
			<div class="cat-list swiper-slider swiper-container -full">
				<div class="swiper-wrapper gtm-items-list gtm-list-not-send" data-cat-name="<?= strtolower($texts['h2']) ?>">
					<? foreach($products as $product) :?>
						<div class="swiper-slide">
							<? include_component(
								"product",
								"productInList",
								array(
									'sf_cache_key' => 'product'.$product->getId().'swiper',
									'product'=>$product,
									'is_swiper' => true
								)); ?>
						</div>
					<? endforeach ?>
				</div>
			</div>
			<div class="swiper-button-prev"><svg> <use xlink:href="#backArrowIcon"></use> </svg></div>
			<div class="swiper-button-next"><svg> <use xlink:href="#backArrowIcon"></use> </svg></div>
		</div>
		<? if($texts['link-name']) :?>
			<div class="v-block-link"><a href="<?=$texts['link']?>"><?=$texts['link-name']?></a></div>
		<? endif ?>
	</section>
	<? if(isset($texts['v-block-mod'])) :?>
</div>
	<? endif ?>
<? endif ?>
