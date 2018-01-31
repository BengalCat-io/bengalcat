<div class="full-width-hero">
    <div class="hero-text">

            <form action="[:current url]" method="post" class="form-login">

                <?php if (!empty($data)) : ?>

                <div class="login-error">
                    <span class="fa fa-warning"></span>
                    <?= $data; ?>
                </div>

                <?php endif; ?>

                <label>
                    Email
                    <input type="text" name="username"/>
                </label>

                <label>
                    Passvort
                    <input type="password" name="password"/>
                </label>

                <button type="submit" name="admin-login">
                    Let me in.
                </button>

                <input type="hidden" name="handle_key" value="username"/>
                <input type="hidden" name="password_key" value="password"/>
            </form>
        </div>
    </div>
</div>