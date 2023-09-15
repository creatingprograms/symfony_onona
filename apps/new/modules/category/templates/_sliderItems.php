<? if (sizeof($products)) : ?>
	<? $i = 0; ?>
	<div class="wrap-block">
		<div class="container">
			<? if (isset($texts['h2'])) : ?>
				<div class="h2 block-title"><?= $texts['h2'] ?>
					<? if (isset($texts['link']) && isset($texts['link-name'])) : ?>
						<a href="<?= $texts['link'] ?>" class="link-text"><?= $texts['link-name'] ?></a>
					<? endif ?>
				</div>
			<? endif ?>
			
			<div class="block-products ">
				<div class="swiper-container sl-products">
					<div class="swiper-wrapper">
						<? foreach ($products as $product) : ?>
							<div class="swiper-slide wrap-sl-product">
								<? include_component(
									"product",
									"productInList",
									array(
										'sf_cache_key' => 'product' . $product->getId() . 'swiper' . '|' . $sf_user->isAuthenticated(),
										'product' => $product,
										'is_swiper' => false,
										'showChoosen' => true,
										'style' => 'short'
									)
								);
								$i++;
								?>
							</div>
						<? endforeach ?>
						<? while ($i++ < 12) : ?>
							<div class="swiper-slide wrap-sl-product empty"></div>
						<? endwhile ?>
					</div>
				</div>
			</div>
		</div>
	</div>

<? endif ?>
