<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("TREELIKE_COMMENTS_TITLE"),
	"DESCRIPTION" => GetMessage("TREELIKE_COMMENTS_DESCR"),
	"ICON" => "/images/treelike_comments.gif",
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