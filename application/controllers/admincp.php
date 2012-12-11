<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once APPPATH . '/libraries/controllers/User_controller.php';

class Admincp extends User_controller
{

    public function __construct()
    {
        parent::__construct();
        $this->tpl->gtpl = 'admin';
        //Admin Check
        $admin = $this->db->get_where('users_admin', array('uid' => $this->user['id']))->row();
        if (empty($admin)) {
            redirect('/');
        }
    }

    public function index()
    {
        $this->tpl->ltpl = array('admin' => 'index');
        $this->tpl->show($this->data);
    }

    public function gifts()
    {
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

    public function users()
    {
        $this->load->model('user_model');
        if ($this->input->get('del')) {
            $this->user_model->remove_user($this->input->get('del'));
        }
        $this->data['users'] = $this->user_model->get_all_users();

        $this->tpl->ltpl = array('admin' => 'users');
        $this->tpl->show($this->data);
    }


    public function help()
    {
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
            $this->data['helps'] = $this->help_model->get_all(20, $this->input->get('page'));
            $this->data['pages'] = $this->help_model->count_pages(20);
            $this->tpl->ltpl = array('admin' => 'help');
        }

        $this->tpl->show($this->data);
    }

    public function brands()
    {
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
            $this->data['brands'] = $this->brands_model->get_all(20, $this->input->get('page'));
            $this->data['pages'] = $this->brands_model->count_pages(20);
            $this->tpl->ltpl = array('admin' => 'brands');
        }

        $this->tpl->show($this->data);
    }

    public function announcements()
    {
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

    public function links()
    {
        $this->load->model('links_model');

        $links = $this->links_model->get_all();
        foreach($links as $i=>$link) {
            $links[$i]['number'] = $this->links_model->getCountUsers($link['id']);
        }

        $this->data['links'] = $links;

        $this->tpl->ltpl = array('admin' => 'links');

        $this->tpl->show($this->data);
    }

    public function add_partner_link()
    {
        $this->load->model('links_model');
        if ($this->input->post('save')) {
            if ($this->links_model->checkLink($this->input->get('id'))) {
                $this->links_model->update();
                redirect('/admincp/links');
            } else {
                $this->data['message'] = 'Title or hash is in use. Please select another.';
            }
        }

        $this->tpl->ltpl = array('admin' => 'link_edit');
        $this->tpl->show($this->data);
    }

    public function edit_partner_link()
    {
        $this->load->model('links_model');
        $this->data['link'] = $this->links_model->get_one($this->input->get('id'));
        if ($this->input->post('save')) {
            if ($this->links_model->checkLink($this->input->get('id'))) {
                $this->links_model->update($this->input->get('id'));
                redirect('/admincp/links');
            } else {
                $this->data['message'] = 'Title or hash is in use. Please select another.';
            }
        }

        $this->tpl->ltpl = array('admin' => 'link_edit');
        $this->tpl->show($this->data);
    }

    public function delete_partner_link()
    {
        $this->load->model('links_model');
        $this->links_model->remove($this->input->get('id'));
        redirect('/admincp/links');
    }

    public function partner_link_users()
    {
        $this->load->model('links_model');
        $this->data['users'] = $this->links_model->get_all_users_by_link();
        $this->tpl->ltpl = array('admin' => 'link_users');

        $this->tpl->show($this->data);
    }


    public function items($new = NULL)
    {

        $tabsToUpdate = array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14);

        if (is_file($_FILES['file']['tmp_name'])) {

            $newPath = APPPATH . 'files/dressup'.time().'.xls';
            move_uploaded_file($_FILES['file']['tmp_name'], $newPath);
            chmod($newPath, 0777);

            require APPPATH . 'libraries/excel_reader.php';
            $data = new Spreadsheet_Excel_Reader($newPath, false);

            //update body parts categories
            $xml_tab = 5;
            if(in_array($xml_tab, $tabsToUpdate)) {

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
            }


            //update body parts

            $xml_tab = 1;
            if(in_array($xml_tab, $tabsToUpdate)) {
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
                            'display_name' => $val[2],
                            'type' => $val[3],
                            'default' => $val[4],
                            'skincolor' => $val[5],
                            'directory' => $val[6],
                            'files' => $val[7],
                            'profileimage' => $val[8]
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
            }


            //update items
            $xml_tab = 0;
            if(in_array($xml_tab, $tabsToUpdate)) {
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
                        if (stripos($val[27], '.png') === FALSE && stripos($val[27], '.jpg') === FALSE) {
                            $val[27] .= '.png';
                        }

                        $ins = array(
                            'id' => $val[1],
                            'removed' => $val[2],
                            'category' => $val[3],
                            'type' => $val[4],
                            'cover' => $val[5],
                            'directory' => $val[6],
                            'shortname' => $val[7],
                            'set_components' => $val[8],
                            'leftright' => $val[9],
                            'files' => $val[10],
                            'clippings' => $val[11],
                            'squeeze' => $val[12],
                            'pants_clippings' => $val[13],
                            'torso_clippings' => $val[14],
                            'sleeve_clippings' => $val[15],
                            'poses' => $val[16],
                            'torso_img' => $val[17],
                            'stand_sleeves_img' => $val[18],
                            'hold_sleeves_img' => $val[19],
                            'torso_squeeze_effect' => $val[20],
                            'sleeve_squeeze_effect' => $val[21],
                            'tightness_effect' => $val[22],
                            'torso_slim_image' => $val[23],
                            'tucked_torso_image' => $val[24],
                            'boots' => $val[25],
                            'profileimage_dir' => $val[26],
                            'profileimage' => $val[27],
                            'item_name' => $val[28],
                            'description' => $val[29],
                            'shop' => $val[30],
                            'price' => $val[31],
                            'transparent_squeeze' => $val[33],
                            'transparent_clipping' => $val[34]
                        );

                        if (in_array($val[1], $exists)) {
                            $this->db->where('id', $val[1]);
                            $this->db->update('dressup_items', $ins);
                        } else {
                            $this->db->insert('dressup_items', $ins);
                        }

                        if (!empty($val[30]) && $val[30] != 'n/a') {
                            //add to shop
                            $rez = $this->db->get_where('items_shop', array('item_id' => $val[1]))->result();
                            if (empty($rez)) {
                                if (strpos($val[31], 'button') != FALSE) {
                                    $price_col = 'price_b';
                                } elseif (strpos($val[31], 'jewel') != FALSE) {
                                    $price_col = 'price_j';
                                } else {
                                    $price_col = 'price_b';
                                }
                                $price = intval($val[31]);
                                $this->db->insert('items_shop', array('item_id' => $val[1], 'limit' => 10, $price_col => $price));
                            }
                        }
                        $this->data['updated'] = true;
                    }
                }
            }
        }


        $this->tpl->ltpl = array('admin' => 'items');
        $this->tpl->show($this->data);
    }

    public function ajax()
    {
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

    public function photos()
    {
        $this->load->model('upload_model');
        $perPage = 100;
        $page = $this->input->get('page');
        $page = $page>0?$page:1;

        $photos = $this->upload_model->latest_photos($perPage, $page-1);

        $this->data['photos'] = $photos;
        $this->data['page'] = $page;

        $this->tpl->ltpl = array('admin' => 'photos');

        $this->tpl->show($this->data);

    }

    public function refresh_square_crop_image()
    {
        $this->load->model('upload_model');
        $upload = $this->upload_model->photo_details_by_id($this->input->get('id'));
        if (isset($upload['id'])) {
            $squareUploadPath = _getSquareUploadPath($upload);
            unlink($squareUploadPath);

        }
        redirect('/admincp/photos?page='.$this->input->get('page'));
    }

}