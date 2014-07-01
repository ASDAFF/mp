		</div>
	</div>

<?
	$path = explode('/', trim($APPLICATION->sDirPath, '/'));
	if(count($path) > 1){
?>

<div class="block-text-w">
	<div class="main">
		<h3>Категории товаров</h3>
			<?
				require_once($_SERVER['DOCUMENT_ROOT'].'/butik/.tags.class.php');
				$tags = new WRTags();
				$catalog = $tags->getSections(12);
				$i = 0;
				foreach($catalog as $key => $value){
					if(++$i==11 || $i==12) echo '<div class="cat-item">&nbsp;</div>';
					echo '<div class="cat-item"><a href="/butik/?catalog='.$key.'"><img src="'.$tags->sectIcons[$key].'" style="height:45px;" alt="">'.$value.'</a></div>';
				}
			?>
		
			
			<!--
			<div class="cat-item"><a href="#"><img src="/src/images/tov_50.jpg" alt="">Bluetooth</a></div>
			<div class="cat-item"><a href="#"><img src="/src/images/tov_43.jpg" alt="">Музыка</a></div>
			<div class="cat-item"><a href="#"><img src="/src/images/tov_45.jpg" alt="">Арт</a></div>
			<div class="cat-item"><a href="#"><img src="/src/images/tovar_48.jpg" alt="">Спорт</a></div>
			
			<div class="cat-item"><a href="#"><img src="/src/images/tov_56.jpg" alt="">Авто</a></div>
			<div class="cat-item"><a href="#"><img src="/src/images/tov_61.jpg" alt="">Игрушки</a></div>
			<div class="cat-item"><a href="#"><img src="/src/images/tov_57.jpg" alt="">Стиль</a></div>
			<div class="cat-item"><a href="#"><img src="/src/images/tovar_58.jpg" alt="">iOs</a></div>
			<div class="cat-item"><a href="#"><img src="/src/images/tov_60.jpg" alt="">Android</a></div>
			
			<div class="cat-item">&nbsp;</div>
			<div class="cat-item"><a href="#"><img src="/src/images/tov_67.jpg" alt="">Активный отдых</a></div>
			<div class="cat-item">&nbsp;</div>
			<div class="cat-item"><a href="#"><img src="/src/images/tov_68.jpg" alt="">Все</a></div>
			<div class="cat-item">&nbsp;</div>
			-->

	</div>
</div>
<?	} ?>
	
<div class="footer">
	<?include $_SERVER['DOCUMENT_ROOT'] . '/include/.dom.footer.php';?>
</div>
</body></html>