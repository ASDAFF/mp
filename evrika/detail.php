<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Эврика");
?>

<?$APPLICATION->IncludeComponent('tesset:blog.element', '', array('IBLOCK_ID' => 18));?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>