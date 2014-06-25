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
	<div class="main">
		<div class="f-01">
			<h4>MUCHMORE</h4>
			<ul>
				<li><a href="#">Бутик</a></li>
				<li><a href="#">Маркетплейс</a></li>
				<li><a href="#">Эврика</a></li>
				<li><a href="#">Журнал</a></li>
			</ul>			
		</div>
		<div class="f-02">
			<h4>О НАС</h4>
			<ul>
				<li><a href="#">Что&nbsp;мы&nbsp;делаем</a></li>
				<li><a href="#">Команда</a></li>
				<li><a href="#">Блоги</a></li>
			</ul>			
		</div>
		<div class="f-03">
			<h4>БУДЬ В КУРСЕ</h4>
			<ul>
				<li><a href="#">Статус заказа</a></li>
				<li><a href="#">Контакты</a></li>
			</ul>			
		</div>
		<div class="f-04">
			<h4>ИНФОРМАЦИЯ</h4>
			<ul>
				<li><a href="#">Налоги</a></li>
				<li><a href="#">Контракты</a></li>
			</ul>
			<!-- Все права защищены &copy; 2014 -->		
		</div>
		
		<div class="f-05">
			<h4>ПОДПИСЫВАЙСЯ</h4>
		<ul class="soc">
			<li><a target="_blank" href="https://www.facebook.com/muchmore.ru"><img src="/src/soc/fb.png" alt=""></a></li>
			<li><a target="_blank" href="http://vk.com/muchmorevk"><img src="/src/soc/vk.png" alt=""></a></li>
			<li><a target="_blank" href="http://instagram.com/muchmorestuff/"><img src="/src/soc/in.png" alt=""></a></li>
			<li><a target="_blank" href="http://www.youtube.com/channel/UCopyr8SIJKYHZdKbm-1YQYQ"><img src="/src/soc/yt.png" alt=""></a></li>
		</ul>
		</div>


	</div>
</div>
</body></html>