<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Help_model extends CI_Model {

    public function get_all($for_page, $page) {
        $begin = $page * $for_page;
        return $this->db->query('SELECT SQL_CALC_FOUND_ROWS * FROM help ORDER BY title LIMIT ' . $begin . ', ' . $for_page)->result_array();
    }

    public function get_one($id) {
            $rez = $this->db->get_where('help', array('id' => $id))->row_array();
        return $rez;
    }

    public function update($id = NULL) {
        if (!empty($id)) {
            $data_upd = array(
                'title' => $this->input->post('title'),
                'text' => $this->input->post('text'),
            );
            $this->db->where('id', $id);
            $this->db->update('help', $data_upd);
        } else {
            $data_ins = array(
                'title' => $this->input->post('title'),
                'text' => $this->input->post('text')
            );
            $this->db->insert('help', $data_ins);
            $id = $this->db->insert_id();
        }
    }

    

    public function remove($id) {
        $this->db->where('id', $id);
        $this->db->delete('help');
    }

    public function count_pages($for_page) {
        $count = $this->db->query('SELECT FOUND_ROWS() as result')->row()->result;
        return ceil($count / $for_page);
    }


}