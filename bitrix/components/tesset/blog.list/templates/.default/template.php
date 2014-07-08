<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<div class="content-blog">
    <?foreach ($arResult['items'] as $item) : ?>
        <div class="blog-item">
            <div style="height:200px; width:300px;">
                <a href="<?=$item['url']?>">
                    <img style="width:300px; height:199px;" src="<?=$item['picture']['src']?>" alt="<?=$item['name']?>">
                </a>
            </div>    
            <div class="date"><span class="fa fa-clock-o"> <?=$item['date']?></span></div>
            <a class="name" href="<?=$item['url']?>"><?=$item['name']?></a>
            <div class="anounce"><?=$item['anounce']?></div>
            <a class="more-link" href="<?=$item['url']?>">Подробнее</a>
            <div class="info">
                <span class="fa fa-eye"> <?=$item['shows']?></span>
                <span class="fa fa-heart-o blog-like blog-item-like" data-element="<?=$item['id']?>" data-ib="<?=$item['iblockId']?>"> <?=$item['likes']?></span>
            </div>
        </div>
    <?endforeach;?>
</div>