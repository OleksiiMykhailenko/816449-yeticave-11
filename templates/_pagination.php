<?php if ($pages_count > 1): ; ?>
    <div class="pagination">
        <ul class="pagination-list">
            <li class="pagination-item pagination-item-prev">
                <a <?php if ($cur_page > 1): ?>href="/search.php?search=<?php echo $search; ?>&page=<?php echo $cur_page - 1; ?>"<?php endif; ?>>Назад</a>
            </li>
            <?php foreach ($pages as $page): ?>
                <li class="pagination__item <?php if ($page === $cur_page): ?>pagination-item-active<?php endif; ?>">
                    <a href="/search.php?search=<?php echo $search; ?>&page=<?php echo $page; ?>"><?php echo $page; ?></a>
                </li>
            <?php endforeach; ?>
            <li class="pagination-item pagination-item-next">
                <a <?php if ($cur_page < $page): ?>href="/search.php?search=<?php echo $search; ?>&page=<?php echo $cur_page + 1; ?>"<?php endif; ?>>Вперед</a>
            </li>
        </ul>
    </div>
<?php endif; ?>
