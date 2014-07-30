<?php
	
	CModule::IncludeModule("iblock");

	Class WRProducts{
		var $iblock_id, $index_iblock_id;
		
		public function __construct($iblock_id){
			$this->iblock_id = intval($iblock_id);
		}
		
		private function checkOffers($product_id){
			if(CCatalogSKU::IsExistOffers($product_id, $this->iblock_id)){
				$arSelect = Array("IBLOCK_ID", "ID", "CATALOG_GROUP_1");
				$arFilter = Array("PROPERTY_CML2_LINK" => $product_id, "ACTIVE"=>"Y");
				$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
				if($ob = $res->GetNextElement()){
					$arFields = $ob->GetFields();
					return $arFields['CATALOG_PRICE_1'];
				}
			}else{
				return false;
			}
		}
		
		public function getProduct($product_id, $big = false){
			$data = array();
			$arSelect = Array("IBLOCK_ID", "ID", "NAME", "PREVIEW_PICTURE", "DETAIL_PICTURE", "CATALOG_GROUP_1", "PREVIEW_TEXT", "PROPERTY_DESCRIPTION", "CODE");
			$arFilter = Array("IBLOCK_ID"=>$this->iblock_id, "ID" => $product_id, "ACTIVE"=>"Y");
			$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
			if($ob = $res->GetNextElement()){
				$arFields = $ob->GetFields();
				
				$small_file = CFile::ResizeImageGet($arFields['PREVIEW_PICTURE'], array('width'=>300*1.5, 'height'=>199*1.5), BX_RESIZE_IMAGE_EXACT, true, false, false, 100);
				$big_file = CFile::ResizeImageGet($arFields['DETAIL_PICTURE'], array('width'=>638*1.5, 'height'=>427*1.5), BX_RESIZE_IMAGE_EXACT, true, false, false, 100);
				
				$data = array(
					'NAME' => $arFields['NAME'],
					'TEXT' => $arFields['PREVIEW_TEXT'],
					'PREVIEW_PICTURE' => $small_file['src'],
					'DETAIL_PICTURE' => $big_file['src'],
					'PRICE' => number_format($arFields['CATALOG_PRICE_1'], -1, ',', ' ' ),
					// 'DESCRIPTION' => $arFields['PROPERTY_DESCRIPTION_VALUE']['TEXT'],
					'DESCRIPTION' => $arFields['PREVIEW_TEXT'],
					'CODE' => $arFields['CODE']
				);
				if (true === $big) {
					$data['DESCRIPTION'] = $arFields['PROPERTY_DESCRIPTION_VALUE']['TEXT'];
				}
				
				if($price = $this->checkOffers($product_id)){
					$data['PRICE'] = number_format($price, -1, ',', ' ' );
				}
				
				return $data;

			}
			
		}
			
		public function setIndexIblock($iblock_id){
			$this->index_iblock_id = intval($iblock_id);
		}
			
		public function getProductID($place_id){
			$product_id = 0;
			$arSelect = Array("IBLOCK_ID", "ID", "NAME", "PROPERTY_PRODUCT");
			$arFilter = Array("IBLOCK_ID"=>$this->index_iblock_id, "ID" => $place_id, "ACTIVE"=>"Y");
			$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
			if($ob = $res->GetNextElement()){
				$arFields = $ob->GetFields();
				$product_id = $arFields['PROPERTY_PRODUCT_VALUE'];
			}
			return $product_id;
		}
			
		public function drawSmallProduct($place_id){
			$product_id = $this->getProductID($place_id);
			$data = $this->getProduct($product_id);
			
			
			if(!empty($data)){
				return '
				<div class="item-block2">
					<div style="height:200px; width:300px;">
						<a href="/butik/' . $data['CODE'] . '/"><img style="width:300px; height:199px;" src="' . $data['PREVIEW_PICTURE'] . '" alt=""></a>
					</div>				
					<a href="/butik/' . $data['CODE'] . '/" class="cat-link">
						' . $data['NAME'] . ': ' . $data['DESCRIPTION'] . '
					</a>
					<div class="price">' . $data["PRICE"] . ' р.</div>
				</div>
				';
			}

		}
		
		public function drawBigProduct($place_id){
			$product_id = $this->getProductID($place_id);
			$data = $this->getProduct($product_id, $big = true);
			
			
			if(!empty($data)){
				return '
				<div class="white-block">
				  <a href="/butik/'.$data['CODE'].'/">
					<div class="w-left"><img style="width:638px; height:427px;" src="'.$data['DETAIL_PICTURE'].'" alt=""></div>
					<div class="w-right">
						<h3>'.$data['NAME'].'</h3>					
						<div class="dsc">'.$data['DESCRIPTION'].'</div>
						<div class="price">'.$data['PRICE'].' р.</div>
					</div>
				  </a>
				</div>
				';
			}

		}
		
	}