<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>

<?

if (!CModule::IncludeModule('prmedia.treelikecomments'))
{
	return;
}

// cancel
class CancelStep extends CWizardStep
{
	function InitStep()
	{
		$this->SetTitle(GetMessage('PRMEDIA_TLC_IMPORT_CANCEL_CANCEL_TITLE'));
		$this->SetStepID('cancel_step');
		$this->SetCancelStep('cancel_step');
	}

	function ShowStep()
	{
		$this->content = GetMessage('PRMEDIA_TLC_IMPORT_CANCEL_CANCEL_CONTENT');
	}

}

// greeting
class GreetingStep extends CWizardStep
{
	function InitStep()
	{
		$this->SetTitle(GetMessage('PRMEDIA_TLC_IMPORT_CANCEL_GREETING_TITLE'));
		$this->SetStepID('greeting_step');
		$this->SetNextStep('import_step');
		$this->SetCancelStep('cancel_step');
	}

	function ShowStep()
	{
		$this->content = GetMessage('PRMEDIA_TLC_IMPORT_CANCEL_GREETING_CONTENT');
	}
}

// import
class ImportStep extends CWizardStep
{

	function InitStep()
	{
		$this->SetTitle(GetMessage('PRMEDIA_TLC_IMPORT_CANCEL_IMPORT_TITLE'));
		$this->SetStepID('import_step');
		$this->SetNextStep('final_step');
		$this->SetFinishStep('final_step');
	}

	function ShowStep()
	{
		$wizard = & $this->GetWizard();
		$path = $wizard->package->path;

		CJSCore::Init(array('ajax'));
		$this->content = '<div id="treelike_comments_import_progress">' . GetMessage('PRMEDIA_TLC_IMPORT_CANCEL_IMPORT_CONTENT') . '</div>';

		$this->content .= '<link rel="stylesheet" href="/bitrix/panel/main/admin-public.css" />';
		$this->content .= '<style>#wait_treelike_comments_import_progress, .step-buttons {display: none !important;}</style>';
		$this->content .= '
			<script>
				parent.document.getElementsByClassName("bx-core-adm-icon-close")[0].style.display = "none";
				;(function (window) {
					var BX = window.BX;
					var commentImporter = function () {};
					commentImporter.prototype.init = function () {
						BX.ajax.insertToNode("' . $path . '/ajax/import.php?start=Y", BX("treelike_comments_import_progress"));
					};
					commentImporter.prototype.update = function () {
						BX.ajax.insertToNode("' . $path . '/ajax/import.php", BX("treelike_comments_import_progress"));
					};

					// add library to window context
					window.jsPrmediaCommentImporter = new commentImporter();
				}) (window);
				jsPrmediaCommentImporter.init();

			</script>
		';
	}

}

// final
class FinalStep extends CWizardStep
{
	function InitStep()
	{
		$this->SetTitle(GetMessage('PRMEDIA_TLC_IMPORT_CANCEL_FINAL_TITLE'));
		$this->SetStepID('final_step');
		$this->SetCancelStep('final_step');
		$this->SetCancelCaption(GetMessage('PRMEDIA_TLC_IMPORT_CANCEL_FINAL_CANCEL_TITLE'));
	}

	function ShowStep()
	{
		$this->content = GetMessage('PRMEDIA_TLC_IMPORT_CANCEL_FINAL_CONTENT');
	}

}

?>