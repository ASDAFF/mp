<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule('sale');
CBitrixComponent::includeComponentClass("component.model:xml");

$xml = '<?xml version="1.0" encoding="UTF-8"?>
 
<customerOrder vatIncluded="true" applicable="true" sourceStoreUuid="existing-store-uuid" 
    payerVat="true" sourceAgentUuid="existing-counterparty-uuid" targetAgentUuid="existing-organization-uuid" 
    moment="2011-06-27T06:27:00+04:00" name="0001">
<customerOrderPosition vat="18" goodUuid="existing-good-uuid" quantity="4.0" discount="0.0">
<basePrice sumInCurrency="555000.0" sum="555000.0"/>
 
<reserve>0.0</reserve>
</customerOrderPosition>
</customerOrder>';
$data = new XML($xml);

var_dump($data->customerOrder->customerOrderPosition->getData());