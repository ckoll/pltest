<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Links_model extends CI_Model {

    public function get_all($limit = 0, $page=0)
    {
        $sql = 'SELECT * FROM partner_links ORDER BY id DESC';
        return $this->db->query($sql)->result_array();
    }

    public function get_one($id) {
        return $this->db->get_where('partner_links',array('id'=>$id))->row_array();
    }

    public function getCountUsers($id)
    {
        return $this->db->get_where('partner_link_users',array('partner_link_id'=>$id))->num_rows();
    }

    
    public function update($id = NULL) {
        if(!empty($id)){
            $data_upd = array(
                'hash'=>$this->input->post('hash'),
                'title'=>$this->input->post('title'),
            );
            $this->db->where('id',$id);
            $this->db->update('partner_links',$data_upd);
        }else{
            $data_ins = array(
                'hash'=>$this->input->post('hash'),
                'title'=>$this->input->post('title'),
            );
            $this->db->insert('partner_links',$data_ins);
        }
    }

    public function remove($id) {
        $this->db->where('id',$id);
        $this->db->delete('partner_links');
    }

    public function checkLink($id = NULL)
    {
        $sql = "SELECT id FROM partner_links WHERE (title='{$this->input->post('title')}' OR hash='{$this->input->post('hash')}')";
        if ($id) {
            $sql .= ' AND id!='.$id;
        }
        $link = $this->db->query($sql)->result_array();

        if (empty($link)) {
            return true;
        }

        return false;
    }

    public function save_partner_link($user_id)
    {
        $hash = $this->input->get('hash');
        if (!$hash) {
            return;
        }

        $link = $this->db->get_where('partner_links',array('hash'=>$hash))->row_array();

        if (!$link) {
            return;
        }

        $data = array(
            'partner_link_id' => $link['id'],
            'user_id' => $user_id,
            'date' => date('Y-m-d H:i:s')
        );

        $this->db->insert('partner_link_users',$data);

    }

    public function get_all_users_by_link()
    {
        $sql = "
            SELECT partner_link_users.* , users.username, partner_links.title
            FROM partner_link_users
            LEFT JOIN users ON users.id=partner_link_users.user_id
            LEFT JOIN partner_links ON partner_links.id=partner_link_users.partner_link_id
            WHERE partner_links.id={$this->input->get('id')}
        ";

        return $this->db->query($sql)->result_array();


    }
}