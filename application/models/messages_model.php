<?

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Messages_model extends CI_Model {

    public $user;

    public function __construct() {
        parent::__construct();
        $this->user = $this->session->userdata('user');
    }

    public function get_message_icons() {
        return $this->db->get('message_icons')->result_array();
    }

    public function message_send() {
        $curdatetime = new DateTime();
        $subject = ($this->input->post('subject')) ? $this->input->post('subject') : '...';
        $icon = ($this->input->post('icon')) ? $this->input->post('icon') : 'mess';
        if ($this->input->post('members')) {
            foreach ($this->input->post('members') as $user) {
                $text = strip_tags($this->input->post('text'), '<img><strong><em><span><br>');
                $data = array(
                    'from' => $this->user['id'],
                    'to' => $user,
                    'subject' => $subject,
                    'text' => $text,
                    'date' => $curdatetime->format('Y-m-d H:i:s'),
                    'img' => $icon
                );
                $id = $this->db->insert('messages', $data);
            }

            $emailData = array(
                'user' => $this->user,
                'text' => $text,
                'id' => $id
            );
            $this->home_model->send_notification($user, 'notif_received_poms_message', ' You have a new private message from '.$this->user['username'].' at Perfect-Look.org', 'received_pms_message', $emailData);

        }


    }

    public function get_messages($folder, $page = 1) {
        switch ($folder) {
            case 'inbox':
                return $this->db->query('SELECT messages.*,users.username,users.fb_fullname,users.tw_fullname,users.id uid FROM messages LEFT JOIN users ON users.id=messages.from WHERE messages.to="' . $this->user['id'] . '" AND messages.removed="0" ORDER BY messages.id DESC LIMIT ' . (($page - 1) * 20) . ',20')->result_array();
                break;
            case 'sent':
                return $this->db->query('SELECT messages.*,users.username,users.fb_fullname,users.tw_fullname,users.id uid FROM messages LEFT JOIN users ON users.id=messages.to WHERE messages.from="' . $this->user['id'] . '" AND messages.removed="0" ORDER BY messages.id DESC LIMIT ' . (($page - 1) * 20) . ',20')->result_array();
                break;
            case 'deleted':
                return $this->db->query('SELECT messages.*,users.username,users.fb_fullname,users.tw_fullname,users.id uid FROM messages LEFT JOIN users ON (users.id=messages.to ) WHERE (messages.from="' . $this->user['id'] . '" OR messages.to="' . $this->user['id'] . '" ) AND messages.removed="1" AND messages.only_db="0" ORDER BY messages.id DESC LIMIT ' . (($page - 1) * 20) . ',20')->result_array();
                break;
        }
    }

    public function get_message($id) {
        $rez = $this->db->query('SELECT messages.*,users.username,users.fb_fullname,users.tw_fullname,users.id uid FROM messages LEFT JOIN users ON IF(`from`="' . $this->user['id'] . '", `to`=users.id,`from`=users.id) WHERE messages.id="' . $id . '" AND (`from`="' . $this->user['id'] . '" OR `to`="' . $this->user['id'] . '")')->row_array();
        return $rez;
    }

    public function change_message_status($id) {
        $this->db->where('id', $id)->update('messages', array('view' => 1));
    }

    public function count_all_messages($folder) {
        switch ($folder) {
            case 'inbox':
                return $this->db->from('messages')->where(array('to' => $this->user['id'], 'removed' => 0))->count_all_results();
                break;
            case 'sent':
                return $this->db->from('messages')->where(array('from' => $this->user['id'], 'removed' => 0))->count_all_results();
                break;
            case 'deleted':
                return count($this->db->query('SELECT id FROM messages WHERE removed="1" AND only_db="0" AND (`to`="' . $this->user['id'] . '" OR `from`="' . $this->user['id'] . '")')->result());

                break;
        }
    }

    public function count_new_messages() {
        return $this->db->from('messages')->where(array('to' => $this->user['id'], 'removed' => 0, 'view' => 0))->count_all_results();
    }

    public function message_mark_change($mark) {
        switch ($mark) {
            case 'unread':
                $update = 'view="0"';
                break;
            case 'read':
                $update = 'view="1"';
                break;
            case 'delete':
                $update = 'removed="1"';
                break;
        }
        $sel_mess = $this->input->post('select_mess');
        if (!empty($sel_mess)) {
            $this->db->query('UPDATE messages SET ' . $update . ' WHERE id IN(' . implode(',', $this->input->post('select_mess')) . ')');
        }
    }

    public function message_empty_trash() {
        $this->db->query('UPDATE messages SET only_db="1" WHERE removed="1"');
    }

    public function message_undelete() {
        $sel_mess = $this->input->post('select_mess');
        if (!empty($sel_mess))
            $this->db->query('UPDATE messages SET removed="0" WHERE id IN(' . implode(',', $this->input->post('select_mess')) . ')');
    }

    public function reply_message() {
        $this->message_send();
    }
    
    public function check_send_mess(&$err){
        $last_sended = $this->session->userdata('last_sended');
        $now = time();
        if($now - $last_sended < 15){
            $err = 'You must wait 15 seconds between each pm.';
            return false;
        }
        $this->session->set_userdata(array('last_sended'=>time()));
        return true;
    }

}