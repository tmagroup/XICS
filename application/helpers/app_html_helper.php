<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * User profile image with href
 * @param  boolean $id        user id
 * @param  array   $classes   image classes
 * @param  string  $type
 * @param  array   $img_attrs additional <img /> attributes
 * @return string
 */
function user_profile_image($id, $classes = array('user-profile-image'), $type = 'small', $img_attrs = array())
{
    $url = base_url('assets/pages/img/avatars/user-placeholder.jpg');
    $id = trim($id);

    $_attributes = '';
    foreach ($img_attrs as $key => $val) {
        $_attributes .= $key . '=' . '"' . $val . '" ';
    }

    $blankImageFormatted = '<img src="' . $url . '" ' . $_attributes . ' class="' . implode(' ', $classes) . '" />';

    $result = '';
    /*if ((string) $id === (string) get_user_id() && isset($GLOBALS['current_user'])) {
        $result = $GLOBALS['current_user'];
    }
    else{*/
        $CI =& get_instance();
        $result =  $CI->object_cache->get('user-profile-image-data-'.$id);

        if(!$result) {
            $CI->db->select('userthumb,name');
            $CI->db->where('userid', $id);
            $result = $CI->db->get('tblusers')->row();
            $CI->object_cache->add('user-profile-image-data-'.$id, $result);
        }
    //}

    if (!$result) {
        return $blankImageFormatted;
    }

    if ($result && isset($result->userthumb) && $result->userthumb !== null) {
        $profileImagePath = 'uploads/user_profile_images/' . $id . '/' . $type . '_' . $result->userthumb;
        if (file_exists($profileImagePath)) {
            $profile_image = '<img ' . $_attributes . ' src="' . base_url($profileImagePath) . '" class="' . implode(' ', $classes) . '" alt="' .
			$result->name . '" />';
        } else {
            return $blankImageFormatted;
        }
    } else {
        $profile_image = '<img src="' . $url . '" ' . $_attributes . ' class="' . implode(' ', $classes) . '" alt="' . $result->name . '" />';
    }

    return $profile_image;
}


/**
 * User profile image with href
 * @param  boolean $id        user id
 * @param  array   $classes   image classes
 * @param  string  $type
 * @param  array   $img_attrs additional <img /> attributes
 * @return string
 */
function customer_profile_image($id, $classes = array('customer-profile-image'), $type = 'small', $img_attrs = array())
{
    $url = base_url('assets/pages/img/avatars/user-placeholder.jpg');
    $id = trim($id);

    $_attributes = '';
    foreach ($img_attrs as $key => $val) {
        $_attributes .= $key . '=' . '"' . $val . '" ';
    }

    $blankImageFormatted = '<img src="' . $url . '" ' . $_attributes . ' class="' . implode(' ', $classes) . '" />';

    $result = '';
    /*if ((string) $id === (string) get_user_id() && isset($GLOBALS['current_user'])) {
        $result = $GLOBALS['current_user'];
    }
    else{*/
        $CI =& get_instance();
        $result =  $CI->object_cache->get('customer-profile-image-data-'.$id);

        if(!$result) {
            $CI->db->select('customerthumb,name');
            $CI->db->where('customernr', $id);
            $result = $CI->db->get('tblcustomers')->row();
            $CI->object_cache->add('customer-profile-image-data-'.$id, $result);
        }
    //}

    if (!$result) {
        return $blankImageFormatted;
    }

    if ($result && isset($result->customerthumb) && $result->customerthumb !== null) {
        $profileImagePath = 'uploads/customer_profile_images/' . $id . '/' . $type . '_' . $result->customerthumb;
        if (file_exists($profileImagePath)) {
            $profile_image = '<img ' . $_attributes . ' src="' . base_url($profileImagePath) . '" class="' . implode(' ', $classes) . '" alt="' .
			$result->name . '" />';
        } else {
            return $blankImageFormatted;
        }
    } else {
        $profile_image = '<img src="' . $url . '" ' . $_attributes . ' class="' . implode(' ', $classes) . '" alt="' . $result->name . '" />';
    }

    return $profile_image;
}

//Database Result Array Param
//Return for Option data array
function dropdown($values, $key_name, $value_name, $custom_first_value=''){

    if($custom_first_value!=""){
        $array_data = array(""=>$custom_first_value);
    }else{
        $array_data = array(""=>lang('page_option_select'));
    }

    if(is_array($values)){
       foreach($values as $key=>$value){
            if(stristr($value[$value_name],'page_')){
                $array_data = $array_data + array($value[$key_name] => lang($value[$value_name]));
            }
            else{
                $array_data = $array_data + array($value[$key_name] => $value[$value_name]);
            }
       }
    }
    else if(is_object($values)){
        foreach($values as $key=>$value){
            if(stristr($value->$value_name,'page_')){
                $array_data = $array_data + array($value->$key_name => lang($value->$value_name));
            }else{
                $array_data = $array_data + array($value->$key_name => $value->$value_name);
            }
       }
    }
    return $array_data;
}
/**
 * Remove <br /> html tags from string to show in textarea with new linke
 * @param  string $text
 * @return string formatted text
 */
function clear_textarea_breaks($text)
{
    $_text  = '';
    $_text  = $text;

    $breaks = array(
        "<br />",
        "<br>",
        "<br/>",
    );

    $_text  = str_ireplace($breaks, "", $_text);
    $_text  = trim($_text);

    return $_text;
}

//Return for values array of provider
function provider_values()
{
    return array(""=>lang('page_option_select'), 'Vodafone' => 'Vodafone', 'Telekom' => 'Telekom', 'o2Business' => 'o2Business');
}