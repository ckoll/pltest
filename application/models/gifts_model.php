<?

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Gifts_model extends CI_Model {
    
    public function count_pages($for_page) {
        $count = $this->db->query('SELECT FOUND_ROWS() as result')->row()->result;
        return ceil($count / $for_page);
    }
    
    public function get_gifts($for_page, $page) {
        $begin = $for_page * $page;
        $gifts = array();
        $all = $this->db->query('SELECT SQL_CALC_FOUND_ROWS dressup_items.*, gifts.id gift_id FROM gifts LEFT JOIN dressup_items ON dressup_items.id=gifts.item_id ORDER BY gifts.id LIMIT '.$begin.', '.$for_page)->result_array();
        if(!empty($all))
        foreach($all as $val){
            $gifts[$val['gift_id']] = $val;
        }
        return $gifts;
    }
    
    public function user_friends() {
        return $this->db->query('SELECT user_friends.adding, users.* FROM user_friends LEFT JOIN users ON IF(user_friends.uid="' . $this->user['id'] . '", user_friends.friend_id = users.id, user_friends.uid = users.id) WHERE (friend_id="' . $this->user['id'] . '" OR uid = "' . $this->user['id'] . '") AND status=1 AND removed=0 ORDER BY adding DESC ')->result_array();
    }
    
    public function send_gifts() {
        $this->load->model('home_model');

        $to_users = $_REQUEST['to_users'];
        $gift_id = $_REQUEST['gift_id'];

        $alredy_sended_res = $this->db->query('SELECT `to` FROM `user_gifts` WHERE `date` LIKE "' . date('Y-m-d') . '%" AND `from`="' . $this->user['id'] . '" AND `to` IN ("' . implode('","', $to_users) . '") ')->result_array();
        $alredy_sended = array();
        if(!empty($alredy_sended_res))
        foreach ($alredy_sended_res as $v) {
            $alredy_sended[] = $v['to'];
        }

        $send_to_diff = array_diff($to_users, $alredy_sended);
        $send_to = array();
        if(!empty($send_to_diff))
        foreach ($send_to_diff as $k => $v)
            $send_to[] = $v;

        if (count($send_to)) {

            $data = array();
            foreach ($send_to as $to_user) {
                $data[] = array(
                    'from' => $this->user['id'],
                    'to' => $to_user,
                    'gift' => $gift_id,
                    'date' => date("Y-m-d H:i:s")
                );

                //Send notification
                $this->home_model->send_notification($to_user, 'notif_received_gifts', ' You have received a gift from '.$this->user['username'].' at Perfect-Look.org', 'received_gift', array('username' => $this->user['username']));
            }

            $this->db->insert_batch('user_gifts', $data);
            //Add item sended to user
            $item_id = $this->db->get_where('gifts',array('id'=>$gift_id))->row()->item_id;
            $this->db->query('INSERT INTO user_items (uid, item_id) VALUES ("'.$to_user.'","'.$item_id.'")');
            
            $sended_to_users = $this->user_model->users_for_ajax($send_to);
        }

        if (count($alredy_sended)) {
            $no_sended_to_users = $this->user_model->users_for_ajax($alredy_sended);
        }

        return array('sended' => $sended_to_users, 'no_sended' => $no_sended_to_users);
    }

    public function add_gift() {
//        $data = array(
//            'name' => trim($this->input->post('name')),
//            'price' => trim($this->input->post('price'))
//        );
//        $this->db->insert('gifts', $data);
//        $id = $this->db->insert_id();
//        $this->load->library('Image_moo');
//        $this->image_moo->load($_FILES['img']['tmp_name'])->resize_crop(100, 100)->set_jpeg_quality('100')->save(APPPATH . 'files/gifts/' . $id . '.jpg', TRUE);
    }

    public function remove_gift($id) {
//        $this->db->where('id', $id);
//        $this->db->delete('gifts');
//        @unlink(APPPATH . 'files/gifts/' . $id . '.jpg');
//        redirect('/admin/gifts');
    }
    
    public function sended_today() {
        $all = array();
        $sended = $this->db->query('SELECT `to` FROM user_gifts WHERE `from`="' . $this->user['id'] . '" AND DATE_FORMAT(`date`,"%Y.%m.%d")="'.  date('Y-m-d H:i:s').'"')->result_array();
        if (!empty($sended)) {
            foreach ($sended as $val) {
                $all[] = $val['to'];
            }
        }
        return $all;
    }
    
    public function get_my_gifts($for_page, $page){
        $begin = $for_page * $page;
        return $this->db->query('SELECT SQL_CALC_FOUND_ROWS dressup_items.*, users.id uid, users.username,user_gifts.date FROM user_gifts 
            LEFT JOIN users ON users.id=user_gifts.from 
            LEFT JOIN dressup_items ON dressup_items.id=(SELECT item_id FROM gifts WHERE id=user_gifts.gift)
            WHERE `to`="'.$this->user['id'].'" LIMIT '.$begin.', '.$for_page)->result_array();
    }

}