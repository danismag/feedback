<!-- Форма входа -->

<form class="navbar-form navbar-right" method="POST">
    <div class="form-group">
        <label class="sr-only" for="loginfield">Логин</label>
        <input type="text" class="form-control" id="loginfield" placeholder="Логин" name="login">
    </div>
    <div class="form-group">
        <label class="sr-only" for="passwordfield">Пароль</label>
        <input type="password" class="form-control" id="passwordfield" placeholder="Пароль" name="password">
    </div>
        <div class="checkbox">
        <label>
            <input type="checkbox" name="remember"> Запомнить меня
        </label>
    </div>
    <button type="submit" class="btn btn-default">Войти</button>
</form>