<?php

	require_once($_SERVER['DOCUMENT_ROOT']. "/bitrix/modules/main/include/prolog_before.php");
	
	CModule::IncludeModule("sale");
	
	if(isset($_GET['delete_from_basket'])){
		CSaleBasket::Delete(intval($_GET['delete_from_basket']));
	}
	
	if(isset($_GET['update_basket_item']) && isset($_GET['basket_item_quantity'])){
		CSaleBasket::Update(intval($_GET['update_basket_item']), array("QUANTITY" => intval($_GET['basket_item_quantity'])));
	}

	$APPLICATION->IncludeComponent("bitrix:sale.basket.basket", "ajax", Array(
		"COLUMNS_LIST" => array(	// Выводимые колонки
			0 => "TYPE",
		),
		"PATH_TO_ORDER" => "/personal/order.php",	// Страница оформления заказа
		"HIDE_COUPON" => "N",	// Спрятать поле ввода купона
		"PRICE_VAT_SHOW_VALUE" => "N",	// Отображать значение НДС
		"COUNT_DISCOUNT_4_ALL_QUANTITY" => "N",	// Рассчитывать скидку для каждой позиции (на все количество товара)
		"USE_PREPAYMENT" => "N",	// Использовать предавторизацию для оформления заказа (PayPal Express Checkout)
		"QUANTITY_FLOAT" => "N",	// Использовать дробное значение количества
		"SET_TITLE" => "Y",	// Устанавливать заголовок страницы
		"ACTION_VARIABLE" => "action",	// Название переменной действия
		"OFFERS_PROPS" => "",	// Свойства, влияющие на пересчет корзины
		),
		false
	);