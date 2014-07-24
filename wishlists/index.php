<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Muchmore.ru - Wishlist пользователя " . $_GET['USER']);
$APPLICATION->AddHeadString('<meta property="og:title" content="Muchmore.ru - Wishlist пользователя ' . $_GET['USER'] . '"/>');
CModule::IncludeModule('iblock');
$wishUser = CUser::GetByLogin($_GET['USER'])->Fetch();
if (false === $wishUser) {

} else {
	$rsItems = CIBlockElement::GetList(array(
		'DATE_CREATE' => 'DESC'
		), array(
		'IBLOCK_ID' => 23,
		'PROPERTY_USER' => $wishUser['ID'],
		'ACTIVE' => 'Y'
		), false, false, array(
		'PROPERTY_OBJECT'
		));
	while ($item = $rsItems->Fetch()) {
		$items[] = $item['PROPERTY_OBJECT_VALUE'];
	}
	if ($items) {
		$rsItems = CIBlockElement::GetList(false, array(
		    'IBLOCK_ID' => 1,
		    'ACTTIVE' => 'Y',
		    'ID' => $items
		    ), false, false, array(
		    'ID', 'NAME', 'CODE', 'CATALOG_GROUP_1', 'CATALOG_PRICE_1', 'PREVIEW_PICTURE'
		    ));
		while ($x = $rsItems->Fetch()) {
		    $arResult['related'][$x['ID']] = array(
		        'id' => $x['ID'],
		        'name' => $x['NAME'],
		        'code' => $x['CODE'],
		        'price' => number_format($x['CATALOG_PRICE_1'], 0, ',', ' '),
		        'picture' => CFile::GetPath($x['PREVIEW_PICTURE'])
		        );
		}
	}
}
?>
<div class="block-gr">
	<div class="main">
		<div class="cabinet">
			<div class="cabinet-box">
				<div class="order">
					<h3>Wishlist <?=$_GET['USER']?></h3>
				</div>
				<?if ($arResult['related']) : ?>
				 <div class="soc" style="margin: 31px 0px -39px -20px">
					<ul>
						<li style="width:80px;">
						<!-- Put this script tag to the <head> of your page -->
						<script type="text/javascript" src="//vk.com/js/api/openapi.js?113"></script>

						<script type="text/javascript">
						  VK.init({apiId: 4454187, onlyWidgets: true});
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
							<div class="fb-like" data-href="<?=$APPLICATION->GetCurDir();?>" data-layout="button_count" data-action="like" data-show-faces="true" data-share="false"></div>
							<!-- <div class="fb-like" data-href="<?=$APPLICATION->GetCurDir();?>" data-width="22" data-layout="button" data-action="like" data-show-faces="false" data-share="false"></div> -->
						</li>
						<li>
							<a href="https://twitter.com/share" class="twitter-share-button" data-url="<?=$APPLICATION->GetCurDir();?>" data-text="Muchmore" data-via="muchmore" data-lang="ru" data-related="muchmore" data-hashtags="muchmore">Твитнуть</a>
							<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
						</li>

						<!-- <li style="width: 160px;">
							<?if ($arResult['wish']['isWished']) : ?>
								<a href="javascript:;" class="wish-item del" data-object="<?=$arResult['ID']?>">Удалить из Wishlist</a></li>
							<?else : ?>
								<a href="javascript:;" class="wish-item" data-object="<?=$arResult['ID']?>">Добавить в Wishlist</a></li>
							<?endif;?>
						<li style="width: 160px;"><span class="fa fa-heart-o blog-item-like blog-item-active-info <?=($arResult['likes']['already_liked']) ? 'active-like' : ''?>" data-object="<?=$arResult['ID']?>" data-ib="<?=$arResult['IBLOCK_ID']?>"> <?=$arResult['likes']['value']?></span></li> -->
					</ul>
				</div>
				<?endif;?>
				<div class="order2">
					<?if ($arResult['related']) : ?>
		            <div class="related">
		                <?foreach ($arResult['related'] as $id => $related) : ?>
		                <div class="item-block2" style="margin: 0 5px 30px 5px">
		                    <div style="height:200px; width:300px;">
		                        <a href="/butik/<?=$related['code']?>/">
		                            <img style="width:300px; height:199px;" src="<?=$related['picture']?>" alt="">
		                        </a>
		                    </div>              
		                    <a href="/butik/<?=$related['code']?>/" class="cat-link">
		                        <?=$related['name']?>
		                    </a>
		                    <div class="price"><?=$related['price']?> р.</div>
		                    <div class="cabinet-box3" style="float: right; margin-top: -46px; display: none;">
		                        <ul>
		                            <li>
		                                <?if (!$USER->isAuthorized()) : ?>
		                                    <a class="open-reg" style="background: #f15824; width: 160px; box-shadow: none;" href="/personal/">Купить</a>
		                                <?else : ?>
		                                    <a style="background: #f15824; width: 160px; box-shadow: none;" class="buy-link" href="/butik/<?=$related['code']?>/?action=BUY&amp;id=<?=$id?>&amp;ELEMENT_CODE=<?=$related['code']?>">Купить</a>
		                                <?endif;?>
		                            </li>                           
		                            <ul>
		                            </ul>
		                        </ul>
		                    </div>
		                </div>
		                <?endforeach;?>
		            </div>
		            <?else : ?>
		            	<p>Нет товаров в WishList</p>
		            <?endif;?>						
				</div>
				<?if ($USER->isAuthorized() && $_GET['USER'] == $USER->GetLogin()) : ?>
				<div class="cabinet-box2">
					<ul>
						<li><a href="/personal/">Заказы</a></li>
						<li><a class="active" href="/wishlists/admin/">WishList</a></li>
						<li><a href="/?logout=yes">Выход</a></li>
					</ul>
				</div>
				<?endif;?>
			</div>
		</div>
	</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>