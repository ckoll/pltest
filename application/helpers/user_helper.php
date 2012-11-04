<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

function show_user_avatar($id, $from = 'avatars', $return = false, $size = 100, $ribb = false) {

    if ($from == 'avatars') {
        $rez = '/files/users/avatars' . $size . '/';
    } else {
        $rez = '/files/users/' . $from . '/';
    }
    
    if($size == 100){
        $rez .= (is_file(APPPATH . $rez . $id . '.jpg')) ? $id . '.jpg?v=' . rand(1, 10000) : 'default.png';
    }else{
        if(is_file(APPPATH . $rez . $id . '.jpg')){
            $rez .= $id . '.jpg?v=' . rand(1, 10000);
        }elseif(is_file(APPPATH . '/files/users/avatars100/' . $id . '.jpg')){
            $rez = APPPATH . '/files/users/avatars100/' . $id . '.jpg?v=' . rand(1, 10000);
            $size = 100;
        }else{
            $rez .= 'default.png';
        }
    }    
    if ($ribb) {
        $ribbon = '<div class="ribbon"></div>';
    }
    $user_image = '<div class="avatar'.$size.'">
            <div><img src="'.$rez.'"></div>
            <div class="border"></div>
            ' . $ribbon . '
        </div>';

    if (!empty($return)) {
        return $user_image;
    } else {
        echo $user_image;
    }
}

function get_user_avatarlink($id, $from = 'avatars', $return = false, $size = 100) {
    if($from == 'avatars'){
        $rez = '/files/users/' . $from . $size . '/';
    }else{
        $rez = '/files/users/' . $from . '/';
    }
    $rez .= (is_file(APPPATH . $rez . $id . '.jpg')) ? $id . '.jpg?v=' . rand(1, 10000) : 'default.png';
    if (!empty($return)) {
        return $rez;
    } else {
        echo $rez;
    }
}

function show_username_link($username) {
    ?><a href="/<?= $username ?>"><?= $username ?></a><?
}

function date_to_unix($date) {
    $dt_el = explode(' ', $date);
    $date_el = explode('-', $dt_el[0]);
    $time_el = explode(':', $dt_el[1]);
    return mktime($time_el[0], $time_el[1], $time_el[2], $date_el[1], $date_el[2], $date_el[0]);
}

function unix_left_time_to_date($date) {
    return date_to_unix($date) - time();
}

function auction_left_time($end) {
    $dt_el = explode(' ', $end);
    $date_el = explode('-', $dt_el[0]);
    $time_el = explode(':', $dt_el[1]);
    $date_end_unix = mktime($time_el[0], $time_el[1], $time_el[2], $date_el[1], $date_el[2], $date_el[0]);
    $last_time = $date_end_unix - time();
    $sec = $last_time;
    $left = "";
    if ($sec / 60 / 60 / 24 >= 1)
        $left = (int) ($sec / 60 / 60 / 24) . " d";
    elseif ($sec / 60 / 60 >= 1)
        $left = (int) ($sec / 60 / 60) . " hrs";
    elseif ($sec / 60 >= 1)
        $left = (int) ($sec / 60) . "m";
    elseif ($sec >= 1)
        $left = (int) ($sec) . "s";
    else
        $left = 0;
    return $left;
}

function time_from($session_time) {
    $session_time = strtotime($session_time);
    $time = strtotime('now');
    $time_difference = $time - $session_time;
    $seconds = $time_difference;
    $minutes = round($time_difference / 60);
    $hours = round($time_difference / 3600);
    $days = round($time_difference / 86400);
    $weeks = round($time_difference / 604800);
    $months = round($time_difference / 2419200);
    $years = round($time_difference / 29030400);
// Seconds
    if ($seconds <= 60) {
        if ($seconds < 1) {
            $seconds = 1;
        }
        return "$seconds seconds ago";
    }
//Minutes
    else if ($minutes <= 60) {

        if ($minutes == 1) {
            return "one minute ago";
        } else {
            return "$minutes minutes ago";
        }
    }
//Hours
    else if ($hours <= 24) {

        if ($hours == 1) {
            return "one hour ago";
        } else {
            return "$hours hours ago";
        }
    }
//Days
    else if ($days <= 7) {

        if ($days == 1) {
            return "one day ago";
        } else {
            return "$days days ago";
        }
    }
//Weeks
    else if ($weeks <= 4) {

        if ($weeks == 1) {
            return "one week ago";
        } else {
            return "$weeks weeks ago";
        }
    }
//Months
    else if ($months <= 12) {

        if ($months == 1) {
            return "one month ago";
        } else {
            return "$months months ago";
        }
    }
//Years
    else {

        if ($years == 1) {
            return "one year ago";
        } else {
            return "$years years ago";
        }
    }
}

function is_my_page($user) {
    $CI = &get_instance();
    $my = $CI->session->userdata('user');
    if ((preg_match('/^id[0-9]+$/i', $user) && $user == 'id' . $my['id']) || $user == $my['username']) {
        return true;
    } else {
        return false;
    }
}

function user_info($return = false) {
    $CI = &get_instance();
    $CI->load->model('user_model');
    $username = $CI->uri->segment(1);
    $user = $CI->user_model->get_user_info('username', $username);
    if (empty($user)) {
        $user = $CI->user;
    }
    if (!$return) {
        return $user;
    } else {
        return $user[$return];
    }
}

