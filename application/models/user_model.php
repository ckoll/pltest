<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User_model extends CI_Model {

    public $user;

    public function __construct() {
        parent::__construct();
        $this->user = $this->session->userdata('user');
    }

    public function check_user_avaliable($user) {
        $user_info = $this->get_user_info('username', $user);
        if (empty($user_info)) {
            $user_info = $this->CI->user;
        }
        return $user_info;
    }

    public function check_save_profile(&$err) {
        if (!preg_match("/^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$/i", $this->input->post('email'))) {
            $err = 'Email is not correct';
            return false;
        }
        if (strlen($this->input->post('password')) > 0) {
            $old_pass = $this->db->query('SELECT password FROM users WHERE id = "' . $this->user['id'] . '" ')->row()->password;
            if (!empty($old_pass) && $old_pass != md5($this->input->post('old_password'))) {
                $err = 'Old password is incorrect';
                return false;
            }

            if (strlen($this->input->post('password')) < 4) {
                $err = 'Password should be more than 4 characters';
                return false;
            }
            if ($this->input->post('password') != $this->input->post('password2')) {
                $err = 'Two field password must match';
                return false;
            }
        }
        $exist_email = $this->db->query('SELECT 1 FROM users WHERE email="' . mysql_real_escape_string($this->input->post('email')) . '" AND id!="' . $this->user['id'] . '"')->result();
        if (!empty($exist_email)) {
            $err = 'Email already exist in another account';
            return false;
        }
        return true;
    }

    public function save_profile() {
        $this->cut_avatar();

        $notif = ($this->input->post('notif') == 1) ? 1 : 0;
        $data = array(
            'email' => $this->input->post('email'),
            'bio' => $this->input->post('bio'),
            'ribbon' => $this->input->post('ribbon'),
        );
        if (strlen($this->input->post('password')) > 0) {
            $data['password'] = md5($this->input->post('password'));
        }
        $this->db->update('users', $data, array('id' => $this->user['id']));
        $this->db->update('user_notifications', array('notif' => $notif), array('uid' => $this->user['id']));

        if (!empty($data['password'])) {
            redirect('/editprofile?password=ok');
        } elseif ($this->input->post('save_image')) {
            redirect('/editprofile?save_image=ok');
        } else {
            redirect('/editprofile?saved=ok');
        }
    }

    public function update_avatar() {
        $this->load->library('Image_moo');
        $dir = APPPATH . 'files/users/originals';
        $original_uploaded = APPPATH . 'files/users/' . $this->user['id'] . '.jpg';
        $all_conf = getimagesize($_FILES['avatar']['tmp_name']);
        $width = $all_conf[0];
        $height = $all_conf[1];
        $mime = $all_conf['mime'];
        if (!empty($width) && !empty($height) && in_array($mime, array('image/gif', 'image/jpeg', 'image/png', 'image/jpg'))) {
            switch ($mime) {
                case 'image/gif':
                    $sourse = imageCreateFromGIF($_FILES['avatar']['tmp_name']);
                    break;
                case 'image/jpeg':
                case 'image/jpg':
                    $sourse = imageCreateFromJPEG($_FILES['avatar']['tmp_name']);
                    break;
                case 'image/png':
                    $sourse = imageCreateFromPNG($_FILES['avatar']['tmp_name']);
                    break;
            }
            $im = imagecreatetruecolor($width, $height);
            $bg = imagecolorallocate($im, 255, 255, 255);
            imagefilledrectangle($im, 0, 0, $width, $height, $bg);
            ImageCopyResized($im, $sourse, 0, 0, 0, 0, $width, $height, $width, $height);
            imagejpeg($im, $original_uploaded, 100);

            $res_prop = $width / $height;
            if ($width > 500 || $height > 500) {
                if ($res_prop >= 1) {
                    $width = 500;
                    $height = $width / $res_prop;
                } else {
                    $height = 500;
                    $width = $height * $res_prop;
                }
            }
            $this->image_moo->load($original_uploaded)->resize($width, $height)->set_jpeg_quality('100')->save($dir . '/' . $this->user['id'] . '.jpg', TRUE);
            $this->cut_avatar(1);
        }
    }

    public function cut_avatar($new = 0) {
        $this->load->library('Image_moo');
        $uploaded_avatar_path = APPPATH . 'files/users/' . $this->user['id'] . '.jpg';
        $original_avatar_path = APPPATH . 'files/users/originals/' . $this->user['id'] . '.jpg';
        $crop_avatar_dir100 = APPPATH . 'files/users/avatars100';
        $crop_avatar_dir200 = APPPATH . 'files/users/avatars200';
        if(!file_exists($uploaded_avatar_path)) return;
        list($width, $height, $type, $attr) = getimagesize($original_avatar_path);
        list($orig_width, $orig_height, $orig_type, $orig_attr) = getimagesize($uploaded_avatar_path);

        if (!$new) {
            $x1 = $this->input->post('avatar_x1');
            $y1 = $this->input->post('avatar_y1');
            $x2 = $this->input->post('avatar_x2');
            $y2 = $this->input->post('avatar_y2');
        } else {
            $new_pos_x = 0;
            if ($width > 100) {
                $res_width = $width * 0.7;
                $res_height = $height * 0.7;
                if ($res_width > $res_height) {
                    $res_width = $res_height * 1.2;
                }
                $new_pos_x = $width / 2 - ($res_width / 2);
                $new_pos_y = 0;
                if ($width <= $height) {
                    $new_pos_y = $height / 2 - ($res_width / 2);
                }
            }else{
                $res_width = $width;
                $res_height = $height;
            }
            $x1 = $new_pos_x;
            $y1 = $new_pos_y;
            $x2 = $new_pos_x + $res_width;
            $y2 = $new_pos_y + $res_width;
        }

        //Get from original image

        $proporc = $orig_width / $width;

        $x1_orig = $x1 * $proporc;
        $y1_orig = $y1 * $proporc;
        $x2_orig = $x2 * $proporc;
        $y2_orig = $y2 * $proporc;

        //100px
        $this->image_moo->load($uploaded_avatar_path)->set_background_colour("#FFF")->crop($x1_orig, $y1_orig, $x2_orig, $y2_orig)->set_jpeg_quality('100')->save($crop_avatar_dir100 . '/' . $this->user['id'] . '.jpg', TRUE);
        $this->image_moo->load($crop_avatar_dir100 . '/' . $this->user['id'] . '.jpg')->resize(100, 100, TRUE)->set_jpeg_quality('100')->save($crop_avatar_dir100 . '/' . $this->user['id'] . '.jpg', TRUE);
        //200px
        if(($x2_orig-$x1_orig) >= 198){
            $this->image_moo->load($uploaded_avatar_path)->set_background_colour("#FFF")->crop($x1_orig, $y1_orig, $x2_orig, $y2_orig)->set_jpeg_quality('100')->save($crop_avatar_dir200 . '/' . $this->user['id'] . '.jpg', TRUE);
            $this->image_moo->load($crop_avatar_dir200 . '/' . $this->user['id'] . '.jpg')->resize(198, 198, TRUE)->set_jpeg_quality('100')->save($crop_avatar_dir200 . '/' . $this->user['id'] . '.jpg', TRUE);
        }else{
            @unlink($crop_avatar_dir200 . '/' . $this->user['id'] . '.jpg');
        }
        $this->db->query('INSERT INTO users_avatar (uid,x1,y1,x2,y2) VALUES ("' . $this->user['id'] . '","' . $x1 . '","' . $y1 . '","' . $x2 . '","' . $y2 . '") ON DUPLICATE KEY UPDATE `x1` = "' . $x1 . '",`y1` = "' . $y1 . '",`x2` = "' . $x2 . '",`y2` = "' . $y2 . '" ');
    }

    public function email_notification_save() {
        $notif = array();
        $fields = array('friend_request', 'received_gift', 'received_comment','received_heart','received_wall_message','received_pms_message', 'item_sold', '_7days');
        $new_data = $this->input->post('notif');
        foreach ($fields as $val) {
            $notif[$val] = ( $new_data[$val] == 1) ? 1 : 0;
        }
        $this->db->update('user_notifications', $notif, array('uid' => $this->user['id']));
    }

    //Bloked Users
    public function get_blocked_users() {
        return $this->db->query('SELECT uid, black_id, DATE_FORMAT(added, "%d.%m.%Y %h:%i") added, users.username FROM user_blacklist LEFT JOIN users ON users.id=user_blacklist.black_id WHERE uid="' . mysql_real_escape_string($this->user['id']) . '"')->result_array();
    }

    public function del_blocked_user($black_id) {
        $this->db->query('DELETE FROM user_blacklist WHERE uid="' . $this->user['id'] . '" AND black_id="' . mysql_real_escape_string($black_id) . '"');
        $all_count = count($this->get_blocked_users());
        echo json_encode($all_count);
    }

    public function social_disconnect($system) {
        $this->load->config('social');
        if ($system == 'fb') {
            $data = array('fb_id' => '', 'fb_username' => '', 'fb_fullname' => '');
            $this->db->where('id', $this->user['id']);
            $this->db->update('users', $data);

            require(APPPATH . 'libraries/facebook/facebook.php');
            $facebook = new Facebook(array(
                        'appId' => $this->config->item('fbAppId'),
                        'secret' => $this->config->item('fbSecret'),
                        'cookie' => true
                    ));

            $logout_url = $facebook->getLogoutUrl(array('next' => 'http://' . $_SERVER['SERVER_NAME'] . '/editprofile/', 'access_token' => $this->user['fb_access']));
            echo json_encode(array('redirect' => $logout_url));
        } else {
            $data = array('tw_id' => '', 'tw_fullname' => '', 'tw_username' => '', 'tw_secret' => '', 'tw_token' => '');
            $this->db->where('id', $this->user['id']);
            $this->db->update('users', $data);
            require_once APPPATH . 'libraries/twitter/twitteroauth.php';
            $tw_token = $this->user['tw_token'];
            $tw_secret = $this->user['tw_secret'];
            $connection = new TwitterOAuth($this->config->item('twConsumerKey'), $this->config->item('twConsumerSecret'), $tw_token, $tw_secret);
            $connection->post('account/end_session');
            $this->session->unset_userdata('tw_session');
        }
    }

    public function get_user_info($id_or_username, $val) {
        return $this->db->get_where('users', array($id_or_username => $val))->row_array();
    }

    public function users_for_ajax($ids) {
        if (is_array($ids)) {
            $users = array();
            $all = $this->db->query('SELECT * FROM users WHERE id IN("' . implode('","', $ids) . '")')->result_array();
            if (!empty($all)) {
                foreach ($all as $val) {
                    $users[] = array('id' => $val['id'], 'avatar' => get_user_avatarlink($val['id'], 'avatars', true), 'username' => $val['username']);
                }
            }
            return $users;
        }
    }

    //ADMIN
    public function get_all_users() {
        $sql = 'SELECT
            users.*,
            ref.username rusername,
            ref.id rid,
            p_l.hash
            FROM users
            LEFT JOIN users as ref ON ref.id=users.invite
            LEFT JOIN partner_link_users as p_l_u ON p_l_u.user_id=users.id
            LEFT JOIN partner_links as p_l ON p_l.id=p_l_u.partner_link_id
            ORDER BY id';
        return $this->db->query($sql)->result_array();
    }

    public function remove_user($id) {
        $this->db->where('id', $id);
        $this->db->delete('users');
        $this->db->where('uid', $id);
        $this->db->delete('user_notifications');
        $this->db->where('uid', $id);
        $this->db->delete('user_dressups');        
        
        @unlink(APPPATH . 'files/users/avatars100/' . $id . '.jpg');
        @unlink(APPPATH . 'files/users/avatars200/' . $id . '.jpg');
        @unlink(APPPATH . 'files/users/' . $id . '.jpg');
    }

    public function search_username($uname=NULL) {
        if(!empty($uname)){
            $search_user = ' AND (username LIKE "' . $uname . '%" OR tw_username LIKE "' . $uname . '%" OR fb_username LIKE "' . $uname . '%")';
        }
        return $this->db->query('SELECT * FROM users WHERE id!="' . $this->user['id'] . '"'.$search_user)->result_array();
    }

    public function wall_messages($uid, $page, $limit = 20) {
        $begin = $page  * $limit;
        return $this->db->query('SELECT SQL_CALC_FOUND_ROWS users.id, user_wall.*, users.id uid, users.username, users.fb_fullname, users.tw_fullname FROM user_wall LEFT JOIN users ON (users.id=user_wall.from)
            WHERE uid="' . $uid . '" ORDER BY date DESC LIMIT ' . $begin . ',' . $limit)->result_array();
    }

    public function count_pages($for_page = 20) {
        $count = $this->db->query('SELECT FOUND_ROWS() as result')->row()->result;
        return ceil($count / $for_page);
    }

    public function add_wall_message($to_uid) {

        $text = strip_tags($this->input->post('wall_message'));

        $data_ins = array(
            'uid' => $to_uid,
            'text' => $text,
            'date' => date('Y-m-d H:i:s'),
            'from' => $this->user['id']
        );
        $this->db->insert('user_wall', $data_ins);

        $emailData = array(
            'user' => $this->user,
            'text' => $text,
        );
        $this->home_model->send_notification($to_uid, 'notif_received_wall_message', ' You have a new wall message from '.$this->user['username'].' at Perfect-Look.org', 'received_wall_message', $emailData);

    }

    public function get_favorite_brands() {
        return $this->db->query('SELECT user_brand.*, brands.title, brands.id, brands.imagename FROM user_brand LEFT JOIN brands ON brands.id = user_brand.brand_id WHERE user_brand.uid =' . $this->user['id'])->result_array();
    }

    public function del_fav_brand($id) {
        $this->db->query('DELETE FROM user_brand WHERE uid=' . $this->user['id'] . ' AND brand_id=' . $id);
        $ret['ok'] = $this->db->affected_rows();
        echo json_encode($ret);
    }
    
    public function write_history_activity($uid, $type, $type_id){
        $this->db->query('INSERT INTO user_activity(`uid`, `type`, `type_id`, `date`) VALUES("'.$uid.'","'.$type.'","'.$type_id.'","'.date('Y-m-d H:i:s').'")');
    }
    
    public function get_history_activity($uid, $limit){
        return $this->db->query('SELECT * FROM user_activity WHERE uid="'.$uid.'" ORDER BY id DESC LIMIT '.$limit)->result_array();
    }
    
    public function updateLastAction($id)
    {
        $this->db->query('UPDATE users SET last_action="'.  date('Y-m-d H:i:s').'", last_action_ip="'.$this->input->ip_address().'" WHERE id="' . $id . '"');
    }

    public function getRecentlyOnline($number = 2)
    {
        $sql = "SELECT * FROM users ORDER BY last_action DESC LIMIT 0,100";

        $users = $this->db->query($sql)->result_array();

        $resentUsers = array();

        foreach($users as $i=>$user) {
            if (count($resentUsers) >= $number) {
                break;
            }
            $avatar = get_user_avatarlink($user['id'],  'avatars', true);
            if ($avatar != '/files/users/avatars100/default.png') {
                $users[$i]['avatar'] = $avatar;
                $resentUsers[] = $users[$i];
            }
        }

        return $resentUsers;

    }

}

?>