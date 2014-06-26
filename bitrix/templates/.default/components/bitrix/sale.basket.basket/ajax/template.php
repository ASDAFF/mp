<?php

	if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

	$result = array(
		'items' => array(),
		'sum'	=> $arResult['allSum'],
		'sumFormat' => number_format($arResult['allSum'], -1, ',', ' ').' р.'
	);
	
	foreach($arResult['GRID']['ROWS'] as $item){		
		
		$res = CIBlockElement::GetByID($item['PRODUCT_ID']);
		$code = '';
		$preview = '';
		$name = '';
		if($ar_res = $res->GetNext()){
			if($ar_res['IBLOCK_ID'] == 2){
				$db_props = CIBlockElement::GetProperty($ar_res['IBLOCK_ID'], $ar_res['ID'], array("sort" => "asc"), array("CODE" => array("CML2_LINK", "CML2_ATTRIBUTES")));
				while($ar_props = $db_props->Fetch()){
					if($ar_props['CODE'] == 'CML2_LINK'){
						$res2 = CIBlockElement::GetByID($ar_props['VALUE']);
						if($ar_res2 = $res2->GetNext()){
							$code = $ar_res2['CODE'];
							$preview = $ar_res2['PREVIEW_TEXT'];
						}
					}elseif($ar_props['CODE']=='CML2_ATTRIBUTES'){
						$name = $ar_res['NAME'].' — '.$ar_props['VALUE'];
					}					
				}					
			}else{
				$name = $ar_res['NAME'];
				$code = $ar_res['CODE'];
				$preview = $ar_res['PREVIEW_TEXT'];
			}
		}
	
		$result['items'][] = array(
			'name' => $name,
			'product_id' => $item['PRODUCT_ID'],
			'quantity' => $item['QUANTITY'],
			'id' => $item['ID'],
			'picture' => $item['PREVIEW_PICTURE_SRC'],
			'sum' => number_format($item['FULL_PRICE']*$item['QUANTITY'], -1, ',', ' ' ).' р.',
			'price' => $item['FULL_PRICE'],
			'code' => $code,
			'description' => $preview,
		);
	}
	
	//echo '<pre>'; print_r($arResult); echo '</pre>';

	header('Content-type: application/json; charset=utf-8');
	
	echo json_encode($result);