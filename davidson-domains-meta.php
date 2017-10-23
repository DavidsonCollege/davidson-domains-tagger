<?php
/*
  Plugin Name: Davidson Domains Meta
  Plugin URI: https://github.com/DavidsonCollege/davidson-domains-meta
  Description: Tag Sort
  Author: John-Michael Murphy, Joe Bannerman
  Author URI: https://github.com/DavidsonCollege/
  Text Domain: davidson-domains-meta
  Version: 0.1

*/

/*
 * Constants
 */

/* Different ways to get remote address: direct & behind proxy */
define('LIMIT_LOGIN_DIRECT_ADDR', 'REMOTE_ADDR');
define('LIMIT_LOGIN_PROXY_ADDR', 'HTTP_X_FORWARDED_FOR');

/* Notify value checked against these in limit_login_sanitize_variables() */
define('LIMIT_LOGIN_LOCKOUT_NOTIFY_ALLOWED', 'log,email');

/*
 * Variables
 *
 * Assignments are for default value -- change on admin page.
 */


?>