<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Личный кабинет");
?>
	<script src="/src/javascript/tabs.js"></script>
	<div class="block-gr">
		<div class="main">
			<div class="cabinet">
				<div class="cabinet-box">
					<div class="cab-img"><img src="/src/images/fl-17.jpg" alt=""></div>
					<div class="cab-des">
						<?
							global $USER;
							$rsUser = CUser::GetByID($USER->GetID());
							$arUser = $rsUser->Fetch();
						?>
						<h3><?=$arUser['NAME'];?></h3>
						<p class="cash">Cash-счет: 390 р.</p>
						<p>Напишите немного о себе</p>
					</div>
				</div>
				<div class="cabinet-box2">
					<ul>
						<li><a class="active" href="/personal/">Профиль</a></li>
						<li><a href="/personal/orders/">Заказы</a></li>
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