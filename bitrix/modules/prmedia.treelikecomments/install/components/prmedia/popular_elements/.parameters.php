<? if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();

if(!CModule::IncludeModule("iblock"))
	return;


$arImageProperties = array();

$arImageProperties['NOT_SHOW'] = GetMessage('PRMEDIA_NOT_SHOW');
$arImageProperties['PREVIEW_PICTURE'] = '[PREVIEW_PICTURE] '.GetMessage('PRMEDIA_PREVIEW_PICTURE');
$arImageProperties['DETAIL_PICTURE'] = '[DETAIL_PICTURE] '.GetMessage('PRMEDIA_DETAIL_PICTURE');

$rsProp = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("ACTIVE"=>"Y"));
while ($arr=$rsProp->Fetch())
{
	if($arr["PROPERTY_TYPE"] == "F")
		$arImageProperties[$arr["CODE"]] = "[".$arr["CODE"]."] ".$arr["NAME"];
}

$arComponentParameters = array(
	'GROUPS' => array(
	  "URL_TEMPLATES" => array(
         "NAME" => GetMessage("PRMEDIA_URL_TEMPLATES")
      ),
	),
	'PARAMETERS' => array(
		'TITLE' => array(
			'NAME' => GetMessage("PRMEDIA_TITLE"),
			'TYPE' => 'STRING',
			'MULTIPLE' => 'N',
			'ADDITIONAL_VALUES' => 'N',
			'PARENT' => 'BASE',
			'DEFAULT' => GetMessage("PRMEDIA_DEFAULT_TITLE")
			),
		'COUNT' => array(
			'NAME' => GetMessage("PRMEDIA_COUNT"),
			'TYPE' => 'INT',
			'PARENT' => 'BASE',
			'ADDITIONAL_VALUES' => 'N',
			'DEFAULT' => 10
			),
		'IMAGE' => array(
			'NAME' => GetMessage("PRMEDIA_IMAGE"),
			'TYPE' => 'LIST',
			'PARENT' => 'BASE',
			'ADDITIONAL_VALUES' => 'N',
			'VALUES' => $arImageProperties,
			),
 		'DETAIL_PAGE_URL' => array(
			'NAME' => GetMessage("PRMEDIA_DETAIL_PAGE_URL"),
			'TYPE' => 'STRING',
			'PARENT' => 'URL_TEMPLATES',
			'ADDITIONAL_VALUES' => 'N',
			'DEFAULT' => '#DETAIL_PAGE_URL#'
			),
		"CACHE_TIME"  =>  array("DEFAULT"=>3600),
	),
);

?>