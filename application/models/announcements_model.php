<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Announcements_model extends CI_Model {

    public function get_all($limit = 0, $page=0) {
        $limit_sql = '';
        if (!empty($limit)) {
            $begin = $limit * $page;
            $limit_sql = ' LIMIT ' .$begin. ',' .$limit;
        }
        
        return $this->db->query('SELECT SQL_CALC_FOUND_ROWS * FROM announcements ORDER BY `date` DESC'.$limit_sql)->result_array();
    }

    public function get_one($id) {
        return $this->db->get_where('announcements',array('id'=>$id))->row_array();
    }

    
    public function update($id = NULL) {
        if(!empty($id)){
            $data_upd = array(
                'title'=>$this->input->post('title'),
                'date'=>$this->input->post('date'),
                'text'=>$this->input->post('text')
            );
            $this->db->where('id',$id);
            $this->db->update('announcements',$data_upd);
        }else{
            $date = ($this->input->post('date'))? $this->input->post('date') : date('Y-m-d') ;
            $data_ins = array(
                'title'=>$this->input->post('title'),
                'text'=>$this->input->post('text'),
                'date'=>$date
            );
            $this->db->insert('announcements',$data_ins);
        }
    }

    public function remove($id) {
        $this->db->where('id',$id);
        $this->db->delete('announcements');
    }
    
    public function count_pages($for_page) {
        $count = $this->db->query('SELECT FOUND_ROWS() as result')->row()->result;
        return ceil($count / $for_page);
    }

}