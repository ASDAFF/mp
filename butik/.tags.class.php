<?php
	CModule::IncludeModule("iblock");

	Class WRTags{
		var $sectIcons, $elemIcons;
	
		public function __construct(){
			
		}
		
		public function getSections($iblock_id, $section_id = 0){
			$result = array();
			$arFilter = Array('IBLOCK_ID'=>$iblock_id, 'GLOBAL_ACTIVE'=>'Y', 'SECTION_ID' => $section_id);
  			$db_list = CIBlockSection::GetList(array('SORT'=>'ASC'), $arFilter, true);
			echo $db_list->NavPrint($arIBTYPE["SECTION_NAME"]);
			while($ar_result = $db_list->GetNext()){
				$this->sectIcons[$ar_result['ID']] = CFile::GetPath($ar_result['PICTURE']);
				
				$result[$ar_result['ID']] = $ar_result['NAME'];
			}
  			return $result;
		}
		
		public function getElements($iblock_id){
			$result = array();
			$arSelect = Array("ID", "NAME", "PREVIEW_PICTURE");
			$arFilter = Array("IBLOCK_ID"=>$iblock_id, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
			$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
			while($ob = $res->GetNextElement()){
				$arFields = $ob->GetFields();
				$this->elemIcons[$arFields['ID']] = CFile::GetPath($arFields['PREVIEW_PICTURE']);
				$result[$arFields['ID']] = $arFields['NAME'];
			}
			return $result;
		}
		
		
/*
		public function drawCatalog(){
			$catalog = $this->getSections(12);
			$i = 0;
			echo '<a href="/butik/">'.(isset($_GET['catalog']) && isset($catalog[$_GET['catalog']])?'<span style="color:#f15824;">'.$catalog[$_GET['catalog']].'</span>':'Каталог').'</a><ul>';
			foreach($catalog as $key => $value){
				++$i;
				if($i>12)$i=1;
				echo '<li class="mn-'.($i<=9?"0$i":$i).'"><a href="/butik/?catalog='.$key.'">'.$value.'</a></li>';
			}
			echo '</ul>';					
		}
*/

		public function getK($target){
			if(count($target)<6 && count($target)%3)
				$k = 2;
			else
				$k = 3;
			return $k;
		}
		
		public function drawCatalog(){
			$catalog = $this->getSections(12);			
			$i = 0;
			$k = $this->getK($catalog);
			echo '<a href="javascript:;">'.(isset($_GET['catalog']) && isset($catalog[$_GET['catalog']])?'<span style="color:#f15824;">Каталог</span>':'Каталог').'</a>';
			echo '<ul><table class="catalog-menu">';
			foreach($catalog as $key => $value){	
				$i++;
				if(($i%$k)==1) echo '<tr>';		
				echo '<td><a href="/butik/?catalog='.$key.'"><img src="'.$this->sectIcons[$key].'" />'.$value.'</a></td>';
				if(($i%$k)==0) echo '</tr>';	
			}	
			echo '</table></ul>';					
		}

		public function drawFacility(){
			$facility = $this->getElements(11);
			$i = 0;
			$k = $this->getK($facility);
				
			echo '<a href="/butik/">'.(isset($_GET['facility']) && isset($facility[$_GET['facility']])?'<span style="color:#f15824;">Безграничные возможности</span>':'Безграничные возможности').'</a>';
			echo '<ul><table class="catalog-menu">';
			
			
			foreach($facility as $key => $value){	
				$i++;
				if(($i%$k)==1) echo '<tr>';		
				echo '<td><a href="/butik/?facility='.$key.'"><img src="'.$this->elemIcons[$key].'" />'.$value.'</a></td>';
				if(($i%$k)==0) echo '</tr>';	
			}	
			echo '</table></ul>';					
		}
		
		public function drawFacilityIndex(){
			$facility = $this->getElements(11);
			$i = 0;
			$k = $this->getK($facility);
			foreach($facility as $key => $value){	
				$i++;
				if(($i%$k)==1) echo '<tr>';		
				echo '<li><a href="/butik/?facility='.$key.'">'.$value.'</a></li>';
				if(($i%$k)==0) echo '</tr>';	
			}	
						
		}
		
		public function drawGifts(){
			$gifts = $this->getElements(10);
			$i = 0;
			$k = $this->getK($gifts);
				
			echo '<a href="/butik/">'.(isset($_GET['gift']) && isset($gifts[$_GET['gift']])?'<span style="color:#f15824;">Подарки</span>':'Подарки').'</a>';
			echo '<ul><table class="catalog-menu">';
			
			
			foreach($gifts as $key => $value){	
				$i++;
				if(($i%$k)==1) echo '<tr>';		
				echo '<td><a href="/butik/?gift='.$key.'"><img src="'.$this->elemIcons[$key].'" />'.$value.'</a></td>';
				if(($i%$k)==0) echo '</tr>';	
			}	
			echo '</table></ul>';					
		}

		public function drawBrands(){
			$brands = $this->getElements(9);
			$i = 0;
			$k = $this->getK($brands);
				
			echo '<a href="/butik/">'.(isset($_GET['gift']) && isset($brands[$_GET['gift']])?'<span style="color:#f15824;">Мануфактуры</span>':'Мануфактуры').'</a>';
			echo '<ul><table class="catalog-menu">';
			
			
			foreach($brands as $key => $value){	
				$i++;
				if(($i%$k)==1) echo '<tr>';		
				echo '<td><a href="/butik/?brand='.$key.'"><img src="'.$this->elemIcons[$key].'" />'.$value.'</a></td>';
				if(($i%$k)==0) echo '</tr>';	
			}	
			echo '</table></ul>';					
		}
		
		/* ------------------- */
		
		public function drawCatalog2(){
			$catalog = $this->getSections(12);			
			$i = 0;
			$k = $this->getK($catalog);
			echo '<a class="index-cat" href="/butik/">'.(isset($_GET['catalog']) && isset($catalog[$_GET['catalog']])?'<span style="color:#f15824;">Каталог</span>':'Каталог').'</a>';
			echo '<ul><table class="catalog-menu">';
			foreach($catalog as $key => $value){	
				$i++;
				if(($i%$k)==1) echo '<tr>';		
				echo '<td><a href="/butik/?catalog='.$key.'"><img src="'.$this->sectIcons[$key].'" />'.$value.'</a></td>';
				if(($i%$k)==0) echo '</tr>';	
			}	
			echo '</table></ul>';					
		}
		
		public function drawFacility2(){
			$facility = $this->getElements(11);
			$i = 0;
			$k = $this->getK($facility);
				
			echo '<a class="index-cat" href="/butik/">'.(isset($_GET['facility']) && isset($facility[$_GET['facility']])?'<span style="color:#f15824;">Безграничные возможности</span>':'Безграничные возможности').'</a>';
			echo '<ul><table class="catalog-menu">';
			
			
			foreach($facility as $key => $value){	
				$i++;
				if(($i%$k)==1) echo '<tr>';		
				echo '<td><a href="/butik/?facility='.$key.'"><img src="'.$this->elemIcons[$key].'" />'.$value.'</a></td>';
				if(($i%$k)==0) echo '</tr>';	
			}	
			echo '</table></ul>';					
		}
		
		public function drawGifts2(){
			$gifts = $this->getElements(10);
			$i = 0;
			$k = $this->getK($gifts);
				
			echo '<a class="index-cat" href="/butik/">'.(isset($_GET['gift']) && isset($gifts[$_GET['gift']])?'<span style="color:#f15824;">Подарки</span>':'Подарки').'</a>';
			echo '<ul><table class="catalog-menu">';
			
			
			foreach($gifts as $key => $value){	
				$i++;
				if(($i%$k)==1) echo '<tr>';		
				echo '<td><a href="/butik/?gift='.$key.'"><img src="'.$this->elemIcons[$key].'" />'.$value.'</a></td>';
				if(($i%$k)==0) echo '</tr>';	
			}	
			echo '</table></ul>';					
		}
		
		public function drawSections(){
			global $APPLICATION;
			$sect = array();
			$catSection = intval($_GET['section']);
			if(isset($_GET['catalog'])){
				$sections = $this->getSections(12, intval($_GET['catalog']));
				if (!array_key_exists($catSection, $sections)) {
					$sec = CIBlockSection::GetList(false, array('IBLOCK_ID' => 12, 'ID' => $catSection), false, array('IBLOCK_SECTION_ID', 'NAME'))->Fetch();
					$catSection = $sec['IBLOCK_SECTION_ID'];
					$APPLICATION->SetTitle($sec['NAME']);

				}
				foreach($sections as $key => $value){
					if ($catSection==$key) {
						$APPLICATION->SetTitle($value);
					}
					$sect[] = '<a href="/butik/?catalog='.intval($_GET['catalog']).($_GET['section']==$key?'':'&section='.$key).'" class="'.($catSection==$key?'active':'').'">'.$value.'</a>';
				}
			}
			return implode('', $sect);
		}

		/**
		 * 3й уровень тегов
		 * @param int $id ID родительского раздела
		 * @return string HTML третьего уровня тегов
		 */
		public function drawSubSections($id = NULL) {
			$id = (is_null($id)) ? intval($_GET['section']) : $id;
			$sect = array(); 
			if(isset($id)) {
				$sections = $this->getSections(12, $id);
				foreach($sections as $key => $value){
					if ($id == $key) {
						$APPLICATION->SetTitle($value);
					}
					$sect[] = '<a href="/butik/?catalog='.intval($_GET['catalog']).($_GET['section']==$key?'':'&section='.$key).'" class="'.($_GET['section']==$key?'active':'').'">'.$value.'</a>';
				}
			}
			return implode('', $sect);
		}
		
		public function setFilter(){
			global $arrFilter;
			
			if(isset($_GET['catalog'])){
				$arrFilter['PROPERTY_CATALOG'] = intval($_GET['catalog']);
			}
			
			if(isset($_GET['brand'])){
				$arrFilter['PROPERTY_BRAND'] = intval($_GET['brand']);
			}
			
			if(isset($_GET['seller'])){
				$arrFilter['PROPERTY_SELLER'] = intval($_GET['seller']);
			}

			if(isset($_GET['facility'])){
				$arrFilter['PROPERTY_FACILITY'] = intval($_GET['facility']);
			}
			
			if(isset($_GET['gift'])){
				$arrFilter['PROPERTY_GIFT'] = intval($_GET['gift']);
			}	
			
			if(isset($_GET['section'])){
				$arrFilter['PROPERTY_CATALOG'] = intval($_GET['section']);
			}	
		}
		

		public function getName() {
			global $APPLICATION;
			if(isset($_GET['catalog'])){
				$res = CIBlockSection::GetByID(intval($_GET["catalog"]));
				if($ar_res = $res->GetNext()){
					$APPLICATION->SetTitle($ar_res['NAME']);
					$sections = $this->drawSections();	
					return '<div class="custom-tags"><div class="title"><strong>'.$ar_res['NAME'].($sections!=''?':':'').'</strong></div> '.$sections.' </div><div style="clear:both;"></div>';
				}				
			}
			
			if(isset($_GET['facility'])){
				$res = CIBlockElement::GetByID(intval($_GET['facility']));
				if($ar_res = $res->GetNext())
					$APPLICATION->SetTitle($ar_res['NAME']);
					return '<div class="custom-tags"><strong><img src="'.$this->elemIcons[intval($_GET["facility"])].'" />'.$ar_res['NAME'].'</strong>'.' </div><div style="clear:both;"></div>';
			}
			
			if(isset($_GET['gift'])){
				$res = CIBlockElement::GetByID(intval($_GET['gift']));
				if($ar_res = $res->GetNext())
					$APPLICATION->SetTitle($ar_res['NAME']);
					return '<div class="custom-tags"><strong><img src="'.$this->elemIcons[intval($_GET["gift"])].'" />'.$ar_res['NAME'].'</strong>'.' </div><div style="clear:both;"></div>';
			}
		}

		/**
		 * HTML категорий типов товаров (3-й уровень тегов)
		 * @param  int $sectionId ID раздела
		 * @return string         HTML типов
		 */
		public function drawSectionChain($sectionId) {
			if ($sections = $this->drawSubSections($sectionId)) {
				$sec = CIBlockSection::GetList(false, array('ID' => $sectionId, 'IBLOCK_ID' => 12), false, array('UF_TYPE_NAME'))->Fetch();
				$typeName = ($sec['UF_TYPE_NAME']) ? $sec['UF_TYPE_NAME'] : 'Тип' ;
				return '<div class="custom-tags"><div class="title"><strong>' . $typeName . ':</strong></div> '.$sections.' </div><div style="clear:both;"></div>';
			} else {
				return false;
			}
		}

		/**
		 * Категории типаов товаров (3-й уровень)
		 * @return string HTML код на вывод
		 */
		public function subTags()
		{
			if(isset($_GET['section'])) {
				$res = CIBlockSection::GetByID(intval($_GET["section"]));
				if($ar_res = $res->GetNext()) {
					if ($ar_res['DEPTH_LEVEL'] == 4) {
						$res = CIBlockSection::GetByID($ar_res['IBLOCK_SECTION_ID']);
						$ar_res = $res->GetNext();
						$res = CIBlockSection::GetByID($ar_res['IBLOCK_SECTION_ID']);
						$ar_res = $res->GetNext();
					} elseif ($ar_res['DEPTH_LEVEL'] == 3) {
						$res = CIBlockSection::GetByID($ar_res['IBLOCK_SECTION_ID']);
						$ar_res = $res->GetNext();
					}
					return $this->drawSectionChain($ar_res['ID']);
				}				
			}
			
			if(isset($_GET['facility'])){
				$res = CIBlockSection::GetByID(intval($_GET["facility"]));
				if($ar_res = $res->GetNext()) {
					if ($ar_res['DEPTH_LEVEL'] == 3) {
						$res = CIBlockSection::GetByID($ar_res['IBLOCK_SECTION_ID']);
						$ar_res = $res->GetNext();
					}
					return $this->drawSectionChain($ar_res['ID']);
				}				
			}
			
			if(isset($_GET['gift'])){
				$res = CIBlockSection::GetByID(intval($_GET["gift"]));
				if($ar_res = $res->GetNext()) {
					if ($ar_res['DEPTH_LEVEL'] == 3) {
						$res = CIBlockSection::GetByID($ar_res['IBLOCK_SECTION_ID']);
						$ar_res = $res->GetNext();
					}
					return $this->drawSectionChain($ar_res['ID']);
				}				
			}
		}

		/**
		 * Категории типаов товаров (3-й уровень)
		 * @return string HTML код на вывод
		 */
		public function subSubTags()
		{
			if(isset($_GET['section'])) {
				$res = CIBlockSection::GetByID(intval($_GET["section"]));
				if($ar_res = $res->GetNext()) {
					if ($ar_res['DEPTH_LEVEL'] > 3) {
						$res = CIBlockSection::GetByID($ar_res['IBLOCK_SECTION_ID']);
						$ar_res = $res->GetNext();
					} elseif ($ar_res['DEPTH_LEVEL'] < 3) {
						return;
					}
					return $this->drawSectionChain($ar_res['ID']);
				}				
			}
			
			if(isset($_GET['facility'])){
				$res = CIBlockSection::GetByID(intval($_GET["facility"]));
				if($ar_res = $res->GetNext()) {
					if ($ar_res['DEPTH_LEVEL'] == 3) {
						$res = CIBlockSection::GetByID($ar_res['IBLOCK_SECTION_ID']);
						$ar_res = $res->GetNext();
					}
					return $this->drawSectionChain($ar_res['ID']);
				}				
			}
			
			if(isset($_GET['gift'])){
				$res = CIBlockSection::GetByID(intval($_GET["gift"]));
				if($ar_res = $res->GetNext()) {
					if ($ar_res['DEPTH_LEVEL'] == 3) {
						$res = CIBlockSection::GetByID($ar_res['IBLOCK_SECTION_ID']);
						$ar_res = $res->GetNext();
					}
					return $this->drawSectionChain($ar_res['ID']);
				}				
			}
		}


		public function getBrands() {
			if (!isset($_GET['section']) && !isset($_GET['catalog'])) {
				return false;
			}
			$catalogBrands = array();
			if (isset($_GET['section'])) {
				$sectionId = $_GET['section'];
				$propertyCode = 'CATALOG';
			} else {
				$sectionId = $_GET['catalog'];
				$propertyCode = 'CATALOG';
			}
			$rsItems = CIBlockElement::GetList(false, array(
				'IBLOCK_ID' => 1,
				'PROPERTY_' . $propertyCode => $sectionId,
				'ACTIVE' => 'Y'
				), false, false, array('PROPERTY_BRAND'));
			while ($item = $rsItems->Fetch()) {
				if (!in_array($item['PROPERTY_BRAND_VALUE'], $catalogBrands) && $item['PROPERTY_BRAND_VALUE']) {
					$catalogBrands[] = $item['PROPERTY_BRAND_VALUE'];
				}
			}
			if (empty($catalogBrands)) {
				return false;
			}
			$rsItems = CIBlockElement::GetList(array('SORT' => 'ASC'), array(
				'IBLOCK_ID' => 9,
				'ACTIVE' => 'Y',
				'ID' => $catalogBrands
				), false, false, array(
				'ID', 'NAME', 'CODE'
				));
			while ($item = $rsItems->Fetch()) {
				$brands[$item['ID']] = array(
					'id' => $item['ID'],
					'name' => $item['NAME'],
					'code' => $item['CODE']
					);
			}
			/**
			 * Рисуем HTML
			 */
			global $APPLICATION;
			$html = array();
			$html[] = '<div class="custom-tags">
				<div class="title"><strong>Бренд:</strong></div>';
			foreach ($brands as $id => $brand) {
				if ($_GET['brand'] == $id) {
					$APPLICATION->SetTitle($brand['name']);
				}
				$html[] = '<a href="' . $APPLICATION->GetCurPageParam('brand=' . $brand['id'], array('brand')) . '" class="' . (($_GET['brand'] == $id) ? 'active' : '')  . '">' . $brand['name'] . '</a>';
			}
			$html[] = '</div>';
			return implode('', $html);
		}
	}	