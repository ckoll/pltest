<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once APPPATH . '/libraries/controllers/User_controller.php';

class Mystuff extends User_controller {
    public $data; 

    public function __construct() {
        parent::__construct();
        $this->data['styles'] = array();
        $this->data['scripts'] = array();
        $this->tpl->gtpl = 'mystuff';
        $this->data['user'] = user_info();
        $this->load->model('mystuff_model');
        $closest = array('req_you_to_add_friend','req_to_you_friends','button_history','diamonds_history','gifts_i_received','gifts_i_sent');
        $module = $this->uri->segment(3);
        if(in_array($module,$closest)){
            if($this->user['id']!=$this->data['user']['id']) {redirect('/'.$this->data['user']['username'].'/mystuff');}
        }
        $this->data['count_req_you_to_add_friend'] = $this->mystuff_model->req_you_to_add_friend(true);
        $this->data['count_req_to_you_friends'] = $this->mystuff_model->req_to_you_friends(true);
        
    }
    
    public function main() {
        $this->load->model('dressup_model');
        $this->data['day_look'] = $this->dressup_model->get_daylook($this->data['user']['id']);
        $this->tpl->ltpl = array('mystuff' => 'main', 'lmenu' => array('mystuff'));
        $this->tpl->show($this->data);
    }
    public function photos(){
        $this->data['scripts'] = array_merge($this->data['scripts'],array('mystuff_init.js'));
        $this->data['title'] = 'All my photos';
        $this->data['photos'] = $this->mystuff_model->get_photos(20, $this->input->get('page'));
        $this->data['pages'] = $this->mystuff_model->count_pages(20);
        $this->tpl->ltpl = array('mystuff' => 'photos', 'lmenu' => array('mystuff'));
        $this->tpl->show($this->data);
    }
    public function button_history(){
        $this->data['history']=$this->mystuff_model->get_button_jewels_history(20, $this->input->get('page'));
        $this->data['pages'] = $this->mystuff_model->count_pages(20);
        $this->tpl->ltpl = array('mystuff' => 'button_history', 'lmenu' => array('mystuff'));
        $this->tpl->show($this->data);
    }
    public function diamonds_history(){
        $this->data['history']=$this->mystuff_model->get_button_jewels_history(20, $this->input->get('page'));
        $this->data['pages'] = $this->mystuff_model->count_pages(20);
        $this->tpl->ltpl = array('mystuff' => 'button_history', 'lmenu' => array('mystuff'));
        $this->tpl->show($this->data);
    }
    public function favorite_photos(){
        $this->data['title'] = 'My Favorite Photos';
        $this->data['photos']=$this->mystuff_model->get_favorite_photos(null,20, $this->input->get('page'));
        $this->data['pages'] = $this->mystuff_model->count_pages(20);
        $this->tpl->ltpl = array('mystuff' => 'list_photos_fav', 'lmenu' => array('mystuff'));
        $this->tpl->show($this->data);
    }
    public function favorite_dressups(){
        $this->data['title'] = 'My Favorite Dressups';
        $this->data['dressups']=$this->mystuff_model->get_favorite_dressups(null,10, $this->input->get('page'));
        $this->data['pages'] = $this->mystuff_model->count_pages(10);
        $this->tpl->ltpl = array('mystuff' => 'list_dressups_fav', 'lmenu' => array('mystuff'));
        $this->tpl->show($this->data);
    }
    public function my_photos_favorite(){
        $this->data['title'] = 'My Photos That Have Been Favorites';
        $this->data['photos']=$this->mystuff_model->get_favorite_photos('my',20, $this->input->get('page'));
        $this->data['pages'] = $this->mystuff_model->count_pages(20);
        $this->tpl->ltpl = array('mystuff' => 'list_photos_fav', 'lmenu' => array('mystuff'));
        $this->tpl->show($this->data);
    }
    public function my_dressups_favorite(){
        $this->data['title'] = 'My Dressups That Have Been Favorites';
        $this->data['dressups']=$this->mystuff_model->get_favorite_dressups('my',10, $this->input->get('page'));
        $this->data['pages'] = $this->mystuff_model->count_pages(10);
        $this->tpl->ltpl = array('mystuff' => 'list_dressups_fav', 'lmenu' => array('mystuff'));
        $this->tpl->show($this->data);
    }
    public function brands(){
        $this->data['title'] = 'Favorite Brands';
        $this->data['brands']=$this->mystuff_model->get_favorite_brands(20, $this->input->get('page'));
        $this->data['pages'] = $this->mystuff_model->count_pages(20);
        $this->tpl->ltpl = array('mystuff' => 'favorite_brands', 'lmenu' => array('mystuff'));
        $this->tpl->show($this->data);
    }
    public function most_hearted_photos(){
        $this->data['title'] = 'My Most Hearted Photos';
        $this->data['photos']=$this->mystuff_model->get_most_hearted_photos(20, $this->input->get('page'));
        $this->data['pages'] = $this->mystuff_model->count_pages(20);
        $this->tpl->ltpl = array('mystuff' => 'photos', 'lmenu' => array('mystuff'));
        $this->tpl->show($this->data);
    }
    public function most_hearted_dressups(){
        $this->data['title'] = 'My Most Hearted Dressups';
        $this->data['dressups']=$this->mystuff_model->get_most_hearted_dressups(10, $this->input->get('page'));
        $this->data['pages'] = $this->mystuff_model->count_pages(10);
        $this->tpl->ltpl = array('mystuff' => 'list_dressups_fav', 'lmenu' => array('mystuff'));
        $this->tpl->show($this->data);
    }
    public function most_commented_dressups(){
        $this->data['title'] = 'My Most Commented Photos';
        $this->data['dressups']=$this->mystuff_model->get_most_commented_dressups(10, $this->input->get('page'));
        $this->data['pages'] = $this->mystuff_model->count_pages(10);
        $this->tpl->ltpl = array('mystuff' => 'list_dressups_fav', 'lmenu' => array('mystuff'));
        $this->tpl->show($this->data);
    }
    public function most_commented_photos(){
        $this->data['title'] = 'My Most Commented Photos';
        $this->data['photos']=$this->mystuff_model->get_most_commented_photos(20, $this->input->get('page'));
        $this->data['pages'] = $this->mystuff_model->count_pages(20);
        $this->tpl->ltpl = array('mystuff' => 'photos', 'lmenu' => array('mystuff'));
        $this->tpl->show($this->data);
    }
    public function recent_photo_comments(){
        $this->data['title'] = 'Recent Photo Comments I\'ve Received';
        $this->data['comments']=$this->mystuff_model->get_recent_photo_comments(10, $this->input->get('page'));
        $this->data['pages'] = $this->mystuff_model->count_pages(10);
        $this->tpl->ltpl = array('mystuff' => 'recent_photo_comments', 'lmenu' => array('mystuff'));
        $this->tpl->show($this->data);
    }
    public function recent_dressup_comments(){
        $this->data['title'] = 'Recent Dressup Comments I\'ve Received';
        $this->data['comments']=$this->mystuff_model->get_recent_dressup_comments(5, $this->input->get('page'));
        $this->data['pages'] = $this->mystuff_model->count_pages(5);
        $this->tpl->ltpl = array('mystuff' => 'recent_dressup_comments', 'lmenu' => array('mystuff'));
        $this->tpl->show($this->data);
    }
    public function recent_photo_likes(){
        $this->data['title'] = 'Recent Photo Hearts I\'ve Received';
        $this->data['likes']=$this->mystuff_model->get_recent_photo_likes(20, $this->input->get('page'));
        $this->data['pages'] = $this->mystuff_model->count_pages(20);
        $this->tpl->ltpl = array('mystuff' => 'recent_photo_likes', 'lmenu' => array('mystuff'));
        $this->tpl->show($this->data);
    }
    public function recent_dressup_likes(){
        $this->data['title'] = 'Recent Dressup Hearts I\'ve Received';
        $this->data['likes']=$this->mystuff_model->get_recent_dressup_likes(15, $this->input->get('page'));
        $this->data['pages'] = $this->mystuff_model->count_pages(15);
        $this->tpl->ltpl = array('mystuff' => 'recent_dressup_likes', 'lmenu' => array('mystuff'));
        $this->tpl->show($this->data);
    }
    
    public function req_to_you_friends(){
        $this->data['title'] = 'Users Requesting To Become Your Friend';
        $this->data['friends']=$this->mystuff_model->req_to_you_friends(false,10, $this->input->get('page'));
        $this->data['pages'] = $this->mystuff_model->count_pages(10);
        $this->tpl->ltpl = array('mystuff' => 'list_friends', 'lmenu' => array('mystuff'));
        $this->tpl->show($this->data);
    }
    public function req_you_to_add_friend(){
        $this->data['title'] = 'Pending Users You Have Requested To Become Friends With';
        $this->data['friends']=$this->mystuff_model->req_you_to_add_friend(false,10, $this->input->get('page'));
        $this->data['pages'] = $this->mystuff_model->count_pages(10);
        $this->tpl->ltpl = array('mystuff' => 'list_friends', 'lmenu' => array('mystuff'));
        $this->tpl->show($this->data);
    }
    public function my_recently_added_friends(){
        $this->data['title'] = 'My Recently Added Friends';
        $this->data['friends']=$this->mystuff_model->my_recently_added_friends(10, $this->input->get('page'));
        $this->data['pages'] = $this->mystuff_model->count_pages(10);
        $this->tpl->ltpl = array('mystuff' => 'list_friends', 'lmenu' => array('mystuff'));
        $this->tpl->show($this->data);
    }
    public function last_friends_photos(){
        $this->data['title'] = 'My Friend\'s Latest Photos';
        $this->data['photos']=$this->mystuff_model->get_last_friends_photos(20, $this->input->get('page'));
        $this->data['pages'] = $this->mystuff_model->count_pages(20);
        $this->tpl->ltpl = array('mystuff' => 'last_friends_photo', 'lmenu' => array('mystuff'));
        $this->tpl->show($this->data);
    }
    public function last_friends_dressups(){
        $this->data['title'] = 'My Friend\'s Latest Dressups';
        $this->data['dressups']=$this->mystuff_model->get_last_friends_dressups(15, $this->input->get('page'));
        $this->data['pages'] = $this->mystuff_model->count_pages(15);
        $this->tpl->ltpl = array('mystuff' => 'last_friends_dressup', 'lmenu' => array('mystuff'));
        $this->tpl->show($this->data);
    }
    public function recent_photos_i_likes(){
        $this->data['title'] = 'Recent Photos I\'ve hearted';
        $this->data['likes']=$this->mystuff_model->recent_photos_i_likes(20, $this->input->get('page'));
        $this->data['pages'] = $this->mystuff_model->count_pages(20);
        $this->tpl->ltpl = array('mystuff' => 'recent_photo_i_likes', 'lmenu' => array('mystuff'));
        $this->tpl->show($this->data);
    }
    public function recent_photos_i_commented(){
        $this->data['title'] = 'Recent Photos I\'ve Commented On';
        $this->data['likes']=$this->mystuff_model->recent_photos_i_commented(20, $this->input->get('page'));
        $this->data['pages'] = $this->mystuff_model->count_pages(20);
        $this->tpl->ltpl = array('mystuff' => 'recent_photo_i_likes', 'lmenu' => array('mystuff'));
        $this->tpl->show($this->data);
    }
    public function recent_dressup_i_likes(){
        $this->data['title'] = 'Recent Dressup I\'ve hearted';
        $this->data['likes']=$this->mystuff_model->recent_dressup_i_likes(15, $this->input->get('page'));
        $this->data['pages'] = $this->mystuff_model->count_pages(15);
        $this->tpl->ltpl = array('mystuff' => 'recent_dressup_i_likes', 'lmenu' => array('mystuff'));
        $this->tpl->show($this->data);
    }
    public function recent_dressup_i_commented(){
        $this->data['title'] = 'Recent Dressup I\'ve Commented On';
        $this->data['likes']=$this->mystuff_model->recent_dressup_i_commented(15, $this->input->get('page'));
        $this->data['pages'] = $this->mystuff_model->count_pages(15);
        $this->tpl->ltpl = array('mystuff' => 'recent_dressup_i_likes', 'lmenu' => array('mystuff'));
        $this->tpl->show($this->data);
    }
    public function my_inventory(){
        $this->data['title'] = 'My Item Inventory';
        $this->data['items']=$this->mystuff_model->get_my_items_inventory(24, $this->input->get('page'));
        $this->data['pages'] = $this->mystuff_model->count_pages(24);
        $this->tpl->ltpl = array('mystuff' => 'items_inventory', 'lmenu' => array('mystuff'));
        $this->tpl->show($this->data);
    }
    public function gifts_i_received(){
        $this->data['title'] = 'Gifts I\'ve Received';
        $this->data['f_t'] = 'from'; 
        $this->data['gifts']=$this->mystuff_model->gifts_i_received(20, $this->input->get('page'));
        $this->data['pages'] = $this->mystuff_model->count_pages(20);
        $this->tpl->ltpl = array('mystuff' => 'gifts', 'lmenu' => array('mystuff'));
        $this->tpl->show($this->data);
    }
    public function gifts_i_sent(){
        $this->data['title'] = 'Gifts I\'ve Received';
         $this->data['f_t'] = 'to';
        $this->data['gifts']=$this->mystuff_model->gifts_i_sent(20, $this->input->get('page'));
        $this->data['pages'] = $this->mystuff_model->count_pages(20);
        $this->tpl->ltpl = array('mystuff' => 'gifts', 'lmenu' => array('mystuff'));
        $this->tpl->show($this->data);
    }
}
?>
