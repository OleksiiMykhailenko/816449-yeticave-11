<?php $form_classname = (isset($errors)) ? "form--invalid" : ""; ?>
<form class="form container <?php echo $form_classname; ?>" action="login.php" method="post"> <!-- form--invalid -->
    <h2>Вход</h2>
    <?php $classname = isset($errors['password']) ? "form__item--invalid" : "";
    $value = isset($form['password']) ? $form['password'] : ""; ?>
    <div class="form__item <?php echo $classname; ?>"> <!-- form__item--invalid -->
        <label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?php echo $value; ?>">
        <?php if (!empty($errors['email'])): ?>
            <span class="form__error"><?php echo $errors['email']; ?></span>
        <?php endif ?>
    </div>
    <?php $classname = isset($errors['password']) ? "form__item--invalid" : "";
    $value = isset($form['password']) ? $form['password'] : ""; ?>
    <div class="form__item form__item--last <?php echo $classname; ?>">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="password" placeholder="Введите пароль">
        <?php if (!empty($errors['password'])): ?>
            <span class="form__error"><?php echo $errors['password']; ?></span>
        <?php endif ?>
    </div>
    <button type="submit" class="button">Войти</button>
</form>
