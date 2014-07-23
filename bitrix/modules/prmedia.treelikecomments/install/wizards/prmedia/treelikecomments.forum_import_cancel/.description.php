<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>

<?

$arWizardDescription = Array(
	'NAME' => GetMessage('PRMEDIA_TLC_IMPORT_CANCEL_TITLE'),
	'DESCRIPTION' => GetMessage('PRMEDIA_TLC_IMPORT_CANCEL_DESCRIPTION'),
	'ICON' => '',
	'COPYRIGHT' => GetMessage('PRMEDIA_TLC_IMPORT_CANCEL_COPYRIGHTS'),
	'VERSION' => '1.0.0',
	'STEPS' => array('GreetingStep', 'ImportStep', 'FinalStep', 'CancelStep'),
);
?>