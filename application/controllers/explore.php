<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once APPPATH . '/libraries/controllers/User_controller.php';

class Explore extends User_controller {

    public $data;

    public function __construct() {
        parent::__construct();
        $this->data['styles'] = array();
        $this->data['scripts'] = array('jquery.numeric.js', 'explore-init.js');
        $this->load->model('explore_model');
        $this->load->model('friends_model');
        $this->load->model('gifts_model');
        $this->data['incoming_friends_count'] = $this->friends_model->incoming_friends(true);
    }

    public function index() {
        $this->data['active_tab'] = 0;
        $this->tpl->ltpl = array('main' => 'explore_map', 'lmenu' => array('my_info', 'explore_map'));
        $this->tpl->show($this->data);
    }
    
    public function bugwall(){
        $this->data['active_tab'] = 8;
        if($this->input->post('send')){
            $this->explore_model->add_bug();
            redirect($_SERVER['HTTP_REFERER']);
        }
        if($this->input->get('rem')){
            $this->explore_model->remove_message($this->input->get('rem'));
            redirect($_SERVER['HTTP_REFERER']);
        }
        $this->data['bugs'] = $this->explore_model->get_bugs(15,$this->input->get('page'));
        $this->data['pages'] = $this->explore_model->count_pages(15);
        $this->data['admin'] = $this->db->get_where('users_admin',array('uid'=>$this->user['id']))->result();
        $this->tpl->ltpl = array('main' => 'explore_bugs', 'lmenu' => array('my_info', 'explore_map'));
        $this->tpl->show($this->data);
    }

    /*
     * Marketplace
     */

    public function shops() {
        $this->data['active_tab'] = 0;
        $this->load->model('market_model');
        $this->data['categories'] = $this->market_model->shop_up_categories();
        $this->data['items'] = $this->market_model->get_items(16, $this->input->get('page'), $this->input->get('cat')); 
        $this->data['pages'] = $this->explore_model->count_pages(16);
        $this->tpl->ltpl = array('main' => 'shop', 'lmenu' => array('my_info', 'explore_map'));
        $this->tpl->show($this->data);
    }
    
    public function item($id){
        $this->load->model('dressup_model');
        $this->data['item'] = $this->dressup_model->item_detail($id, 'shortname');
        $this->tpl->ltpl = array('main' => 'item_details', 'lmenu' => array('item_details'));
        $this->tpl->show($this->data);
    }
    
    public function userauction($username = NULL){
        $this->data['scripts'] = array_merge($this->data['scripts'], array('jquery.numeric_1.js','numeric_init.js','jquery.timer.js'));
        $this->data['active_tab'] = 0;
        $this->load->model('market_model');
        $this->data['categories'] = $this->market_model->shop_up_categories();
        if(!$username)
            $this->data['items'] = $this->market_model->get_items(16, $this->input->get('page'), $this->input->get('cat'), 'userauction');
        else
            $this->data['items'] = $this->market_model->get_items(16, $this->input->get('page'), $this->input->get('cat'), 'one_userauction', $username);
        $this->data['pages'] = $this->explore_model->count_pages(16);
        $this->tpl->ltpl = array('main' => 'auction', 'lmenu' => array('my_info', 'explore_map'));
        $this->tpl->show($this->data);
    }
    public function auction(){
        $this->data['scripts'] = array_merge($this->data['scripts'], array('jquery.numeric_1.js','numeric_init.js','jquery.timer.js'));
        $this->data['active_tab'] = 0;
        $this->load->model('market_model');
        $this->data['categories'] = $this->market_model->shop_up_categories();
        $this->data['items'] = $this->market_model->get_items(16, $this->input->get('page'), $this->input->get('cat'),'auction');
        $this->data['pages'] = $this->explore_model->count_pages(16);
        $this->tpl->ltpl = array('main' => 'auction', 'lmenu' => array('my_info', 'explore_map'));
        $this->tpl->show($this->data);
    }

    public function users_shop($username = NULL) {
        $this->data['active_tab'] = 0;
        $this->load->model('market_model');
        $this->data['categories'] = $this->market_model->shop_up_categories();
        if (!empty($username)) {
            $this->data['items'] = $this->market_model->get_items(16, $this->input->get('page'), $this->input->get('cat'), 'one_user_shop', $username);
        } else {
            $this->data['items'] = $this->market_model->get_items(16, $this->input->get('page'), $this->input->get('cat'), 'users');
        }
        $this->data['pages'] = $this->explore_model->count_pages(16);
        $this->tpl->ltpl = array('main' => 'shop', 'lmenu' => array('my_info', 'explore_map'));
        $this->tpl->show($this->data);
    }

    /*
     * The hangout
     */

    public function myfriends() {
        $this->data['active_tab'] = 2;
        $this->data['scripts'] = array_merge($this->data['scripts'], array('user-init.js'));
        $this->data['incoming_friends'] = $this->friends_model->incoming_friends();
        $this->data['your_friends'] = $this->friends_model->user_friends($this->user['id']);
        $this->tpl->ltpl = array('main' => 'explore_my_friends', 'lmenu' => array('my_info', 'explore_map'));
        $this->tpl->show($this->data);
    }

    public function find_friends() {
        $this->load->config('social');

        if ($this->input->get('search_username')) {
            $this->data['finded_users'] = $this->user_model->search_username($this->input->get('username'));
        } elseif (!empty($_GET['hotmail'])) {
            
            echo 'Work in progress...';
            //print_r($_REQUEST);exit;
            require APPPATH . '/libraries/hotmail/index.php';
            $this->data['mail_contacts'] = get_people_array();
        } elseif (!empty($_GET['gmail'])) {
            $this->data['system'] = 'gmail';
            $this->data['mail_contacts'] = $this->explore_model->gmail_contacts();
            $this->data['finded'] = $this->explore_model->search_user_contacts('email', $this->data['mail_contacts']);
        } elseif (!empty($_GET['yahoo'])) {
            $this->data['system'] = 'yahoo';
            $this->data['mail_contacts'] = $this->explore_model->yahoo_contacts();
            $this->data['finded'] = $this->explore_model->search_user_contacts('email', $this->data['mail_contacts']);
        } elseif ($this->input->post('system') && $this->input->post('u_login') && $this->input->post('u_password')) {

            exit;
        } elseif (!empty($_GET['fb'])) {
            //Facebook
            $this->data['system'] = 'facebook';
            $this->data['social_contacts'] = $this->explore_model->fb_friends($this->user['fb_id']);
            $this->data['finded'] = $this->explore_model->search_user_contacts('facebook', $this->data['social_contacts']); //Who exists in system
        } elseif (!empty($_GET['tw'])) {
            //Twitter
            $this->data['system'] = 'twitter';
            $this->data['social_contacts'] = $this->explore_model->tw_friends($this->user['tw_id'], $this->user['tw_secret'], $this->user['tw_token']);
            $this->data['finded'] = $this->explore_model->search_user_contacts('twitter', $this->data['social_contacts']); //Who exists in system
        }
        $this->data['active_tab'] = 2;
        $this->tpl->ltpl = array('main' => 'explore_find_friends', 'lmenu' => array('my_info', 'explore_map'));
        $this->tpl->show($this->data);
    }

    public function invite_friends() {
        if ($this->input->post('send_invite')) {
            if ($this->input->post('system')) {
                //User for invite selected
                $this->data['system'] = $this->input->post('system');

                if ($this->data['system'] == 'facebook') {
                    if ($this->input->post('invite')) {
                        foreach ($this->input->post('invite') as $key => $val) {
                            $_POST['selected_users'][] = $key . '|' . $val;
                        }
                        $_POST['selected_users'] = implode(',', $_POST['selected_users']);
                    }
                }

                $this->data['sended'] = $this->explore_model->send_invites($this->input->post('system'), $this->input->post('selected_users'), $this->input->post('message'), $this->data['err']);
            } else {
                $this->data['sended'] = $this->explore_model->send_invites('email', $this->input->post('email'), $this->input->post('message'), $this->data['err']);
            }
            $this->tpl->ltpl = array('main' => 'explore_invite_send', 'lmenu' => array('my_info', 'explore_map'));
        } else {
            $this->tpl->ltpl = array('main' => 'explore_invite_friends', 'lmenu' => array('my_info', 'explore_map'));
        }
        $this->data['active_tab'] = 2;
        $this->tpl->show($this->data);
    }

    public function send_free_gifts() {
        $this->data['active_tab'] = 2;
        if (!$this->input->get('free_gift')) {
            $this->data['gifts'] = $this->gifts_model->get_gifts(20, $this->input->get('page'));
            $this->data['pages'] = $this->gifts_model->count_pages(20);
        } elseif (!$this->input->post('free_gift_user')) {
            $this->data['free_gift'] = $this->input->post('free_gift');
            $this->data['gift_to_friends'] = $this->gifts_model->user_friends();
            $this->data['sended_today'] = $this->gifts_model->sended_today();
        }
        $this->tpl->ltpl = array('main' => 'explore_send_free_gifts', 'lmenu' => array('my_info', 'explore_map'));
        $this->tpl->show($this->data);
    }

    public function my_gifts() {
        $this->data['active_tab'] = 2;
        $this->load->model('gifts_model');
        $this->data['gifts'] = $this->gifts_model->get_my_gifts(20, $this->input->get('page'));
        $this->data['pages'] = $this->gifts_model->count_pages(20);
        $this->tpl->ltpl = array('main' => 'explore_my_gifts', 'lmenu' => array('my_info', 'explore_map'));
        $this->tpl->show($this->data);
    }

    

    /*
     * Brands
     */
    
    public function brands($id=NULL){
        $this->data['active_tab'] = 1;
        $this->load->model('brands_model');
        $this->data['brands'] = $this->brands_model->get_all(30, $this->input->get('page'));
        $this->data['pages'] = $this->brands_model->count_pages(30);
        if(empty($id)){
            $this->tpl->ltpl = array('main' => 'explore_brands', 'lmenu' => array('my_info', 'explore_map'));
        }else{
            $this->data['brand'] = $this->brands_model->get_one($id);
            $par = $this->uri->segment(3);
            
            if($par == 'all'){
                $this->data['images'] = $this->brands_model->get_images($this->data['brand']['id'],12,$this->input->get('page'));
                $this->data['pages'] = $this->brands_model->count_pages(12);
                $this->tpl->ltpl = array('main' => 'explore_brands_images', 'lmenu' => array('my_info', 'explore_map'));
            }elseif(!empty($par)){
                $this->load->model('user_model');
                $this->data['images'] = $this->brands_model->get_images3($this->data['brand']['id'],$par);
                $this->data['tags'] = $this->brands_model->get_image_tags($this->data['images'][1]['photo_id']);
                $this->data['posted_by'] = $this->user_model->get_user_info('id',$this->data['images'][1]['uid']);
                $this->tpl->ltpl = array('main' => 'explore_brands_browser', 'lmenu' => array('my_info', 'explore_map'));
            }else{
                if(isset($_POST['add_to_favorite'])){
                    $this->brands_model->add_to_favorite($this->data['brand']['id']);
                    redirect($_SERVER['HTTP_REFERER']);
                }
                if(isset($_POST['del_from_favorite'])){
                    $this->brands_model->del_from_favorite($this->data['brand']['id']);
                    redirect($_SERVER['HTTP_REFERER']);
                }
                $this->data['fav_brand'] = $this->db->query('SELECT 1 FROM user_brand WHERE uid='.$this->user['id'].' AND brand_id='.$this->data['brand']['id'])->row_array();
                $this->data['images'] = $this->brands_model->get_images($this->data['brand']['id'],4);
                $this->tpl->ltpl = array('main' => 'explore_brands_inf', 'lmenu' => array('my_info', 'explore_map'));
            }
        }
        $this->tpl->show($this->data);
    }
    
    /*
     * Marketplace
     */

    public function buy_jewels() {
        $this->load->model('market_model');
        if (isset($_POST['PayPal'])) {
            $this->market_model->buy_jewels_paypal();
        }
        if (isset($_GET['id']) && isset($_GET['token'])) {
            $this->market_model->buy_jewels_order_check_paypal();
        }
        if(isset($_GET['key']) && isset($_GET['2checkout'])){
            $this->market_model->buy_jewels_order_check_2checkout();
        }
        if (isset($_GET['order']))
            $this->data['order_done'] = $_GET['order'];
        $this->data['active_tab'] = 0;
        $this->data['u_jewels'] = $this->user['jewels'];
        $this->tpl->ltpl = array('main' => 'explore_buy_jewels', 'lmenu' => array('my_info', 'explore_map'));
        $this->tpl->show($this->data);
    }

    public function bank() {
        $this->data['scripts'] = array_merge($this->data['scripts'], array('jquery.numeric_1.js', 'numeric_init.js'));
        $this->data['active_tab'] = 0;
        $this->data['u_jewels'] = $this->user['jewels'];
        $this->data['u_buttons'] = $this->user['buttons'];
        $this->tpl->ltpl = array('main' => 'explore_bank', 'lmenu' => array('my_info', 'explore_map'));
        $this->tpl->show($this->data);
    }

    /*
     * End Marketplace
     */
    public function latest_dressups(){
        $this->data['active_tab'] = 3;
        $this->load->model('dressup_model');
        $dressups = $this->dressup_model->all_latest_dressups(20, $this->input->get('page'));
        foreach($dressups as $i=>$photo) {
            $dressups[$i]['liked'] = $this->dressup_model->isLiked($photo);
        }
        $this->data['dressups'] = $dressups;
        $this->data['pages'] = $this->dressup_model->count_pages(20);
        $this->tpl->ltpl = array('main' => 'explore_latest_dressups', 'lmenu' => array('my_info', 'explore_map'));
        $this->tpl->show($this->data);
    }
    /*
     * Latest updates
     */
    
    public function latest_photos(){
        $this->data['active_tab'] = 3;
        $this->load->model('upload_model');
        $photos = $this->upload_model->latest_photos(20, $this->input->get('page'));
        foreach($photos as $i=>$photo) {
            $photos[$i]['liked'] = $this->upload_model->isLiked($photo);
        }
        $this->data['photos'] = $photos;
        $this->data['pages'] = $this->upload_model->count_pages(20);
        $this->tpl->ltpl = array('main' => 'explore_latest_photos', 'lmenu' => array('my_info', 'explore_map'));
        $this->tpl->show($this->data);
    }
    
    /*
     * Popular
     */
    
    public function top_dressups(){
        $this->data['active_tab'] = 4;
        $this->load->model('dressup_model');
        $dressups = $this->dressup_model->top_10_dressups();
        foreach($dressups as $i=>$photo) {
            $dressups[$i]['liked'] = $this->dressup_model->isLiked($photo);
        }
        $this->data['dressup'] = $dressups;
        $this->tpl->ltpl = array('main' => 'explore_top_dressups', 'lmenu' => array('my_info', 'explore_map'));
        $this->tpl->show($this->data);
    }
    
    public function top_photos(){
        $this->data['active_tab'] = 4;
        $this->load->model('upload_model');
        $photos = $this->upload_model->top_10_photos();
        foreach($photos as $i=>$photo) {
            $photos[$i]['liked'] = $this->upload_model->isLiked($photo);
        }
        $this->data['photos'] = $photos;
        $this->tpl->ltpl = array('main' => 'explore_top_photo', 'lmenu' => array('my_info', 'explore_map'));
        $this->tpl->show($this->data);
    }
    
    public function featured_photos(){
        $this->data['active_tab'] = 5;
        $this->load->model('upload_model');
        $this->data['photo'] = $this->upload_model->featured_photos(5, $this->input->get('page'));
        $this->data['pages'] = $this->upload_model->count_pages(5);
        $this->tpl->ltpl = array('main' => 'featured_photo', 'lmenu' => array('my_info', 'explore_map'));
        $this->tpl->show($this->data);
    }
    public function featured_dressups(){
        $this->data['active_tab'] = 5;
        $this->load->model('dressup_model');
        $this->data['photo'] = $this->dressup_model->featured_dressups(5, $this->input->get('page'));
        $this->data['pages'] = $this->dressup_model->count_pages(5);
        $this->tpl->ltpl = array('main' => 'featured_photo', 'lmenu' => array('my_info', 'explore_map'));
        $this->tpl->show($this->data);
    }
    
    public function tutorials(){
        $this->load->model('help_model');
        $this->data['tuts'] = $this->help_model->get_all('100', 0);
        $this->tpl->ltpl = array('main' => 'tutorials', 'lmenu' => array('my_info', 'explore_map'));
        $this->tpl->show($this->data);
    }

    public function ajax() {
        $this->load->model('market_model');
        switch ($_REQUEST['func']) {
            case 'send_gifts' :
                $rez = $this->gifts_model->send_gifts();
                break;
            case 'facebook_invites' :
                $this->explore_model->check_fb_invites($_POST['data']);
                break;
            case 'sort_friends':
                $this->friends_model->sort_myfriends_by($this->input->post('val'));
                break;
            case 'market_filter':
                $this->market_model->market_filter($this->input->post('type'));
                break;
            case 'market_sort':
                $this->market_model->market_sort($this->input->post('type'));
                break;
            case 'check_buy_item':
                if ($this->input->post('shop_type') == 'shops') {
                    $this->market_model->check_buy_item($this->input->post('item_id'));
                } else {
                    $this->market_model->check_buy_useritem($this->input->post('item_id'));
                }
                break;
            case 'buy_item':
                if ($this->input->post('shop_type') == 'shops') {
                    $this->market_model->buy_item($this->input->post('item_id'));
                } else {
                    $this->market_model->buy_user_item($this->input->post('item_id'));
                }
                break;
            case 'get_userauction_item':
                    if($this->input->post('auction_type')=='userauction')
                        $this->market_model->get_userauction_item($this->input->post('item_id'));
                    else
                        $this->market_model->get_auction_item($this->input->post('item_id'));
                break;
            case 'bid_item':
                    $this->market_model->bid_item($this->input->post('item_id'),$this->input->post('auction_type'));
                break;
            case 'bank_exchange':
                $this->load->library('buttons');
                $rez = $this->buttons->bank_exchange($this->user);
                break;
            case 'get_checkout_prID':
                $j_pr=array('10'=>2,'25'=>3,'75'=>4,'150'=>5,'400'=>6);
                $rez['prID']=$j_pr[$_POST['j_count']];
                break;
            case 'bug_status':
                $this->explore_model->bug_status_change();
                break;
            case 'bug_text_save':
                $this->explore_model->bug_text_save();
                break;
            
        }
        if (!empty($rez)) {
            echo json_encode($rez);
        }
    }

    public function delauth_handler()
    {
        include APPPATH .'/libraries/hotmail/settings.php';

        include APPPATH .'/libraries/hotmail/windowslivelogin.php';

// Initialize the WindowsLiveLogin module.
        $wll = WindowsLiveLogin::initFromXml($KEYFILE);
        $wll->setDebug($DEBUG);

// Extract the 'action' parameter, if any, from the request.
        $action = @$_REQUEST['action'];

        if ($action == 'delauth') {
            $consent = $wll->processConsent($_REQUEST);

// If a consent token is found, store it in the cookie that is
// configured in the settings.php file and then redirect to
// the main page.
            if ($consent) {
                setcookie($COOKIE, $consent->getToken(), $COOKIETTL, '/');
            }
            else {
                setcookie($COOKIE);
            }
        }

        header("Location: $INDEX");
    }

} 