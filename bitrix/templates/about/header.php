<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<?$APPLICATION->ShowHead();?>
	<title><?$APPLICATION->ShowTitle()?></title>
	<meta name="Description" content="Главная">
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
	<link rel="stylesheet" type="text/css" href="/src/css/view.css">
	<link rel="stylesheet" type="text/css" href="/src/javascript/fancybox/jquery.fancybox.css">
	<script type="text/javascript" src="/src/javascript/fancybox/jquery.fancybox.js"></script>

</head>
<body>
<?$APPLICATION->ShowPanel()?>
<div class="all">
	<div class="header">
		<div class="main">
			<ul class="top-menu">
				<li><a href="/"><img src="/src/images/icon_build_preview.png" style="height:80px;" alt=""></a></li>
				<li><a <?=strstr($APPLICATION->sDirPath, 'butik')?'class="active"':'';?> href="/butik/">Магазин<span> покупать</span></a></li>
				<!-- <li><a href="#">Магазин<span>покупать</span></a></li> -->
				<li><a <?=strstr($APPLICATION->sDirPath, '/evrika/')?'class="active"':'';?> href="/evrika/">ЭВРИКА<span>находить ниши</span></a></li>
				<li class="bl"><a <?=strstr($APPLICATION->sDirPath, 'lifehack')?'class="active"':'';?> href="/lifehack/">ЛАЙФХАК<span>знать и уметь</span></a></li>
				<li class="bl"><a href="/about/">ЧТО ТАКОЕ MUCHMORE<span>сотрудничать и инвестировать</span></a></li>
			</ul>
			
			<div class="top">
				<a href="#" class="show-search"><img src="/src/icons/search4.png" style="width:30px;" alt=""></a> 
			<?
				global $USER;
				if($USER->IsAuthorized()){
					echo '
					<a href="/personal/"><img src="/src/icons/user.png" style="width:30px;" /></a> 
					<a class="show-cart" href="#"><img src="/src/icons/basket18.png" style="width:30px;" /></a>
					';
				}else{
					echo '<a class="open-reg" href="/registration/"><img src="/src/icons/user.png" style="width:30px;" /></a>';
					echo '<a class="show-cart" href="#"><img src="/src/icons/basket18.png" style="width:30px;" /></a>';
					// echo '<a class="open-reg" href="/registration/">Регистрация</a> / <a class="open-auth" href="/personal/">Вход</a>';
					// echo '<a class="open-reg" href="/personal/">Вход</a>';
				}
			?>
			</div>

			<div class="text">
			СУПЕРМАРКЕТ С ОТКРЫТОЙ<br>АРХИТЕКТУРОЙ
			<!-- <span>Мы продаем возможности, а не просто товары. Возможности быть кем<br> угодно. Возможности учиться, экспериментировать, открывать новое и<br> самому исполнять свои заветные мечты</span> -->
			</div>
		</div>
	</div>