<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Заказы");
?>
<div class="block-gr">
		<div class="main">
			<div class="cabinet">
					<div class="cabinet-box">
						<div class="order">
							<h3>Заказы</h3>
							
<!--
							<div class="styled-select2">
								<select>
									<option>Выполненные</option>
									<option>Выполненные</option>
									<option>Выполненные</option>
									<option>Выполненные</option>
									
								</select>
							</div>	
-->						
						</div>
						<div class="order2">
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
							<li><a href="/personal/">Профиль</a></li>
							<li><a class="active" href="/personal/orders/">Заказы</a></li>
						</ul>
					</div>
		
			</div>
	
			
			
		</div>
	</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>