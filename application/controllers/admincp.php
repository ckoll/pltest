<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once APPPATH . '/libraries/controllers/User_controller.php';

class Admincp extends User_controller {

    public function __construct() {
        parent::__construct();
        $this->tpl->gtpl = 'admin';
        //Admin Check
        $admin = $this->db->get_where('users_admin', array('uid' => $this->user['id']))->row();
        if (empty($admin)) {
            redirect('/');
        }
    }

    public function index() {
        $this->tpl->ltpl = array('admin' => 'index');
        $this->tpl->show($this->data);
    }

    public function gifts() {
        $this->load->model('gifts_model');
        if ($this->input->post('name')) {
            $this->gifts_model->add_gift();
        } elseif ($this->input->get('del')) {
            $this->gifts_model->remove_gift($this->input->get('del'));
        }
        $this->data['gifts'] = $this->gifts_model->get_gifts();
        $this->tpl->ltpl = array('admin' => 'gifts');
        $this->tpl->show($this->data);
    }

    public function users() {
        $this->load->model('user_model');
        if ($this->input->get('del')) {
            $this->user_model->remove_user($this->input->get('del'));
        }
        $this->data['users'] = $this->user_model->get_all_users();
        $this->tpl->ltpl = array('admin' => 'users');
        $this->tpl->show($this->data);
    }
    public function help(){
        $this->load->model('help_model');
        if ($this->input->get('add') || $this->input->get('edit')) {
            if ($this->input->post('save')) {
                $this->help_model->update($this->input->get('edit'));
                redirect('/admincp/help');
            }
            if ($this->input->get('edit')) {
                $this->data['helps'] = $this->help_model->get_one($this->input->get('edit'));
            }
            $this->tpl->ltpl = array('admin' => 'help_edit');
        } elseif ($this->input->get('del')) {
            $this->help_model->remove($this->input->get('del'));
            redirect('/admincp/help');
        } else {
            $this->data['helps'] = $this->help_model->get_all(20,$this->input->get('page'));
            $this->data['pages'] = $this->help_model->count_pages(20);
            $this->tpl->ltpl = array('admin' => 'help');
        }

        $this->tpl->show($this->data);
    }
    public function brands() {
        $this->load->model('brands_model');
        if ($this->input->get('add') || $this->input->get('edit')) {
            if ($this->input->post('save')) {
                $this->brands_model->update($this->input->get('edit'));
                redirect('/admincp/brands');
            }
            if ($this->input->get('edit')) {
                $this->data['brands'] = $this->brands_model->get_one($this->input->get('edit'));
            }
            $this->tpl->ltpl = array('admin' => 'brand_edit');
        } elseif ($this->input->get('del')) {
            $this->brands_model->remove($this->input->get('del'));
            redirect('/admincp/brands');
        } else {
            $this->data['brands'] = $this->brands_model->get_all(20,$this->input->get('page'));
            $this->data['pages'] = $this->brands_model->count_pages(20);
            $this->tpl->ltpl = array('admin' => 'brands');
        }

        $this->tpl->show($this->data);
    }

    public function announcements() {
        $this->load->model('announcements_model');
        if ($this->input->get('add') || $this->input->get('edit')) {
            if ($this->input->post('save')) {
                $this->announcements_model->update($this->input->get('edit'));
                redirect('/admincp/announcements');
            }
            if ($this->input->get('edit')) {
                $this->data['news'] = $this->announcements_model->get_one($this->input->get('edit'));
            }
            $this->tpl->ltpl = array('admin' => 'announcement_edit');
        } elseif ($this->input->get('del')) {
            $this->announcements_model->remove($this->input->get('del'));
        } else {
            $this->data['news'] = $this->announcements_model->get_all();
            $this->tpl->ltpl = array('admin' => 'announcement');
        }

        $this->tpl->show($this->data);
    }

    public function items($new = NULL) {

        if (is_file($_FILES['file']['tmp_name'])) {

            require APPPATH . 'libraries/excel_reader.php';
            $data = new Spreadsheet_Excel_Reader($_FILES['file']['tmp_name'], false);

            //update body parts categories
            $xml_tab = 5;
            $rows = $data->rowcount($xml_tab);
            $cols = $data->colcount($xml_tab);
            $categories = $this->db->get('body_parts_category')->result_array();
            $exists_categories = array();
            if (!empty($categories)) {
                foreach ($categories as $v) {
                    $exists_categories[] = $v['id'];
                }
            }
            for ($r = 2; $r <= $rows; $r++) {
                for ($c = 1; $c <= $cols; $c++) {
                    $val[$c] = trim($data->val($r, $c, $xml_tab));
                }
                if (!empty($val[1])) {
                    $ins = array(
                        'id' => $val[1],
                        'shortname' => $val[2],
                        'name' => $val[3],
                        'pid' => $val[4]
                    );
                    if (in_array($val[1], $exists_categories)) {
                        $this->db->where('name', $val[1]);
                        $this->db->update('body_parts_category', $ins);
                    } else {
                        $this->db->insert('body_parts_category', $ins);
                    }
                }
            }
            //clear 
            unset($val);
            unset($exists_categories);


            //update body parts
            $xml_tab = 1;
            $rows = $data->rowcount($xml_tab);
            $cols = $data->colcount($xml_tab);
            $exists_bodypart = array();
            $exists_bodypart = array();
            $items = $this->db->get('dressup_body_parts')->result_array();
            if (!empty($items)) {
                foreach ($items as $v) {
                    $exists_bodypart[] = $v['name'];
                }
            }
            for ($r = 2; $r <= $rows; $r++) {
                for ($c = 1; $c <= $cols; $c++) {
                    $val[$c] = trim($data->val($r, $c, $xml_tab));
                }
                if (!empty($val[1])) {
                    $ins = array(
                        'name' => $val[1],
                        'type' => $val[2],
                        'default' => $val[3],
                        'skincolor' => $val[4],
                        'directory' => $val[5],
                        'files' => $val[6],
                        'profileimage' => $val[7]
                    );
                    if (in_array($val[1], $exists_bodypart)) {
                        $this->db->where('name', $val[1]);
                        $this->db->update('dressup_body_parts', $ins);
                    } else {
                        $this->db->insert('dressup_body_parts', $ins);
                    }
                }
            }
            //clear 
            unset($val);
            unset($exists_bodypart);


            //update items
            $xml_tab = 0;
            $rows = $data->rowcount($xml_tab);
            $cols = $data->colcount($xml_tab);
            $exists = array();

            $items = $this->db->get('dressup_items')->result_array();
            if (!empty($items)) {
                foreach ($items as $v) {
                    $exists[] = $v['id'];
                }
            }

            for ($r = 3; $r <= $rows; $r++) {

                for ($c = 1; $c <= $cols; $c++) {
                    $val[$c] = trim($data->val($r, $c, $xml_tab));
                }

                if (is_int($data->val($r, 1)) && empty($val[2])) {
                    if (stripos($val[26], '.png') === FALSE && stripos($val[26], '.jpg') === FALSE) {
                        $val[26].='.png';
                    }

                    $ins = array(
                        'id' => $val[1],
                        'removed' => $val[2],
                        'category' => $val[3],
                        'type' => $val[4],
                        'directory' => $val[5],
                        'shortname' => $val[6],
                        'set_components' => $val[7],
                        'leftright' => $val[8],
                        'files' => $val[9],
                        'clippings' => $val[10],
                        'squeeze' => $val[11],
                        'pants_clippings' => $val[12],
                        'torso_clippings' => $val[13],
                        'sleeve_clippings' => $val[14],
                        'poses' => $val[15],
                        'torso_img' => $val[16],
                        'stand_sleeves_img' => $val[17],
                        'hold_sleeves_img' => $val[18],
                        'torso_squeeze_effect' => $val[19],
                        'sleeve_squeeze_effect' => $val[20],
                        'tightness_effect' => $val[21],
                        'torso_slim_image' => $val[22],
                        'tucked_torso_image' => $val[23],
                        'boots' => $val[24],
                        'profileimage_dir' => $val[25],
                        'profileimage' => $val[26],
                        'item_name' => $val[27],
                        'description' => $val[28],
                        'shop' => $val[29],
                        'price' => $val[30],
                        'transparent_squeeze' => $val[32],
                        'transparent_clipping' => $val[33]
                    );

                    if (in_array($val[1], $exists)) {
                        $this->db->where('id', $val[1]);
                        $this->db->update('dressup_items', $ins);
                    } else {
                        $this->db->insert('dressup_items', $ins);
                    }

                    if (!empty($val[29]) && $val[29] != 'n/a') {
                        //add to shop
                        $rez = $this->db->get_where('items_shop', array('item_id' => $val[1]))->result();
                        if (empty($rez)) {
                            if (strpos($val[30], 'button') != FALSE) {
                                $price_col = 'price_b';
                            } elseif (strpos($val[30], 'jewel') != FALSE) {
                                $price_col = 'price_j';
                            } else {
                                $price_col = 'price_b';
                            }
                            $price = intval($val[30]);
                            $this->db->insert('items_shop', array('item_id' => $val[1], 'limit' => 10, $price_col => $price));
                        }
                    }

                    $this->data['updated'] = true;
                }
            }
        }




        $this->tpl->ltpl = array('admin' => 'items');
        $this->tpl->show($this->data);
    }

    public function ajax() {
        $this->load->model('dressup_model');
        switch ($_POST['func']) {
            case 'add_item':
                $this->dressup_model->admin_add_item();
                break;
        }
        if (!empty($rez)) {
            echo json_encode($rez);
        }
    }

}