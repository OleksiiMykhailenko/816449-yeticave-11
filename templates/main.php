<section class="promo">
    <h2 class="promo__title">Нужен стафф для катки?</h2>
    <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и
        горнолыжное снаряжение.</p>
    <ul class="promo__list">
        <?php
        foreach ($categories as $category): ?>
            <li class="promo__item promo__item--<?php echo htmlspecialchars($category['character_code']); ?>">
                <a class="promo__link" href="pages/all-lots.html"><?php echo htmlspecialchars($category['title']); ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</section>
<section class="lots">
    <div class="lots__header">
        <h2>Открытые лоты</h2>
    </div>
    <ul class="lots__list">
        <?php foreach ($goods as $good): ?>
            <?php echo include_template('_goods.php', ['good' => $good, 'time_report' => get_dt_range($good['date_of_completion'])]) ?>
        <?php endforeach; ?>
    </ul>
</section>
