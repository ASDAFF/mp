<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Личный кабинет");
if (isset($_POST['edit'])) {
	global $USER;
	global $APPLICATION;
	$user = new CUser;
	$user->Update($USER->GetId(), array('PERSONAL_STREET' => htmlspecialchars($_POST['PERSONAL_STREET'])));
	LocalRedirect($APPLICATION->GetCurDir());
}
?>
<?
	global $USER;
	$rsUser = CUser::GetByID($USER->GetID());
	$arUser = $rsUser->Fetch();
	$avatar = CFile::ResizeImageGet($arUser["PERSONAL_PHOTO"], Array("width" => 170, "height" => 170));
?>
	<script src="/src/javascript/tabs.js"></script>
	<div class="block-gr">
		<div class="main">
			<div class="cabinet">
				<div class="cabinet-box">
					<div class="cab-img">
						<?if (!$arUser['PERSONAL_PHOTO']) : ?>
							<img src="/src/images/fl-17.jpg" alt="">
						<?else : ?>
							<img src="<?=$avatar['src']?>" alt="">
						<?endif;?>
					</div>
					<div class="cab-des">
						
						<h3><?=$arUser['NAME'];?></h3>
						<p>Адрес доставки</p>
						<form method="post" action="">
							<input type="text" name="PERSONAL_STREET" value="<?=$arUser['PERSONAL_STREET']?>" placeholder="Адрес доставки">
							<input type="submit" name="edit" value="Изменить">
						</form>
						<p>Телефон: <?=$arUser['PERSONAL_PHONE']?></p>
						<p>Email адрес: <?=$arUser['EMAIL']?></p>
						<!-- <p class="cash">Cash-счет: 390 р.</p> -->
						<hr/>
							<?$APPLICATION->IncludeComponent(
							"bitrix:sale.personal.order",
							"",
							Array(
								"ACTIVE_DATE_FORMAT" => "j F Y",
								"SEF_MODE" => "N",
								"CACHE_TYPE" => "A",
								"CACHE_TIME" => "3600",
								"CACHE_GROUPS" => "Y",
								"ORDERS_PER_PAGE" => "20",
								"PATH_TO_PAYMENT" => "payment.php",
								"PATH_TO_BASKET" => "basket.php",
								"SET_TITLE" => "Y",
								"SAVE_IN_SESSION" => "Y",
								"NAV_TEMPLATE" => "",
								"CUSTOM_SELECT_PROPS" => array(""),
								"HISTORIC_STATUSES" => array(),
								"STATUS_COLOR_N" => "green",
								"STATUS_COLOR_F" => "green",
								"STATUS_COLOR_PSEUDO_CANCELLED" => "green"
							)
						);?>						
					</div>
				</div>
				<div class="cabinet-box2">
					<ul>
						<li><a class="active" href="/personal/">Профиль</a></li>
						<!-- <li><a href="/personal/orders/">Заказы</a></li> -->
						<li><a href="/?logout=yes">Выход</a></li>
					</ul>
				</div>
			</div>
			
			<div class="section">

			<ul class="tabs">
				<li class="current">Понравилось</li>
				<li>Wishlist</li>
			</ul>

			<div class="box visible">
				<div class="gallery">
					<div class="gbox"><img src="/src/images/fl-18.jpg" alt=""></div>
					<div class="gbox"><img src="/src/images/fl-18.jpg" alt=""></div>
					<div class="gbox"><img src="/src/images/fl-18.jpg" alt=""></div>
					<div class="gbox"><img src="/src/images/fl-18.jpg" alt=""></div>
				</div>
			</div>

			<div class="box">
				<div class="gallery">
					<p>Закон внешнего мира, как принято считать, реально рассматривается знак, отрицая очевидное. Гегельянство творит катарсис, хотя в официозе принято обратное. Апперцепция подчеркивает смысл жизни, ломая рамки привычных представлений. Представляется логичным, что адживика откровенна.</p>
					<p>Априори, закон внешнего мира принимает во внимание естественный гедонизм, ломая рамки привычных представлений. Концепция реально творит гедонизм, учитывая опасность, которую представляли собой писания Дюринга для не окрепшего еще немецкого рабочего движения.</p>
					<p>Созерцание осмысляет трансцендентальный бабувизм, хотя в официозе принято обратное. Бабувизм абстрактен. Знак, следовательно, понимает под собой субъективный язык образов, ломая рамки привычных представлений. Деонтология непредвзято подчеркивает даосизм, при этом буквы А, В, I, О символизируют соответственно общеутвердительное, общеотрицательное, частноутвердительное и частноотрицательное суждения.</p>
				</div>
			</div>
		</div>
	</div>
	
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>