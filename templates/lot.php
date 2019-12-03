<section class="lot-item container">
    <h2><?php echo htmlspecialchars($lot['title'] ?? ''); ?></h2>
    <div class="lot-item__content">
        <div class="lot-item__left">
            <div class="lot-item__image">
                <img src="/uploads/<?php echo htmlspecialchars($lot['image'] ?? ''); ?>" width="730" height="548"
                     alt="<?php echo htmlspecialchars($lot['title'] ?? ''); ?>">
            </div>
            <p class="lot-item__category">Категория: <span><?php echo htmlspecialchars($lot['category'] ?? ''); ?></span></p>
            <p class="lot-item__description"><?php echo htmlspecialchars($lot['description'] ?? ''); ?></p>
        </div>
        <div class="lot-item__right">
                <div class="lot-item__state">
                    <div
                        class="lot__timer timer <?php echo (!empty($time_report['hours'] === '00') ? 'timer--finishing' : ''); ?>">
                        <?php echo is_array($time_report) ? implode(':', $time_report) : 'error'; ?>
                    </div>
                    <div class="lot-item__cost-state">
                        <div class="lot-item__rate">
                            <span class="lot-item__amount">Текущая цена</span>
                            <span
                                class="lot-item__cost"><?php echo formatting_sum(htmlspecialchars($lot['price'] ?? '')); ?></span>
                        </div>

                        <?php if ($is_auth) : ?>
                        <div class="lot-item__min-cost">
                            Мин. ставка <span><?php echo $lot['bid_step'] ?? ''; ?></span>
                        </div>
                    </div>
                    <div>
                    <?php if ($show_rate_block) : ?>
                        <form class="lot-item__form" action="<?php echo 'lot.php?id=' . $lot['id'] ?? ''; ?>" method="post"
                              autocomplete="off">
                            <?php $classname = isset($errors['cost']) ? "form__item--invalid" : ""; ?>
                            <p class="lot-item__form-item form__item <?php echo $classname; ?>">
                                <label for="cost">Ваша ставка</label>
                                <input id="cost" type="text" name="cost"
                                       placeholder="<?php echo $lot['starting_price'] + $lot['bid_step']; ?>">
                                <span class="form__error"><?php echo $errors['cost'] ?? ''; ?></span>
                            </p>
                            <button type="submit" class="button">Сделать ставку</button>
                        </form>
                    <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>

            <?php if (!empty($rates)) :?>
                <div class="history">
                    <h3>История ставок (<span><?php echo count($rates); ?></span>)</h3>
                    <table class="history__list">
                        <?php foreach ($rates as $rate): ?>
                            <tr class="history__item">
                                <td class="history__name"><?php echo htmlspecialchars($rate['user'] ?? ''); ?></td>
                                <td class="history__price"><?php echo htmlspecialchars($rate['price'] ?? ''); ?></td>
                                <td class="history__time"><?php echo format_rate_date($rate['time'] ?? ''); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

