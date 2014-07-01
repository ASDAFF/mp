<?php

	require_once($_SERVER['DOCUMENT_ROOT']. "/bitrix/modules/main/include/prolog_before.php");
	
	CModule::IncludeModule("sale");
	
	if(isset($_GET['order_basket'])) {
		$dbBasketItems = CSaleBasket::GetList(
		    false, array(
            "FUSER_ID" => CSaleBasket::GetBasketUserID(),
            "LID" => SITE_ID,
            "ORDER_ID" => "NULL"
	        ), false, false, array("ID",
			"QUANTITY", 
			"PRICE", 
			));
		$price = 0;
		while ($item = $dbBasketItems->Fetch()) {
			$price += intval($item['QUANTITY']) * $item['PRICE'];
		}
		$arFields = array(
			"LID" => 's1',
			"PERSON_TYPE_ID" => 1,
			"PAYED" => "N",
			"CANCELED" => "N",
			"STATUS_ID" => "N",
			"PRICE" => $price,
			"CURRENCY" => "RUB",
			"USER_ID" => intval($USER->GetId()),
			"PAY_SYSTEM_ID" => 1,
			"USER_DESCRIPTION" => ""
		);
		$orderId = CSaleOrder::Add($arFields);
		if (false === $orderId) {
			return false;
		}
		$user = CUser::GetByID($USER->GetId())->Fetch();
		$ar = array(
			'ORDER_ID' => $orderId,
			'ORDER_PROPS_ID' => 2,
			"NAME" => "Адрес доставки",
			"CODE" => "delivery_address",
			'VALUE' => $user['PERSONAL_STREET']
		);
		CSaleOrderPropsValue::Add($ar);
		CSaleBasket::OrderBasket($orderId, $_SESSION["SALE_USER_ID"], SITE_ID);
		$eventFields = array(
			'NAME' => $USER->getName(),
			'EMAIL' => $USER->getEmail()
			);
		$eventName = ($user['PERSONAL_CITY'] == 'Москва') ? 'NEW_ORDER_MOSCOW' : 'NEW_ORDER_RUSSIA' ;
		CEvent::Send($eventName, SITE_ID, $eventFields);
		return true;
	}