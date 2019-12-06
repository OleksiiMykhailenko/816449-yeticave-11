<li class="lots__item lot">
    <div class="lot__image">
        <img src="<?php echo htmlspecialchars($lot['image'] ?? ''); ?>" width="350" height="260" alt="">
    </div>
    <div class="lot__info">
        <span class="lot__category"><?php echo htmlspecialchars($lot['category'] ?? ''); ?></span>
        <h3 class="lot__title"><a class="text-link"
                                  href="lot.php?id=<?php echo $lot['id'] ?? ''; ?>"><?php echo htmlspecialchars($lot['title'] ?? ''); ?></a>
        </h3>
        <div class="lot__state">
            <div class="lot__rate">
                <span class="lot__amount">Стартовая цена</span>
                <span
                    class="lot__cost"><?php echo formatting_sum(htmlspecialchars($lot['starting_price']) ?? ''); ?></span>
            </div>
            <div class="lot__timer timer <?php echo (int) $time_report['hours'] === '00' ? 'timer--finishing' : ''; ?>">
                <?php echo is_array($time_report) ? implode(':', $time_report) : 'error'; ?>
            </div>
        </div>
    </div>
</li>
