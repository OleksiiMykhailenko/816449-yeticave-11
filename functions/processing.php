<?php

/**
 * Функция получения значения из параметра пост-запроса
 * @param string $name строка с наименованием параметра пост-запроса
 * @return string значение параметра пост-запроса
 */
function get_post_val($name)
{
    return filter_input(INPUT_POST, $name);
}
