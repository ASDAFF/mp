<? if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();

if(!CModule::IncludeModule("iblock"))
	return;

$arEditorList = array(
					"no" => GetMessage("EDITOR_NO"),
					"light" => GetMessage("EDITOR_LIGHT"),
				);

$arComponentParameters = array(
	'GROUPS' => array(

      "VIS" => array(
         "NAME" => GetMessage("VISUAL_PARAMS"),
         "SORT" => 1
      ),
	  "ACCESS" => array(
         "NAME" => GetMessage("ACCESS_FIELDS_GROUP_NAME"),
         "SORT" => 300

      ),
      	  "NOTIFICATIONS" => array(
         "NAME" => GetMessage("NOTIFICATIONS"),
         "SORT" => 302

      ),
      	  "SEO" => array(
         "NAME" => GetMessage("SEO_FIELDS_GROUP_NAME"),
         "SORT" => 301
      ),

          "EXTRA" => array(
         "NAME" => GetMessage("EXTRA"),
         "SORT" => 350

      ),
	),
	'PARAMETERS' => array(
		'OBJECT_ID' => array(
			'NAME' => GetMessage("OBJECT_ID"),
			'TYPE' => 'INT',
			'MULTIPLE' => 'N',
			'ADDITIONAL_VALUES' => 'N',
			'PARENT' => 'BASE',
			),
		'CAN_MODIFY' => array(
			'NAME' => GetMessage("CAN_MODIFY"),
			'TYPE' => 'CHECKBOX',
			'MULTIPLE' => 'N',
			'ADDITIONAL_VALUES' => 'N',
			'PARENT' => 'BASE',
			'DEFAULT' => 'N'
		),
		'PREMODERATION' => array(
			'NAME' => GetMessage("PREMODERATION"),
			'TYPE' => 'CHECKBOX',
			'PARENT' => 'BASE',
			'ADDITIONAL_VALUES' => 'N',
			"DEFAULT" => 'Y',
			),
 		'ALLOW_RATING' => array(
			'NAME' => GetMessage("ALLOW_RATING"),
			'TYPE' => 'CHECKBOX',
			'PARENT' => 'BASE',
			'ADDITIONAL_VALUES' => 'N',
			"DEFAULT" => 'Y',
			),
		'AUTH_PATH' => array(
			'NAME' => GetMessage("AUTH_PATH"),
			'TYPE' => 'STRING',
			'PARENT' => 'EXTRA',
			'ADDITIONAL_VALUES' => 'N',
            "DEFAULT" => '/auth/',
			),
		'TO_USERPAGE' => array(
			'NAME' => GetMessage("TO_USERPAGE"),
			'TYPE' => 'STRING',
			'PARENT' => 'EXTRA',
            "DEFAULT" => "/users/#USER_LOGIN#/",
			'ADDITIONAL_VALUES' => 'N',
			),
		'LEFT_MARGIN' => array(
			'NAME' => GetMessage("LEFT_MARGIN"),
			'TYPE' => 'STRING',
			'PARENT' => 'VIS',
			'ADDITIONAL_VALUES' => 'N',
            "DEFAULT" => "50",
			),

 		'MAX_DEPTH_LEVEL' => array(
			'NAME' => GetMessage("MAX_DEPTH_LEVEL"),
			'TYPE' => 'STRING',
			'PARENT' => 'VIS',
			'ADDITIONAL_VALUES' => 'N',
            "DEFAULT" => '5',
			),

		'ASNAME' => array(
			'NAME' => GetMessage("ASNAME"),
			'TYPE' => 'LIST',
			'PARENT' => 'VIS',
			'ADDITIONAL_VALUES' => 'N',
            "DEFAULT" => "50",
            'VALUES' => Array(
            	"login" => GetMessage('SHOW_LOGIN'),
            	"name_lastname" => GetMessage("SHOW_NAME_LASTNAME"),
            	"name" => GetMessage("SHOW_NAME")
			)
			),

		'SHOW_USERPIC' => array(
			'NAME' => GetMessage("SHOW_USERPIC"),
			'TYPE' => 'CHECKBOX',
			'PARENT' => 'VIS',
			'ADDITIONAL_VALUES' => 'N',
			"DEFAULT" => 'Y',
			),

		'SHOW_DATE' => array(
			'NAME' => GetMessage("SHOW_DATE"),
			'TYPE' => 'CHECKBOX',
			'PARENT' => 'VIS',
			'ADDITIONAL_VALUES' => 'N',
			"DEFAULT" => 'Y',
			),
				'SHOW_COMMENT_LINK' => array(
			'NAME' => GetMessage("SHOW_COMMENT_LINK"),
			'TYPE' => 'CHECKBOX',
			'PARENT' => 'VIS',
			'ADDITIONAL_VALUES' => 'N',
			"DEFAULT" => 'N'
			),
            "DATE_FORMAT" => CIBlockParameters::GetDateFormat(GetMessage("DATE_FORMAT"), "VIS"),
		'SHOW_COUNT' => array(
			'NAME' => GetMessage("SHOW_COUNT"),
			'TYPE' => 'CHECKBOX',
			'PARENT' => 'VIS',
			'ADDITIONAL_VALUES' => 'N',
			"DEFAULT" => 'Y'
			),

		'NO_FOLLOW' => array(
			'NAME' => GetMessage("NO_FOLLOW"),
			'TYPE' => 'CHECKBOX',
			'PARENT' => 'SEO',
			'ADDITIONAL_VALUES' => 'N',
			),
		'NO_INDEX' => array(
			'NAME' => GetMessage("NO_INDEX"),
			'TYPE' => 'CHECKBOX',
			'PARENT' => 'SEO',
			'ADDITIONAL_VALUES' => 'N',
			),
		'NON_AUTHORIZED_USER_CAN_COMMENT' => array(
			'NAME' => GetMessage("NON_AUTHORIZED_USER_CAN_COMMENT"),
			'TYPE' => 'CHECKBOX',
			'PARENT' => 'ACCESS',
			'DEFAULT' => 'N',
			'ADDITIONAL_VALUES' => 'N',
			),

		'USE_CAPTCHA' => array(
			'NAME' => GetMessage("USE_CAPTCHA"),
			'TYPE' => 'LIST',
			'PARENT' => 'ACCESS',
			'ADDITIONAL_VALUES' => 'N',
            "DEFAULT" => "50",
            'VALUES' => Array(
            	"NO" => GetMessage('NO_CAPTCHA'),
            	"CAPTCHA_BITRIX" => GetMessage("CAPTCHA_BITRIX"),
            	"ROBOT" => GetMessage("ROBOT")
			)
			),
		'FORM_MIN_TIME' => array(
			'NAME' => GetMessage('FORM_MIN_TIME'),
			'TYPE' => 'STRING',
			'PARENT' => 'ACCESS',
			'DEFAULT' => '3'
		),

 		'SEND_TO_USER_AFTER_ANSWERING' => array(
			'NAME' => GetMessage("SEND_TO_USER_AFTER_ANSWERING"),
			'TYPE' => 'CHECKBOX',
			'PARENT' => 'NOTIFICATIONS',
			'ADDITIONAL_VALUES' => 'N',
			"DEFAULT" => 'Y',
			),

            		'SEND_TO_USER_AFTER_MENTION_NAME' => array(
			'NAME' => GetMessage("SEND_TO_USER_AFTER_MENTION_NAME"),
			'TYPE' => 'CHECKBOX',
			'PARENT' => 'NOTIFICATIONS',
			'ADDITIONAL_VALUES' => 'N',
			"DEFAULT" => 'Y',
			),

            		'SEND_TO_ADMIN_AFTER_ADDING' => array(
			'NAME' => GetMessage("SEND_TO_ADMIN_AFTER_ADDING"),
			'TYPE' => 'CHECKBOX',
			'PARENT' => 'NOTIFICATIONS',
			'ADDITIONAL_VALUES' => 'N',
			"DEFAULT" => 'Y',
			),

          		'SEND_TO_USER_AFTER_ACTIVATE' => array(
			'NAME' => GetMessage("SEND_TO_USER_AFTER_ACTIVATE"),
			'TYPE' => 'CHECKBOX',
			'PARENT' => 'NOTIFICATIONS',
			'ADDITIONAL_VALUES' => 'N',
			"DEFAULT" => 'Y',
			),

		"CACHE_TIME"  =>  array("DEFAULT"=>3600),

	),
);
if(COption::GetOptionString('prmedia.treelikecomments', 'bb_code_enable') == 1)
{
	$arComponentParameters['PARAMETERS']['SHOW_FILEMAN'] = array(
				'NAME' => GetMessage("SHOW_FILEMAN"),
				'TYPE' => 'LIST',
				'PARENT' => 'VIS',
				'DEFAULT' => 'no',
				'VALUES' => $arEditorList,
				);
}
?>