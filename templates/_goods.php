<li class="lots__item lot">
    <div class="lot__image">
        <img src="<?php echo htmlspecialchars($good['url_image']); ?>" width="350" height="260" alt="">
    </div>
    <div class="lot__info">
        <span class="lot__category"><?php echo htmlspecialchars($good['category']); ?></span>
        <h3 class="lot__title"><a class="text-link"
                                  href="pages/lot.html"><?php echo htmlspecialchars($good['title']); ?></a></h3>
        <div class="lot__state">
            <div class="lot__rate">
                <span class="lot__amount">Стартовая цена</span>
                <span
                    class="lot__cost"><?php echo formatting_sum(htmlspecialchars($good['price'])); ?></span>
            </div>
            <div class="lot__timer timer <?php echo $time_report['$hours'] === '00' ? 'timer--finishing' : '' ?>">
                <?php echo implode(':', $time_report) ?>
            </div>
        </div>
    </div>
</li>
