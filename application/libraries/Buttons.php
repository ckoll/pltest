<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Buttons {

    private $CI;

    public function __construct() {
        $this->CI = &get_instance();
    }

    public function add_money($uid, $count, $type = 'buttons') {
        $user = $this->CI->db->get_where('users', array('id' => $uid))->row_array();
        $money = $user[$type] + $count;
        $this->CI->db->where('id', $uid);
        $this->CI->db->update('users', array($type => $money));
    }

    public function remove_money($uid, $count, $type = 'buttons') {
        $user = $this->CI->db->get_where('users', array('id' => $uid))->row_array();
        $money = $user[$type] - $count;
        $this->CI->db->where('id', $uid);
        $this->CI->db->update('users', array($type => $money));
    }

    public function bank_exchange($user) {
        $err = '';
        $jewels = -1;
        $buttons = -1;
        $count = $this->CI->input->post('count');
        switch ($_POST['item']) {
            case 'buttons':
                if ($user['buttons'] >= $count) {
                    $count = str_replace('.', ',', $count);
                    $ret_buttons = $count % 1000;
                    $jewels = $user['jewels'] + (($count - $ret_buttons) / 1000);
                    $buttons = $user['buttons'] - $count + $ret_buttons;
                }
                break;
            case 'jewels':
                if ($user['jewels'] >= $count) {
                    $count = str_replace('.', ',', $count);
                    $ret_jewels = $count % 1;
                    $count -= $ret_jewels;
                    $buttons = $user['buttons'] + $count * 1000;
                    $jewels = $user['jewels'] - $count;
                }
                break;
        }
        if ($jewels != -1 && $buttons != -1) {
            $this->CI->db->where('id', $user['id']);
            $this->CI->db->update('users', array('buttons' => $buttons, 'jewels' => $jewels));
            $this->write_history($user['id'], array('action' => (($_POST['item'] == 'jewels') ? 'exchange_to_buttons' : 'exchange_to_jewels'), 'uid' => $user['id'], 'jewels' => $user['jewels'], 'now_jewels' => $jewels, 'buttons' => $user['buttons'], 'now_buttons' => $buttons, 'description' => (($_POST['item'] == 'jewels') ? 'exchange to buttons' : 'exchange to jewels')));
        } else {
            $err = true;
        }
        return array('err' => $err);
    }

    public function write_history($uid, $history_info) {
        if (!empty($uid)) {
            $history_info = array_merge($history_info, array('date' => date('Y-m-d H:i:s'), 'uid' => $uid));
            $this->CI->db->insert('money_history', $history_info);
        }
    }

}