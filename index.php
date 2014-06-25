<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("1С-Битрикс: Управление сайтом");
?>

<?
	require_once('.index.items.php');
	$prod = new WRProducts(1);
	$prod->setIndexIblock(13);
	
?>
			<div class="items">
				<?
					$topItems = array(364, 365, 366);
					foreach($topItems as $topItem)
						echo $prod->drawSmallProduct($topItem);
					
				?>			
			</div>
		
		
			<div class="block-text">
				<h3>Сегодняшнее предложение</h3>
				<?=$prod->drawBigProduct(367);?>
				<!--
				<div class="white-block">
					<div class="w-left"><img src="/src/images/fl-05.jpg" alt=""></div>
					<div class="w-right">
						<span>ПРЕДЗАКАЗ</span>
						<h3>Название товара</h3>					
						<div class="dsc">C точки зрения банальной эрудиции каждый индивидуум, критически мотивирующий абстракцию, не может игнорировать критерии утопического субъективизма, концептуально интерпретируя общепринятые дефанизирующие поляризаторы, поэтому консенсус, достигнутый диалектической материальной классификацией всеобщих мотиваций в парадогматических связях предикатов, решает проблему усовершенствования формирующих </div>
						<div class="price">1900 р.</div>					
					
					</div>
				
				</div>
				-->
			</div>
		
		
			<div class="block-text2">
				<h4>Недавно добавленные</h4>
				<div class="items">
				<?
					$topItems = array(368, 369, 370);
					foreach($topItems as $topItem)
						echo $prod->drawSmallProduct($topItem);
					
				?>				
<!--
					<div class="item-block">
						<img src="/src/images/fl-02.jpg" alt="">					
						<span>ПРЕДЗАКАЗ</span>
						<h4>Название товара</h4>					
						<div class="dsc">C точки зрения банальной эрудиции каждый индивидуум, критически мотивирующий абстракцию, не может игнорировать критерии утопического субъективизма</div>
						<div class="price">1900 р.</div>
					</div>
					<div class="item-block">
						<img src="/src/images/fl-03.jpg" alt="">					
						<span>ПРЕДЗАКАЗ</span>
						<h4>Название товара</h4>					
						<div class="dsc">C точки зрения банальной эрудиции каждый индивидуум, критически мотивирующий абстракцию, не может игнорировать критерии утопического субъективизма</div>
						<div class="price">1900 р.</div>
					</div>
					<div class="item-block">
						<img src="/src/images/fl-04.jpg" alt="">					
						<span>ПРЕДЗАКАЗ</span>
						<h4>Название товара</h4>					
						<div class="dsc">C точки зрения банальной эрудиции каждый индивидуум, критически мотивирующий абстракцию, не может игнорировать критерии утопического субъективизма</div>
						<div class="price">1900 р.</div>
					</div>
-->
				
				</div>				
			
			</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>