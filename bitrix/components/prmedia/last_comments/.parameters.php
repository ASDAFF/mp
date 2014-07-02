<? if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();

if(!CModule::IncludeModule("iblock"))
	return;

$arIBlocks = array();
$arIBlocks["N"] = GetMessage("DO_NOT_RESTRICT");

$res = CIBlock::GetList(array(), array());
while($arRes = $res->Fetch())
	$arIBlocks[$arRes["ID"]] = $arRes["NAME"];

$arComponentParameters = array(
	'GROUPS' => array(
	  "URL_TEMPLATES" => array(
         "NAME" => GetMessage("URL_TEMPLATES")
      ),
	),
	'PARAMETERS' => array(
		'TITLE' => array(
			'NAME' => GetMessage("TITLE"),
			'TYPE' => 'STRING',
			'MULTIPLE' => 'N',
			'ADDITIONAL_VALUES' => 'N',
			'PARENT' => 'BASE',
			'DEFAULT' => GetMessage("DEFAULT_TITLE")
			),
		'COUNT' => array(
			'NAME' => GetMessage("COUNT"),
			'TYPE' => 'INT',
			'PARENT' => 'BASE',
			'ADDITIONAL_VALUES' => 'N',
			'DEFAULT' => 10
			),
		'SHOW_TEXT' => array(
			'NAME' => GetMessage("SHOW_TEXT"),
			'TYPE' => 'CHECKBOX',
			'PARENT' => 'BASE',
			'ADDITIONAL_VALUES' => 'N',
			'DEFAULT' => 'N',
			'REFRESH' => 'Y'
			),
		"IBLOCK_RESTRICTIONS" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("IBLOCK_RESTRICTIONS"),
			"TYPE" => "LIST",
			"MULTIPLE" => "Y",
			"DEFAULT" => "N",
			"VALUES" => $arIBlocks
		),
 		'DETAIL_PAGE_URL' => array(
			'NAME' => GetMessage("DETAIL_PAGE_URL"),
			'TYPE' => 'STRING',
			'PARENT' => 'URL_TEMPLATES',
			'ADDITIONAL_VALUES' => 'N',
			'DEFAULT' => '#DETAIL_PAGE_URL#'
			),
		'PROFILE_URL' => array(
			'NAME' => GetMessage("PROFILE_URL"),
			'TYPE' => 'STRING',
			'PARENT' => 'URL_TEMPLATES',
			'ADDITIONAL_VALUES' => 'N',
			'DEFAULT' => '/users/#USER_LOGIN#/'
			),
		"CACHE_TIME"  =>  array("DEFAULT"=>3600),
	),
);

if($arCurrentValues["SHOW_TEXT"] == "Y"){
	
		$arComponentParameters["PARAMETERS"]["TEXT_LENGTH"] = Array(
				"NAME"=>GetMessage("TEXT_LENGTH"),
				"PARENT" => "BASE",
				"TYPE"=>"STRING",
				"DEFAULT"=>"50",
			);
}

?>