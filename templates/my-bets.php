<section class="rates container">
    <h2>Мои ставки</h2>
    <table class="rates__list">
        <?php foreach ($rates as $rate): ?>
            <tr class="rates__item <?= $rate['rate_class'] ?>">
                <td class="rates__info">
                    <div class="rates__img">
                        <img src="/uploads/<?php echo $rate['lot_img'] ?>" width="54" height="40"
                             alt="<?php echo htmlspecialchars($rate['lot_name']) ?>">
                    </div>
                    <div>
                        <h3 class="rates__title"><a
                                href="<?php echo 'lot.php?id=' . $rate['lot_id'] ?>"><?php echo htmlspecialchars($rate['lot_name']) ?></a>
                        </h3>
                        <p><?php echo $rate['contacts'] ?></p>
                    </div>
                </td>
                <td class="rates__category">
                    <?php echo htmlspecialchars($rate['lot_category']) ?>
                </td>
                <td class="rates__timer">
                    <div class="timer <?php echo $rate['timer_class'] ?>"><?php echo $rate['timer_message'] ?></div>
                </td>
                <td class="rates__price">
                    <?php echo htmlspecialchars($rate['price']) ?>
                </td>
                <td class="rates__time">
                    <?php echo format_rate_date($rate['dt_add']) ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</section>
