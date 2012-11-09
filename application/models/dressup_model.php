<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dressup_model extends CI_Model {

    public function load_my_items($category) {
        $category = mysql_real_escape_string($category);

        $subcat = $this->db->query('SELECT * FROM dressup_category WHERE pid=(SELECT id FROM dressup_category WHERE shortname="'.$category.'")')->result_array();
        if(!empty($subcat)){
            $subcat_names = array();
            foreach($subcat as $val){
                $subcat_names[] = $val['shortname'];
            }
            $category_where = 'category IN ("' . implode('","', $subcat_names) . '")';
        }else{
            $category_where = 'category="' . $category . '"';
        }
        
        $items = $this->db->query('SELECT *,dressup_items.* FROM user_items 
            LEFT JOIN dressup_items ON dressup_items.id=user_items.item_id 
            WHERE uid="' . $this->user['id'] . '" 
                AND item_id IN (SELECT id FROM dressup_items WHERE '.$category_where.') 
                AND dressup_items.type!="haircolors" AND dressup_items.category!="default" 
                    GROUP BY item_id')->result_array();
        //ADD default items
        if (empty($items)) {
            $items = $this->db->query('SELECT * FROM dressup_items WHERE category="' . $category . '" AND category ="default"')->result_array();

            //add defaults body parts
            if (empty($items)) {
                //get skin
                if ($this->input->post('category') == 'skin') {
                    //all skins
                    $items_body = $this->db->query('SELECT * FROM dressup_body_parts WHERE `type` = "skincolor"')->result_array();
                } elseif ($this->input->post('category') == 'eyes' || $this->input->post('category') == 'mouth') {
                    //skin elements
                    $dressup = $this->session->userdata('dressup');
                    $skin = $dressup['doll']['skincolor'];
                    $items_body = $this->db->query('SELECT * FROM dressup_body_parts WHERE `type` ="' . mysql_real_escape_string($this->input->post('category')) . '" AND skincolor="' . $skin . '"')->result_array();
                }
                if (!empty($items_body)) {
                    foreach ($items_body as $val) {
                        $type = str_replace('files/', '', $val['directory']);
                        $items[] = array('id' => $val['name'], 'item_name' => $val['name'], 'profileimage_dir' => 'profilepics', 'profileimage' => $val['profileimage'], 'directory' => '../' . $type, 'type' => $type);
                    }
                }
            }
        }


        if (!empty($items)) {
            $this->load->view('ajax_block/dressup_item_list', array('items' => $items));
        }
    }

    public function lookday_clear() {
        $this->db->query('UPDATE user_dressups SET day_look=0 WHERE uid="' . $this->user['id'] . '"');
    }

    public function get_outfits() {
        return $this->db->query('SELECT * FROM user_dressups WHERE uid="' . $this->user['id'] . '" AND outfit=1 ORDER BY id DESC')->result_array();
    }

    public function get_dressups($for_page, $page = 0) {
        $begin = $page * $for_page;
        return $this->db->query('SELECT SQL_CALC_FOUND_ROWS *,user_dressups.* FROM user_dressups WHERE uid="' . $this->user['id'] . '" AND outfit=0 ORDER BY id DESC LIMIT ' . $begin . ',' . $for_page)->result_array();
    }

    public function wear_outfit($id) {
        $this->lookday_clear();
        $this->db->query('UPDATE user_dressups SET day_look=1 WHERE id="' . $id . '"');
    }

    public function dressup_items_date($date, $uid) {
        $rez = array();
        $all = $this->db->query('SELECT user_dressups.*,users.username FROM user_dressups LEFT JOIN users ON users.id=user_dressups.uid WHERE DATE_FORMAT(`date`,"%Y-%m-%d")="' . $date . '" AND uid="' . $uid . '" AND outfit=0 ORDER BY user_dressups.id DESC')->result_array();
        if (!empty($all)) {
            foreach ($all as $val) {
                $rez[] = array('id' => $val['id'], 'date' => time_from($val['date']), 'username' => $val['username'], 'name' => $val['name'], 'comment' => $val['comment']);
            }
        }
        return $rez;
    }

    public function get_daylook($uid) {
        return $this->db->query('SELECT * FROM user_dressups WHERE uid="' . $uid . '" AND day_look=1')->row_array();
    }

    public function calendat_available_days($date, $uid) {
        $days = $this->db->query('SELECT DISTINCT DATE_FORMAT(`date`, "%e") `day` FROM user_dressups WHERE DATE_FORMAT(`date`, "%Y-%m")=DATE_FORMAT("' . $date . '", "%Y-%m") AND uid="' . $uid . '" AND outfit=0')->result();
        if (!empty($days)) {
            $all = array();
            foreach ($days as $val) {
                $all[] = $val->day;
            }
            return $all;
        }
    }

    public function dressup_details($id) {
        return $this->db->query('SELECT user_dressups.*,users.username FROM user_dressups LEFT JOIN users ON users.id=user_dressups.uid WHERE user_dressups.id="' . $id . '"')->row_array();
    }

    public function item_detail($id, $col = 'id') {
        return $this->db->query('SELECT * FROM dressup_items WHERE `' . $col . '`="' . $id . '"')->row_array();
    }

    public function add_dressup_comment($id) {
        $comment = trim(strip_tags($this->input->post('comment')));
        if (!empty($comment)) {
            $comment_data = array(
                'dressup_id' => $id,
                'uid' => $this->user['id'],
                'comment' => $comment,
                'date' => date("Y-m-d H:i:s")
            );
            $this->db->insert('dressup_comments', $comment_data);
            $this->db->query('UPDATE user_dressups SET last_comment = "' . date('Y-m-d H:i:s') . '", comments=comments+1 WHERE id = ' . $id);
        }
    }

    public function count_pages($for_page) {
        $count = $this->db->query('SELECT FOUND_ROWS() as result')->row()->result;
        return ceil($count / $for_page);
    }

    public function get_latest_dressups($uid, $for_page, $page) {
        $begin = $for_page * $page;
        return $this->db->query('SELECT SQL_CALC_FOUND_ROWS user_dressups.id, users.username, `like`, 
            (SELECT COUNT(id) FROM dressup_comments WHERE dressup_id = user_dressups.id) comment 
            FROM user_dressups 
            LEFT JOIN users ON users.id=uid 
            WHERE uid = "' . $uid . '" AND outfit=0 ORDER BY id DESC LIMIT ' . $begin . ',' . $for_page)->result_array();
    }

    public function all_latest_dressups($for_page, $page=0) {
        $begin = $for_page * $page;
        return $this->db->query('SELECT SQL_CALC_FOUND_ROWS user_dressups.id, users.username, `like`, 
            (SELECT COUNT(id) FROM dressup_comments WHERE dressup_id = user_dressups.id) comment 
            FROM user_dressups 
            LEFT JOIN users ON users.id=uid 
            WHERE outfit=0 AND used_items!="53,67" ORDER BY id DESC LIMIT ' . $begin . ',' . $for_page)->result_array();
    }

    public function get_dressup_comments($for_page, $page, $id) {
        $begin = $for_page * $page;
        return $this->db->query('SELECT SQL_CALC_FOUND_ROWS *, users.username, dressup_comments.id comment_id FROM dressup_comments LEFT JOIN users ON dressup_comments.uid = users.id WHERE dressup_id=' . $id . ' ORDER BY dressup_comments.id DESC LIMIT ' . $begin . ',' . $for_page)->result_array();
    }

    public function share_email_dressup($id, $emails, $mode) {
        $emails = explode(',', $emails);
        $rez = array('err' => array(), 'ok' => array());
        if (!empty($emails)) {
            foreach ($emails as $val) {
                $val = trim(strtolower($val));
                if (!preg_match("/^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$/i", $val)) {
                    $rez['err'][] = $val . ' - Email is not correct';
                } else {
                    if ($mode == 'dressup') {
                        $this->send_share_mail($val, $this->user['username'] . ' has shared a dressup with you', 'share_dressup', $id);
                    } elseif ($mode == 'upload') {
                        $this->send_share_mail($val, $this->user['username'] . ' has shared a photo with you', 'share_photo', $id);
                    }
                    $rez['ok'][] = $val . ' - Email sent';
                }
            }
        }
        return $rez;
    }

    public function send_share_mail($email, $subject, $file_content, $dressup_id) {
        $message_text = $this->load->view('email/' . $file_content, array('username' => $this->user['username'], 'uid' => $this->user['id'], 'id' => $dressup_id), true);
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: Perfect-Look.Org <admin@perfect-look.org>' . "\r\n";
        return mail($email, $subject, $message_text, $headers);
    }

    public function send_share_fb($id, $description, $mode) {
        require(APPPATH . 'libraries/facebook/facebook.php');
        $this->load->config('social');
        $facebook = new Facebook(array(
                    'appId' => $this->config->item('fbAppId'),
                    'secret' => $this->config->item('fbSecret'),
                    'cookie' => true
                ));
        $user = $facebook->getUser();
        if ($user) {
            if ($mode == 'dressup') {
                $attachment = array(
                    'message' => 'Check out this dressup',
                    'name' => 'New perfect-look.org dressup',
                    'link' => 'http://' . $_SERVER['SERVER_NAME'] . '/' . $this->user['username'] . '/dressup/' . $id,
                    'description' => $description,
                    'picture' => 'http://' . $_SERVER['SERVER_NAME'] . '/files/users/dressup/' . $id . '.jpg',
                    'access_token' => $this->user['fb_access']
                );
            } elseif ($mode == 'upload') {
                $attachment = array(
                    'message' => 'Check out this photo',
                    'name' => 'New perfect-look.org photo',
                    'link' => 'http://' . $_SERVER['SERVER_NAME'] . '/' . $this->user['username'] . '/photo/' . $id,
                    'description' => $description,
                    'picture' => 'http://' . $_SERVER['SERVER_NAME'] . '/files/users/uploads/' . $this->user['id'] . '/' . $id . '.jpg',
                    'access_token' => $this->user['fb_access']
                );
            }

            if (!($sendMessage = $facebook->api('/me/feed/', 'post', $attachment))) {
                $errors = error_get_last();
                return array('err' => 'Facebook publish error: ' . $errors['type']);
            } else {
                return;
            }
        } else {
            $this->session->set_userdata(array('last_page' => $_SERVER['REQUEST_URI'])); // Last page for back redirect
            return array('err' => 'not logined');
        }
    }

    public function send_share_tw($id, $description, $mode) {
        $this->session->set_userdata(array('last_page' => $_SERVER['REQUEST_URI'])); // Last page for back redirect
        require_once APPPATH . 'libraries/twitter/twitteroauth.php';
        $this->load->config('social');
        $tw_token = $this->user['tw_token'];
        $tw_secret = $this->user['tw_secret'];

        if (!empty($tw_secret)) {
            $connection = new TwitterOAuth($this->config->item('twConsumerKey'), $this->config->item('twConsumerSecret'), $tw_token, $tw_secret);
            $content = $connection->get('account/verify_credentials');
            if (!empty($content->id)) {
                $this->load->model('explore_model');
                if ($mode == 'dressup') {
                    $url = 'http://' . $_SERVER['SERVER_NAME'] . '/' . $this->user['username'] . '/dressup/' . $id;
                } elseif ($mode == 'upload') {
                    $url = 'http://' . $_SERVER['SERVER_NAME'] . '/' . $this->user['username'] . '/photo/' . $id;
                }
                $url = $this->explore_model->short_url($url);
                $connection->post('statuses/update', array('status' => $description . ' Caption:' . $url));
                return;
            } else {
                return array('err' => 'not logined');
            }
        } else {
            return array('err' => 'not logined');
        }
    }

    public function like_add($id) {
        $dressup = $this->db->get_where('user_dressups', array('id' => $id))->row_array();
        $liked_users = empty($dressup['like_users']) ? array() : explode(',', $dressup['like_users']);
        if (!in_array($this->user['id'] . '_' . $this->user['username'], $liked_users)) {
            $liked_users[] = $this->user['id'] . '_' . $this->user['username'];
            $this->db->query('UPDATE user_dressups SET `like`=`like`+1, like_users="' . mysql_real_escape_string(implode(',', $liked_users)) . '", last_like="' . date('Y-m-d H:i:s') . '" WHERE id="' . $id . '"');
            return ;
        } else {
            return array('err' => 'You have already voted');
        }
    }

    public function last_hearted() {
        $hearts = $this->db->query('SELECT *, "dressup" `type`, UNIX_TIMESTAMP(last_like) date_unix FROM user_dressups WHERE `like`!=0 AND uid="' . $this->user['id'] . '" AND `last_like` > "' . date('Y-m-d H:i:s') . '" - INTERVAL 1 day')->result_array();
        return $hearts;
    }

    public function get_all_last_hearted($limit, $page=1)
    {
        $offset = $limit * ($page - 1);
        $sql = 'SELECT user_dressups.*, "dressup" `type`, UNIX_TIMESTAMP(last_like) date_unix,users.username
        FROM user_dressups
        LEFT JOIN users ON users.id=user_dressups.uid
        WHERE `like`!=0  AND used_items!="53,67" ORDER BY `last_like` DESC LIMIT '.$offset.','.$limit;
        return $this->db->query($sql)->result_array();
    }

    public function last_like_count() {
        $dress = $this->db->query('SELECT id FROM user_dressups WHERE last_like > "' . date('Y-m-d H:i:s') . '" - INTERVAL 1 DAY AND uid="' . $this->user['id'] . '"')->num_rows();
        $photo = $this->db->query('SELECT id FROM upload_photo WHERE last_like > "' . date('Y-m-d H:i:s') . '" - INTERVAL 1 DAY AND uid="' . $this->user['id'] . '"')->num_rows();
        return ($dress + $photo);
    }

    public function last_comments_count() {
        $dress = $this->db->query('SELECT id FROM user_dressups WHERE last_comment > "' . date('Y-m-d H:i:s') . '" - INTERVAL 1 DAY AND uid="' . $this->user['id'] . '"')->num_rows();
        $photo = $this->db->query('SELECT id FROM upload_photo WHERE last_comment > "' . date('Y-m-d H:i:s') . '" - INTERVAL 1 DAY AND uid="' . $this->user['id'] . '"')->num_rows();
        return ($dress + $photo);
    }

    public function last_commented() {
        $comments = $this->db->query('SELECT *, "dressup" `type`, UNIX_TIMESTAMP(last_comment) date_unix FROM user_dressups WHERE `last_comment`!="0000-00-00 00:00:00" AND uid="' . $this->user['id'] . '" AND `last_comment` > "' . date('Y-m-d H:i:s') . '" - INTERVAL 1 day')->result_array();
        return $comments;
    }

    public function get_all_last_commented($limit, $page=1)
    {
        $offset = $limit * ($page - 1);
        $sql = 'SELECT user_dressups.*, "dressup" `type`, UNIX_TIMESTAMP(last_comment) date_unix,users.username
        FROM user_dressups
        LEFT JOIN users ON users.id=user_dressups.uid
        WHERE `last_comment`!="0000-00-00 00:00:00" AND used_items!="53,67" ORDER BY `last_comment` DESC LIMIT '.$offset.','.$limit;
        return $this->db->query($sql)->result_array();
    }

    public function last_commented_details($dressups) {
        $all = array();
        if (!empty($dressups)) {
            foreach ($dressups as $val) {
                $all_id[] = $val['id'];
            }
            $rez = $this->db->query('SELECT * FROM (SELECT dressup_comments.*, users.username FROM dressup_comments LEFT JOIN users on users.id=dressup_comments.uid WHERE dressup_id IN (' . implode(',', $all_id) . ') ORDER BY id DESC) d1 GROUP BY dressup_id')->result_array();
            if (!empty($rez)) {
                foreach ($rez as $val) {
                    $all[$val['dressup_id']] = $val;
                }
            }
        }
        return $all;
    }

    public function remove_outfit($id) {
        $rez = $this->db->get_where('user_dressups', array('uid' => $this->user['id'], 'id' => $id))->row_array();
        if (!empty($rez)) {
            $this->_change_item_status($rez['used_items'], 'dress');
            $this->db->query('DELETE FROM user_dressups WHERE id="' . $id . '"');
            unlink(APPPATH . 'files/users/dressup/' . $id . '.jpg');
        }
    }

    private function _change_item_status($items, $old_status, $status = '') {
        $used_items = explode(',', $items);
        foreach ($used_items as $val) {
            $this->db->query('UPDATE user_items SET status="' . $status . '" WHERE uid="' . $this->user['id'] . '" AND item_id="' . $val . '" AND status="' . $old_status . '" LIMIT 1');
        }
    }

    public function duplicate_dressup($id) {
        $old = $this->dressup_details($id);
        unset($old['id']);
        unset($old['username']);
        $old['day_look'] = 0;
        $this->db->insert('user_dressups', $old);
        $new_id = $this->db->insert_id();
        copy(APPPATH . 'files/users/dressup/' . $id . '.jpg', APPPATH . 'files/users/dressup/' . $new_id . '.jpg');
    }

    public function top_10_dressups() {
        return $this->top_dressups(10);
    }

    public function top_dressups($limit, $page = 1)
    {
        $offset = $limit * ($page-1);

        $sql = "SELECT
                user_dressups.*,
                users.username,
                (SELECT COUNT(1) FROM dressup_comments WHERE dressup_comments.dressup_id=user_dressups.id) comments
                FROM user_dressups
                LEFT JOIN users ON users.id=user_dressups.uid
                ORDER BY `like` DESC LIMIT $offset,$limit";

        return $this->db->query($sql)->result_array();

    }




    /* ----- NEW version ------- */

    public function admin_add_item() {
//        $files = $this->input->post('files');
//        $values = $this->input->post('values');
//        $position = $this->input->post('position');
//        $pose = $this->input->post('pose');
//        $title = $this->input->post('title');
//        $category = $this->input->post('category');
//        $tightness = $this->input->post('tightness');
//        $squeeze = $this->input->post('squeeze');
//        $folder = $this->input->post('folder');
//
//        $id = $this->db->query('SELECT id FROM dressup_items WHERE title="' . $title . '"')->row()->id;
//        if (empty($id)) {
//            $this->db->insert('dressup_items', array('category' => $category, 'title' => $title, 'tightness' => strtolower($tightness)));
//            $id = $this->db->insert_id();
//        }
//
//        if (!empty($files)) {
//            foreach ($files as $key => $val) {
//
//                if ($val == 'preview') {
//                    $this->db->where('id', $id);
//                    $this->db->update('dressup_items', array('preview' => $folder . '/' . $key . '.png'));
//                } else {
//                    $def_val = ($val == 'back') ? 1 : 'default';
//                    $type_val = (!empty($values[$key])) ? $values[$key] : $def_val;
//                    $pose_val = (!empty($pose[$key])) ? $pose[$key] : 0;
//                    $squeeze_val = (!empty($squeeze[$key])) ? $squeeze[$key] : 0;
//                    $ins_array = array(
//                        'id' => $id,
//                        'pose' => $pose_val,
//                        'part' => $position[$key],
//                        'img' => $key . '.png',
//                        'folder' => $folder,
//                        'squeeze' => $squeeze_val
//                    );
//                    if ($val != 'default') {
//                        $ins_array[$val] = $type_val;
//                    }
//                    $this->db->insert('dressup_layers', $ins_array);
//                }
//            }
//        }
    }

    public function show_items($id = NULL) {
        $this->session->set_userdata(array('last_page' => $_SERVER['REQUEST_URI'])); // Last page for back redirect

        require_once APPPATH . 'libraries/Dressup.php';
        $dressup = new Dressup_lib();

        if (!empty($id) && !(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == "XMLHttpRequest")) {
            $dress = $this->db->query('SELECT * FROM user_dressups WHERE id="' . $id . '"')->row_array();
            $dressup->clear_doll();
            if (!empty($dress['doll'])) {
                $dressup->doll = unserialize($dress['doll']);
            }
            $dressup->add_items(explode(',', $dress['used_items']));
        } elseif (!(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == "XMLHttpRequest")) {
            $dressup->clear_doll();
            $dressup->create_default();
        }
        $view = $dressup->result_view();
        return array('items' => $view['items'], 'code' => $view['code'], 'doll' => $view['doll']);
    }

    public function add_item($item_id) {
        require_once APPPATH . 'libraries/Dressup.php';
        $dressup = new Dressup_lib();
        $tmp_items = array_keys($dressup->items);
        $tmp_items[] = $item_id;
        $dressup->clear_doll();
        $dressup->add_items($tmp_items);
        $view = $dressup->result_view();

        return array('code' => $view['code'], 'items' => $view['items']);
    }

    public function delete_item($item_id) {
        require_once APPPATH . 'libraries/Dressup.php';
        $dressup = new Dressup_lib();
        $dressup->remove_item($item_id);
        $view = $dressup->result_view();
        return array('code' => $view['code'], 'items' => $view['items']);
    }

    public function sort_items($items) {
        require_once APPPATH . 'libraries/Dressup.php';
        $dressup = new Dressup_lib();
        $dressup->sort_items($items);
        $view = $dressup->result_view();
        return array('code' => $view['code'], 'items' => $view['items']);
    }

    public function save_look($outfit = 0, $dressup = NULL, $uid = NULL, $edit = NULL) {
        require_once APPPATH . 'libraries/Dressup.php';
        if (empty($dressup)) {
            $dressup = new Dressup_lib();
        }
        if (empty($uid)) {
            $uid = $this->user['id'];
        }
        $view = $dressup->result_view();

        if (!empty($view['items'])) {
            foreach ($view['items'] as $val) {
                $items[] = $val['id'];
            }
        }

        $day_look = ($this->input->post('day_look') == 1) ? 1 : 0;
        if ($day_look == 1) {
            $this->db->query('UPDATE user_dressups SET day_look=0 WHERE day_look=1 AND uid="' . $uid . '"');
        }
        $name = strip_tags($this->input->post('name'));
        $comment = strip_tags($this->input->post('comment'));


        if (empty($edit)) {
            $this->db->query('INSERT INTO user_dressups (uid, used_items, doll, `date`,day_look,outfit,`name`, dress_comment) 
            VALUES ("' . $uid . '", "' . implode(',', $items) . '", "' . mysql_real_escape_string(serialize($dressup->doll)) . '", "' . date('Y-m-d H:i:s') . '", 
                "' . $day_look . '","' . $outfit . '", "' . mysql_real_escape_string($name) . '", "' . mysql_real_escape_string($comment) . '")');
            $id = $this->db->insert_id();
        } else {

            $id = $edit;
            unset($dressup->doll['background']);
            $this->db->query('UPDATE user_dressups SET doll="' . mysql_real_escape_string(serialize($dressup->doll)) . '" WHERE id="' . $id . '"');
        }


        //get background
        $bg = $this->db->get_where('dressup_items', array('id' => $view['doll']['background_id']))->row_array();

        $room = imagecreatefromjpeg(FILES . 'items/' . $bg['directory'] . '/' . $bg['files']);
        if (!empty($view['code'])) {
            foreach ($view['code'] as $layer) {
                $lay = urldecode(FILES . $layer);

                if (is_file($lay)) {

                    $layer_img = imagecreatefrompng($lay);
                    $size = getimagesize($lay);
                    ImageCopyResampled($room, $layer_img, 0, 30, 0, 0, $size[0], $size[1], $size[0], $size[1]);
                    ImageDestroy($layer_img);
                } else {
                    //error, file not found
                }
            }
            ImageJPEG($room, FILES . 'users/dressup-HD/' . $id . '.jpg', 100);

            //create small image
            require_once APPPATH . 'libraries/ImageHandler.php';
            $ImageHandler = new ImageHandler();
            $ImageHandler->load(FILES . 'users/dressup-HD/' . $id . '.jpg')->resize(500, 350)->save(FILES . 'users/dressup/' . $id . '.jpg',2,100);
        }

        //add buttons
        if (empty($edit)) {
            $this->load->library('buttons');
            $this->buttons->add_money($this->user['id'], 10);
            $this->buttons->write_history($this->user['id'], array('action' => 'new_dressup', 'buttons' => $this->user['buttons'], 'now_buttons' => $this->user['buttons'] + 10, 'description' => 'Added new dressup'));

            if (!empty($uid)) {
                $this->load->model('user_model');
            }
            $this->user_model->write_history_activity(((!empty($uid)) ? $uid : $this->user['id']), 'dressup', $id);
        }

        return array('id' => $id);
    }

    public function featured_dressups($for_page, $page) {
        $begin = intval($page * $for_page);
        return $this->db->query('SELECT SQL_CALC_FOUND_ROWS * FROM featured WHERE type="dressup" ORDER BY id DESC LIMIT ' . $begin . ', ' . $for_page)->result_array();
    }

    public function change_bg($bg_id) {
        require_once APPPATH . 'libraries/Dressup.php';
        $dressup = new Dressup_lib();
        $dressup->change_bg($bg_id);
        return array('img' => $dressup->doll['background']);
    }

    public function change_hair($hair_id) {
        require_once APPPATH . 'libraries/Dressup.php';
        $dressup = new Dressup_lib();
        $dressup->change_hair($hair_id);
        $view = $dressup->result_view();
        $colors = $dressup->find_haircolors();
        return array('code' => $view['code'], 'items' => $view['items'], 'colors' => $colors);
    }

    public function find_today_dressup($uid) {
        return $this->db->query('SELECT 1 FROM user_dressups WHERE day_look=1 AND uid="' . $uid . '" and DATE_FORMAT(date,"%Y-%m-%d")="' . date('Y-m-d') . '"')->num_rows();
    }

    public function change_skin($item) {
        require_once APPPATH . 'libraries/Dressup.php';
        $dressup = new Dressup_lib();
        $dressup->change_skin($item);
        $view = $dressup->result_view();
        return array('code' => $view['code'], 'items' => $view['items']);
    }

    public function change_face($item) {
        require_once APPPATH . 'libraries/Dressup.php';
        $dressup = new Dressup_lib();
        $dressup->change_face($item);
        $view = $dressup->result_view();
        return array('code' => $view['code'], 'items' => $view['items']);
    }

    public function change_arm_layer($layer) {
        require_once APPPATH . 'libraries/Dressup.php';
        $dressup = new Dressup_lib();
        $dressup->change_arm_layer($layer);
        $view = $dressup->result_view();
        return array('code' => $view['code'], 'items' => $view['items']);
    }

    public function remove_comment($id) {
        $photo = $this->db->query('SELECT * FROM dressup_comments WHERE uid="' . $this->user['id'] . '" AND id = "' . mysql_real_escape_string($id) . '"')->row_array();
        if (!empty($photo)) {
            $this->db->query('DELETE FROM dressup_comments WHERE id = "' . mysql_real_escape_string($id) . '"');
            $this->db->query('UPDATE user_dressups SET comments = comments-1 WHERE id="' . $photo['dressup_id'] . '"');
        }
    }

}
