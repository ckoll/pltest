<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Items_model extends CI_Model {

    public function find_new() {
        $exists = $new_layer = array();
        $exist = $this->db->query('SELECT CONCAT(folder,"/",img) img FROM dressup_layers')->result_array();
        foreach ($exist as $img){
            $exists[] = '/files/items/'.$img['img'];
        }
        
        $folders = array('accessories','bottoms','charms','innerwear','spa','sportswear');

        foreach ($folders as $val) {
            foreach (glob(APPPATH.'files/items/' . $val . "/*.png") as $v) {
                $my_val = str_replace('application', '', $v);
                if(!in_array($my_val, $exists)){
                    $file = explode('/', $v);
                    $file = explode('.',$file[count($file)-1]);
                    $filename = $file[0];
                    $filename = explode('_',$filename);
                    array_unshift($filename, $val, $v);
                    $new_layer[$filename[2]][] = $filename;
                    
                }
            }
        }
        return $new_layer;
    }
    
    public function items_from_category($id){
        return $this->db->query('SELECT * FROM dressup_items WHERE category="'.$id.'"')->result_array();
    }

}