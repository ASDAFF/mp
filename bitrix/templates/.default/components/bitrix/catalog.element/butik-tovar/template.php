<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>			
			<div class="block-t">
				<div class="slider">
				<?
					if(intval($arResult['DETAIL_PICTURE']['ID'])){
						$file = CFile::ResizeImageGet($arResult['DETAIL_PICTURE']['ID'], array('width'=>960*1.5, 'height'=>10000), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true, false, false, 100);
				?>
					<!-- Видео (vimeo) + фото (https://grandst.com/p/toymailco) --><img src="<?=$file['src']?>" style="width:960px;" alt="">
				<?
					}
				?>
				</div>
				<div class="col-01">
					<!-- (Инвестор и продавец, отдельны инфоблок с описанием, и возможностью фильтрации по ним) -->
					<!-- <div class="brand"><img src="/src/images/tov_07.jpg" alt=""></div> -->
					<?
						if(intval($arResult['PROPERTIES']['SELLER']['VALUE'])){
							$res = CIBlockElement::GetByID($arResult['PROPERTIES']['SELLER']['VALUE']);
							if($ar_res = $res->GetNext()){
								$file = CFile::ResizeImageGet($ar_res['PREVIEW_PICTURE'], array('width'=>150, 'height'=>150), BX_RESIZE_IMAGE_PROPORTIONAL, true);
								?>
								<div class="tpad"><div class="cab-img2"><a href="#" title="<?=$ar_res['NAME'];?>"><img style="width:100px;" src="<?=$file['src']?>" alt=""></a></div></div>
								<?
							}
						}
					?>
					
				</div>
				<div class="col-02">
					<div class="like">14</div>
					<h1><?=$arResult['NAME']?></h1>
					<p><?=$arResult['PROPERTIES']['DESCRIPTION']['VALUE']['TEXT']?></p>
					<div class="soc">
						<ul>
							<li>
<script type="text/javascript">(function() {
  if (window.pluso)if (typeof window.pluso.start == "function") return;
  if (window.ifpluso==undefined) { window.ifpluso = 1;
    var d = document, s = d.createElement('script'), g = 'getElementsByTagName';
    s.type = 'text/javascript'; s.charset='UTF-8'; s.async = true;
    s.src = ('https:' == window.location.protocol ? 'https' : 'http')  + '://share.pluso.ru/pluso-like.js';
    var h=d[g]('body')[0];
    h.appendChild(s);
  }})();</script>
<div class="pluso" data-background="transparent" data-options="medium,square,line,horizontal,nocounter,theme=04" data-services="facebook,vkontakte,twitter,email"></div>

</li>

							<li><a href="#">Добавить в Wishlist</a></li>
						</ul>
					</div>
					
				</div>
				<div class="col-03">
					<?
						$offers = array();
						$price = 0;
						if(!empty($arResult['OFFERS'])){
							foreach($arResult['OFFERS'] as $offer){
								$offen_name = array();
								foreach($offer['PROPERTIES']['CML2_ATTRIBUTES']['VALUE'] as $key => $value){
									$offen_name[] = $value;
								}
								$offers[$offer['ID']] = '<option value="'.$offer['BUY_URL'].'">'.implode(', ', $offen_name).'</option>';
								$price = $offer['CATALOG_PRICE_1'];
							}
						}else{
							$price = $arResult['CATALOG_PRICE_1'];
						}
					?>
					<div class="tprice"><?=number_format($price, -1, ',', ' ' );?> р.</div>
					<? if(!empty($offers)){ ?>
					<div class="styled-select4">
						<select class="offers">
							<?=implode('', $offers);?>
						</select>
					</div>
					<? }?>
					<div class="cabinet-box3">
						<ul>
							<li>
								<?if ($USER->isAuthorized()) : ?>
									<a class="buy-link" href="<?=$arResult['BUY_URL']?>">Купить</a>
								<? else : ?>
									<a class="open-auth save-buy" href="/personal/">Купить</a>
								<?endif; ?>
							</li>							
						</ul>
					</div>	
					<div class="ticon">C точки зрения банальной эрудиции. (статусы наличия с иконками)</div>
				</div>
			</div>
			<div class="col-04">
				<div class="section2">
					<ul class="tabs2">
						<li class="current2">Описание</li>
						<li>Комментарии <span class="comm">28</span></li>
					</ul>
					<div class="box2 visible">
						<div class="cat-text">
							<?=$arResult['DETAIL_TEXT'];?>
						</div>
					</div>
				</div>		
			</div>
			<div class="col-05">
				<?
					function getProductTags($values){
						$result = array();
						foreach($values as $ID){
							$res = CIBlockElement::GetByID($ID);
							if($ar_res = $res->GetNext()){
								$url = '/butik/';
								switch($ar_res['IBLOCK_ID']){
									case 10:
										$url .= '?gift='.$ar_res['ID'];
										break;
									case 11:
										$url .= '?facility='.$ar_res['ID'];
										break;
								}
								$result[] = '<a href="'.$url.'">'.$ar_res['NAME'].'</a>';
							}
						}
						return $result;
					}
					
					function getProductCat($values){
						$result = array();
						foreach($values as $ID){
							$res = CIBlockSection::GetByID($ID);
							if($ar_res = $res->GetNext()){
								$url = '/butik/';
								if(isset($ar_res['IBLOCK_SECTION_ID'])){
									$url .= '?catalog='.$ar_res['IBLOCK_SECTION_ID'].'&section='.$ar_res['ID'];
								}else{
									$url .= '?catalog='.$ar_res['ID'];
								}
								$result[] = '<a href="'.$url.'">'.$ar_res['NAME'].'</a>';
							}
						}
						return $result;
					}
					
					function getProductAllTags($arResult){
						$allTags = array();
						$tag_iblock_el = array('CATALOG', 'BRAND','GIFT','FACILITY');
						foreach($tag_iblock_el as $iblock){
							if($iblock == 'CATALOG')
								$tags = getProductCat($arResult['PROPERTIES'][$iblock]['VALUE']);
							else
								$tags = getProductTags($arResult['PROPERTIES'][$iblock]['VALUE']);
							$allTags = array_merge($allTags, $tags);
						}
						
						return '<li>'.implode('</li><li>', $allTags).'</li>';
					}
				?>

				<ul class="r-menu">
					<?=getProductAllTags($arResult);?>
				</ul>
				
				<?
				if(!empty($arResult['PROPERTIES']['RECOMMEND']['VALUE'])){
					require_once($_SERVER['DOCUMENT_ROOT'].'/.index.items.php');
										
					
					function drawRecommend($target){
						$prod = new WRProducts(1);
						$result=array();						
						foreach($target as $item){
							$data = $prod->getProduct($item);
							//echo '<pre>'; print_r($data); echo '</pre>';				
							//$file = CFile::ResizeImageGet($item['PREVIEW_PICTURE'], array('width'=>217*1.5, 'height'=>144*1.5), BX_RESIZE_IMAGE_EXACT, true);
														
							$result[] = '
								<div class="r-rec">
									<a href="/butik/'.$data['CODE'].'/" style="text-decoration:none; color:#000;">
										<img src="'.$data['PREVIEW_PICTURE'].'" style="width:217px; height:144px;" alt="">
										<p>'.$data['NAME'].(!empty($data['TEXT'])?': '.$data['TEXT']:'').'</p>
									</a>
									<p class="rprice">'.$data['PRICE'].' р.</p>
								</div>
							';
						}
						return implode('', $result);
					}
					
					echo '<h3>Рекомендуемые товары</h3>'.drawRecommend($arResult['PROPERTIES']['RECOMMEND']['VALUE']);
				}
				?>
			</div>