<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller {

    public $data = array();

    public function __construct() {
        parent::__construct();
        $this->tpl->gtpl = 'homepage';
        $this->load->model('home_model');
    }

    public function index() {
        $this->home_model->check_loginned_user();
        if ($this->input->get('confirmed')) {
            $this->data['err'] = 'Your email has been confirmed';
        } elseif ($this->input->get('not_confirmed')) {
            $this->data['err'] = 'Wrong activation key';
        } elseif($this->input->get('social')){
            $this->data['err'] = 'User '.$this->input->get('social').' not found';
        }
        $this->tpl->ltpl = array('homepage' => 'index');
        $this->tpl->show($this->data);
    }

    public function signin() {
        $this->home_model->check_loginned_user();
        if ($this->input->post('signin')) {
            if ($this->home_model->check_signin_form($this->data['err'])) {
                $this->home_model->signin();
            }
        }
        $this->tpl->ltpl = array('homepage' => 'signin');
        $this->tpl->show($this->data);
    }

    public function register() {
        $this->home_model->check_loginned_user();
        //invite
        if ($this->input->get('invite')) {
            $this->session->set_userdata(array('invite' => $this->input->get('invite')));
        }
        if ($this->input->post('signup')) {
            if ($this->home_model->check_signup_form($this->data['err'])) {
                $this->home_model->signup();
                $this->data['sending'] = 1;
            }
        } elseif ($this->input->get('key')) {
            //Activation
            if ($this->home_model->check_activate($this->input->get('key'))) {
                $this->home_model->activate();
                redirect('/?confirmed=1');
            } else {
                redirect('/?not_confirmed=1');
            }
            exit;
        }
        $this->tpl->ltpl = array('homepage' => 'signup');
        $this->tpl->show($this->data);
    }

    public function forgotpassword() {
        $this->home_model->check_loginned_user();

        if ($this->input->post('send')) {
            $user_mail_key = $this->home_model->check_forgotpassword_form($this->data['err']);
            if ($user_mail_key) {
                $this->home_model->send_to_mail($user_mail_key['email'], $user_mail_key['key'], 'Password reset for perfect-look.org', 'forgotpassword', 'forgotpassword',$user_mail_key['username']);
                $this->data['sended'] = 1;
            }
        }
        if ($this->input->post('change')) {
            if ($this->home_model->check_change_password_form($this->data['err'])) {
                $this->data['changed'] = $this->home_model->change_forgot_password();
            }
        }

        if (!$this->input->get('key')) {
            $this->data['mess'] = "Enter your email or username to receive instructions<br>
                for how to reset your password at your registered email address.";
        } else {
            $this->data['mess'] = "Please choose a new password.";
        }
        $this->tpl->ltpl = array('homepage' => 'forgotpassword');
        $this->tpl->show($this->data);
    }

    public function resendemail() {
        $this->home_model->check_loginned_user();
        if ($this->input->post('send') && $this->input->post('email')) {
            //Search user
            $user = $this->db->query('SELECT * FROM users WHERE email="' . mysql_real_escape_string($this->input->post('email')) . '" OR username="' . mysql_real_escape_string($this->input->post('email')) . '"')->row_array();
            if (!empty($user)) {
                //finded
                $key = md5($user['id'] . $user['username'] . 'sl3W1' . $user['email']);
                $this->home_model->send_to_mail($user['email'], $key, 'Confirm your registration for Perfect-Look.org', 'signup', 'register');
                $this->data['sended'] = 1;
            } else {
                //not found
                $this->data['err'] = 'Email is not registered';
            }
        }
        $this->tpl->ltpl = array('homepage' => 'resendemail');
        $this->tpl->show($this->data);
    }

    //Login with Facebook
    public function fb() {
        require(APPPATH . 'libraries/facebook/facebook.php');
        $this->load->config('social');
        $facebook = new Facebook(array(
                    'appId' => $this->config->item('fbAppId'),
                    'secret' => $this->config->item('fbSecret'),
                    'cookie' => true
                ));
        $user = $facebook->getUser();
        if ($user) {
            try {
                $this->home_model->fb_profile($facebook);
            } catch (FacebookApiException $e) {
                $user = null;
                exit;
            }
        }else{
            $loginUrl = $facebook->getLoginUrl(array('req_perms' => 'email,publish_stream,read_friendlists,publish_stream'));
            redirect($loginUrl);
        }
    }

    //Twitter callback function for getAccessToken
    public function tw_callback() {
        require_once APPPATH . 'libraries/twitter/twitteroauth.php';
        $this->load->config('social');

        $tokens = $this->session->userdata('tw_session');
        $oauth_token = $tokens['oauth_token'];
        $oauth_token_secret = $tokens['oauth_token_secret'];

        //2 - get Access tokens
        if (isset($_REQUEST['oauth_token']) && $oauth_token !== $_REQUEST['oauth_token']) {
            //Old token, clear and get new
            $this->session->unset_userdata('tw_session');
        } elseif (!empty($_REQUEST['oauth_token']) && !empty($oauth_token) && !empty($oauth_token_secret)) {
            //good, get access
            $connection = new TwitterOAuth($this->config->item('twConsumerKey'), $this->config->item('twConsumerSecret'), $oauth_token, $oauth_token_secret);
            $access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);
            $tokens = array(
                'access_token' => $access_token['oauth_token'], 'access_token_secret' => $access_token['oauth_token_secret'],
                'oauth_token' => $oauth_token, 'oauth_token_secret' => $oauth_token_secret);
            $this->session->set_userdata(array('tw_session' => $tokens));
        }
        $this->tw();
    }

    public function tw() {
        require_once APPPATH . 'libraries/twitter/twitteroauth.php';
        $this->load->config('social');
        $tokens = $this->session->userdata('tw_session');
        $oauth_token = $tokens['oauth_token'];
        $oauth_token_secret = $tokens['oauth_token_secret'];

        if (empty($oauth_token) || empty($oauth_token_secret)) {
            //1 - Create autorize URL and redirect
            $connection = new TwitterOAuth($this->config->item('twConsumerKey'), $this->config->item('twConsumerSecret'));
            $request_token = $connection->getRequestToken(site_url("twitter/callback"));

            $tokens = array('oauth_token' => $request_token['oauth_token'], 'oauth_token_secret' => $request_token['oauth_token_secret']);
            $this->session->set_userdata(array('tw_session' => $tokens));

            if ($connection->http_code == 200) {
                $url = $connection->getAuthorizeURL($request_token['oauth_token']); /* Build authorize URL and redirect user to Twitter. */
                header('Location: ' . $url);
                exit;
            } else {
                echo 'Could not connect to Twitter. Refresh the page or try again later.';
                exit;
            }
        } else {

            //3 - all fine, get data
            $access_token = $this->session->userdata('tw_session');
            $connection = new TwitterOAuth($this->config->item('twConsumerKey'), $this->config->item('twConsumerSecret'), $access_token['access_token'], $access_token['access_token_secret']);
            $content = $connection->get('account/verify_credentials');
            if (empty($content->error)) {
                $this->home_model->tw_profile($content);
            }else{
                $this->session->unset_userdata('tw_session');
                $this->tw();
            }
        }
    }

}