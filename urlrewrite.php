<?
$arUrlRewrite = array(
	array(
		"CONDITION" => "#^/butik/([^\\/]*)/.*#",
		"RULE" => "ELEMENT_CODE=\$1",
		"ID" => "",
		"PATH" => "/butik/detail.php",
	),
	array(
		"CONDITION" => "#^/lifehack/([^\\/]*)/.*#",
		"RULE" => "ELEMENT_CODE=\$1",
		"ID" => "",
		"PATH" => "/lifehack/detail.php",
	),
);

?>