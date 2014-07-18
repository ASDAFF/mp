<?
AddEventHandler("main", "OnAfterUserAdd", "OnBeforeUserRegisterHandler"); 
AddEventHandler("main", "OnAfterUserUpdate", "OnAfterUserUpdateHandler"); 

// AddEventHandler("sale", "OnOrderPaySendEmail", "OnOrderPaySendEmailHandler"); 
// AddEventHandler("sale", "OnOrderDeliverSendEmail", "OnOrderDeliverSendEmailHandler"); 
AddEventHandler("sale", "OnOrderStatusSendEmail", "OnOrderStatusSendEmailHandler"); 
AddEventHandler("sale", "OnSaleStatusOrder", "OnSaleStatusOrderHandler"); 


    function OnBeforeUserRegisterHandler(&$arFields)
    {
      CModule::IncludeModule('sale');
      //создаём профиль
      //PERSON_TYPE_ID - идентификатор типа плательщика, для которого создаётся профиль
      $arProfileFields = array(
         "NAME" => "Профиль покупателя (".$arFields['LOGIN'].')',
         "USER_ID" => $arFields['ID'],
         "PERSON_TYPE_ID" => 1
      );
      /**
       * Добавляем счет пользователя
       * @var array
       */
      $arFields = array(
        "USER_ID" => $arFields['ID'], 
        "CURRENCY" => "RUB", 
        "CURRENT_BUDGET" => 0
      );
      $accountID = CSaleUserAccount::Add($arFields);

      $PROFILE_ID = CSaleOrderUserProps::Add($arProfileFields);
      //если профиль создан
      if ($PROFILE_ID) {
         //формируем массив свойств
         $PROPS = array(
         array(
               "USER_PROPS_ID" => $PROFILE_ID,
               "ORDER_PROPS_ID" => 2,
               "NAME" => "Адрес доставки",
               "VALUE" => $arFields['PERSONAL_STREET']
            ),
         array(
               "USER_PROPS_ID" => $PROFILE_ID,
               "ORDER_PROPS_ID" => 1,
               "NAME" => "Ф.И.О.",
               "VALUE" => $arFields['LAST_NAME'].' '.$arFields['NAME'].' '.$arFields['SECOND_NAME']
            )
         );
         //добавляем значения свойств к созданному ранее профилю
         foreach ($PROPS as $prop) {
            CSaleOrderUserPropsValue::Add($prop);
         }
      }
    }

    function OnAfterUserUpdateHandler(&$arFields)
    {
      CModule::IncludeModule('sale');
      global $USER;
      $profile = CSaleOrderUserProps::GetList(array("DATE_UPDATE" => "DESC"), array("USER_ID" => $arFields['ID']))->Fetch();
      $PROFILE_ID = $profile['ID'];
      //если профиль создан
      if ($PROFILE_ID)
      {
         //формируем массив свойств
         $props = array(
            $PROFILE_ID => array(
               "USER_PROPS_ID" => $PROFILE_ID,
               "NAME" => "Адрес доставки",
               "VALUE" => $arFields['PERSONAL_STREET']
               )
            );
         //добавляем значения свойств к созданному ранее профилю
         foreach ($props as $propId => $prop) {
            $x = CSaleOrderUserPropsValue::Update($propId, $prop);
         }
      }
    }

    function OnOrderPaySendEmailHandler($orderId, &$eventName, &$arFields) {
      $eventName = 'ORDER_PAYED';
      $order = CSaleOrder::GetByID($orderId);
      $user = CUser::GetByID($order['USER_ID'])->Fetch();
      $tmp = explode(',', $user['NAME']);
      $eventFields = array(
      'NAME' => $tmp[0],
      'EMAIL' => $user['EMAIL']
      );
      $arFields = $eventFields;
    }

    function OnOrderDeliverSendEmailHandler($orderId, &$eventName, &$arFields) {
      $order = CSaleOrder::GetByID($orderId);
      $user = CUser::GetByID($order['USER_ID'])->Fetch();
      $tmp = explode(',', $user['NAME']);
      $eventFields = array(
      'NAME' => $tmp[0],
      'EMAIL' => $user['EMAIL']
      );
      $arFields = $eventFields;
      $eventName = ($user['PERSONAL_CITY'] == 'Москва') ? 'ORDER_IN_DELIVERY_MOSCOW' : 'ORDER_IN_DELIVERY_RUSSIA' ;
    }

    function OnOrderStatusSendEmailHandler($ID, &$eventName, &$arFields, $val) {
      // $order = CSaleOrder::GetByID($ID);
      // $user = CUser::GetByID($order['USER_ID'])->Fetch();
      // $tmp = explode(',', $user['NAME']);
      // $eventFields = array(
      // 'NAME' => $tmp[0],
      // 'EMAIL' => $user['EMAIL']
      // );
      // $arFields = $eventFields;
      // if ($val == 'P') {
      //   $eventName = 'ORDER_PAYED';
      // } elseif ($val == 'D') {
      //   $eventName = ($user['PERSONAL_CITY'] == 'Москва') ? 'ORDER_IN_DELIVERY_MOSCOW' : 'ORDER_IN_DELIVERY_RUSSIA';
      // } else {
      // }
      return false;
    }

    function OnSaleStatusOrderHandler($ID, $val) {
      if ($val == "F") {
        $order = CSaleOrder::GetByID($ID);
        $user = CUser::GetByID($order['USER_ID'])->Fetch();
        CSaleUserAccount::UpdateAccount($user['ID'], $order['PRICE'] * 0.03, 'RUB', 'order', $ID);
      }
    }
?>