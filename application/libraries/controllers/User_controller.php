<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User_controller extends CI_Controller {

    public $user;

    public $publicActions = array(
       'dressup',
       'photo'
    );

    public function __construct() {
        parent::__construct();
//        date_default_timezone_set('Europe/Kyiv');

        $this->user = $this->session->userdata('user');
        $this->load->model('user_model');
        $this->load->model('dressup_model');
        $this->load->model('home_model');

        if(empty($this->user)) {
            $this->home_model->loginFromCookies();
            $this->user = $this->session->userdata('user');
        }

        //Check User, Update last login
        $user_check = $this->db->get_where('users', array('id' => $this->user['id']))->row_array();
        if (empty($user_check) && !in_array($this->router->method, $this->publicActions)) {
            //Not found
            $this->session->unset_userdata('user');
            $this->session->unset_userdata('tw_session');
            redirect('/signin?back_url='.current_url());
            exit;

        } else {
            //Update session
            $this->user = $user_check;
            $this->session->set_userdata('user', $user_check);
            $this->user_model->updateLastAction($this->user['id']);
        }

        $day = $this->db->query('SELECT 1 FROM check_daily WHERE `date`="'.  date('Y-m-d').'"')->result_array();
        if (empty($day)) {
            //Send notification who last activity < 7 days
            $this->check_daily_notif();
            $this->db->query('INSERT INTO check_daily (`date`) VALUES ("'.  date('Y-m-d H:i:s').'")');
        }
        $this->check_auction_items();

        //User messages
        $this->user['messages'] = intval($this->db->query('SELECT COUNT(1) coun FROM messages WHERE `to`="' . $this->user['id'] . '" AND view=0')->row()->coun);

        $this->data['admin'] = $this->db->get_where('users_admin', array('uid' => $this->user['id']))->row();
        $last_dressup = $this->dressup_model->get_daylook($this->user['id']);
        $this->data['last_dressup'] = $last_dressup['id'];
    }

    //7 days notification (now 1 day for test)
    private function check_daily_notif() {
        $notif_users = $this->db->query('SELECT * FROM users WHERE last_action < "'.  date('Y-m-d H:i:s').'" - INTERVAL 1 DAY')->result_array();
        if (!empty($notif_users)) {
            $this->load->model('home_model');
            foreach ($notif_users as $val) {
                if (empty($val['doll_name'])) {
                    $val['doll_name'] = 'Your doll';
                }
                $this->home_model->send_notification($val['id'], 'notif_7days', $val['doll_name'] . ' wants to go shopping!', '_7days');
            }
        }
    }

    private function check_auction_items() {
        $items = $this->db->query("SELECT user_items.*, dressup_items.item_name FROM user_items LEFT JOIN dressup_items ON user_items.item_id = dressup_items.id WHERE status = 'auction' AND auction_date_end < '".  date('Y-m-d H:i:s')."' AND auction_reserve<= IF(price_b,price_b,price_j)")->result_array();
        foreach ($items as $item) {
            $item_id = $item['id'];
            $user_add_auction = $item['uid'];
            $duration = ((empty($item['price_b'])) ? 'jewels' : 'buttons');
            $db_col_price = ((empty($item['price_b'])) ? 'price_j' : 'price_b');
            $price = $item[$db_col_price];
            $last_bid = array_pop(unserialize($item['auction_date_price']));
            $user_buy = $last_bid['id'];
            
            $user_buy_info = $this->user_model->get_user_info('id',$user_buy);
            $user_add_info = $this->user_model->get_user_info('id',$user_add_auction);
            
            $this->load->library('buttons');
            $this->buttons->write_history($user_buy, array('action' => 'buy_auction', 'buttons' => $user_buy_info['buttons']+$price, 'now_buttons' => (($duration=='buttons')?($user_buy_info['buttons']):$user_buy_info['buttons']), 'jewels' => $user_buy_info['jewels']+$price, 'now_jewels' => (($duration=='jewels')?($user_buy_info['jewels']):$user_buy_info['jewels']), 'description' => 'Bought at auction item: '.$item['item_name']));
            $this->buttons->write_history($user_add_auction, array('action' => 'sold_auction', 'buttons' => $user_add_info['buttons'], 'now_buttons' => (($duration=='buttons')?($user_add_info['buttons']+$price):$user_add_info['buttons']), 'jewels' => $user_add_info['jewels'], 'now_jewels' => (($duration=='jewels')?($user_add_info['jewels']+$price):$user_add_info['jewels']), 'description' => 'Sold at auction: '.$item['item_name']));
            
            $this->db->query('UPDATE users SET `' . $duration . '` = `' . $duration . '` + ' . $price . ' WHERE id = ' . $user_add_auction);
            $this->db->query('UPDATE user_items SET `uid` = ' . $user_buy . ', `status` = "", `' . $db_col_price . '`=0, auction_date_price="", auction_date_end=0, auction_reserve=0 WHERE id=' . $item_id);
        }
    }

}