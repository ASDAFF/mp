<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED == false)
    die();
/** @var $arResult array */
?>
<div class="reviews-page">
<div class="reviews__right">
    <div class="veteran">
        <p> У обладателя почетного знака не было ни одного выходного за год</p>
    </div>
    <!--noindex--><a class="btn-blue_onliner" rel="nofollow" target="_blank" href="http://4082.shop.onliner.by/"><span>Смотреть отзывы на Onliner</span></a><!--/noindex-->
</div>
<!--reviews__right-->
<div class="reviews__left">
    <h1>Отзывы о магазине</h1>
    <div class="textStart">
                <div class="descr_pref">
           <div class="descrText_pref">
               <div class="textHeight_pref">
        <?
        $APPLICATION->IncludeComponent(
                "bitrix:main.include", "", Array(
         "AREA_FILE_SHOW" => "page",
         "AREA_FILE_SUFFIX" => "page_bottom_inc",
         "AREA_FILE_RECURSIVE" => "Y",
         "EDIT_TEMPLATE" => ""
                ), false
        );
        ?>
               </div>
           </div>
         <a class="moreLink_pref" href="javascript:void(0);" title="Развернуть">Развернуть</a>
    </div> 
     </div>
    <?if($USER->IsAuthorized()):?>
        <div class="reviews-top__reiting">
            <a class="add-review" href="javascript:void(0);"><span>Добавить отзыв</span></a>
        </div>
    <?endif;?>
    <? foreach ($arResult["REVIEWS"] as $item): ?>
    <div class="review-block" itemprop="review" itemscope="" itemtype="http://schema.org/Review" id="review<?=$item['PROPERTY_REVIEW_VALUE_ID']?>">
        <div class="review-block__user">
            <ul class="list-reiting">
                <? for ($i = 0; $i < (int) $item["PROPERTY_RATING_VALUE"]; ++$i): ?>
                    <li class="item-reiting reiting-good"></li>
                <? endfor; ?>
                <?$k = $i; for ($j = $i; $j < 5; ++$j): ?>
                    <li class="item-reiting"></li>
                <? endfor; ?>
                    <li class="raiting-none" itemscope="" itemtype="http://schema.org/Rating">
                        <meta itemprop="worstRating" content="1">
                        <span itemprop="ratingValue"><?=$k?></span>
                        <span itemprop="bestRating">5</span>
                    </li>
            </ul>
            <h2 itemprop="name"><?= $item["PROPERTY_TITLE_VALUE"] ?></h2>

            <p itemprop="description"><?= $item["PROPERTY_REVIEW_VALUE"]["TEXT"] ?></p>

            <div class="review-tail"></div>
        </div>
        <!--review-block__user-->
        <ul class="list-user-data">
            <li class="user-data_name" itemprop="author"><?= $item["PROPERTY_NAME_VALUE"] ?></li>
            <li class="user-data_date"><meta itemprop="datePublished" content="<?= $item["DATE_CREATE"] ?>"><?= $item["DATE_CREATE"] ?></li>
            <? if ($item["PROPERTY_WILL_BUY_VALUE"]): ?>
                <li class="user-data_review review-good">Буду еще здесь покупать</li>
            <? else: ?>
                <li class="user-data_review">Больше не буду здесь покупать</li>
            <? endif; ?>
        </ul>
    </div>
        <!--review-block-->
        <? if ($item["PROPERTY_REPLY_VALUE"]): ?>
        	<div class="review-block">
          	  <div class="review-block__admin">
               	 <p><?= $item["PROPERTY_REPLY_VALUE"] ?></p>
              	  <div class="review-tail"></div>
           	 </div>
            <!--review-block__user-->
            <ul class="list-admin-data">
                <li class="admin-data_name">Ответ продавца</li>
            </ul>
        </div>
        <? endif; ?>
    <? endforeach;  ?>
    <!--review-block-->
    <?= (strlen($arResult["NAV_STRING"])) ? $arResult["NAV_STRING"] : '' ?>
</div>
<!--reviews__left-->
<?if($USER->IsAuthorized()):?>
    <a class="add-review" href="javascript:void(0);"><span>Добавить отзыв</span></a>
<? else: ?>
    <div class="forum_guest">
        <span>Только зарегистрированные пользователи могут оставлять отзывы.</span>
        <br/>
        <span id="enter" class="ob" onclick="$('body').animate({scrollTop:0}, '100');">Войти</span> или <a href="http://vipmag.by/auth/" class="ob" title="Зарегистрироваться">Зарегистрироваться</a>
    </div>
<?endif;?>
<?if($USER->IsAuthorized()):?>
<div class="wrapper-review-form">
    <form class="form-review" style="<?= $_REQUEST["ACTION"] == "ADD" ? "display:block;"
        : "" ?>" action="<?= POST_FORM_ACTION_URI ?>" method="POST" enctype="application/x-www-form-urlencoded">
        <input type="hidden" name="ACTION" value="ADD"/>
        <h3>Добавление отзыва:</h3>
        <? foreach ($arResult["ERRORS"] as $item): ?>
            <span style="color:red;margin-left: 20px;"><?= $item ?></span><br>
        <? endforeach; ?>
        <? if ($arResult["SUCCESS"]): ?>
            <span style="color: green;margin-left: 20px; margin-bottom: 15px;">Ваш отзыв успешно добавлен и отправлен на модерацию</span>
        <? else: ?>
        <div class="row-input-review">
            <div class="input-review__title">
                <div class="title-input">Опишите ваши впечатления:</div>
                Минимум 10 слов, обязательно упомяните модель товара, который вы приобрели
            </div>
            <div class="input-review__input">
                <div class="wrapper-input">
                    <textarea class="textarea-review" name="REVIEW"><?= $_REQUEST["REVIEW"] ? : "" ?></textarea>
                    <?CheckError("REVIEW", $arResult["FIELD_ERRORS"])?>
                </div>
            </div>
        </div>
        <!--row-input-review-->
        <div class="row-input-review">
            <div class="input-review__title">
                <div class="title-input">Подытожьте ваш отзыв:</div>
                Максимум 15 слов
            </div>
            <div class="input-review__input">
                <div class="wrapper-input">
                    <input type="text" name="TITLE" class="input-review" value="<?= $_REQUEST["TITLE"] ? : "" ?>">
                    <?CheckError("TITLE", $arResult["FIELD_ERRORS"])?>
                </div>
            </div>
        </div>
        <!--row-input-review-->
        <div class="row-input-review">
            <div class="input-review__title">
                <div class="title-input">Общая оценка VIPMAG.BY:</div>
            </div>
            <ul class="list-radiobtn">
                <li class="item-radiobtn active">
                    <input type="radio" name="RATING" id="ev_1" value="5" <? GetRating(5) ?>><label for="ev_1">отлично</label></li>
                <li class="item-radiobtn">
                    <input type="radio" name="RATING" value="4" id="ev_2" <? GetRating(4) ?>><label for="ev_2">хорошо</label></li>
                <li class="item-radiobtn">
                    <input type="radio" name="RATING" value="3" id="ev_3" <? GetRating(3) ?>><label for="ev_3">средне</label></li>
                <li class="item-radiobtn">
                    <input type="radio" name="RATING" value="2" id="ev_4" <? GetRating(2) ?>><label for="ev_4">ниже среднего</label></li>
                <li class="item-radiobtn">
                    <input type="radio" name="RATING" value="1" id="ev_5" <? GetRating(1) ?>><label for="ev_5">жуть</label></li>
            </ul>
        </div>
        <!--row-input-review-->
        <div class="row-input-review">
            <div class="input-review__title">
                <div class="title-input">Будете ли в дальнейшем пользоваться нашими услугами:</div>
            </div>
            <ul class="list-radiobtn">
                <li class="item-radiobtn_2 active">
                    <input type="radio" name="WILL_BUY" value="Да" id="yes_1" checked><label for="yes_1">да</label></li>
                <li class="item-radiobtn_2">
                    <input type="radio" name="WILL_BUY" id="yes_2"><label for="yes_2">нет</label></li>
            </ul>
        </div>
        <!--row-input-review-->
        <?//echo '<pre>' . var_dump($arResult["REVIEWS"]) . '</pre>';?>
        <?if ($USER->IsAuthorized()):?>
            <div class="row-input-review" style="display: none;">
                <div class="input-review__title">
                    <div class="title-input">Ваше имя:</div>
                </div>
                <div class="input-review__input_small">
                    <div class="wrapper-input">
                        <input type="text" name="NAME" class="input-review" value="<?=$USER->GetLogin();?>">
                        <?CheckError("NAME", $arResult["FIELD_ERRORS"])?>
                    </div>
                </div>
            </div>
        <?else:?>
            <div class="row-input-review">
                <div class="input-review__title">
                    <div class="title-input">Ваше имя:</div>
                </div>
                <div class="input-review__input_small">
                    <div class="wrapper-input">
                        <input type="text" name="NAME" class="input-review" <?= $_REQUEST["NAME"] ? : "" ?>>
                        <?CheckError("NAME", $arResult["FIELD_ERRORS"])?>
                    </div>
                </div>
            </div>
        <?endif;?>
        <!--row-input-review-->


        <!--row-input-review-->
        <div class="row-input-review">
            <div class="input-review__input wrapper-checkbox wrapper-submit-review">
                <input type="submit" class="submit-review" value="Опубликовать отзыв"><a class="cancellation-review" href="javascript:void(0);">Отмена</a>
            </div>
        </div>
        <!--row-input-review-->
       
        <? endif; ?>
    </form>
</div>
<?endif;?>
<!--wrapper-review-form-->
</div><!--reviews-page-->
<?if($USER->IsAuthorized()):?>
<script>
    $(document).ready(function () {
        //Смена активного класса в форме отзывов
        $('.list-radiobtn input[type="radio"]').on('click', function () {
            if (!$(this).closest('li').hasClass('active')) {
                $(this).closest('.list-radiobtn').find('li').removeClass('active');
                $(this).closest('li').addClass('active');
            }
        });
        //Открытие-закрытие формы отзывов
        $('.add-review').on('click', function () {
            var $formReview = $('.form-review');
            if (!$formReview.hasClass('visible')) {
                var y = $('.wrapper-review-form').offset().top - 100;
                $('html, body').animate({
                    scrollTop: y
                }, 300);
            }
            $formReview.slideToggle(300).toggleClass('visible');
            return false;
        });
        $('.cancellation-review').on('click', function () {
            $('.form-review').slideUp(300).removeClass('visible')
            return false;
        });
    });
</script>
<?endif;?>