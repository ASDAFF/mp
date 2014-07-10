<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<?$APPLICATION->ShowHead();?>
	<title><?$APPLICATION->ShowTitle()?></title>
	<meta name="Keywords" content="Главная">
	<meta name="Description" content="Главная">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link rel="stylesheet" type="text/css" href="/src/css/view.css">
	<link rel="stylesheet" type="text/css" href="/src/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="/src/css/butik.css" />
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
	<script type="text/javascript" src="/src/javascript/superfish.js"></script>
		<link rel="stylesheet" type="text/css" href="/src/jquery-ui-1.10.4.custom/css/custom-theme/jquery-ui-1.10.4.custom.css">
	<script type="text/javascript" src="/src/jquery-ui-1.10.4.custom/js/jquery-ui-1.10.4.custom.min.js"></script>
	<script type="text/javascript" src="/src/underscore-min.js"></script>
	<link rel="stylesheet" type="text/css" href="/src/javascript/fancybox/jquery.fancybox.css">
	<script type="text/javascript" src="/src/javascript/fancybox/jquery.fancybox.js"></script>
	<script type="text/javascript" src="/src/javascript/custom.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$('.show-search').click(function(e){
				e.preventDefault();
				$('.search').slideToggle();				
				return false;
			});+
				
		});
	</script>
</head>
<body>
<?$APPLICATION->ShowPanel()?>

<? require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/templates/butik/cart.php'); ?>
<? require_once($_SERVER['DOCUMENT_ROOT'].'/registration.php'); ?>

<style type="text/css">
	.left-column{
		width:100%;
		text-align: center;
		font-size: 18px;
		line-height: 25px;
		padding: 0px 50px 0px 0px;
		color: #999;
		font-weight: 300;
	}
</style>

<div class="all">
	<div class="header">
		<div class="main">
			<ul class="top-menu">
				<li><a href="/"><img src="/src/images/icon_build_preview.png" style="height:80px;" alt=""></a></li>
				<li><a <?=strstr($APPLICATION->sDirPath, 'butik')?'class="active"':'';?> href="/butik/">Магазин<span> покупать</span></a></li>
				<!-- <li><a href="#">Магазин<span>покупать</span></a></li> -->
				<li><a <?=strstr($APPLICATION->sDirPath, 'evrika')?'class="active"':'';?> href="/evrika/">ЭВРИКА<span>находить ниши</span></a></li>
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
			 ОПЫТЫ
			</div>
			<div style="position: absolute; top: 300px; width: 100%; text-align: center;">
				<a class="index-cat-evrika" href="mailto:office@muchmore.ru">НАПИСАТЬ</a>
			</div>
			<div style="clear:both;"></div>
		</div>
	</div>
	
	<div class="container" style="min-height: 500px;">
		<div class="main">
		
		<div class="search" style="margin: 0px 20px 28px 20px;">
				<form action="/search/">
				<input name="search" type="text" value="Поиск по сайту" class="sinp" onfocus="if(this.value=='Поиск по сайту')this.value='';" onblur="if(this.value=='')this.value='Поиск по сайту';">
				<p>Поиск по названию или описанию</p><!--  (Поиск по всем категориям изначально, с последующей фильтрацией) -->
				<input type="submit" style="position:absolute; opacity:0;" />
				</form>
			</div>		