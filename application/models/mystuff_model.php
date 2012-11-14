<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mystuff_model extends CI_Model {
    public function get_photos($for_page, $page=0){
        $begin = $for_page * $page;
        return $this->db->query('SELECT SQL_CALC_FOUND_ROWS CONCAT(id,rand_num) photo_id, image_type FROM upload_photo WHERE uid='.$this->data['user']['id'].' ORDER BY date DESC LIMIT '.$begin.','.$for_page)->result_array();
    }
    public function get_button_jewels_history($for_page, $page=0){
        $begin = $for_page * $page;
        return $this->db->query('SELECT SQL_CALC_FOUND_ROWS * FROM money_history WHERE uid='.$this->data['user']['id'].' ORDER BY id DESC LIMIT '.$begin.', '.$for_page)->result_array();
    }
    public function get_favorite_photos($my=NULL, $for_page, $page=0){
        $begin = $for_page * $page;
        if(empty($my)){
            $where = 'like_users LIKE "%'.($this->data['user']['id'].'_'.$this->data['user']['username']).'" OR like_users LIKE "%'.($this->data['user']['id'].'_'.$this->data['user']['username']).'%" OR like_users LIKE "'.($this->data['user']['id'].'_'.$this->data['user']['username']).'%"';
        }else{
            $where = 'uid="'.$this->data['user']['id'].'" AND upload_photo.like > 0';
        }
        return $this->db->query('SELECT SQL_CALC_FOUND_ROWS users.id, users.username, CONCAT(upload_photo.id,upload_photo.rand_num) photo_id, upload_photo.image_type FROM upload_photo LEFT JOIN users ON upload_photo.uid=users.id WHERE '.$where.' LIMIT '.$begin.', '.$for_page)->result_array();
    }
    public function get_favorite_dressups($my=NULL, $for_page, $page=0){
        $begin = $for_page * $page;
        if(empty($my)){
            $where = 'like_users LIKE "%'.($this->data['user']['id'].'_'.$this->data['user']['username']).'" OR like_users LIKE "%'.($this->data['user']['id'].'_'.$this->data['user']['username']).'%" OR like_users LIKE "'.($this->data['user']['id'].'_'.$this->data['user']['username']).'%"';
        }else{
            $where = 'uid="'.$this->data['user']['id'].'" AND user_dressups.like > 0';
        }
        return $this->db->query('SELECT SQL_CALC_FOUND_ROWS users.username, user_dressups.id FROM user_dressups LEFT JOIN users ON user_dressups.uid=users.id WHERE '.$where.' ORDER BY `like` LIMIT '.$begin.', '.$for_page)->result_array();
    }
    public function get_most_hearted_dressups($for_page, $page=0){
        $begin = $for_page * $page;
        return $this->db->query('SELECT SQL_CALC_FOUND_ROWS id, "'.$this->data['user']['username'].'" as username FROM user_dressups WHERE uid='.$this->data['user']['id'].' AND `like`>0 ORDER BY `like` DESC LIMIT '.$begin.', '.$for_page)->result_array();
    }
    public function get_most_hearted_photos($for_page, $page=0){
        $begin = $for_page * $page;
        return $this->db->query('SELECT SQL_CALC_FOUND_ROWS CONCAT(id,rand_num) photo_id, upload_photo.image_type FROM upload_photo WHERE uid='.$this->data['user']['id'].' AND `like`>0 ORDER BY `like` DESC LIMIT '.$begin.', '.$for_page)->result_array();
    }
    public function get_most_commented_photos($for_page, $page=0){
        $begin = $for_page * $page;
        return $this->db->query('SELECT SQL_CALC_FOUND_ROWS CONCAT(upload_photo.id,upload_photo.rand_num) photo_id, upload_photo.image_type FROM upload_photo WHERE uid='.$this->data['user']['id'].' AND comments>0 ORDER BY comments DESC LIMIT '.$begin.', '.$for_page)->result_array();
    }
    public function get_most_commented_dressups($for_page, $page=0){
        $begin = $for_page * $page;
        return $this->db->query('SELECT SQL_CALC_FOUND_ROWS id, "'.$this->data['user']['username'].'" as username FROM user_dressups WHERE uid='.$this->data['user']['id'].' AND comments>0 ORDER BY comments DESC LIMIT '.$begin.', '.$for_page)->result_array();
    }
    public function get_recent_photo_comments($for_page, $page=0){
        $begin = $for_page * $page;
        $comments_all = $this->db->query('SELECT SQL_CALC_FOUND_ROWS CONCAT(upload_photo.id,upload_photo.rand_num) full_photo_id, photo_comments.comment, photo_comments.date date_comment, photo_comments.photo_id, users.username  FROM  (SELECT photo_comments.* FROM photo_comments LEFT JOIN upload_photo ON upload_photo.id=photo_comments.photo_id WHERE upload_photo.uid = "'.$this->data['user']['id'].'" ORDER BY photo_comments.date DESC) photo_comments LEFT JOIN users ON users.id = photo_comments.uid LEFT JOIN upload_photo ON upload_photo.id = photo_comments.photo_id  GROUP BY photo_comments.photo_id  ORDER BY photo_comments.date DESC LIMIT '.$begin.', '.$for_page)->result_array();
        return $comments_all;
    }
    public function get_recent_dressup_comments($for_page, $page=0){
        $begin = $for_page * $page;
        $comments_all = $this->db->query('SELECT SQL_CALC_FOUND_ROWS dressup_comments.dressup_id, dressup_comments.comment, dressup_comments.date date_comment, users.username FROM  (SELECT dressup_comments.* FROM dressup_comments LEFT JOIN user_dressups ON user_dressups.id=dressup_comments.dressup_id WHERE user_dressups.uid = "'.$this->data['user']['id'].'" ORDER BY dressup_comments.date DESC) dressup_comments LEFT JOIN users ON users.id = dressup_comments.uid  GROUP BY dressup_comments.dressup_id  ORDER BY dressup_comments.date DESC LIMIT '.$begin.', '.$for_page)->result_array();
        return $comments_all;
    }
    public function get_recent_photo_likes($for_page, $page=0){
        $begin = $for_page * $page;
        $likes_all = $this->db->query('SELECT CONCAT(id,rand_num) photo_id, like_users, upload_photo.image_type FROM upload_photo WHERE uid="'.$this->data['user']['id'].'" AND like_users!="" ORDER BY last_like DESC LIMIT '.$begin.', '.$for_page)->result_array();
        $last_likes = array();
        for($i=0; $i<count($likes_all); $i++){
            $users_like=explode(',',$likes_all[$i]['like_users']);
            $last_user = array_pop($users_like);
            $userinf = explode('_', $last_user, 2);
            $username = $userinf[1];
            
            $last_likes[] = array('photo_id'=>$likes_all[$i]['photo_id'], 'username'=>$username);
        }
        return $last_likes;
    }
    public function get_recent_dressup_likes($for_page, $page=0){
        $begin = $for_page * $page;
        $likes_all = $this->db->query('SELECT id dressup_id, like_users FROM user_dressups WHERE uid="'.$this->data['user']['id'].'" AND like_users!="" ORDER BY last_like DESC LIMIT '.$begin.', '.$for_page)->result_array();
        $last_likes = array();
        for($i=0; $i<count($likes_all); $i++){
            $users_like=explode(',',$likes_all[$i]['like_users']);
            $last_user = array_pop($users_like);
            $userinf = explode('_', $last_user, 2);
            $username = $userinf[1];
            
            $last_likes[] = array('dressup_id'=>$likes_all[$i]['dressup_id'], 'username'=>$username);
        }
        return $last_likes;
    }
    public function get_favorite_brands($for_page, $page=0){
        $begin = $for_page * $page;
        return $this->db->query('SELECT SQL_CALC_FOUND_ROWS imagename, title FROM user_brand LEFT JOIN brands ON brand_id = brands.id WHERE uid='.$this->data['user']['id'].' LIMIT '.$begin.', '.$for_page)->result_array();
    }
    
    public function req_to_you_friends($count=FAlSE, $for_page=0, $page=0){
        if($count){
            $result = $this->db->query('SELECT users.id FROM user_friends LEFT JOIN users ON user_friends.uid = users.id WHERE status=0 AND friend_id='.$this->data['user']['id'].' ORDER BY adding DESC')->result_array();
            return count($result);
        }else{
            $begin = $for_page * $page;
            $result = $this->db->query('SELECT SQL_CALC_FOUND_ROWS users.username, users.id, adding FROM user_friends LEFT JOIN users ON user_friends.uid = users.id WHERE status=0 AND friend_id='.$this->data['user']['id'].' ORDER BY adding DESC LIMIT '.$begin.', '.$for_page)->result_array();
            return $result;
        }
    }
    public function req_you_to_add_friend($count=FAlSE, $for_page=0, $page=0){
        if($count){
            $result = $this->db->query('SELECT users.id FROM user_friends LEFT JOIN users ON user_friends.friend_id = users.id WHERE status=0 AND uid='.$this->data['user']['id'].' ORDER BY adding DESC')->result_array();
            return count($result);
        }else{
            $begin = $for_page * $page;
            $result = $this->db->query('SELECT SQL_CALC_FOUND_ROWS users.username, users.id, adding FROM user_friends LEFT JOIN users ON user_friends.friend_id = users.id WHERE status=0 AND uid='.$this->data['user']['id'].' ORDER BY adding DESC LIMIT '.$begin.', '.$for_page)->result_array();
            return $result;
        }
    }
    public function my_recently_added_friends($for_page, $page=0){
        $begin = $for_page * $page;
        return $this->db->query('SELECT SQL_CALC_FOUND_ROWS users.username, users.id, adding FROM user_friends LEFT JOIN users ON IF(user_friends.uid = "'.$this->data['user']['id'].'", user_friends.friend_id, user_friends.uid ) = users.id WHERE removed = 0 AND status = 1 AND (uid='.$this->data['user']['id'].' OR friend_id='.$this->data['user']['id'].')'.' ORDER BY adding DESC LIMIT '.$begin.', '.$for_page)->result_array();
    }
    public function get_last_friends_photos($for_page, $page=0){
        $begin = $for_page * $page;
        return $this->db->query('SELECT SQL_CALC_FOUND_ROWS CONCAT(upload_photo.id,upload_photo.rand_num) photo_id, users.id, users.username, upload_photo.image_type FROM upload_photo LEFT JOIN users ON upload_photo.uid=users.id WHERE upload_photo.uid IN (SELECT IF(user_friends.uid='.$this->data['user']['id'].',user_friends.friend_id, user_friends.uid) FROM user_friends WHERE status=1 AND removed=0) ORDER BY upload_photo.date DESC LIMIT '.$begin.', '.$for_page)->result_array();
    }
    public function get_last_friends_dressups($for_page, $page=0){
        $begin = $for_page * $page;
        return $this->db->query('SELECT SQL_CALC_FOUND_ROWS user_dressups.id dressup_id, users.username FROM user_dressups LEFT JOIN users ON user_dressups.uid=users.id WHERE user_dressups.uid IN (SELECT IF(user_friends.uid='.$this->data['user']['id'].',user_friends.friend_id, user_friends.uid) FROM user_friends WHERE status=1 AND removed=0) ORDER BY user_dressups.date DESC LIMIT '.$begin.', '.$for_page)->result_array();
    }
    public function recent_photos_i_likes($for_page, $page=0){
        $begin = $for_page * $page;
        return $this->db->query('SELECT SQL_CALC_FOUND_ROWS CONCAT(upload_photo.id,upload_photo.rand_num) photo_id, users.username, upload_photo.uid, upload_photo.image_type FROM upload_photo LEFT JOIN users ON users.id = upload_photo.uid WHERE (like_users LIKE "'.$this->data['user']['id'].'_'.$this->data['user']['username'].'" OR like_users LIKE "%,'.$this->data['user']['id'].'_'.$this->data['user']['username'].'" OR like_users LIKE "'.$this->data['user']['id'].'_'.$this->data['user']['username'].',%" OR like_users LIKE "%,'.$this->data['user']['id'].'_'.$this->data['user']['username'].',%")  ORDER BY last_like DESC LIMIT '.$begin.', '.$for_page)->result_array();
    }
    public function recent_photos_i_commented($for_page, $page=0){
        $begin = $for_page * $page;
        return $this->db->query('SELECT SQL_CALC_FOUND_ROWS DISTINCT CONCAT(upload_photo.id,upload_photo.rand_num) photo_id, users.username, upload_photo.uid FROM (SELECT * FROM photo_comments WHERE uid='.$this->data['user']['id'].' ORDER BY `date` DESC) photo_comments LEFT JOIN upload_photo ON upload_photo.id = photo_comments.photo_id LEFT JOIN users ON users.id = upload_photo.uid LIMIT '.$begin.', '.$for_page)->result_array();
    }
    public function recent_dressup_i_likes($for_page, $page=0){
        $begin = $for_page * $page;
        return $this->db->query('SELECT SQL_CALC_FOUND_ROWS user_dressups.id dressup_id, users.username FROM user_dressups LEFT JOIN users ON users.id = user_dressups.uid WHERE (like_users LIKE "'.$this->data['user']['id'].'_'.$this->data['user']['username'].'" OR like_users LIKE "%,'.$this->data['user']['id'].'_'.$this->data['user']['username'].'" OR like_users LIKE "'.$this->data['user']['id'].'_'.$this->data['user']['username'].',%" OR like_users LIKE "%,'.$this->data['user']['id'].'_'.$this->data['user']['username'].',%")  ORDER BY last_like DESC LIMIT '.$begin.', '.$for_page)->result_array();
    }
    public function recent_dressup_i_commented($for_page, $page=0){
        $begin = $for_page * $page;
        return $this->db->query('SELECT SQL_CALC_FOUND_ROWS DISTINCT dressup_comments.dressup_id, users.username FROM (SELECT * FROM dressup_comments WHERE uid='.$this->data['user']['id'].' ORDER BY `date` DESC) dressup_comments LEFT JOIN user_dressups ON user_dressups.id = dressup_comments.dressup_id LEFT JOIN users ON users.id = user_dressups.uid LIMIT '.$begin.', '.$for_page)->result_array();
    }
    public function get_my_items_inventory($for_page, $page){
        $begin = $for_page * $page;
        return $this->db->query('SELECT item_id, COUNT(*) counts, dressup_items.* FROM user_items LEFT JOIN dressup_items ON dressup_items.id = user_items.item_id WHERE uid = "'.$this->data['user']['id'].'" AND removed="" GROUP BY item_id LIMIT '.$begin.', '.$for_page)->result_array();
        
    }
    public function gifts_i_received($for_page, $page){
        $begin = $for_page * $page;
        return $this->db->query('SELECT SQL_CALC_FOUND_ROWS dressup_items.*, users.id uid, users.username,user_gifts.date FROM user_gifts 
            LEFT JOIN users ON users.id=user_gifts.from 
            LEFT JOIN dressup_items ON dressup_items.id=(SELECT item_id FROM gifts WHERE id=user_gifts.gift)
            WHERE `to`="'.$this->user['id'].'" LIMIT '.$begin.', '.$for_page)->result_array();
    }
    public function gifts_i_sent($for_page, $page){
        $begin = $for_page * $page;
        return $this->db->query('SELECT SQL_CALC_FOUND_ROWS dressup_items.*, users.id uid, users.username,user_gifts.date FROM user_gifts 
            LEFT JOIN users ON users.id=user_gifts.to 
            LEFT JOIN dressup_items ON dressup_items.id=(SELECT item_id FROM gifts WHERE id=user_gifts.gift)
            WHERE `from`="'.$this->user['id'].'" LIMIT '.$begin.', '.$for_page)->result_array();
    }
    
    public function count_pages($for_page) {
        $count = $this->db->query('SELECT FOUND_ROWS() as result')->row()->result;
        return ceil($count / $for_page);
    }
}
?>
