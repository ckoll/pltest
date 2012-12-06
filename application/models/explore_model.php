<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Explore_model extends CI_Model
{

    //FIND FRIENDS

    public function fb_friends($fb_id = NULL)
    {
        $this->session->set_userdata(array('last_page' => $_SERVER['REQUEST_URI'])); // Last page for back redirect
        if (!empty($fb_id)) {
            require(APPPATH . 'libraries/facebook/facebook.php');
            $this->load->config('social');
            $facebook = new Facebook(array(
                'appId' => $this->config->item('fbAppId'),
                'secret' => $this->config->item('fbSecret'),
                'cookie' => true
            ));
            $user = $facebook->getUser();
            if (!empty($user)) {
                try {
                    $friends = $facebook->api('/me/friends', 'GET', array('access_token' => $this->user['fb_access']));
                    if (!empty($friends)) {
                        return $friends['data'];
                    }
                } catch (Exception $e) {
                    echo '<pre>';
                    var_dump($e);
                    echo'</pre>';
                    exit;
                }
            } else {
                redirect('/facebook');
            }
        } else {
            redirect('/facebook');
        }
    }

    public function tw_friends($tw_id = NULL)
    {
        $this->session->set_userdata(array('last_page' => $_SERVER['REQUEST_URI'])); // Last page for back redirect
        require_once APPPATH . 'libraries/twitter/twitteroauth.php';
        $this->load->config('social');
        $tw_token = $this->user['tw_token'];
        $tw_secret = $this->user['tw_secret'];

        if (!empty($tw_id) && !empty($tw_secret)) {
            $all = array();
            $connection = new TwitterOAuth($this->config->item('twConsumerKey'), $this->config->item('twConsumerSecret'), $tw_token, $tw_secret);
            $content = $connection->get('friends', array('count' => 100));

            if (!empty($content->users)) {
                foreach ($content->users as $val) {
                    $all[] = array('id' => $val->id, 'name' => $val->name);
                }
                return $all;
            }
            return false;
        } else {
            redirect('/twitter');
        }
    }

    public function search_user_contacts($type, $friends)
    {
        $finded = array();
        switch ($type) {
            case 'email':
                $all_users = $this->db->query('SELECT email,id,username FROM users WHERE email!=""')->result_array();
                if (!empty($all_users) && !empty($friends)) {
                    $friends_email = array_keys($friends);
                    foreach ($all_users as $val) {
                        if (in_array($val['email'], $friends_email)) {
                            $finded[$val['email']] = array('email' => $val['email'], 'id' => $val['id'], 'username' => $val['username']);
                        }
                    }
                }
                break;
            case 'facebook':
                $all_users = $this->db->query('SELECT id,fb_id,username FROM users WHERE fb_id!=""')->result_array();
                $friends_ids = array();
                if (!empty($friends)) {
                    foreach ($friends as $val) {
                        $friends_ids[] = $val['id'];
                    }
                }
                if (!empty($all_users) && !empty($friends)) {
                    foreach ($all_users as $val) {
                        if (in_array($val['fb_id'], $friends_ids)) {
                            $finded[$val['fb_id']] = array('fb_id' => $val['fb_id'], 'id' => $val['id'], 'username' => $val['username']);
                        }
                    }
                }
                break;
            case 'twitter':
                $all_users = $this->db->query('SELECT id,tw_id,username FROM users WHERE tw_id!=""')->result_array();
                $friends_ids = array();
                if (!empty($friends)) {
                    foreach ($friends as $val) {
                        $friends_ids[] = $val['id'];
                    }
                }
                if (!empty($all_users) && !empty($friends)) {
                    foreach ($all_users as $val) {
                        if (in_array($val['tw_id'], $friends_ids)) {
                            $finded[$val['tw_id']] = array('tw_id' => $val['tw_id'], 'id' => $val['id'], 'username' => $val['username']);
                        }
                    }
                }
                break;
        }
        return $finded;
    }

    public function short_url($url)
    {
        $login = 'vlodkow';
        $appkey = 'R_4563fe15ede57a2c05fc1437b9300405';
        $format = 'json';
        $version = '2.0.1';
        $bitly = 'http://api.bit.ly/shorten?version=' . $version . '&longUrl=' . urlencode($url) . '&login=' . $login . '&apiKey=' . $appkey . '&format=' . $format;
        $response = file_get_contents($bitly);
        $json = @json_decode($response, true);
        return $json['results'][$url]['shortUrl'];
    }

    //INVITE FRIENDS
    public function send_invites($type, $friends, $message = '', &$err = NULL)
    {
        $add_buttons = 0;
        $this->session->set_userdata(array('last_page' => $_SERVER['REQUEST_URI'])); // Last page for back redirect

        $message_append = (empty($message)) ? '' : '<br>' . $message . '<br>';
        $user = $this->user['username'];

        if ($type == 'twitter') {
            $link = $this->short_url('http://perfect-look.org/');
            $twitter_message = 'Join to ' . $link . ' - fun fashion site where you can share and tag fashion photos and create perfect look dressup';
        } else {
            $message = $message_append . $user . ' has invited you to join perfect-look.org. Perfect-look.org is a fun fashion site where you can share and tag fashion photos and create perfect look dressup dolls. Collect buttons by sharing photos and dressing up, join and start now!<br>
[<a href="http://' . $_SERVER['SERVER_NAME'] . '/register/?invite=' . $this->user['id'] . '">Sign Up</a>]';
            $subject = $user . ' has invited you to join perfect-look.org';
        }
        $friends = str_replace('\n', ',', $friends);
        $friends = explode(',', $friends);
        if (!empty($friends)) {
            //GET ALL SENDED INVITES
            $all_sended_status = $already_sended = array();
            $sended = $this->db->query('SELECT system,`to`,`date` FROM sended_invite WHERE `uid`="' . $this->user['id'] . '" AND `system`="' . $type . '"');
            if ($sended->num_rows() > 0) {
                foreach ($sended->result_array() as $val) {
                    $already_sended[$val['to']] = $val;
                }
            }
            //GET SENDED INVITES TODAY
            $today_sended = $this->db->get_where('sended_invite', array('uid' => $this->user['id'], 'date' => date('Y-m-d')))->num_rows();
            if ($today_sended >= 50) {
                $err = 'Today you already send 50 invites';
                return false;
            }

            switch ($type) {
                case 'facebook':
                    //CHECK TO SENDING FOR FACEBOOK - we use js SDK for sending
                    foreach ($friends as $val) {
                        $user = explode('|', $val);
                        if (!in_array($user[0], array_keys($already_sended)) && $today_sended <= 50) {
                            $all_sended_status[$user[1]] = 1;
                        } elseif (!in_array($user[0], array_keys($already_sended))) {
                            $all_sended_status[$user[1]] = 0;
                        } else {
                            $all_sended_status[$user[1]] = -1;
                        }
                        $fb_more_data[$user[1]] = $user[0];
                    }

                    break;
                case 'twitter':
                    //SEND TO TWITEER
                    require_once APPPATH . 'libraries/twitter/twitteroauth.php';
                    $this->load->config('social');

                    if (!empty($this->user['tw_id'])) {
                        $connection = new TwitterOAuth($this->config->item('twConsumerKey'), $this->config->item('twConsumerSecret'), $this->user['tw_token'], $this->user['tw_secret']);
                        foreach ($friends as $val) {
                            $user = explode('|', $val);
                            if (!in_array($user[0], array_keys($already_sended)) && $today_sended <= 50) {

                                $connection->post('direct_messages/new', array('user_id' => $user[0], 'text' => $twitter_message));
                                $ins_data = array(
                                    'uid' => $this->user['id'],
                                    'system' => $type,
                                    'to' => $user[0],
                                    'date' => date('Y-m-d')
                                );
                                $this->db->insert('sended_invite', $ins_data);
                                $all_sended_status[$user[1]] = 1;
                                $today_sended++;
                                $add_buttons++;
                            } elseif (!in_array($user[0], array_keys($already_sended))) {
                                $all_sended_status[$user[1]] = 0;
                            } else {
                                $all_sended_status[$user[1]] = -1;
                            }
                        }
                    } else {
                        redirect('/tw');
                    }
                    break;
                default:
                    //SEND TO EMAIL
                    $this->load->model('home_model');

                    $registered = array();
                    $registered_rez = $this->db->query('SELECT email FROM users WHERE email!=""')->result_array();
                    if ($registered_rez) {
                        foreach ($registered_rez as $val) {
                            $registered[] = $val['email'];
                        }
                    }

                    foreach ($friends as $val) {
                        $val = trim(strtolower($val));
                        if (!preg_match('/^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$/i', $val)) {
                            $all_sended_status[$val] = -2;
                        } elseif (in_array($val, $registered)) {
                            $all_sended_status[$val] = -3; //already registered
                        } elseif (!in_array($val, array_keys($already_sended)) && $today_sended <= 50) {

                            $this->home_model->send_to_mail($val, null, $subject, 'invite_friend', $this->user['username'], null, array('message' => $message, 'user' => $this->user, 'add_message' => $message_append));

                            $ins_data = array(
                                'uid' => $this->user['id'],
                                'system' => $type,
                                'to' => $val,
                                'date' => date('Y-m-d')
                            );
                            $this->db->insert('sended_invite', $ins_data);
                            $all_sended_status[$val] = 1;
                            $today_sended++;
                            $add_buttons++;
                        } elseif (!in_array($val, array_keys($already_sended))) {
                            $all_sended_status[$val] = 0;
                        } else {
                            $all_sended_status[$val] = -1;
                        }
                    }
            }
            if (!empty($add_buttons)) {
                $this->load->library('buttons');
                $this->buttons->add_money($this->user['id'], $add_buttons * 50);
                $this->buttons->write_history($this->user['id'], array('action' => 'invite_friends', 'jewels' => $this->user['jewels'], 'now_jewels' => $this->user['jewels'], 'buttons' => $this->user['buttons'], 'now_buttons' => ($this->user['buttons'] + ($add_buttons * 50)), 'description' => 'Sent invites to ' . $add_buttons . ' friends'));
            }
            return array('already_sended' => $already_sended, 'fb_more_data' => $fb_more_data, 'all_sended_status' => $all_sended_status, 'today_sended' => $today_sended);
        }
    }

    public function check_fb_invites($user)
    {
        if (!empty($user)) {
            //GET SENDED INVITES TODAY
            $today_sended = $this->db->get_where('sended_invite', array('uid' => $this->user['id'], 'date' => date('Y-m-d')))->num_rows();
            if ($today_sended >= 50) {
                $err = 'Today you already send 50 invites';
            }

            if (empty($err)) {
                $sended = $this->db->query('SELECT 1 FROM sended_invite WHERE `uid`="' . $this->user['id'] . '" AND `to`="' . $this->input->post('data') . '" AND `system`="facebook"');
                if ($sended->num_rows() == 0) {
                    $data_ins = array(
                        'uid' => $this->user['id'],
                        'system' => 'facebook',
                        'to' => $this->input->post('data'),
                        'date' => date('Y-m-d')
                    );
                    $this->db->insert('sended_invite', $data_ins);
                    //add buttons
                    $this->load->library('buttons');
                    $this->buttons->add_money($this->user['id'], 50);
                    $this->buttons->write_history($this->user['id'], array('action' => 'invite_friends', 'jewels' => $this->user['jewels'], 'now_jewels' => $this->user['jewels'], 'buttons' => $this->user['buttons'], 'now_buttons' => ($this->user['buttons'] + 50), 'description' => 'Sent invite to friend'));
                } else {
                    $err = 'Invite already sent';
                }
            }
            echo json_encode(array('err' => $err));
        }
    }

    public function yahoo_contacts()
    {
        $all = array();
        require_once APPPATH . 'libraries/yahoo/Yahoo.inc';
        define('CONSUMER_KEY', "dj0yJmk9SU1ucEprQzN4RlpIJmQ9WVdrOWF6ZDBNR0ppTXpJbWNHbzlNQS0tJnM9Y29uc3VtZXJzZWNyZXQmeD1jZg--");
        define('CONSUMER_SECRET', "2674be3b834a766b18fa4a6c469cfe0811b080bb");
        define('APPID', "k7t0bb32");
        $session = YahooSession::requireSession(CONSUMER_KEY, CONSUMER_SECRET, APPID);
        $query = sprintf("SELECT * FROM social.contacts WHERE guid=me");
        $response = $session->query($query);
        if (count($response->query->results->contact) > 0) {
            foreach ($response->query->results->contact as $val) {
                $all[$val->fields[1]->value] = $val->fields[0]->value;
            }
        }
        return $all;
    }

    public function gmail_contacts()
    {
        $all = array();
        $client_id = $this->config->item('gmail_client_id');
        $client_secret = $this->config->item('gmail_client_secret');
        $redirect_uri = $this->config->item('gmail_redirect_uri');
        $max_results = $this->config->item('gmail_max_results');
        $auth_code = $_GET["code"];

        function curl_file_get_contents($url)
        {
            $curl = curl_init();
            $userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';
            curl_setopt($curl, CURLOPT_URL, $url); //The URL to fetch. This can also be set when initializing a session with curl_init().
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE); //TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5); //The number of seconds to wait while trying to connect.	
            curl_setopt($curl, CURLOPT_USERAGENT, $userAgent); //The contents of the "User-Agent: " header to be used in a HTTP request.
//              curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE); //To follow any "Location: " header that the server sends as part of the HTTP header.
            curl_setopt($curl, CURLOPT_AUTOREFERER, TRUE); //To automatically set the Referer: field in requests where it follows a Location: redirect.
            curl_setopt($curl, CURLOPT_TIMEOUT, 10); //The maximum number of seconds to allow cURL functions to execute.
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); //To stop cURL from verifying the peer's certificate.
            $contents = curl_exec($curl);
            curl_close($curl);
            return $contents;
        }

        $fields = array(
            'code' => urlencode($auth_code),
            'client_id' => urlencode($client_id),
            'client_secret' => urlencode($client_secret),
            'redirect_uri' => urlencode($redirect_uri),
            'grant_type' => urlencode('authorization_code')
        );
        $post = '';
        foreach ($fields as $key => $value) {
            $post .= $key . '=' . $value . '&';
        }
        $post = rtrim($post, '&');
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://accounts.google.com/o/oauth2/token');
        curl_setopt($curl, CURLOPT_POST, 5);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        $result = curl_exec($curl);
        curl_close($curl);

        $response = json_decode($result);
        $accesstoken = $response->access_token;

        $url = 'https://www.google.com/m8/feeds/contacts/default/full?max-results=' . $max_results . '&oauth_token=' . $accesstoken;
        $xmlresponse = curl_file_get_contents($url);
        if (strlen(stristr($xmlresponse, 'error ')) > 0) { //At times you get Authorization error from Google.
            redirect('/find_friends/');
        }
        $xml = new SimpleXMLElement($xmlresponse);
        $xml->registerXPathNamespace('gd', 'http://schemas.google.com/g/2005');
        $result = $xml->xpath('//gd:email');

        foreach ($result as $title) {
            $addr = (string)$title->attributes()->address;
            $all[$addr] = '';
        }
        return $all;
    }

    public function get_bugs($limit, $page)
    {
        $begin = $limit * $page;
        return $this->db->query('SELECT SQL_CALC_FOUND_ROWS bugs.*, users.username FROM bugs LEFT JOIN users ON users.id = bugs.uid ORDER BY id DESC LIMIT ' . $begin . ',' . $limit)->result_array();
    }

    public function add_bug()
    {
        $bug_text = $this->input->post('text');
        $user = $this->user['id'];
        $this->db->query('INSERT INTO bugs(uid,text,date) VALUES("' . $user . '","' . $bug_text . '", "' . date('Y-m-d H:i:s') . '")');
        $this->load->library('buttons');
        $this->buttons->add_money($user, 5);
        $this->buttons->write_history($this->user['id'], array('action' => 'add_bug', 'jewels' => $this->user['jewels'], 'now_jewels' => $this->user['jewels'], 'buttons' => $this->user['buttons'], 'now_buttons' => ($this->user['buttons'] + 5), 'description' => 'For posted information about a bug'));
        if (is_file($_FILES['attach']['tmp_name'])) {
            $name = $_FILES['attach']['name'];
            $id = $this->db->insert_id();
            copy($_FILES['attach']['tmp_name'], FILES . 'bugs/' . $id . '-' . $name);
            $this->db->query('UPDATE bugs SET attach="' . mysql_real_escape_string($name) . '" WHERE id="' . $id . '"');
        }
    }

    public function count_pages($for_page)
    {
        $count = $this->db->query('SELECT FOUND_ROWS() as result')->row()->result;
        return ceil($count / $for_page);
    }

    public function remove_message($id)
    {
        $acc_check = '';
        $admin = $this->db->get_where('users_admin', array('uid' => $this->user['id']))->result();
        if (empty($admin)) {
            $acc_check = ' AND uid="' . $this->user['id'] . '"';
        }
        $attach = $this->db->query('SELECT attach FROM bugs WHERE id="' . $id . '" ' . $acc_check)->row()->attach;
        @unlink(FILES . 'bugs/' . $id . '-' . $attach);
        $this->db->query('DELETE FROM bugs WHERE id="' . $id . '" ' . $acc_check);
    }

    public function bug_status_change()
    {
        $val = $this->input->post('val');
        $id = $this->input->post('id');
        $admin = $this->db->get_where('users_admin', array('uid' => $this->user['id']))->result();
        if ($admin) {
            $this->db->query('UPDATE bugs SET status="' . $val . '" WHERE id="' . $id . '"');
        }
    }

    public function bug_text_save()
    {
        $val = $this->input->post('val');
        $id = $this->input->post('id');
        $this->db->query('UPDATE bugs SET `text`="' . $val . '" WHERE id="' . $id . '"');
    }

}
