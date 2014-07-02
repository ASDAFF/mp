<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>	
<?
$arResult['FANCYBOX'] = false;
if (is_array($arResult['PROPERTIES']['MORE_PHOTO']['VALUE'])) {
	foreach ($arResult['PROPERTIES']['MORE_PHOTO']['VALUE'] as $photo) {
		$arResult['FANCYBOX'][] = array(
			'thumb' => CFile::ResizeImageGet($photo, array('width' => 100, 'height' => 100), BX_RESIZE_IMAGE_PROPORTIONAL, true),
			'normal' => CFile::ResizeImageGet($photo, array('width' => 800, 'height' => 800), BX_RESIZE_IMAGE_PROPORTIONAL, true)
			);
	}
}

CModule::IncludeModule('prmedia.treelikecomments');
$arFilter = array("OBJECT_ID_NUMBER" => $arResult['ID']);
$commentsCounter = CTreelikeComments::GetList(array("ID" => "DESC"), $arFilter)->SelectedRowsCount();
$showComments = false;
if ($_SESSION['COMMENTS']['ADD'] == 'Y') {
	$showComments = true;
	unset($_SESSION['COMMENTS']['ADD']);
}
?>
<style>
	.play-btn {width: 160px; opacity: 0.9; display: block; line-height: 0.7em; padding: 19px 0 19px 0; margin: 0 0 21px 0; background: #bfbfbf url(/src/icons/play48.png) no-repeat left; background-origin: content-box; text-align: center; color: #fff;  font-weight: 400; font-size: 24px; font-family: 'Open Sans', sans-serif; text-decoration: none; -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px;position: absolute; top: 181px; left: 391px; padding-left: 12px;}
	.play-btn:hover {background: #f15824 url(/src/icons/play48.png) no-repeat left; background-origin: content-box;}
	.back-to-photos {cursor: pointer;}
	.black {background: #000 !important;}
</style>	
			<div class="block-t">
				<div class="slider">
					<? if(intval($arResult['DETAIL_PICTURE']['ID'])) : ?>
						<? $file = CFile::ResizeImageGet($arResult['DETAIL_PICTURE']['ID'], array('width'=>960*1.5, 'height'=>10000), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true, false, false, 100); ?>
						<img src="<?=$file['src']?>" style="width:960px;" alt="" class="detail-photo" style="cursor: pointer;">
						<?if ($arResult['PROPERTIES']['VIDEO']['VALUE']) :?>
							<div  style="display: none; width: 640px; margin: 0 auto;" class="detail-video">
								<iframe width="640" height="360" src="//www.youtube.com/embed/<?=$arResult['PROPERTIES']['VIDEO']['VALUE']?>" frameborder="0" allowfullscreen></iframe>
							</div>
							<a class="play-btn" href="#" style="display: none;">Play</a>
						<?endif;?>
						<?if (false !== $arResult['FANCYBOX']) : ?>
							<div style="position: absolute; right: 0; top: 275px;background: #ccc; padding: 6px;" class="more-photo">
								<?foreach ($arResult['FANCYBOX'] as $photo) : ?>
									<a class="fancybox" rel="product-gallery" href="<?=$photo['normal']['src']?>" title="<?=$arResult['NAME']?>">
										<img src="<?=$photo['thumb']['src']?>" alt="" />
									</a>
								<?endforeach;?>
							</div>
						<?endif;?>
					<? endif; ?>
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
								<div class="tpad"><div class="cab-img2"><a href="/butik/?seller=<?=$ar_res['ID'];?>" title="<?=$ar_res['NAME'];?>"><img style="width:100px;" src="<?=$file['src']?>" alt=""></a></div></div>
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
							<!-- Put this script tag to the <head> of your page -->
							<script type="text/javascript" src="//vk.com/js/api/openapi.js?113"></script>

							<script type="text/javascript">
							  VK.init({apiId: 4438261, onlyWidgets: true});
							</script>

							<!-- Put this div tag to the place, where the Like block will be -->
							<div id="vk_like"></div>
							<script type="text/javascript">
							VK.Widgets.Like("vk_like", {type: "mini"});
							</script>
							</li>
							<li>
								<div id="fb-root"></div>
								<script>(function(d, s, id) {
								  var js, fjs = d.getElementsByTagName(s)[0];
								  if (d.getElementById(id)) return;
								  js = d.createElement(s); js.id = id;
								  js.src = "//connect.facebook.net/ru_RU/sdk.js#xfbml=1&appId=681312905274770&version=v2.0";
								  fjs.parentNode.insertBefore(js, fjs);
								}(document, 'script', 'facebook-jssdk'));</script>
								<div class="fb-like" data-href="<?=$APPLICATION->GetCurDir();?>" data-width="22" data-layout="button" data-action="like" data-show-faces="false" data-share="false"></div>
							</li>
							<li>
								<a href="https://twitter.com/share" class="twitter-share-button" data-url="http://mm.wrdev.ru/" data-text="Muchmore" data-via="muchmore" data-lang="ru" data-related="muchmore" data-hashtags="muchmore">Твитнуть</a>
								<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
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
									<a class="buy-link buy-sku" style="background: #f15824;" href="<?=$arResult['BUY_URL']?>">Купить</a>
								<? else : ?>
									<a class="open-reg save-buy" style="background: #f15824;" href="/personal/">Купить</a>
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
						<li>Комментарии <span class="comm"><?=$commentsCounter?></span></li>
					</ul>
					<div class="box2 visible" <?if (true === $showComments) : ?>style="display: none;"<?endif;?>>
						<div class="cat-text">
							<?=$arResult['DETAIL_TEXT'];?>
						</div>
					</div>
					<div class="box2" <?if (false === $showComments) : ?>style="display: none;"<?endif;?>>
					<div class="cat-text">
						<?$APPLICATION->IncludeComponent("prmedia:treelike_comments", "butik-treecomments", Array(
							"LEFT_MARGIN" => "50",	// Отступ для дочерних комментариев
							"MAX_DEPTH_LEVEL" => "5",	// Максимальный уровень вложенности
							"ASNAME" => "login",	// Показывать в качестве имени
							"SHOW_USERPIC" => "Y",	// Показывать аватар
							"SHOW_DATE" => "Y",	// Показывать дату комментария
							"SHOW_COMMENT_LINK" => "N",	// Показывать ссылку на комментарий
							"DATE_FORMAT" => "d.m.Y",	// Формат даты
							"SHOW_COUNT" => "Y",	// Показывать количество комментариев
							"OBJECT_ID" => "",	// ID объекта комментирования
							"CAN_MODIFY" => "N",	// Разрешить редактирование комментария
							"PREMODERATION" => "Y",	// Включить премодерацию
							"ALLOW_RATING" => "Y",	// Разрешить рейтинг
							"NON_AUTHORIZED_USER_CAN_COMMENT" => "N",	// Разрешить неавторизованным пользователям добавлять комменарии
							"USE_CAPTCHA" => "NO",	// Показывать CAPTCHA
							"FORM_MIN_TIME" => "3",	// Минимальное время заполнения формы
							"NO_FOLLOW" => "N",	// Добавить атрибут rel="nofollow" к ссылкам в комментариях
							"NO_INDEX" => "N",	// Не индексировать комментарии
							"SEND_TO_USER_AFTER_ANSWERING" => "Y",	// Отправлять  пользователю письмо при ответе на комментарий
							"SEND_TO_USER_AFTER_MENTION_NAME" => "Y",	// Отправлять  пользователю письмо при упоминании его логина в комментариях
							"SEND_TO_ADMIN_AFTER_ADDING" => "Y",	// Отправлять  письмо администратору при добавлении новых комментариев
							"SEND_TO_USER_AFTER_ACTIVATE" => "Y",	// Отправлять письмо пользователю после активации
							"AUTH_PATH" => "/personal/",	// Путь до страницы авторизации
							"TO_USERPAGE" => "/users/#USER_LOGIN#/",	// Путь до страницы пользователя
							"CACHE_TYPE" => "A",	// Тип кеширования
							"CACHE_TIME" => "3600",	// Время кеширования (сек.)
							),
							false
						); ?>
					</div>
					</div>
				</div>		
			</div>
			<div class="col-05">
				<?
					function getProductTags($values){
						$result = array();
						if (!is_array($values)) {
							$values = array($values);
						}
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
									case 9:
										$url .= '?brand='.$ar_res['ID'];
										break;
									case 3:
										$url .= '?seller='.$ar_res['ID'];
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
						$tag_iblock_el = array('CATALOG', 'BRAND','GIFT','FACILITY', 'SELLER');
						foreach($tag_iblock_el as $iblock){
							if($iblock == 'CATALOG')
								$tags = getProductCat($arResult['PROPERTIES'][$iblock]['VALUE']);
							else {
								$tags = getProductTags($arResult['PROPERTIES'][$iblock]['VALUE']);
							}
							$allTags = array_merge($allTags, $tags);
						}
						
						return '<li>'.implode('</li><li>', $allTags).'</li>';
					}
				?>

				<ul class="r-menu">
					<?=getProductAllTags($arResult);?>
				</ul>
				<script>
					$(document).ready(function () {
						$('.r-rec').hover(function () {
							$(this).find('div.cabinet-box3').fadeIn();
						}, function () {
							$(this).find('div.cabinet-box3').fadeOut();
						})	
					});
				</script>
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
							global $USER;
							if ($USER->isAuthorized()) {
								$basketHtml = '<a style="width: 130px;" class="buy-link" href="/butik/'.$data['CODE'].'/?action=BUY&amp;id='.$item.'&amp;ELEMENT_CODE='.$data['CODE'].'">В корзину</a>';
							} else {
								$basketHtml = '<a class="open-reg" style="background: #f15824; width: 160px;" href="/personal/">Купить</a>';
							}
							$result[] = '
								<div class="r-rec">
									<a href="/butik/'.$data['CODE'].'/" style="text-decoration:none; color:#000;">
										<img src="'.$data['PREVIEW_PICTURE'].'" style="width:217px; height:144px;" alt="">
										<p>'.$data['NAME'].(!empty($data['TEXT'])?': '.$data['TEXT']:'').'</p>
									</a>
									<p class="rprice">'.$data['PRICE'].' р.</p>
									<div class="cabinet-box3" style="float: right; margin-top: -71px; display: none;">
											<ul>
												<li>
												'.
													$basketHtml
												.'</li>							
											<ul>
									</div>
								</div>
							';
						}
						return implode('', $result);
					}
					
					echo '<h3>Рекомендуемые товары</h3>'.drawRecommend($arResult['PROPERTIES']['RECOMMEND']['VALUE']);
				}
				?>
			</div>