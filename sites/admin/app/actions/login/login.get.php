<?php unset($_SESSION['user']); ?>
<?php render_header(false); ?>
            <form id="login" method="post">
                <label for="username">username:</label><input type="text" name="username" id="username" /><br />
                <label for="password">password:</label><input type="password" name="password" id="password" /><br />

                <div class="buttons">
                    <?php
                    if(!empty($_SESSION['login_flash'])){
                        ?>
                        <div class="error"><?= $_SESSION['login_flash'] ?></div>
                        <?php
                        unset($_SESSION['login_flash']);
                    }
                    ?>
                    <input type="submit" value="login" />
                </div>
            </form>
            <script type="text/javascript">
            $(function(){
                $('#login').submit(function(){
                    var pass = $('#password'),
                        user = $('#username');
                    if(! user.val()){ user.focus(); return false; }
                    if(! pass.val()){ pass.focus(); return false; }
                }).find('input').eq(0).focus();
            })
            </script>
<?php render_footer(); ?>