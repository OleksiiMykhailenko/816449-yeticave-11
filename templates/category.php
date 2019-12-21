<div class="container">
    <section class="lots">
        <h2>Все лоты в категории «<span><?php echo $category_name; ?></span>»</h2>
        <?php if (empty($lots)): ?>
            <p>В данной категории нет лотов</p>
        <?php endif ?>
        <ul class="lots__list">
            <?php foreach ($lots as $lot): ?>
                <?php echo include_template('_lots.php', ['lot' => $lot,
                    'time_report' => get_dt_range($lot['date_of_completion'])]); ?>
            <?php endforeach; ?>
        </ul>
    </section>
    <?php if ($pages_count > 1): ?>
        <ul class="pagination-list">
            <li class="pagination-item pagination-item-prev"><a href="<?php echo $prev_page_link; ?>">Назад</a></li>
            <?php foreach ($pages as $page): ?>
                <li class="pagination-item <?php if ($page === $cur_page): ?>pagination-item-active<?php endif; ?>"><a href="<?php echo $url; ?>&page=<?php echo $page; ?>"><?php echo $page; ?></a></li>
            <?php endforeach; ?>
            <li class="pagination-item pagination-item-next"><a href="<?php echo $next_page_link; ?>">Вперед</a></li>
        </ul>
    <?php endif ?>
</div>
