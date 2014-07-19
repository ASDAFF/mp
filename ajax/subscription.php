<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule('subscribe');

$rsRubric = CRubric::GetList(false, array('ACTIVE' => 'Y'));
while ($rubric = $rsRubric->Fetch()) {
	$rubrics[] = $rubric['ID'];
}
$error = false;
if (isset($_GET['email'])) {
	if (!check_email($_GET['email'])) {
		$error = 'Введен неверый Email адрес.';
	} else {
		$fields = Array(
		    'FORMAT' => 'html',
		    'EMAIL' => trim($_GET['email']),
		    'ACTIVE' => 'Y',
			'CONFIRMED' => 'Y',
		    'RUB_ID' => $rubrics
		);
	}
} else {
	if ($USER->isAuthorized()) {
		$fields = Array(
		    'USER_ID' => $USER->GetId(),
		    'FORMAT' => 'html',
		    'EMAIL' => $USER->GetEmail(),
		    'ACTIVE' => 'Y',
			'CONFIRMED' => 'Y',
		    'RUB_ID' => $rubrics
		);
	}
}

if (false === $error) {
	$subscr = new CSubscription;
	$subscr->Add($fields);
	$error = (!$subscr->LAST_ERROR) ? 0 : $subscr->LAST_ERROR;
}


echo json_encode($error);
