<div class="container">
    <section class="lots">
        <h2>Результаты поиска по запросу «<span><?php echo $search; ?></span>»</h2>
        <ul class="lots__list">

            <?php foreach ($lots as $lot): ?>
                <?php echo include_template('_lots.php', ['lot' => $lot,
                    'time_report' => get_dt_range($lot['date_of_completion'])]); ?>
            <?php endforeach; ?>

        </ul>
    </section>
    <?php echo include_template('_pagination.php', [
        'pages' => $pages,
        'pages_count' => $pages_count,
        'cur_page' => $cur_page,
        'search' => $search
    ]); ?>
</div>
