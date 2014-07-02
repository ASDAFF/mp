<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arComponentParameters = array(
         "GROUPS" => array(
         ),
	"PARAMETERS" => array(
                "REVIEWS_COUNT" => array(
                    "TYPE" => "TEXT",
                    "NAME" => "Количество отзывов на странице",
                    "DEFAULT" => '20'
                ),
                "SHOW_AVATART" => array(
                    "TYPE" => "TEXT",
                    "NAME" => "Отображение аватара",
                    "DEFAULT" => false
                ),
                "RAITING" => array(
                    "TYPE" => "TEXT",
                    "NAME" => "Отображать только с таким рейтингом",
                    "DEFAULT" => false
                ),
                'REVIEWS_PAGE' => array(
                    'TYPE' => 'TEXT',
                    'NAME' => 'URL на страницу отзывов',
                    'DEFAULT' => false
                    ),
                "CACHE_TIME" => array("DEFAULT"=>"3600000"),
		"AJAX_MODE"  => array()
	)
);