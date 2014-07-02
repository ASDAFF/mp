<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<div class="reviews-box">
    <div class="h4"><span class="solid">Отзывы о магазине:</span></div>

	<?foreach ($arResult['REVIEWS'] as $item) : ?>
    <div class="review-box">
        <a class="review-box__link black-link" href="<?=$item['URL']?>" title="<?=$item['PROPERTY_TITLE_VALUE']?>"><?=$item['PROPERTY_TITLE_VALUE']?></a>

        <div class="review-box__author">
            <div class="author-name"><span class="author-name__name"><?=$item['PROPERTY_NAME_VALUE']?></span>
                 <span class="avatar">
                    <?if ($item['AVATAR']['HTML']) : ?>
                        <?=$item['AVATAR']['HTML']?>
                    <?else : ?>
                        <img src="/images/temp/new/av.jpg" alt=""/>
                    <?endif;?>
                </span></div>

            <div class="review-date">
                <div><?=$item['DATE_FORMATED'][0]?></div>
                <div><?=$item['DATE_FORMATED'][1]?></div>
            </div>

        </div>
        <div class="review-box__body">
            <?=$item['PROPERTY_REVIEW_VALUE']['TEXT']?>
        </div>

    </div>
    <?endforeach;?>
</div>