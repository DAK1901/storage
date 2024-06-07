<!DOCTYPE html>
<html lang="eng">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./fonts/fonts.css" />
    <link rel="stylesheet" href="./styles/globals.css">
    <link rel="stylesheet" href="./styles/styles.css"> 
    <title>Вход</title>
</head>
<body class="body">
    <form class="form" action="login.php" method="post">
        <p>Логин</p>
        <input class="input" type="text" placeholder="login" name="login">
        <p>Пороль</p>
        <input class="input" type="password" placeholder="password" name="pass">
        <button class="button" type="submit">Войти</button>
    </form>
    <button 
        class="dialog-button"
        onclick="window['dialog-id'].showModal();"
        aria-controls="dialog-id">
    <p class="dialog-text">Регистрация</p>
    </button>
    <dialog class="dialog" id="dialog-id">
        <form class="form-dialog" action="register.php" method="post">
            <p>Логин</p>
            <input class="input" type="text" placeholder="login" name="login">
            <p>Пороль</p>
            <input class="input" type="password" placeholder="password" name="pass">
            <p>Подтверждение пароля</p>
            <input class="input" type="password" placeholder="password" name="true-pass">
            <button class="dialog-button">
                <span class="button__text" type="submit" >Зарегистрироваться</span>
            </button>
        </form>
        <button class="dialog-button" onclick="window['dialog-id'].close()">
                <span class="button__text">Закрыть</span>
        </button>
    </dialog>
    <?php
        if (isset($_GET['message']))
        {
            $error = $_GET['message'];
            switch($error){
                case 'passnoteq':
                    echo "<script>alert('Пароли не совпадают!');</script>";
                    break;
                case 'notusername':
                    echo "<script>alert('Имя пользователя не дано!');</script>";
                    break;
                case 'notpass':
                    echo "<script>alert('Не введен пароль!');</script>";
                    break;
                case 'notconnected':
                    echo "<script>alert('Проверьте имя пользователя или пароль!');</script>";
                    break;
                case 'isregistered':
                    echo "<script>alert('Пользователь с таким именем уже существует');</script>";
                    break;
                case 'success':
                    echo "<script>alert('Учетная запись успешно добавлена');</script>";
                    break;
            }
    }
    ?>
</body>