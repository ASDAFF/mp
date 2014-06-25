<?php
	CModule::IncludeModule("iblock");

	Class WRTags{
		var $tags, $activeTags;
		
		public function __construct(){
			/* Группы тегов */
			$this->tags = array(
				'CAT'	=> array('NAME' => 'Каталог',	'IBLOCK_ID' => 6,	'CODE' => 'cat'),
				'SECT'	=> array('NAME' => 'Раздел',	'IBLOCK_ID' => 7,	'CODE' => 'sect'),
				'TYPE'	=> array('NAME' => 'Тип',		'IBLOCK_ID' => 8,	'CODE' => 'type'),
				'BRAND'	=> array('NAME' => 'Бренд',		'IBLOCK_ID' => 9,	'CODE' => 'brand'),
				'GIFT'	=> array('NAME' => 'Подарки',	'IBLOCK_ID' => 10,	'CODE' => 'gift'),
			);
			
			foreach($this->tags as $tag){
				$activeTags = array();
				if(isset($_GET[$tag['CODE']])){
					$activeTags = explode(':', $_GET[$tag['CODE']]);
				}				
				$this->activeTags[$tag['CODE']] = $activeTags;
			}
			
			//echo '<pre>'; print_r($this->activeTags); echo '</pre>';
			
		}
		
		/* Получение списков тегов */
		public function getTags($tagType){
			if(!isset($this->tags[$tagType])) return false;
			$result = array('category' => array('name' => $this->tags[$tagType]['NAME'], 'code' => $this->tags[$tagType]['CODE']), 'items' => array());
			$res = CIBlockElement::GetList(array(), array("IBLOCK_ID"=>$this->tags[$tagType]['IBLOCK_ID'], "ACTIVE"=>"Y"), false, false, array("ID", "NAME"));
			while($ob = $res->GetNextElement()){
				$arFields = $ob->GetFields();
				$result['items'][] = array(
					'name' => $arFields['NAME'],
					'id' => $arFields['ID'],
					'selected' => in_array($arFields['ID'], $this->activeTags[$this->tags[$tagType]['CODE']]) ? true : false  
				);
			}			
			return $result;
		}
		
		public function drawScripts(){
			global $APPLICATION;
			echo '
			<script type="text/javascript">
				$(document).ready(function(){
					$(\'.custom-tag\').each(function(){
						$(this).click(function(e){
							e.preventDefault();
							
							if($(this).hasClass("active"))
								$(this).removeClass("active");
							else
								$(this).addClass("active");
							
							var path = [];
			';	
			foreach($this->tags as $tagSection){
				echo '
							// Категория '.$tagSection['NAME'].'
							var '.$tagSection['CODE'].'=[];
							$(\'a[item-cat="'.$tagSection['CODE'].'"]\').each(function(){
								if($(this).hasClass(\'active\')) '.$tagSection['CODE'].'.push($(this).attr(\'item-id\'));
							});	
							path.push("'.$tagSection['CODE'].'="+'.$tagSection['CODE'].'.join(":"));			
				';
			}							
			echo'
							window.location.href = "'.$APPLICATION->sDirPath.'?"+path.join("&");
							return false;							
						});
					});
				});
			</script>';
		}
		
		public function drawSelects(){			
			foreach($this->tags as $key => $tagSection){
				if($tags = $this->getTags($key)){				
					echo '<div><h2>'.$tags['category']['name'].'</h2>';
					if(!empty($tags['items'])){
						foreach($tags['items'] as $item){
							echo '<a href="#" item-cat="'.$tags['category']['code'].'" item-id="'.$item['id'].'" class="custom-tag'.($item['selected']?' active':'').'">'.$item['name'].'</a>';
						}
					}					
					echo '</div>';
				}
			}			
		}
		
		public function setFilter(){
			global $arrFilter;
			foreach($this->tags as $key => $tag){
				if(!empty($this->activeTags[$tag['CODE']])){
					$arrFilter['PROPERTY_'.$key] = $this->activeTags[$tag['CODE']];
				}
			}
		}
	}