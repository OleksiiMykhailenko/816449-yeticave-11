<?php $form_classname = (isset($errors)) ? "form--invalid" : ""; ?>
<form class="form container <?php echo $form_classname; ?>" action="sign-up.php" method="post"
      autocomplete="off" enctype="multipart/form-data">
    <h2>Регистрация нового аккаунта</h2>
    <?php $classname = isset($errors['email']) ? "form__item--invalid" : ""; ?>
    <div class="form__item <?php echo $classname; ?>">
        <label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail"
               value="<?php echo get_post_val('email'); ?>">
        <?php if (!empty($errors['email'])): ?>
            <span class="form__error"><?php echo $errors['email'] ?? ''; ?></span>
        <?php endif; ?>
    </div>
    <?php $classname = isset($errors['password']) ? "form__item--invalid" : ""; ?>
    <div class="form__item <?php echo $classname; ?>">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="password" placeholder="Введите пароль">
        <?php if (!empty($errors['password'])): ?>
            <span class="form__error"><?php echo $errors['password'] ?? ''; ?></span>
        <?php endif; ?>
    </div>
    <?php $classname = isset($errors['name']) ? "form__item--invalid" : ""; ?>
    <div class="form__item <?php echo $classname; ?>">
        <label for="name">Имя <sup>*</sup></label>
        <input id="name" type="text" name="name" placeholder="Введите имя"
               value="<?php echo get_post_val('name'); ?>">
        <?php if (!empty($errors['name'])): ?>
            <span class="form__error"><?php echo $errors['name'] ?? ''; ?></span>
        <?php endif; ?>
    </div>
    <?php $classname = isset($errors['contacts']) ? "form__item--invalid" : ""; ?>
    <div class="form__item <?php echo $classname; ?>">
        <label for="contacts">Контактные данные <sup>*</sup></label>
        <textarea id="contacts" name="contacts"
                  placeholder="Напишите как с вами связаться"><?php echo get_post_val('contacts'); ?></textarea>
        <?php if (!empty($errors['contacts'])): ?>
            <span class="form__error"><?php echo $errors['contacts'] ?? ''; ?></span>
        <?php endif; ?>
    </div>
    <?php if (!empty($errors)): ?>
        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <?php endif; ?>
    <button type="submit" class="button">Зарегистрироваться</button>
    <a class="text-link" href="login.php">Уже есть аккаунт</a>
</form>
