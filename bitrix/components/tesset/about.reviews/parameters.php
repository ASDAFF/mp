<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true){die();
}


$arComponentParameters = array(
	"PARAMETERS" => array(
		"CACHE_TIME" => array("DEFAULT"=>"3600000"),
		"AJAX_MODE"  => array(),
        "REVIEWS_COUNT" => array(
            "TYPE" => "TEXT",
            "NAME" => "Количество отзывов на странице"
        )
	)
);