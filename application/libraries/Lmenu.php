<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Lmenu {

    protected $CI, $user;

    public function __construct() {
        $this->CI = &get_instance();
        $this->user = $this->CI->session->userdata('user');
    }

    public function my_info() {
        $this->CI->load->model('friends_model');
        $this->CI->load->model('messages_model');
        $this->CI->load->model('dressup_model');
        return array(
            'id' => $this->user['id'],
            'buttons' => $this->user['buttons'],
            'jewels' => $this->user['jewels'],
            'messages' => $this->CI->messages_model->count_new_messages(),
            'comments' => $this->CI->dressup_model->last_comments_count(),
            'hearts' => $this->CI->dressup_model->last_like_count(),
            'pending_friend' => count($this->CI->friends_model->incoming_friends()));
    }

    public function my_recent_photos() {
        $user = user_info();
        $this->CI->load->model('upload_model');
        $photos = $this->CI->upload_model->user_photos($user['id'], 2, 0);
        $all = $this->CI->upload_model->count_pages(1);
        return array(
            'recent_photos' => $photos,
            'recent_photos_total' => $all,
            'user'=>$user);
    }

    public function favorite_brands() {
        $user = user_info();
        $this->CI->load->model('brands_model');
        $favorite = $this->CI->brands_model->get_favorite($user['id'], 4);
        $all = $this->CI->brands_model->count_pages(1);
        return array(
            'favorite_brands' => $favorite,
            'favorite_brands_total' => $all,
            'user'=>$user
            );
    }

    public function photos_i_hearted() {
        $user = user_info();
        $this->CI->load->model('upload_model');
        $photos = $this->CI->upload_model->hearted_photos($user,4);
        $all = $this->CI->upload_model->count_pages(1);
        return array(
            'photos_hearted' => $photos,
            'photos_hearted_total' => $all,
            'user'=> $user);
    }

    public function my_photos() {
        $user = user_info();
        $this->CI->load->model('upload_model');
        $photos = $this->CI->upload_model->most_hearted_photos($user,4);
        $all = $this->CI->upload_model->count_pages(1);
        return array(
            'my_photos' => $photos,
            'my_photos_total' => $all,
            'user'=>$user);
    }

    public function my_favorite_photos() {
        $user = user_info();
        $this->CI->load->model('upload_model');
        $photos = $this->CI->upload_model->favorite_photos($user,4);
        $all = $this->CI->upload_model->count_pages(1);
        return array(
            'my_favorite_photos' => $photos,
            'my_favorite_photos_total' => $all,
            'user'=>$user);
    }

    public function user_info() {
        $this->CI->load->model('user_model');
        $this->CI->load->model('friends_model');
        $user = $this->CI->uri->segment(1, 0);
        $user_info = $this->CI->user_model->check_user_avaliable($user);
        return array(
            'user_info' => $user_info,
            'friend_status' => $this->CI->friends_model->get_friend_status($user_info['id'])
        );
    }

    public function user_friends() {
        $user = $this->CI->uri->segment(1, 0);
        $this->CI->load->model('user_model');
        $this->CI->load->model('friends_model');
        $user_info = $this->CI->user_model->check_user_avaliable($user);
        return array(
            'user_friends' => $this->CI->friends_model->user_friends($user_info['id'], 2),
            'user_friends_count' => $this->CI->friends_model->user_friends_count($user_info['id']));
    }

    public function user_photos() {
        return array();
    }

    public function user_commented_photos() {
        $user = user_info();
        $this->CI->load->model('upload_model');
        $photos = $this->CI->upload_model->most_commented_photos($user,4);
        $all = $this->CI->upload_model->count_pages(1);
        return array(
            'commented_photos' => $photos,
            'commented_photos_total' => $all,
            'user'=>$user);
    }

    public function user_favorite_photos() {
        return array();
    }

    public function items_i_seling() {
        $user = user_info();
        $sell = $this->CI->db->query('SELECT DISTINCT user_items.item_id, user_items.*,dressup_items.category,dressup_items.directory,dressup_items.profileimage_dir,dressup_items.profileimage, dressup_items.item_name FROM user_items LEFT JOIN dressup_items ON dressup_items.id=user_items.item_id WHERE status="sell" AND uid="' . $user['id'] . '"')->result_array();
        $rand_items = array_slice($sell, 0, 2);
        return array(
            'items_selling' => $rand_items,
            'items_selling_total' => count($sell),
            'username' => $user['username']);
    }

    //Messages
    public function user_messages() {
        $this->CI->load->model('messages_model');
        return array(
            'messages_new' => $this->CI->messages_model->count_new_messages()
        );
    }

    //Explore
    public function explore_map() {
        return true;
    }

    public function items_user_auction() {
        $uid = user_info('id');
//        $sell = $this->CI->db->query('SELECT user_items.*,dressup_items.category,dressup_items.title FROM user_items LEFT JOIN dressup_items ON dressup_items.id=user_items.item_id WHERE status="auction" AND uid="' . $uid . '"')->result_array();
//        $rand_items = array_slice($sell, 0, 2);
        return array(
            'items_auction' => $rand_items,
            'items_auction_total' => count($sell));
    }

    public function look_of_the_day() {
        $uid = user_info('id');
        $this->CI->load->model('dressup_model');
        $last_dressup = $this->CI->dressup_model->get_daylook($uid);
        return array('look_day' => $last_dressup['id']);
    }

    public function dressup_doll() {
        $this->CI->load->model('dressup_model');
        $last_dressup = $this->CI->dressup_model->get_daylook($this->user['id']);
        return array('last_dressup' => $last_dressup['id']);
    }

    public function dressup_using_items() {
        $dressup_id = intval($this->CI->uri->segment(3));
        $dressup_info = $this->CI->db->get_where('user_dressups', array('id' => $dressup_id))->row_array();
        $itemsIds = $dressup_info['used_items'];
        $doll = unserialize($dressup_info['doll']);
        if (isset($doll['hair'])) {
            $itemsIds .= ','.$doll['hair'];
        }

        $items = array();

        if ($itemsIds) {
            $items = $this->CI->db->query('SELECT * FROM dressup_items WHERE id IN (' . $itemsIds . ')')->result_array();
        }


        return array('items' => $items);
    }

    public function photo_details() {
        $this->CI->load->model('user_model');
        $this->CI->load->model('upload_model');
        $photo = $this->CI->uri->segment(3, 0);
        $photo_inf = $this->CI->upload_model->photo_details($photo);
        $user_info = $this->CI->user_model->check_user_avaliable($photo_inf['username']);
        return array(
            'user_info' => $user_info,
            'photos' => $this->CI->upload_model->user_photos($user_info['id'], 4)
        );
    }
    
    public function mystuff(){
        
    }
    
    public function item_details(){
        $item = $this->CI->uri->segment(2);
        $this->CI->load->model('dressup_model');
        $item = $this->CI->dressup_model->item_detail($item, 'shortname');
        $this->CI->load->model('market_model');
        $finded = $this->CI->market_model->find_sell_item($item['id']);
        return $finded;
    }

}