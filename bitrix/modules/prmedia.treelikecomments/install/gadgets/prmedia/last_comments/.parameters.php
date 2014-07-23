<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?

$arValues = array('5' => 5, '10' => 10, '15' => 15, '20' => 20);
$arParameters = array(
	"PARAMETERS" => array(),
	"USER_PARAMETERS" => array(
		"COUNT" => array(
			"NAME" => GetMessage("COUNT"),
			"TYPE" => "LIST",
			"VALUES" => $arValues,
			"MULTIPLE" => "N",
			"DEFAULT" => 10
		),
		"DETAIL_PAGE_URL" => array(
			"NAME" => GetMessage("DETAIL_PAGE_URL"),
			"TYPE" => "STRING",
			"DEFAULT" => "#DETAIL_PAGE_URL#"
		)
	)
);
?>