<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("BEST_COMMENTS"),
	"DESCRIPTION" => GetMessage("BEST_COMMENTS_DESCRIPTION"),
	"ICON" => "/images/bestcomments.gif",
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