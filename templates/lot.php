<section class="lot-item container">
    <h2><?php echo htmlspecialchars($lot['title']); ?></h2>
    <div class="lot-item__content">
        <div class="lot-item__left">
            <div class="lot-item__image">
                <img src="<?php echo htmlspecialchars($lot['image']); ?>" width="730" height="548"
                     alt="<?php echo htmlspecialchars($lot['title']) ?>">
            </div>
            <p class="lot-item__category">Категория: <span><?php echo htmlspecialchars($lot['category']); ?></span></p>
            <p class="lot-item__description"><?php echo htmlspecialchars($lot['description']); ?></p>
        </div>
        <div class="lot-item__right">
            <div class="lot-item__state">
                <div class="lot__timer timer <?php echo $time_report['hours'] === '00' ? 'timer--finishing' : '' ?>">
                    <?php echo implode(':', $time_report) ?>
                </div>
                <div class="lot-item__cost-state">
                    <div class="lot-item__rate">
                        <span class="lot-item__amount">Текущая цена</span>
                        <span
                            class="lot-item__cost"><?php echo formatting_sum(htmlspecialchars($lot['starting_price'])); ?></span>
                    </div>
                    <div class="lot-item__min-cost">
                        Мин. ставка <span><?php echo $lot['bid_step']; ?></span>
                    </div>
                </div>
            </div>
</section>
