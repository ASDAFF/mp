<?

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

if (!CModule::IncludeModule("iblock"))
    return;

$arTypesEx = CIBlockParameters::GetIBlockTypes(Array("-" => " "));

$arIBlocks = Array();
$db_iblock = CIBlock::GetList(Array("SORT" => "ASC"), Array("SITE_ID" => $_REQUEST["site"], "TYPE" => ($arCurrentValues["IBLOCK_TYPE"] != "-" ? $arCurrentValues["IBLOCK_TYPE"] : "")));
while ($arRes = $db_iblock->Fetch())
    $arIBlocks[$arRes["ID"]] = $arRes["NAME"];

$arComponentParameters = array(
    "PARAMETERS" => array(
        "IBLOCK_TYPE" => Array(
            "NAME" => "Тип информационного блока",
            "TYPE" => "LIST",
            "VALUES" => $arTypesEx,
            "DEFAULT" => "news",
            "REFRESH" => "Y",
        ),
        "IBLOCK_ID" => Array(
            "NAME" => "Код информационного блока",
            "TYPE" => "LIST",
            "VALUES" => $arIBlocks,
            "DEFAULT" => '={$_REQUEST["ID"]}',
            "ADDITIONAL_VALUES" => "Y",
            "REFRESH" => "Y",
        ),
        "ELEMENT_CODE" => Array(
            "NAME" => "Символьный код элемента",
            "TYPE" => "STRING",
            "VALUE" => $_REQUEST['ELEMENT_CODE'],
        ),
        'AJAX_MODE' => array(),
        "CACHE_TIME" => Array("DEFAULT" => 3600),
    ),
);
?>
