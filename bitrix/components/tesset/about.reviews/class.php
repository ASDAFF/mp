<?php

function CheckError($name, $errors) {
    if (isset($errors[$name])) {
        echo "<span style='color:red;'>{$errors[$name]}</span>";
    }
}

function GetRating($int) {
    if ($_REQUEST["RATING"] == $int) {
        echo "checked";
    } elseif (!$_REQUEST["RATING"] && $int == 5) {
        echo "checked";
    }
}

class CNewsiteAboutReviews extends CBitrixComponent {

    private $reviewsIBlockId = 288;
    private $validateReview
    = array(
        "REVIEW" => array(
            "REQUIRED" => true
        ),
        "TITLE" => array(
            "REQUIRED" => true
        ),
        "RATING" => array(
            "REQUIRED" => true
        ),
        "NAME" => array(
            "REQUIRED" => true
        ),
        "PHONE" => array(
            "REQUIRED" => false,
            ),
    );
    private $avgCount = 0;

    public function executeComponent() {
        /** @var CMain */
        global $APPLICATION;
        $APPLICATION->SetTitle("Отзызы о магазине");
        if ($_REQUEST["ACTION"] == "ADD") {
            try {
                $this->AddReview($_REQUEST);
                if (count($this->arResult["FIELD_ERRORS"])) {
                    throw new Exception;
                }
                $this->arResult["SUCCESS"] = true;
            } catch (Exception $ex) {
                $this->arResult["ERRORS"][] = $ex->getMessage();
            }
        }
        if (!$this->arParams["ALL_ITEMS_COUNT"]) {
            $arNavParams = array(
                "nPageSize" => $this->arParams["REVIEWS_COUNT"],
            );
        } else {
            $arNavParams = array(
                "nTopCount" => $this->arParams["ALL_ITEMS_COUNT"],
            );
        }

        $arNavigation = CDBResult::GetNavParams($arNavParams);

        global $USER;
        $cache = $arNavigation.bitrix_sessid_get().$USER->GetID().$_GET["PAGEN_2"];
        
        if ($this->startResultCache(36000000, $cache)) {
            $this->arResult["REVIEWS"] = $this->GetReviews($arNavParams);
            $this->includeComponentTemplate();
        }
    }

    private function GetReviews($arNavParams) {
        $filter = array(
            "IBLOCK_ID" => $this->reviewsIBlockId, 
            "ACTIVE" => "Y"
            );
        if ($this->arParams['RAITING']) {
            $filter['PROPERTY_RATING'] = $this->arParams['RAITING'];
        }
        $dbRes = CIBlockElement::GetList(
                    $arOrder = array("CREATED"=>"DESC"), 
                    $filter, 
                    false, 
                    $arNavParams, array(
                    "IBLOCK_ID",
                    "ACTIVE",
                    "PROPERTY_REVIEW",
                    "PROPERTY_TITLE",
                    "PROPERTY_RATING",
                    "PROPERTY_WILL_BUY",
                    "PROPERTY_NAME",
					"PROPERTY_REPLY",
                    "DATE_CREATE"
                        )
        );
        $arResult = array();
        $summary = 0;
        while ($arIBlockElement = $dbRes->GetNext(true, false)) {
            $arIBlockElement['DATE_FORMATED'] = explode(' ', $arIBlockElement['DATE_CREATE']);
            $summary += (int) $arIBlockElement["PROPERTY_RATING_VALUE"];
            $user = CUser::GetByLogin($arIBlockElement['PROPERTY_NAME_VALUE'])->Fetch();
            $arIBlockElement["AVATAR"] = array(
                "ID" => $user['PERSONAL_PHOTO'],
                "FILE" => CFile::ResizeImageGet(
                        $user['PERSONAL_PHOTO'],
                        array(
                            "width" => 85, 
                            "height" => 85
                        ), BX_RESIZE_IMAGE_EXACT, false 
                        )
            );
            if (!empty($arIBlockElement["AVATAR"]["FILE"])) {
                $arIBlockElement["AVATAR"]["HTML"] = CFile::ShowImage($arIBlockElement["AVATAR"]["FILE"]['src'], 85, 85);
            }
            $arIBlockElement['URL'] = $this->arParams['REVIEWS_PAGE'] . '#review' . $arIBlockElement['PROPERTY_REVIEW_VALUE_ID'];
            $arResult[] = $arIBlockElement;
        }
        $this->arResult["NAV_STRING"] = $dbRes->GetPageNavStringEx();
        $this->avgCount = $summary / count($arResult);
        return $arResult;
    }

    private function ValidateFields($arParams) {
        $errors = array();
        foreach ($this->validateReview as $key => $value) {
            if ($value["REQUIRED"] && !strlen($arParams[$key])) {
                $errors[$key] = "Поле обязательно для заполнения";
            } elseif (strlen($arParams[$key]) && $value["VALIDATOR"]) {
                switch ($value["VALIDATOR"]) {
                    case "PHONE" : {
                            if (!validatePhoneNumber($arParams[$key])) {
                                $errors[$key] = "Неверный номер телефона";
                            }
                        }
                }
            }
        }
        if (strlen($arParams["PHONE"]) && !strlen($arParams["PHONE_CONFIRM"])) {
            $errors["PHONE_CONFIRM"] = "Подтвердите условия передачи телефона";
        }
        if (count($errors)) {
            $this->arResult["FIELD_ERRORS"] = $errors;
            return false;
        } else {
            return true;
        }
    }

    private function AddReview($arParams) {
        if ($this->ValidateFields($arParams)) {
            $arIBlockFields = array(
                "IBLOCK_ID" => $this->reviewsIBlockId,
                "NAME" => $arParams["NAME"] . " " . date("Y-m-d H:i:s"),
                "ACTIVE" => "N",
                "PROPERTY_VALUES" => array(
                    "REVIEW" => array("VALLUE" => array("TEXT" => $arParams["REVIEW"], "TYPE" => "TEXT")),
                    "TITLE" => $arParams["TITLE"],
                    "RATING" => $arParams["RATING"],
                    "WILL_BUY" => array("VALUE" => $arParams["WILL_BUY"] ? "2900" : ""),
                    "NAME" => $arParams["NAME"],
                    "PHONE" => $arParams["PHONE"],
                    "ONLINER" => array("VALUE" => $arParams["ONLINER"] ? "2902" : "")
                )
            );
            $CIBlockElement = new CIBlockElement();
            $resId = $CIBlockElement->Add($arIBlockFields);
            if (!$resId) {
                throw new Exception($CIBlockElement->LAST_ERROR);
            }
            $arIBlock = CIBlockElement::GetById($resId)->Fetch();
            CEvent::SendImmediate("NEW_SITE_REVIEW_EVENT", "s1", $arIBlock);
        }
    }

}