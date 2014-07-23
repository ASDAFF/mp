<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("PRMEDIA_POPULAR_ELEMENTS_TITLE"),
	"DESCRIPTION" => GetMessage("PRMEDIA_POPULAR_ELEMENTS_DESCR"),
	"ICON" => "/images/popular_elements.gif",
	"CACHE_PATH" => "Y",
	"PATH" => array(
		"ID" => "communication",
		"CHILD" => array(
			"ID" => "prmedia_treelike_comments",
			"NAME" => GetMessage("TREELIKE_COMMENTS_TITLE")
		),
	),
);
?>