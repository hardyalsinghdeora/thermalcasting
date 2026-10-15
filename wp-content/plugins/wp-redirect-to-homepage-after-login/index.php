<?php
/*
Plugin Name: WP - Redirect to homepage after login
Description: Redirect all users to homepage after the login step.
Version: 1.7.9
Author: Hervé Yvis
License: MIT
License URI: http://opensource.org/licenses/MIT
*/

function redirect_after_login($url, $request, $user)
{         
    return home_url();
}

add_filter('login_redirect', 'redirect_after_login', 10, 3);
