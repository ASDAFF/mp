<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<?$APPLICATION->ShowHead();?>
	<title><?$APPLICATION->ShowTitle()?></title>
	<meta name="Keywords" content="Подарок парню, подарок музыканту, подарок для девушки, оригинальный подарок, подарок для спортсмена, подарок для фотографа, подарок блоггеру, подарок экстремалу, подарок дачнику, подарок охотнику и рыболову, подарок фотографу, подарок художнику, подарок киноману, подарок спортсмену, подарок автолюбителю, подарок любовнице, подарок меломану, подарок для семьи, подарок девушке, подарок хозяйке, подарок боссу, подарок для босса, подарок для ребенка, подарок ребёнку, аксессуары для iPhone, гаджеты, гаджеты для спорта,">
	<meta name="Description" content="Всё, что нужно для жизни в городе, для занятий спортом, йогой, музыкой, бизнесом и развлечений. Безграничные возможности научиться новому, превзойти, наслаждаться и отличаться.">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link rel="stylesheet" type="text/css" href="/src/css/view.css">
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
			});
			
			
		});
	</script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-52713505-1', 'auto');
  ga('require', 'displayfeatures'); 
  ga('send', 'pageview');
  

</script>

</head>
<body>
<?$APPLICATION->ShowPanel()?>

<? require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/templates/butik/cart.php'); ?>
<? require_once($_SERVER['DOCUMENT_ROOT'].'/registration.php'); ?>

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
			МАГАЗИН  БЕЗГРАНИЧНЫХ<br>ВОЗМОЖНОСТЕЙ
			</div>
			
			<?
				require_once('butik/.tags.class.php');
				$tags = new WRTags();
			?>
			<link rel="stylesheet" type="text/css" href="/src/css/butik.css" />
			<ul class="top-menu2">
			<!-- 	<? $tags->drawFacilityIndex();?> -->
			
	<div class="cat-menu" style="padding-top:0px;">
		<ul class="sf-menu">
			<li class="index-cat-li"><? $tags->drawCatalog2();?></li>
			<li class="index-cat-li"><? $tags->drawGifts2();?></li>
			<li class="index-cat-li"><? $tags->drawFacility2();?></li>			
		</ul>
	</div>	
	<div style="clear:both;"></div>
				<!--
<li><a href="#">Быть</a></li>
				<li><a href="#">Отличаться</a></li>
				<li><a href="#">Наслаждаться</a></li>
				<li><a href="#">Превзойти</a></li>
-->
			</ul>
			
		</div>
	</div>
	
	<div class="container">
		<div class="main">
		
		<div class="search" style="margin: 0px 20px 28px 20px;">
				<form action="/search/">
				<input name="search" type="text" value="Поиск по сайту" class="sinp" onfocus="if(this.value=='Поиск по сайту')this.value='';" onblur="if(this.value=='')this.value='Поиск по сайту';">
				<p>Поиск по названию или описанию</p><!--  (Поиск по всем категориям изначально, с последующей фильтрацией) -->
				<input type="submit" style="position:absolute; opacity:0;" />
				</form>
			</div>
		
		<?
	require_once($_SERVER['DOCUMENT_ROOT'].'/butik/.tags.class.php');
	$tags = new WRTags();
	?>
	
		