<? if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();

if(!CModule::IncludeModule("iblock"))
	return;

$arComponentParameters = array(
	'GROUPS' => array(
		"VIS" => array(
		 "NAME" => GetMessage("PRMEDIA_BC_VISUAL_PARAMS"),
		 "SORT" => 200
		),
          "EXTRA" => array(
         "NAME" => GetMessage("PRMEDIA_BC_EXTRA_PARAMS"),
         "SORT" => 350

      ),
	),
	'PARAMETERS' => array(
		'OBJECT_ID' => array(
			'NAME' => GetMessage("PRMEDIA_BC_OBJECT_ID"),
			'TYPE' => 'INT',
			'MULTIPLE' => 'N',
			'ADDITIONAL_VALUES' => 'N',
			'PARENT' => 'BASE',
		),
 		'ALLOW_RATING' => array(
			'NAME' => GetMessage("PRMEDIA_BC_ALLOW_RATING"),
			'TYPE' => 'CHECKBOX',
			'PARENT' => 'BASE',
			'ADDITIONAL_VALUES' => 'N',
			"DEFAULT" => 'Y',
			),
		'NES_RATING' => array(
			'NAME' => GetMessage("PRMEDIA_BC_NES_RATING"),
			'TYPE' => 'INT',
			'MULTIPLE' => 'N',
			'ADDITIONAL_VALUES' => 'N',
			'PARENT' => 'BASE',
		),
		'NES_COMMENTS_COUNT' => array(
			'NAME' => GetMessage("PRMEDIA_BC_NES_COMMENTS_COUNT"),
			'TYPE' => 'INT',
			'MULTIPLE' => 'N',
			'ADDITIONAL_VALUES' => 'N',
			'PARENT' => 'BASE',
			'DEFAULT' => 3
		),		
		'TO_USERPAGE' => array(
			'NAME' => GetMessage("PRMEDIA_BC_TO_USERPAGE"),
			'TYPE' => 'STRING',
			'MULTIPLE' => 'N',
			'DEFAULT' => '/users/#USER_LOGIN#/',
			'ADDITIONAL_VALUES' => 'N',
			'PARENT' => 'EXTRA',
		),
		'ASNAME' => array(
			'NAME' => GetMessage("PRMEDIA_BC_ASNAME"),
			'TYPE' => 'LIST',
			'PARENT' => 'VIS',
			'ADDITIONAL_VALUES' => 'N',
            "DEFAULT" => "50",
            'VALUES' => Array(
            	"login" => GetMessage('PRMEDIA_BC_ASNAME_SHOW_LOGIN'),
            	"name_lastname" => GetMessage("PRMEDIA_BC_ASNAME_SHOW_NAME_LASTNAME"),
            	"name" => GetMessage("PRMEDIA_BC_ASNAME_SHOW_NAME")
			)
		),
		'SHOW_USERPIC' => array(
			'NAME' => GetMessage("PRMEDIA_BC_SHOW_USERPIC"),
			'TYPE' => 'CHECKBOX',
			'PARENT' => 'VIS',
			'ADDITIONAL_VALUES' => 'N',
			"DEFAULT" => 'Y',
		),
		'SHOW_DATE' => array(
			'NAME' => GetMessage("PRMEDIA_BC_SHOW_DATE"),
			'TYPE' => 'CHECKBOX',
			'PARENT' => 'VIS',
			'ADDITIONAL_VALUES' => 'N',
			"DEFAULT" => 'Y',
		),
		'SHOW_COMMENT_LINK' => array(
			'NAME' => GetMessage("PRMEDIA_BC_SHOW_COMMENT_LINK"),
			'TYPE' => 'CHECKBOX',
			'PARENT' => 'VIS',
			'ADDITIONAL_VALUES' => 'N',
			"DEFAULT" => 'N'
		),	
        "DATE_FORMAT" => CIBlockParameters::GetDateFormat(GetMessage("PRMEDIA_BC_DATE_FORMAT"), "VIS"),	
		"CACHE_TIME"  =>  array("DEFAULT"=>3600),
	),
);

?>