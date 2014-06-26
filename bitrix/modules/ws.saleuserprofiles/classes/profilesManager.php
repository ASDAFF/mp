<?
class WS_SaleUserProfilesManager {
    static function OnBuildGlobalMenu(&$aGlobalMenu, &$aModuleMenu){
        $MODULE_ID = basename(dirname(__FILE__));
        if (file_exists($path = dirname(__FILE__).'/admin')) {
            if ($dir = opendir($path)) {
                while(false !== $item = readdir($dir)) {
                    if (in_array($item,array('.','..','menu.php'))) {
                        continue;
                    }

                    if (!file_exists($file = $_SERVER['DOCUMENT_ROOT'].'/bitrix/admin/'.$MODULE_ID.'_'.$item))
                        file_put_contents($file,'<'.'? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/'.$MODULE_ID.'/admin/'.$item.'");?'.'>');
                }
            }
        }
    }

    static function GetPersonFieldsByID($personID) {
        if (!empty($personID)) {
            $res = CSalePersonType::GetList(array(), array("ID" => $personID), false, array('nTopCount' => 1), array());
            if ($arRes = $res->Fetch()) {
                return $arRes;
            }
        }
        return array();
    }

    static function GetProfileFieldsByID($profileID) {
        if (!empty($profileID)) {
            $res = CSaleOrderUserProps::GetList(array(), array("ID" => $profileID), false, array('nTopCount' => 1));
            while ($arRes = $res->Fetch()) {
                $arRes["NAME"] = htmlspecialchars($arRes["NAME"]);
                return $arRes;
            }
        }
        return array();
    }

    static function GetProfileProps($profileID, $personID) {

        if (!empty($profileID)) {
            // получаем значения свойств
            $props = array();
            $res = CSaleOrderUserPropsValue::GetList(array(), array('USER_PROPS_ID'=>$profileID), false, false, array('ID', 'ORDER_PROPS_ID', 'VALUE'));
            while ($arRes = $res->Fetch()) {
                $props[$arRes['ORDER_PROPS_ID']] = array('VALUE' => htmlspecialchars($arRes['VALUE']));
            }
        }

        // если не задан $personID
        if (empty($personID)) {
            $res = CSalePersonType::GetList(Array(), Array());
            if ($arRes = $res->Fetch()) {
                $personID = $arRes["ID"];
            }
        }

        // получаем свойства
        $arProps = array();
        $res = CSaleOrderProps::GetList(array("SORT"=>"ASC"), array("PERSON_TYPE_ID" => $personID, "USER_PROPS" => "Y"), false, false, array());
        while ($arRes = $res->Fetch()) {
            if (in_array($arRes["TYPE"], array("SELECT", "MULTISELECT", "RADIO"))) {
                $rs = \CSaleOrderPropsVariant::GetList(array(), array("ORDER_PROPS_ID" => $arRes["ID"]));
                while ($arRs = $rs->Fetch()) {
                    $arRes["variants"][] = $arRs;
                }
            }

            if (!empty($props[$arRes['ID']])) {
                $arProps[$arRes['ID']] = array_merge($props[$arRes['ID']], $arRes);
            } else {
                $arProps[$arRes['ID']] = $arRes;
            }
        }
            return $arProps;
    }

    static function Update($profileID, $arFields) {
        global $DB;

        $result = new WS_SaleUserProfilesErrorsContainer();
        if (empty($profileID)) {
            return $result->addErrorString(GetMessage("ws.saleuserprofiles_save_error_required_id"));
        }
        $DB->StartTransaction();

        if (!empty($arFields["PROPS"])) {
            $props = $arFields["PROPS"];
            unset($arFields["PROPS"]);
        }

        // сохраняем поля
        if (!empty($arFields)) {
            if(!$profileID = CSaleOrderUserProps::Update($profileID, $arFields)){
                $result->addErrorString(GetMessage("ws.saleuserprofiles_save_error_save_fields"));
            } else {
                $arFields = CSaleOrderUserProps::GetByID($profileID);
            }
        }

        // сохраняем свойства
        if (!empty($props) && !$result->getErrorsAsString()) {
            // удаляем все свойства
            CSaleOrderUserPropsValue::DeleteAll($profileID);
            $res = CSaleOrderProps::GetList(array(), array("PERSON_TYPE_ID" => $arFields["PERSON_TYPE_ID"], "USER_PROPS" => "Y"), false, false, array());
            while ($arRes = $res->Fetch()) {
                if ($arRes['REQUIED'] === 'Y' && empty($props[$arRes['ID']])) {
                    $result->addErrorString(GetMessage("ws.saleuserprofiles_save_error_required_field") . "\"" . $arRes["NAME"] . "\"");
                    continue;
                }

                $arValueTemp = $props[$arRes['ID']];
                if (is_array($arValueTemp)) {
                    $arValueTemp = "";

                    for ($i = 0; $i < count($props[$arRes['ID']]); $i++) {
                        if ($i > 0) {
                            $arValueTemp .= ",";
                        }
                        $arValueTemp .= $props[$arRes['ID']][$i];
                    }

                }

                $arProp = array(
                    "VALUE" => $arValueTemp,
                    "NAME" => $arRes["NAME"],
                    "ORDER_PROPS_ID" => $arRes['ID'],
                    "USER_PROPS_ID" => $profileID
                );
                CSaleOrderUserPropsValue::Add($arProp);
            }
        }

        if ($result->getErrorsAsString()) {
            $DB->Rollback();
        } else {
            $DB->Commit();
        }
        return $result;
    }

    static function Add($arFields) {
        $result = new WS_SaleUserProfilesErrorsContainer();
        $fields = array(
            "NAME"              => $arFields["NAME"],
            "PERSON_TYPE_ID"    => $arFields["PERSON_TYPE_ID"],
            "USER_ID"           => $arFields["USER_ID"],
            "DATE_UPDATE"       => $arFields["DATE_UPDATE"]
        );
        if (empty($fields["USER_ID"])) {
            $result->addErrorString(GetMessage("ws.saleuserprofiles_save_error_required_field") . "\"код пользователя, которому принадлежит профиль\"");
        }

        // сохраняем поля
        if (!$result->getErrorsAsString() && !empty($arFields)) {
            $id = CSaleOrderUserProps::Add($arFields);

            if ($id) {
                return $id;
            }
        }
        return $result;
    }

    static function SelectBoxPersonTypes($personID, $name, $htmlattrs = ""){
        if (empty($name)) {
            return false;
        }

        $html = '<select name="'.$name.'" '.$htmlattrs.'>';
        $res = CSalePersonType::GetList(Array(), Array());
        while ($arRes = $res->Fetch()) {
            $html .= '<option '.(($arRes['ID']==$personID)?'selected':'').' value="'.$arRes['ID'].'">'.$arRes["NAME"].'</option>';
        }
        $html .= '</select>';
        return $html;
    }

    static function SelectBoxLocations($lid, $name, $locationID, $onChange="") {
        $html = '<select name="'.$name.'" onChange="'.$onChange.'">';
        $res = \CSaleLocation::GetList(array(), array("LID" => $lid), false, false, array());
        while ($arRes = $res->Fetch()) {
            $html .= '<option '.(($arRes['ID']==$locationID)?'selected':'').' value="'.$arRes['ID'].'">'.htmlspecialcharsbx($arRes["COUNTRY_NAME"] . ((!empty($arRes["REGION_NAME"]))?' - '.$arRes["REGION_NAME"]:'') . ((!empty($arRes["CITY_NAME"]))?' - '.$arRes["CITY_NAME"]:'')) . '</option>';
        }
        $html .= '</select>';
        return $html;
    }
}
?>