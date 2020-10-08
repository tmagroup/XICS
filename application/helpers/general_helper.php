<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Is user logged in
 * @return boolean
 */
function is_logged_in()
{
    $CI =& get_instance();
    if ($CI->session->has_userdata('logged_in')) {
        return true;
    }

    return false;
}

/**
 * Return logged User ID from session
 * @return mixed
 */
function get_user_id()
{
    $CI =& get_instance();
    if (!$CI->session->has_userdata('logged_in')) {
        return false;
    }

    return $CI->session->userdata('user_id');
}

/**
 * Return logged User Role from session
 * @return mixed
 */
function get_user_role()
{
    $CI =& get_instance();
    if (!$CI->session->has_userdata('logged_in')) {
        return false;
    }

    return $CI->session->userdata('role');
}

/**
 * Set session alert / flashdata
 * @param string $type    Alert type
 * @param string $message Alert message
 */
function set_alert($type, $message)
{
    $CI =& get_instance();
    $CI->session->set_flashdata('message-' . $type, $message);
   // @session_start();
   // unset($_SESSION['__ci_vars']);
}

/**
 * Get current date format from options
 * @return string
 */
function get_current_date_format($php = false)
{
    $format = get_option('dateformat');
    $format = explode('|', $format);

    $hook_data = do_action('get_current_date_format', array(
        'format' => $format,
        'php' => $php,
    ));

    $format = $hook_data['format'];
    $php    = $php;

    if ($php == false) {
        return $format[1];
    } else {
        return $format[0];
    }
}

/**
 * Format date to selected dateformat
 * @param  date $date Valid date
 * @return date/string
 */
function _d($date)
{
    if ($date == '' || is_null($date) || $date == '0000-00-00') {
        return '';
    }
    if (strpos($date, ' ') !== false) {
        return _dt($date);
    }
    $format = get_current_date_format();
    $date   = strftime($format, strtotime($date));

    return do_action('after_format_date', $date);
}

/**
 * Format datetime to selected datetime format
 * @param  datetime $date datetime date
 * @return datetime/string
 */
function _dt($date, $is_timesheet = false)
{
    if ($date == '' || is_null($date) || $date == '0000-00-00 00:00:00') {
        return '';
    }
    $format = get_current_date_format();
    $hour12 = (get_option('time_format') == 24 ? false : true);

    if ($is_timesheet == false) {
        $date = strtotime($date);
    }

    if ($hour12 == false) {
        $tf = '%H:%M:%S';
        if ($is_timesheet == true) {
            $tf = '%H:%M';
        }
        $date   = strftime($format . ' ' . $tf, $date);
    } else {
        $date = date(get_current_date_format(true). ' g:i A', $date);
    }

    return do_action('after_format_datetime', $date);
}
/**
 * Convert string to sql date based on current date format from options
 * @param  string $date date string
 * @return mixed
 */
function to_sql_date($date, $datetime = false)
{
    if ($date == '' || $date == null) {
        return null;
    }

    $to_date     = 'Y-m-d';
    $from_format = get_current_date_format(true);


    $hook_data['date']        = $date;
    $hook_data['from_format'] = $from_format;
    $hook_data['datetime']    = $datetime;

    $hook_data = do_action('before_sql_date_format', $hook_data);

    $date        = $hook_data['date'];
    $from_format = $hook_data['from_format'];

    if ($datetime == false) {
        return date_format(date_create_from_format($from_format, $date), $to_date);
    } else {
        if (strpos($date, ' ') === false) {
            $date .= ' 00:00:00';
        } else {
            $hour12 = (get_option('time_format') == 24 ? false : true);
            if ($hour12 == false) {
                $_temp = explode(' ', $date);
                $time  = explode(':', $_temp[1]);
                if (count($time) == 2) {
                    $date .= ':00';
                }
            } else {
                $tmp = _simplify_date_fix($date, $from_format);
                $time = date("G:i", strtotime($tmp));
                $tmp = explode(' ', $tmp);
                $date = $tmp[0]. ' ' . $time.':00';
            }
        }

        $date = _simplify_date_fix($date, $from_format);
        $d = strftime('%Y-%m-%d %H:%M:%S', strtotime($date));

        return do_action('to_sql_date_formatted', $d);
    }
}
/**
 * Function that will check the date before formatting and replace the date places
 * This function is custom developed because for some date formats converting to y-m-d format is not possible
 * @param  string $date        the date to check
 * @param  string $from_format from format
 * @return string
 */
function _simplify_date_fix($date, $from_format)
{
    if ($from_format == 'd/m/Y') {
        $date = preg_replace('#(\d{2})/(\d{2})/(\d{4})\s(.*)#', '$3-$2-$1 $4', $date);
    } elseif ($from_format == 'm/d/Y') {
        $date = preg_replace('#(\d{2})/(\d{2})/(\d{4})\s(.*)#', '$3-$1-$2 $4', $date);
    } elseif ($from_format == 'm.d.Y') {
        $date = preg_replace('#(\d{2}).(\d{2}).(\d{4})\s(.*)#', '$3-$1-$2 $4', $date);
    } elseif ($from_format == 'm-d-Y') {
        $date = preg_replace('#(\d{2})-(\d{2})-(\d{4})\s(.*)#', '$3-$1-$2 $4', $date);
    }

    return $date;
}
/**
 * Check if user has permission
 * @param  string  $permission permission shortname
 * @param  mixed  $userid if you want to check for particular user
 * @return boolean
 */
function has_permission($permission, $can, $userid='')
{
    $CI =& get_instance();
    //Master Admin has all permissions
    if($userid==''){ $userid = get_user_id(); }
    if($userid==1 && get_user_role()=='user'){ return true; }

    $CI->load->model('User_model');
    $CI->load->model('Role_model');

    /**
     * Not current user?
     * Get permissions for this user
     * Permissions will be cached in object cache upon first request
     */
    if(get_user_role()=='customer'){
        $roleid = 4;
        $permissions = $CI->Role_model->get_role_permissions($roleid);
        if ( ($GLOBALS['current_user']->parent_customer_id > 0) ) {
            if ( ($GLOBALS['current_user']->customer_role == 1) ) {
                foreach ($permissions as $key_permissions => $value_permissions) {
                    if ( $value_permissions->permissionid == 23 ) {
                        // $value_permissions->can_view = 1;
                        // $value_permissions->can_view_own = 1;
                        // $value_permissions->can_create = 1;
                        // $value_permissions->can_edit = 1;
                        // $value_permissions->can_delete = 1;
                        // $value_permissions->can_import = 1;
                    } else {
                        $value_permissions->can_view = 0;
                        $value_permissions->can_view_own = 0;
                        $value_permissions->can_create = 0;
                        $value_permissions->can_edit = 0;
                        $value_permissions->can_delete = 0;
                        $value_permissions->can_import = 0;
                    }
                }
            } else if ( ($GLOBALS['current_user']->customer_role == 2) || ($GLOBALS['current_user']->customer_role == 3) ) { // 2=buchhaltung,3=controlling
                foreach ($permissions as $key_permissions => $value_permissions) {
                    if (
                        $value_permissions->permissionid == 20 || // Assignment
                        $value_permissions->permissionid == 23 || // Ticket
                        $value_permissions->permissionid == 29 || // Monitoring
                        $value_permissions->permissionid == 31 || // Document
                        $value_permissions->permissionid == 45 || // Infodocument
                        $value_permissions->permissionid == 7  || //Hardware
                        $value_permissions->permissionid == 54 || // Hardware Budget
                        $value_permissions->permissionid == 22 || //Hardwareassignment
                        $value_permissions->permissionid == 25 || // Deliverynote
                        $value_permissions->permissionid == 26 || // Hardwareinvoice
                        $value_permissions->permissionid == 42 || // Assignment Invoice
                        $value_permissions->permissionid == 48 || // Assignment Hardware Inventory
                        $value_permissions->permissionid == 21    // Hardwareinput
                    ) {
                        // $value_permissions->can_view = 1;
                        // $value_permissions->can_view_own = 1;
                        // $value_permissions->can_create = 1;
                        // $value_permissions->can_edit = 1;
                        // $value_permissions->can_delete = 1;
                        // $value_permissions->can_import = 1;
                    } else {
                        $value_permissions->can_view = 0;
                        $value_permissions->can_view_own = 0;
                        $value_permissions->can_create = 0;
                        $value_permissions->can_edit = 0;
                        $value_permissions->can_delete = 0;
                        $value_permissions->can_import = 0;
                    }
                }
            }
        }
        // print_r($permissions); exit(0);
    } else {
        $permission_type = $CI->User_model->get($userid, 'permission_type');
        if($permission_type->permission_type=='role'){
            $CI->load->model('Role_model');
            $roleid = $CI->User_model->get($userid, 'userrole')->userrole;
            $permissions = $CI->Role_model->get_role_permissions($roleid);
        } else {
            $permissions = $CI->User_model->get_user_permissions($userid);
        }
    }
    $hasPermission = false;

    /**
     * Based on permissions user object check if user have permission
     */
    foreach ($permissions as $permObject) {
        if ($permObject->permission_name == $permission
            && $permObject->{'can_' . $can} == '1') {
            $hasPermission = true;
            break;
        }
    }

    return $hasPermission;
}


/**
 * Check if user has permission
 * @param  string  $permission permission shortname
 * @param  mixed  $userid if you want to check for particular user
 * @return boolean
 */
function has_role_permission($permission, $can, $roleid)
{
    $CI =& get_instance();

    /**
     * Not current user?
     * Get permissions for this user
     * Permissions will be cached in object cache upon first request
     */
    $CI->load->model('Role_model');
    $permissions = $CI->Role_model->get_role_permissions($roleid);
    $hasPermission = false;

    /**
     * Based on permissions user object check if user have permission
     */
    foreach ($permissions as $permObject) {
        if ($permObject->permission_name == $permission
            && $permObject->{'can_' . $can} == '1') {
            $hasPermission = true;
            break;
        }
    }

    return $hasPermission;
}

/**
 * Redirect to access danied page and log activity
 * @param  string $permission If permission based to check where user tried to acces
 */
function access_denied($permission = '')
{
    set_alert('danger', lang('access_denied'));
    logActivity('Tried to access page where don\'t have permission [' . $permission . ']');
    if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
        redirect($_SERVER['HTTP_REFERER']);
    } else {
        redirect(base_url('admin/permission/denied'));
    }
}


/**
 * Format money with 2 decimal based on symbol
 * @param  mixed $total
 * @param  string $symbol Money symbol
 * @return string
 */
function format_money($total, $symbol = '')
{
    if (!is_numeric($total) && $total != 0) {
        //return $total;
    }

    $decimal_separator  = get_option('decimal_separator');
    $thousand_separator = get_option('thousand_separator');
    $currency_placement = get_option('currency_placement');
    $d                  = get_decimal_places();

    if (get_option('remove_decimals_on_zero') == 1) {
        if (!is_decimal($total)) {
            $d = 0;
        }
    }

    $total = number_format($total, $d, $decimal_separator, $thousand_separator);
    $total = do_action('money_after_format_without_currency', $total);

    if ($currency_placement === 'after') {
        $_formatted = $total . '' . $symbol;
    } else {
        $_formatted = $symbol . '' . $total;
    }

    $_formatted = do_action('money_after_format_with_currency', $_formatted);

    return $_formatted;
}

/**
 * Return decimal places
 * The srcipt do not support more then 2 decimal places but developers can use action hook to change the decimal places
 * @return [type] [description]
 */
function get_decimal_places()
{
    return do_action('app_decimal_places', 2);
}

/**
 * Generate md5 hash
 * @return string
 */
function app_generate_hash()
{
    return md5(rand() . microtime() . time() . uniqid());
}


/**
 * Get system favourite colors
 * @return array
 */
function get_system_favourite_colors()
{
    // don't delete any of these colors are used all over the system
    $colors[1] = '#7b87c9';
    $colors[2] = '#02b67f';
    $colors[3] = '#9528a6';
    $colors[4] = '#eb7e75';
    $colors[5] = '#f8c142';
    $colors[6] = '#fa5527';
    $colors[7] = '#019ae1';
    $colors[8] = '#626262';
    $colors[9] = '#4452b2';
    $colors[10] = '#01814a';
    $colors[11] = '#db1202';

    $colors = do_action('system_favourite_colors', $colors);

    return $colors;
}

/* Get Reminders of User or Customer */
function get_user_reminders(){
    $CI =& get_instance();
    $CI->load->model('Reminder_model');
    $data['reminder'] = $CI->Reminder_model->get_user_reminders();
    return $data['reminder'];
    //******************** End Initialise ********************/
}

/* Action of History */
function do_action_history($data){
    $CI =& get_instance();
    $CI->load->model('History_model');
    $CI->History_model->add($data);
}

if ( !function_exists('has_user_dashboard_widget_order') ) {
    function has_user_dashboard_widget_order($widget_name_p) {
        if ( isset($GLOBALS['current_user']->dashboard_widgets_order) && !empty($GLOBALS['current_user']->dashboard_widgets_order) ) {
            $widget_data = explode(',', $GLOBALS['current_user']->dashboard_widgets_order);
            $widget_data = array_map(function($value) {
                $new_value = explode(':', $value);
                if ( count($new_value) > 1) {
                    return strtolower($new_value[1]);
                }
                return FALSE;
            }, $widget_data);

            return in_array($widget_name_p, $widget_data);
        }

        return FALSE;
    }
}



/**
 *
 */
function my_debug_file( $data, $debugP ) {
    $dir = getcwd() .'/debug';
    if ( is_dir(dirname($dir)) && !file_exists($dir) ) { mkdir($dir); }
    $my_debug_file_path = $dir .'/'. 'my_debug_file_'. $debugP .'.json';
    $my_debug_file = fopen($my_debug_file_path, 'w');
    fwrite($my_debug_file, json_encode(array('$data' => $data)));
    fclose($my_debug_file);
}


/**
 *
 */
function my_array_search_by_id($s_array, $s_key, $s_value, $is_strict = FALSE) {
    foreach ($s_array as $key => $value) {
        if ($is_strict) {
            if ($value[$s_key] === $s_value) {
                return $key;
            }
        } else {
            if ($value[$s_key] == $s_value) {
                return $key;
            }
        }
    }

    return -1;
}




/**
 *  my_mkdir
 */
if ( !function_exists('my_mkdir') ) {
    function my_mkdir( $path ) {
        if ( !file_exists($path) ) {
            $path = rtrim($path, '/') .'/';
            mkdir($path, 0777, TRUE);
            $file_index = fopen($path .'index.html', 'w');
            fclose($file_index);
        }
    }
}

if ( !function_exists('pdebug') ) {
    function pdebug( $dataP, $exitP = FALSE ) {
        if ($_SERVER['REMOTE_ADDR'] === '123.201.19.165' || $_SERVER['REMOTE_ADDR'] === '::1') {
            echo '<pre>';
            print_r($dataP);
            echo '</pre>';
            if ( $exitP ) {
                exit(1);
            }
        }
    }
}

if ( !function_exists('ddebug') ) {
    function ddebug( $dataP, $exitP = FALSE ) {
        if ($_SERVER['REMOTE_ADDR'] === '123.201.19.165' || $_SERVER['REMOTE_ADDR'] === '::1') {
            echo '<pre>';
            var_dump($dataP);
            echo '</pre>';
            if ( $exitP ) {
                exit(1);
            }
        }
    }
}

if ( !function_exists('fdebug') ) {
    function fdebug( $dataP, $debugP ) {
        if ($_SERVER['REMOTE_ADDR'] === '123.201.19.165' || $_SERVER['REMOTE_ADDR'] === '::1') {
            $dir = getcwd() .'/bouwstartbe_debug';
            if ( is_dir(dirname($dir)) && !file_exists($dir) ) { mkdir($dir); }
            $my_debug_file_path = $dir .'/'. 'my_debug_file_'. $debugP .'.txt';
            $my_debug_file = fopen($my_debug_file_path, 'w');
            fwrite($my_debug_file, json_encode(array('$data' => $dataP)));
            fclose($my_debug_file);
        }
    }
}
