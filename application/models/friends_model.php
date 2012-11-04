<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Friends_model extends CI_Model {

    public function incoming_friends($count = false) {
        if (!$count) {
            return $this->db->query('SELECT uid,adding,users.username,users.fb_fullname,users.tw_fullname FROM user_friends LEFT JOIN users ON users.id = user_friends.uid WHERE friend_id="' . $this->user['id'] . '" AND status="0" AND removed=0')->result_array();
        } else {
            return $this->db->select('id')->from('user_friends')->where(array('friend_id' => $this->user['id'], 'status' => 0))->count_all_results();
        }
    }

    public function get_my_friends_count() {
        return $this->db->query('SELECT 1 FROM user_friends WHERE (friend_id="' . $this->user['id'] . '" || uid="' . $this->user['id'] . '") AND status="1" AND removed=0')->num_rows();
    }

    public function user_friends_count($uid) {
        return count($this->user_friends($uid));
    }

    public function user_friends($uid = NULL, $limit = 0, $rand = false) {
        $friends_sort = $this->session->userdata('friends_sort');
        $order = (!empty($friends_sort)) ? $friends_sort : 'users.username';

        $limit = (!empty($limit)) ? 'LIMIT 0,' . $limit : '';
        $random = (!empty($rand)) ? ' ORDER BY RAND() ' : '';
        $uid = (empty($uid)) ? $this->user['id'] : $uid;
        return $this->db->query('SELECT user_friends.adding, users.* FROM user_friends LEFT JOIN users ON IF(user_friends.uid="' . $uid . '", user_friends.friend_id = users.id, user_friends.uid = users.id) WHERE (friend_id="' . $uid . '" OR uid = "' . $uid . '") AND status=1 AND removed=0 ORDER BY ' . $order . ' DESC ' . $limit . $random)->result_array();
    }

    public function get_friend_status($friend_id) {
        $query = $this->db->query('SELECT `status`,friend_id FROM user_friends WHERE removed=0 AND (friend_id = "' . $friend_id . '" AND uid = "' . $this->user['id'] . '") OR (uid = "' . $friend_id . '" AND friend_id = "' . $this->user['id'] . '") ');
        if (!$query->num_rows())
            return 'none';
        $res = $query->row_array();
        if ($res['status'])
            return 'friend';
        elseif ($res['friend_id'] == $this->user['id'])
            return 'confirm';
        else
            return 'wait';
    }

    public function add_friend($id) {
        $all = $this->db->query('SELECT 1 FROM user_friends WHERE (friend_id="' . $id . '" AND uid="' . $this->user['id'] . '") OR (friend_id="' . $this->user['id'] . '" AND uid="' . $id . '")')->result_array();
        $data = array(
            'uid' => $this->user['id'],
            'friend_id' => $id,
            'status' => 0,
            'adding' => date('Y-m-d H:i:s')
        );
        $this->db->insert('user_friends', $data);
        
        
        if (empty($all)) {
            //Send notification
            $this->load->model('home_model');
            $this->home_model->send_notification($id, 'notif_friend_request', 'New friends request from ' . $this->user['username'] . ' at Perfect-Look.org', 'friend_request', array('username' => $this->user['username']));
        }
    }

    public function del_friend($id) {
        $this->db->query('UPDATE user_friends SET removed=1 WHERE (friend_id="' . $id . '" AND uid="' . $this->user['id'] . '") OR (friend_id="' . $this->user['id'] . '" AND uid="' . $id . '")');
//        $this->db->query('DELETE FROM user_friends WHERE (friend_id="' . $id . '" AND uid="' . $this->user['id'] . '") OR (friend_id="' . $this->user['id'] . '" AND uid="' . $id . '")');
    }

    public function confirm_friend($id) {
        $this->db->query('UPDATE user_friends SET status=1 WHERE uid="' . $id . '" AND removed=0 AND friend_id="' . $this->user['id'] . '"');
        $user = $this->user_model->get_user_info('id', $id);
        $this->user_model->write_history_activity($id, 'friend', $this->user['id']);
        $this->user_model->write_history_activity($this->user['id'], 'friend', $id);
        //Send notification
//        $this->load->model('home_model');
//        $this->home_model->send_notification($id, 'notif_friend_accepted','User accept your friend request', 'friend_accept', array('username'=>  $user['username']));
    }

    public function sort_myfriends_by($val) {
        switch ($val) {
            case 'adding':
                $this->session->set_userdata(array('friends_sort' => 'user_friends.adding'));
                break;
            case 'last_action':
                $this->session->set_userdata(array('friends_sort' => 'users.last_action'));
                break;
            default:
                $this->session->set_userdata(array('friends_sort' => 'users.username'));
        }
    }
    
    public function friends_dolls_updates(){
        $rez = $friends_id = array();
        $friends = $this->user_friends($this->user['id']);
        if(!empty($friends))
        foreach($friends as $user){
            $friends_id[] = $user['id'];
        }
        if(!empty($friends_id)){
            $rez = $this->db->query('SELECT t1.uid, t1.*, users.username FROM (SELECT * FROM user_dressups WHERE uid IN ("'.implode('","',$friends_id).'") ORDER BY user_dressups.id DESC) t1 LEFT JOIN users ON users.id=t1.uid GROUP BY t1.uid')->result_array();
        }
        return $rez;
    }
    
    public function friends_photo_updates(){
        $rez = $friends_id = array();
        $friends = $this->user_friends($this->user['id']);
        if(!empty($friends))
        foreach($friends as $user){
            $friends_id[] = $user['id'];
        }
        if(!empty($friends_id)){
            $rez = $this->db->query('SELECT t1.uid, t1.*, users.username FROM (SELECT * FROM upload_photo WHERE uid IN ("'.implode("','",$friends_id).'") ORDER BY upload_photo.id DESC) t1 LEFT JOIN users ON users.id=t1.uid GROUP BY t1.uid')->result_array();
        }
        return $rez;
    }

}

?>
