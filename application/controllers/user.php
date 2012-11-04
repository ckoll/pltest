<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once APPPATH . '/libraries/controllers/User_controller.php';

class User extends User_controller {

    public $data;

    public function __construct() {
        parent::__construct();
        $this->data['styles'] = array();
        $this->data['scripts'] = array('user-init.js');
    }

    public function index() {
        $this->load->model('dressup_model');
        $this->load->model('friends_model');
        $this->load->model('upload_model');
        $this->load->model('mystuff_model');
        $this->load->model('brands_model');
        
        $user = $this->uri->segment(1, 0);
        $this->data['scripts'] = array_merge($this->data['scripts'], array('jquery.horizontal.scroll.js'));
        if (is_my_page($user)) {
            $this->load->model('announcements_model');

            $this->data['content'] = array(
                'announcements' => $this->announcements_model->get_all(2),
                'last_dressup' => $this->dressup_model->get_daylook($this->user['id']),
                'friends_dolls' => $this->friends_model->friends_dolls_updates(),
                'friends_photos' => $this->friends_model->friends_photo_updates(),
                'recently_friends' => $this->friends_model->user_friends($this->user['id'], 4),
            );
            $this->tpl->ltpl = array('main' => 'my_page', 'lmenu' => array('my_info', 'my_recent_photos', 'favorite_brands', 'photos_i_hearted', 'my_photos', 'my_favorite_photos', 'items_i_seling'));
        } else {

            $this->data['user_info'] = $this->user_model->check_user_avaliable($user);
            if(empty($this->data['user_info'])){
                show_404();
            }
            
            $rez = $this->upload_model->user_photos($this->data['user_info']['id'], 4);
            $rez_count = $this->upload_model->count_pages(1);
            $this->data['content'] = array(
                'wall' => $this->user_model->wall_messages($this->data['user_info']['id'], 0),
                'day_look' => $this->dressup_model->get_daylook($this->data['user_info']['id']),
                'latest_photos' => $rez,
                'latest_photos_count' => $rez_count,
                'recently_activity' => $this->user_model->get_history_activity($this->data['user_info']['id'],10)
            );
            if ($this->input->post('wall_send')) {
                $this->user_model->add_wall_message($this->data['user_info']['id']);
                $last_url = explode('?', $_SERVER['HTTP_REFERER']);
                $last_url = $last_url[0]; //remove all get parameters
                redirect($last_url);
            }
            $this->tpl->ltpl = array('main' => 'user_page', 'lmenu' => array('user_info', 'user_friends', 'favorite_brands', 'my_photos', 'user_commented_photos', 'my_favorite_photos', 'items_i_seling', 'items_user_auction'));
        }
        $this->tpl->show($this->data);
    }

    public function editprofile() {
        $this->session->set_userdata(array('last_page' => $_SERVER['REQUEST_URI'])); // Last page for back redirect
        $this->data['styles'] = array_merge($this->data['styles'], array('imgareaselect.css'));
        $this->data['scripts'] = array_merge($this->data['scripts'], array('jquery.imgareaselect.pack.js'));
        if (is_file($_FILES['avatar']['tmp_name'])) {
            $this->user_model->update_avatar();
        }
        if (($this->input->post('saveprofile') || $this->input->post('save_image')) && $this->user_model->check_save_profile($this->data['err'])) {
            $this->data['message'] = $this->user_model->save_profile();
        }

        $this->data['user'] = $this->db->where(array('id' => $this->user['id']))->join('users_avatar', 'users_avatar.uid = users.id', 'left')->get('users')->row_array();
        $user_notif = $this->db->get_where('user_notifications', array('uid' => $this->user['id']))->row_array();
        $this->data['user'] = array_merge($this->data['user'], $user_notif);
        $this->data['blocked'] = $this->user_model->get_blocked_users();
        $this->data['favorite_brands'] = $this->user_model->get_favorite_brands();

        $this->tpl->ltpl = array('main' => 'editprofile', 'lmenu' => 'my_info');
        $this->tpl->show($this->data);
    }

    public function logout() {
        $this->session->unset_userdata('user');
        $this->session->unset_userdata('tw_session');
        redirect('/');
    }

    //MESSAGES

    public function pms($folder = null, $mess_id = NULL) {
        $this->load->model('messages_model');
        $this->load->model('friends_model');
        $this->data['message_icons'] = $this->messages_model->get_message_icons();

        if (!empty($_GET['page'])) {
            $page = $_GET['page'];
        } else {
            $page = 1;
        }

        if ($this->input->post('mark')) {
            $this->messages_model->message_mark_change($this->input->post('mark'));
        } elseif ($this->input->post('empty_trash')) {
            $this->messages_model->message_empty_trash();
        } elseif ($this->input->post('undelete')) {
            $this->messages_model->message_undelete();
        }

        switch ($folder) {
            case 'sent':
            case 'deleted':
                $this->data['messages'] = $this->messages_model->get_messages($folder, $page);
                $this->data['folder'] = $folder;
                $this->data['messages_all'] = $this->messages_model->count_all_messages($folder);
                $this->tpl->ltpl['main'] = 'messages';
                break;

            case 'reply':
                if ($this->input->post('reply')) {
                    //check blacklist
                    $this->messages_model->message_send();
                    redirect('/pms/sent');
                } else {
                    $this->data['scripts'] = array_merge($this->data['scripts'], array('tiny_mce/jquery.tinymce.js', 'tinymce.messages.init.js'));
                    $this->data['reply_mess'] = $this->messages_model->get_message($mess_id);
                    if ($this->data['reply_mess']['to'] == $this->user['id']) {
                        $this->messages_model->change_message_status($this->data['reply_mess']['id']);
                    }
                }
                $this->tpl->ltpl['main'] = 'messages_reply';
                break;

            case 'new':
                $this->data['styles'] = array_merge($this->data['styles'], array('chosen.css'));
                $this->data['scripts'] = array_merge($this->data['scripts'], array('tiny_mce/jquery.tinymce.js', 'chosen.jquery.min.js', 'tinymce.messages.init.js'));
                if ($this->input->get('to')) {
                    $this->data['members'][] = $this->user_model->get_user_info('id', $this->input->get('to'));
                } else {
                    $this->data['members'] = $this->friends_model->user_friends($this->user['id']);
                }

                if ($this->input->post('send') && $this->messages_model->check_send_mess($this->data['err'])) {
                    //check blacklist
                    $this->messages_model->message_send();
                    redirect('/pms/sent');
                }

                $this->tpl->ltpl['main'] = 'messages_new';
                break;

            default:
                $this->data['messages'] = $this->messages_model->get_messages('inbox', $page);
                $this->data['messages_all'] = $this->messages_model->count_all_messages('inbox');
                $this->data['messages_new'] = $this->messages_model->count_new_messages();
                $this->data['folder'] = 'inbox';
                $this->tpl->ltpl['main'] = 'messages';
        }

        $this->data['page'] = $page;
        $this->tpl->ltpl['lmenu'] = 'user_messages';
        $this->tpl->show($this->data);
    }

    //END MESSAGES

    public function friends($of_friend) {
        $this->load->model('friends_model');
        if (preg_match('/^id[0-9]+$/i', $of_friend)) {
            $this->data['user_info'] = $this->user_model->get_user_info('id', substr($of_friend, 2));
        } else {
            $this->data['user_info'] = $this->user_model->get_user_info('username', $of_friend);
        }
        if (empty($this->data['user_info'])) {
            show_404('error_404');
        }
        $this->data['friends_list'] = $this->friends_model->user_friends($this->data['user_info']['id']);

        if ($this->data['user_info']['id'] != $this->user['id']) {
            $this->data['friend_status'] = $this->friends_model->get_friend_status($this->data['user_info']['id']);
        }
        $this->tpl->ltpl = array('main' => 'user_friends', 'lmenu' => array('user_info', 'user_friends', 'favorite_brands', 'photos_i_hearted', 'my_photos', 'user_favorite_photos', 'items_i_seling'));
        $this->tpl->show($this->data);
    }

    public function wall($username = NULL) {
        if(empty($username)){
            $username = $this->user['username'];
        }
        $this->data['user'] = $this->user_model->get_user_info('username', $username);
        if (empty($this->data['user'])){
            show_404();
        }
        if ($this->input->post('wall_send')) {
                $this->user_model->add_wall_message($this->data['user']['id']);
                $last_url = explode('?', $_SERVER['HTTP_REFERER']);
                $last_url = $last_url[0]; //remove all get parameters
                redirect($last_url);
            }
        $page = $this->input->get('page');
        $this->data['wall'] = $this->user_model->wall_messages($this->data['user']['id'], $page);
        $this->data['pages'] = $this->user_model->count_pages();

        if (is_my_page($username)) {
            $this->tpl->ltpl = array('main' => 'full_wall', 'lmenu' => array('my_info', 'my_recent_photos', 'favorite_brands', 'photos_i_hearted', 'my_photos', 'my_favorite_photos', 'items_i_seling'));
        } else {
            $this->tpl->ltpl = array('main' => 'full_wall', 'lmenu' => array('user_info', 'user_friends', 'favorite_brands', 'user_photos', 'user_commented_photos', 'user_favorite_photos', 'items_i_seling', 'items_user_auction'));
        }
        $this->tpl->show($this->data);
    }

    public function announcements($id = NULL) {
        $this->load->model('announcements_model');
        if (!empty($id)) {
            $this->data['announcement'] = $this->announcements_model->get_one($id);
        } else {
            $this->data['announcements'] = $this->announcements_model->get_all(10);
            $this->data['pages'] = $this->announcements_model->count_pages(10);
        }
        
        $this->tpl->ltpl = array('main' => 'announcements', 'lmenu' => array('my_info', 'my_recent_photos', 'favorite_brands', 'photos_i_hearted', 'my_photos', 'my_favorite_photos', 'items_i_seling', 'look_of_the_day'));
        $this->tpl->show($this->data);
    }

    public function dressup($id) {
        $this->load->model('dressup_model');

        if ($this->input->post('add_dressup_comment')) {
            $this->dressup_model->add_dressup_comment($id);
            redirect($_SERVER['HTTP_REFERER']);
        }elseif($this->input->get('rem')){
            $this->dressup_model->remove_comment($this->input->get('rem'));
            redirect($_SERVER['HTTP_REFERER']);
        }

        $this->data['comments'] = $this->dressup_model->get_dressup_comments(5, $this->input->get('page'), $id);
        $this->data['pages'] = $this->dressup_model->count_pages(5);
        $this->data['item'] = $this->dressup_model->dressup_details($id);
        $this->data['dress_id'] = $id;
        if (empty($this->data['item'])) {
            show_404();
        }
        $this->tpl->ltpl = array('main' => 'dressup_details', 'lmenu' => array('dressup_using_items'));
        $this->tpl->show($this->data);
    }

    public function dressup_calendar($username) {
        $this->data['user'] = $this->user_model->get_user_info('username', $username);
        if (is_my_page($username)) {
            $this->tpl->ltpl = array('main' => 'dressup_calendar', 'lmenu' => array('my_info'));
        } else {
            $this->tpl->ltpl = array('main' => 'dressup_calendar', 'lmenu' => array('user_info'));
        }
        $this->tpl->show($this->data);
    }

    public function new_info($type) {
        $this->load->model('dressup_model');
        $this->load->model('upload_model');
        switch ($type) {
            case 'hearts':
                $dressup_hearts = $this->dressup_model->last_hearted();
                $photo_hearts = $this->upload_model->last_hearted();
                $all_likes = array_merge($dressup_hearts, $photo_hearts);
                if(!empty($all_likes)){
                    foreach($all_likes as $val){
                        $this->data['hearts'][$val['date_unix']] = $val;
                    }
                    ksort($this->data['hearts'],SORT_NUMERIC);
                    $this->data['hearts'] = array_reverse($this->data['hearts']);
                }
                $this->tpl->ltpl = array('main' => 'last_hearts', 'lmenu' => array('my_info'));
                break;
            case 'comments':
                $dressup = $this->dressup_model->last_commented();
                $photo = $this->upload_model->last_commented();
                $all_likes = array_merge($dressup, $photo);
                if(!empty($all_likes)){
                    foreach($all_likes as $val){
                        $this->data['comments'][$val['date_unix']] = $val;
                    }
                    ksort($this->data['comments'],SORT_NUMERIC);
                    $this->data['comments'] = array_reverse($this->data['comments']);
                }
                $this->data['comments_details']['dressup'] = $this->dressup_model->last_commented_details($dressup);
                $this->data['comments_details']['photo'] = $this->upload_model->last_commented_details($photo);
                $this->tpl->ltpl = array('main' => 'last_comments', 'lmenu' => array('my_info'));
                break;
        }
        $this->tpl->show($this->data);
    }

    public function photo($id) {
        $this->load->model('upload_model');
        if ($this->input->post('add_photo_comment')) {
            if(!empty($_POST['comment'])){
                $this->upload_model->add_photo_comment(substr($id,0,-5));
            }
            redirect($_SERVER['HTTP_REFERER']);
        }elseif($this->input->get('rem')){
            $this->upload_model->remove_comment($this->input->get('rem'));
            redirect($_SERVER['HTTP_REFERER']);
        }
        $this->data['photo'] = $this->upload_model->photo_details($id);
        $this->data['comments'] = $this->upload_model->get_photo_comments(5, $this->input->get('page'), substr($id,0,-5));
        $this->data['pages'] = $this->upload_model->count_pages(5);
        $this->tpl->ltpl = array('main' => 'photo_details', 'lmenu' => array('photo_details'));
        $this->tpl->show($this->data);
    }
    
    public function ajax() {
        $this->load->model('friends_model');
        $this->load->model('doll_model');

        switch ($_REQUEST['func']) {
            case 'tw_disconnect':
                $this->user_model->social_disconnect('tw');
                break;
            case 'fb_disconnect':
                $this->user_model->social_disconnect('fb');
                break;
            case 'user_notifications':
                $this->user_model->email_notification_save();
                break;
            case 'del_blocked_user':
                $this->user_model->del_blocked_user($this->input->post('id'));
                break;
            case 'add_friend':
                $this->friends_model->add_friend($this->input->post('id'));
                break;
            case 'del_friend':
                $this->friends_model->del_friend($this->input->post('id'));
                break;
            case 'confirm_friend':
                $this->friends_model->confirm_friend($this->input->post('id'));
                break;
            case 'change_dollname':
                $this->doll_model->change_name($this->input->post('new_name'));
                break;
            case 'del_fav_brand':
                $this->user_model->del_fav_brand($this->input->post('brand_id'));
                break;
        }
        exit;
    }

}