<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Doll_model extends CI_Model {

    public $user;

    public function __construct() {
        parent::__construct();
        $this->user = $this->session->userdata('user');
    }
    
    public function change_name($new_name){
        $this->db->where('id',$this->user['id']);
        $this->db->update('users',array('doll_name'=>  strip_tags($new_name)));
    }

}