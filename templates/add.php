<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добавление лота</title>
    <link href="../css/normalize.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <link href="../css/flatpickr.min.css" rel="stylesheet">
</head>
<body>

<div class="page-wrapper">
    <main>
        <?php $classname = (isset($errors)) ? "form--invalid" : ""; ?>
        <form class="form form--add-lot container <?php echo $classname; ?>" action="add.php" method="post"
              enctype="multipart/form-data">
            <h2>Добавление лота</h2>
            <div class="form__container-two">
                <?php $classname = isset($errors['lot-name']) ? "form__item--invalid" : ""; ?>
                <div class="form__item <?php echo $classname; ?>">
                    <label for="lot-name">Наименование <sup>*</sup></label>
                    <input id="lot-name" type="text" name="lot-name"
                           value="<?php echo get_post_val('lot-name'); ?>" placeholder="Введите наименование лота">
                    <?php if (!empty($errors['lot-name'])): ?>
                        <span class="form__error"><?php echo $errors['lot-name']  ?? ''; ?></span>
                    <?php endif ?>
                </div>
                <?php $classname = isset($errors['category-id']) ? "form__item--invalid" : ""; ?>
                <div class="form__item <?php echo $classname; ?>">
                    <label for="category">Категория <sup>*</sup></label>
                    <select id="category" name="category-id">
                        <option>Выберите категорию</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category['id'] ?? ''; ?>"
                                    <?php if ($category['id'] === get_post_val('category-id')): ?>selected<?php endif; ?>><?php echo $category['title'] ?? '';
                                ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (!empty($errors['category-id'])): ?>
                        <span class="form__error"><?php echo $errors['category-id'] ?? ''; ?></span>
                    <?php endif ?>
                </div>
            </div>
            <?php $classname = isset($errors['message']) ? "form__item--invalid" : ""; ?>
            <div class="form__item form__item--wide <?php echo $classname; ?>">
                <label for="message">Описание <sup>*</sup></label>
                <textarea id="message" name="message"
                          placeholder="Напишите описание лота"><?php echo get_post_val('message'); ?></textarea>
                <?php if (!empty($errors['message'])): ?>
                    <span class="form__error"><?php echo $errors['message'] ?? ''; ?></span>
                <?php endif ?>
            </div>
            <?php $classname = isset($errors['file']) ? "form__item--invalid" : ""; ?>
            <div class="form__item form__item--file <?php echo $classname; ?>">
                <label>Изображение <sup>*</sup></label>
                <div class="form__input-file">
                    <input class="visually-hidden" type="file" id="lot-img" name="lot-img"
                           value="<?php echo $lot['path'] ?? ''; ?>">
                    <label for="lot-img">
                        Добавить
                    </label>
                    <?php if (!empty($errors['file'])): ?>
                        <span class="form__error"><?php echo $errors['file'] ?? ''; ?></span>
                    <?php endif ?>
                </div>
            </div>
            <div class="form__container-three">
                <?php $classname = isset($errors['lot-rate']) ? "form__item--invalid" : ""; ?>
                <div class="form__item form__item--small <?php echo $classname; ?>">
                    <label for="lot-rate">Начальная цена <sup>*</sup></label>
                    <input id="lot-rate" type="text" name="lot-rate" placeholder="0"
                           value="<?php echo $lot['lot-rate'] ?? ''; ?>">
                    <?php if (!empty($errors['lot-rate'])): ?>
                        <span class="form__error"><?php echo $errors['lot-rate'] ?? ''; ?></span>
                    <?php endif ?>
                </div>
                <?php $classname = isset($errors['lot-step']) ? "form__item--invalid" : ""; ?>
                <div class="form__item form__item--small <?php echo $classname; ?>">
                    <label for="lot-step">Шаг ставки <sup>*</sup></label>
                    <input id="lot-step" type="text" name="lot-step" placeholder="0"
                           value="<?php echo $lot['lot-step'] ?? ''; ?>">
                    <?php if (!empty($errors['lot-step'])): ?>
                        <span class="form__error"><?php echo $errors['lot-step'] ?? ''; ?></span>
                    <?php endif ?>
                </div>
                <?php $classname = isset($errors['lot-date']) ? "form__item--invalid" : ""; ?>
                <div class="form__item <?php echo $classname; ?>">
                    <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
                    <input class="form__input-date" id="lot-date" type="text" name="lot-date"
                           value="<?php echo $lot['lot-date'] ?? ''; ?>" placeholder="Введите дату в формате ГГГГ-ММ-ДД">
                    <?php if (!empty($errors['lot-date'])): ?>
                        <span class="form__error"><?php echo $errors['lot-date'] ?? ''; ?></span>
                    <?php endif ?>
                </div>
            </div>
            <span class="form__error form__error--bottom">
                <?php if (isset($errors)): ?>
                    <div class="form__errors">
           <p>Пожалуйста, исправьте следующие ошибки:</p>
           <ul>
             <?php foreach ($errors as $val): ?>
                 <li><strong><?php echo $val; ?>:</strong></li>
             <?php endforeach; ?>
           </ul>
        </div>
                <?php endif; ?>
            </span>
            <button type="submit" class="button" value="">Добавить лот</button>
        </form>
    </main>
</div>
<script src="../flatpickr.js"></script>
<script src="../script.js"></script>
</body>
</html>



