<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home_model extends CI_Model {

    public $usr;

    //Login
    public function signin() {
        $this->session->set_userdata(array('user' => $this->usr));
        header('Location: /' . $this->usr['username']);
        exit;
    }

    public function check_signin_form(&$err) {
        if (!$this->input->post('username')) {
            if ($_SERVER['HTTP_REFERER'] == base_url())
                return false;
            $err = 'Field Username is empty';
            return false;
        }
        if (!$this->input->post('password')) {
            $err = 'Field Password is empty';
            return false;
        }
        $this->usr = $this->db->get_where('users', array('username' => strtolower($this->input->post('username')), 'password' => md5($this->input->post('password'))))->row_array();
        if (empty($this->usr)) {
            $err = 'Login/Password is incorrect';
            return false;
        } elseif ($this->usr['active'] == 0) {
            $err = '<span>Account is unconfirmed [ <a href="/resendemail" id="resendemail">resend activation link</a> ]</span>';
            return false;
        }
        return true;
    }

    //Register
    public function check_signup_form(&$err) {
        if (strlen($this->input->post('username')) < 1 || strlen($this->input->post('username')) > 30) {
            $err = 'The username must contain 1 to 30 characters';
            return false;
        }
        if (!preg_match('/^[a-zA-Z0-9_]+$/i', $this->input->post('username'))) {
            $err = 'Username can contain numbers, letters and "_" char.';
            return false;
        }
        if (!preg_match("/^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$/i", $this->input->post('email'))) {
            $err = 'Email is not correct';
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
        if (preg_match('/^id[0-9]+$/i', $this->input->post('username'))) {
            $err = 'Please, select another username';
            return false;
        }
        $isset_username = $this->db->get_where('users', array('username' => $this->input->post('username')))->result();
        $isset_email = $this->db->get_where('users', array('email' => $this->input->post('email')))->result();
        if (!empty($isset_username)) {
            $err = 'Username already exists.';
            return false;
        }
        if (!empty($isset_email)) {
            $err = 'Email already exists.';
            return false;
        }
        return true;
    }

    public function check_forgotpassword_form(&$err) {
        $isset_email = $this->db->get_where('users', array('email' => $this->input->post('email')))->row_array();
        $isset_username = $this->db->get_where('users', array('username' => $this->input->post('email')))->row_array();
        if (empty($isset_email) && empty($isset_username)) {
            $err = 'Email/Username is not registered.';
            return false;
        } else {
            $user_data = ((!empty($isset_email)) ? $isset_email : $isset_username);
            $return_data['email'] = $user_data['email'];
            $return_data['key'] = md5($user_data['id'] . $user_data['username'] . 'sxSf55' . $user_data['email'] . date('z'));
            $return_data['username'] = $user_data['username'];
            return $return_data;
        }
    }

    public function check_change_password_form(&$err) {
        if (strlen($this->input->post('password')) < 4) {
            $err = 'Password should be more than 4 characters';
            return false;
        }
        if ($this->input->post('password') != $this->input->post('password2')) {
            $err = 'Two field password must match';
            return false;
        }
        //Activation link valid 1 day
        $this->usr = $this->db->query('SELECT id FROM users WHERE "' . mysql_real_escape_string($this->input->get('key')) . '"=MD5(CONCAT(id,username,"sxSf55",email,"' . date('z') . '")) OR "' . mysql_real_escape_string($this->input->get('key')) . '"=MD5(CONCAT(id,username,"sxSf55",email,"' . (date('z') - 1) . '"))')->row_array();
        if (!$this->usr) {
            $err = 'User from this key not found';
            return false;
        }
        return true;
    }

    public function change_forgot_password() {
        return $this->db->update('users', array('password' => md5($this->input->post('password'))), array('id' => $this->usr['id']));
    }

    public function signup() {
        $invite = $this->session->userdata('invite');
        $username = strtolower($this->input->post('username'));
        $data = array(
            'username' => $username,
            'password' => md5($this->input->post('password')),
            'email' => $this->input->post('email'),
            'reg_date' => date('Y-m-d H:i:s'),
            'reg_ip' => $this->input->ip_address(),
            'invite' => $invite,
            'buttons' => 200
        );
        
        $this->db->insert('users', $data);
        $id = $this->db->insert_id();

        $this->load->model('links_model');
        $this->links_model->save_partner_link($id);

        if($id<=1005){
            $this->db->where('id',$id);
            $this->db->update('users', array('buttons'=>1000));
            $this->load->library('buttons');
            $this->buttons->write_history($this->usr, array('action' => 'first_bonus', 'jewels' => 0, 'now_jewels' => 0, 'buttons' => 0, 'now_buttons' => 1000, 'description' => 'First 1000 users'));
        }

        //Add user to notification
        $this->addNotification($id);

        //Add default view
        $this->_addDefaultView($id);

        $key = md5($id . $username . 'sl3W1' . $this->input->post('email'));
        $this->send_to_mail($this->input->post('email'), $key, 'Confirm your registration for Perfect-Look.org', 'signup', 'register');
    }

    public function _addDefaultView($uid) {
        require_once APPPATH . 'libraries/Dressup.php';
        $this->load->model('dressup_model');
        $_POST['day_look'] = 1;
        $dressup = new Dressup_lib();
        $dressup->clear_doll();
        $dressup->create_default();
        $this->dressup_model->save_look(0, $dressup, $uid);
    }

    public function addNotification($id) {
        $notif_data = array('uid' => $id);
        $this->db->insert('user_notifications', $notif_data);
    }

    public function send_to_mail($email, $key, $subject, $file_content, $from_name = 'admin', $username = null) {
        $message_text = $this->load->view('email/' . $file_content, array('key' => $key, 'username' => $username), true);
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: Perfect-Look.Org <' . $from_name . '@perfect-look.org>' . "\r\n";
        mail($email, $subject, $message_text, $headers);
//        $this->load->library('email');
//        $this->load->config('email');
//        $config = $this->config->item('smtp_data');
//        $from = $this->config->item('smtp_from');
//        $this->email->initialize($config);
//        $this->email->from($from['from_mail'], $from['from_site']);
//        $this->email->to($email);
//        $this->email->subject($subject);
//        $this->email->message($message_text);
//        $this->email->send();
    }

    public function send_notification($uid, $template, $subject, $notif_type, $data = NULL) {
        $user = $this->db->query('SELECT * FROM users LEFT JOIN user_notifications ON users.id=user_notifications.uid WHERE id="' . $uid . '"')->row_array();
        if (!empty($user['email']) && $user['notif'] == 1 && $user[$notif_type] == 1) {
            $message_text = $this->load->view('email/' . $template, array('data' => $data), true);
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= 'From: Perfect-Look.Org <notifications@perfect-look.org>' . "\r\n";
            mail($user['email'], $subject, $message_text, $headers);
        }
    }

    public function check_activate($key) {
        $rez = $this->db->query('SELECT * FROM users WHERE "' . mysql_real_escape_string($key) . '"=MD5(CONCAT(id,username,"sl3W1",email))')->row_array();
        if (!empty($rez['id'])) {
            $this->usr = $rez['id'];

            //ADD buttons if it from invite
            if (!empty($rez['invite'])) {
                $this->load->library('buttons');
                $this->buttons->add_money($rez['invite'], 500);
                $this->buttons->write_history($rez['invite'], array('action' => 'invite_friends', 'jewels' => $this->user['jewels'], 'now_jewels' => $this->user['jewels'], 'buttons' => $this->user['buttons'], 'now_buttons' => ($this->user['buttons'] + 500), 'description' => 'Registred your invite friend <a href="/' . $rez['username'] . '" target="_blank">' . $rez['username'] . '</a>'));
            }
            return true;
        }
        //Error, key not found
        return false;
    }

    public function activate() {
        $this->db->query('UPDATE users SET active=1 WHERE id="' . $this->usr . '"');
    }

    //end Register
    //Facebook & Twitter Login
    public function social_login($id) {
        $loginned_user = $this->session->userdata('user');
        $user = $this->db->get_where('users', array('id' => $id))->row_array();
        $this->session->set_userdata(array('user' => $user));
        if (!empty($loginned_user)) {
            $last = $this->session->userdata('last_page');
            $location = (empty($last)) ? '/editprofile' : $last;
            header('Location: ' . $location);
        } else {
            header('Location: /' . $user['username']);
        }
        exit;
    }

    public function fb_profile($facebook) {
        try {
            $access_token = $facebook->getAccessToken();
            $uid = $facebook->getUser();
            $api_call = array(
                'method' => 'users.getinfo',
                'uids' => $uid,
                'fields' => 'uid, first_name, last_name, pic_big, email'
            );
            $user = $facebook->api($api_call);
        } catch (Exception $e) {
            echo 'Facebook error';
            exit;
        }
        if (!empty($user)) {
            $loginned_user = $this->session->userdata('user');
            if (!empty($loginned_user)) {
                $data = array(
                    'fb_fullname' => $user[0]['first_name'] . ' ' . $user[0]['last_name'],
                    'fb_username' => $user[0]['first_name'],
                    'fb_id' => $user[0]['uid'],
                    'fb_access' => $access_token
                );
                $this->db->where('id', $loginned_user['id']);
                $this->db->update('users', $data);
                $id = $loginned_user['id'];
            } else {
                $find_user = $this->db->get_where('users', array('fb_id' => $user[0]['uid']))->row_array();

                if (!empty($find_user)) {
                    $id = $find_user['id']; //User Finded!
                    $data = array(
                        'fb_access' => $access_token
                    );
                    $this->db->where('id', $loginned_user['id']);
                    $this->db->update('users', $data);
                } else {
                    redirect('/?social=' . urlencode($user[0]['first_name'] . ' ' . $user[0]['last_name']));
                }
            }
            $this->social_login($id);
        }
    }

    public function tw_profile($response) {
        $data = array();
        $loginned_user = $this->session->userdata('user');

        $tokens = $this->session->userdata('tw_session');
        $oauth_token = $tokens['access_token'];
        $oauth_token_secret = $tokens['access_token_secret'];

        if (!empty($loginned_user)) {
            $data = array(
                'tw_fullname' => $response->name,
                'tw_username' => $response->screen_name,
                'tw_id' => $response->id,
                'tw_token' => $oauth_token,
                'tw_secret' => $oauth_token_secret
            );
            $this->db->where('id', $loginned_user['id']);
            $this->db->update('users', $data);
            $id = $loginned_user['id'];
        } else {
            $find_user = $this->db->get_where('users', array('tw_id' => $response->id))->row_array();
            if (!empty($find_user)) {
                $id = $find_user['id'];
                //Update DB keys
                $this->db->update('users', array('tw_secret' => $oauth_token_secret, 'tw_token' => $oauth_token), array('id' => $id));
            } else {
                redirect('/?social=' . urlencode($response->screen_name));
            }
        }
        $this->social_login($id);
    }

    public function check_loginned_user() {
        $user = $this->session->userdata('user');
        if (!empty($user)) {
            $usr_ident = (empty($user['username'])) ? 'id' . $user['id'] : $user['username'];
            header('Location: /' . $usr_ident);
            exit;
        }
    }

    //end Login
}

?>
