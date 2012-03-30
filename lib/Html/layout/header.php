<header>
    <div id="loading"></div>
    <a href="/" class="logo ir"><?php echo TITLE; ?></a>

    <nav>
        <a href="/" class="home"><?php echo __('Home'); ?></a>
        
        <?php

        if($user_id = reader())
        {
            if(isAdmin())
            {
                if(DEV) echo '<a href="/admin/dev">Dev</a>';

                echo '<a href="/user" class="users">'.__('Users').'</a>';
            }

            echo '<a href="/user/'.IB_User::readerName().'">My account</a>';

            echo '<a id="logout">'.__('Sign out').'</a>';
        }
        else
        {
            echo '<a id="signin">'.__('Sign in').'</a>';
            
            if(SIGNUP_OPEN)
            {
                echo '<a id="signup">'.__('Sign up').'</a>';
            }
        }
        
        echo view('user/form/search');
        ?>
    </nav>
</header>