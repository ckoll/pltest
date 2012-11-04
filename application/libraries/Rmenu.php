<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Rmenu {

    protected $CI, $user;

    public function __construct() {
        $this->CI = &get_instance();
        $this->user = $this->CI->session->userdata('user');
    }
    
    public function dressup_menu($avaliable = NULL){
        $this->CI->load->model('market_model');
        $categories = $this->CI->market_model->shop_categories();
        $body_parts = $this->CI->market_model->body_part_categories();
        $items = $this->CI->market_model->category_count_items($avaliable);
        $items = $this->CI->market_model->category_count_default($items);
        $parts_count = $this->CI->market_model->count_body_parts();
        return array('categories'=>$categories, 'body_parts'=>$body_parts, 'items'=>$items, 'parts_count'=>$parts_count);
    }
    
    public function dressup_ajax_menu(){
        return $this->dressup_menu(true);
    }
    
}