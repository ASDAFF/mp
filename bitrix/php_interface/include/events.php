<?
AddEventHandler("main", "OnAfterUserAdd", "OnBeforeUserRegisterHandler"); 
AddEventHandler("main", "OnAfterUserUpdate", "OnAfterUserUpdateHandler"); 

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
      $PROFILE_ID = CSaleOrderUserProps::Add($arProfileFields);
      //если профиль создан
      if ($PROFILE_ID)
      {
         //формируем массив свойств
         $PROPS=Array(
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
         foreach ($PROPS as $prop)
            CSaleOrderUserPropsValue::Add($prop);
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
?>