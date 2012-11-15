<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once APPPATH . '/libraries/controllers/User_controller.php';

class Dressup extends User_controller {

    public $data;

    public function __construct() {
        parent::__construct();
        $this->data['styles'] = array('dressup.css');
        $this->data['scripts'] = array('jquery.numeric.js', 'user-init.js', 'dressup-init.js');
        $this->load->model('dressup_model');
    }

    public function index($category = NULL) {
        $this->load->model('market_model');

//        if ($this->user['id'] == 1) {
//            $this->load->model('dressup_model');
//            require_once APPPATH . 'libraries/Dressup.php';
//            for ($x = 270; $x <= 310; $x++) {
//                $dress = $this->db->get_where('user_dressups', array('id' => $x))->row_array();
//                if (!empty($dress)) {
//                    $dressup = new Dressup_lib;
//                    if (!empty($dress['doll'])) {
//                        $dressup->doll = unserialize($dress['doll']);
//                    }
//                    $dressup->add_items(explode(',', $dress['used_items']));
//                    $this->dressup_model->save_look(0, $dressup, $dress['uid'], $dress['id']);
//                }
//            }
//        }


        $all_id = array();
        $items = $this->db->get('dressup_items')->result_array();
        $all = $this->db->get_where('user_items', array('uid' => 2))->result_array();
        foreach ($all as $val) {
            $all_id[] = $val['item_id'];
        }
        foreach ($items as $val) {
            if (!in_array($val['id'], $all_id) && ( $val['id'] != '53' && $val['id'] != '67') && $val['type'] != "haircolors") {
                $this->db->insert('user_items', array('uid' => 1, 'item_id' => $val['id']));
                $this->db->insert('user_items', array('uid' => 2, 'item_id' => $val['id']));
            }
        }
        $this->data['items'] = $this->market_model->get_my_items($category);
        $this->data['items'] = $this->market_model->add_default_items($this->data['items'], $category);
        $this->data['counts'] = $this->market_model->calck_counts($this->data['items']);
        $this->data['all_items'] = $this->market_model->calck_all_items();
        $this->tpl->ltpl = array('main' => 'dressup_my_items', 'rmenu' => array('dressup_menu'), 'lmenu' => array('dressup_doll'));
        $this->tpl->show($this->data);
    }

    public function dress($id = NULL) {
        $this->data['scripts'] = array_merge($this->data['scripts'], array('jquery.horizontal.scroll.js'));
        $this->load->model('dressup_model');
        $this->data['doll'] = $this->dressup_model->show_items($id);
        $this->data['today_dressup_finded'] = $this->dressup_model->find_today_dressup($this->user['id']);
        $this->tpl->ltpl = array('main' => 'dressup_home', 'rmenu' => array('dressup_ajax_menu'), 'lmenu' => array('dressup_doll'));
        $this->tpl->show($this->data);
    }

    public function edit_dressup($id = NULL) {
        $for_page = 12;
        $this->data['dressups'] = $this->dressup_model->get_dressups($for_page, $this->input->get('page'));
        $this->data['pages'] = $this->dressup_model->count_pages($for_page);
        $this->tpl->ltpl = array('main' => 'all_my_dressup', 'lmenu' => array('my_info'));
        $this->tpl->show($this->data);
    }

    public function outfits() {
        $this->data['scripts'] = array_merge($this->data['scripts'], array('jquery.horizontal.scroll.js'));
        $this->data['outfits'] = $this->dressup_model->get_outfits();
        $this->tpl->ltpl = array('main' => 'dressup_outfits', 'lmenu' => array('my_info'));

        $this->tpl->show($this->data);
    }

    public function ajax() {
        $this->load->model('market_model');
        $this->load->model('dressup_model');
        switch ($_POST['func']) {
            case 'del_item':
                $this->market_model->delete_item($this->input->post('id'));
                break;
            case 'rem_sell':
                $this->market_model->rem_selling($this->input->post('id'));
                break;
            case 'item_average_price':
                $rez = $this->market_model->item_average_price($this->input->post('item'));
                break;
            case 'item_edit_info':
                $rez = $this->market_model->item_average_price($this->input->post('item'));
                $count = $this->market_model->calck_one_items($this->input->post('item'));
                $count_enabled = $this->market_model->calck_enabled_items($this->input->post('item'));
                $rez = array_merge($rez, array('summ_element' => $count, 'count_enabled' => $count_enabled));
                break;
            case 'sell_option1':
                $rez = $this->market_model->sell_option1($this->input->post('count'), $this->input->post('id'));
                break;
            case 'sell_option2':
                if ($this->input->post('edit')) {
                    $rez = $this->market_model->eміit_sell_option2($this->input->post('count'), $this->input->post('id'), $this->input->post('price'));
                } else {
                    $rez = $this->market_model->sell_option2($this->input->post('count'), $this->input->post('id'), $this->input->post('price'));
                }
                break;
            case 'add_auction':
                $rez = $this->market_model->add_auction($this->input->post('id'), $this->input->post('start_price'), $this->input->post('reserve'), $this->input->post('price_type'), $this->input->post('duration'));
                break;
            case 'rem_auction':
                $this->market_model->rem_auction($this->input->post('id'));
                break;
            case 'wear_outfit':
                $rez = $this->dressup_model->wear_outfit($this->input->post('id'));
                break;
            case 'remove_outfit':
                $this->dressup_model->remove_outfit($this->input->post('id'));
                break;

            //Dressup Calendar
            case 'dressup_items_date':
                $rez = $this->dressup_model->dressup_items_date($this->input->post('date'), $this->input->post('uid'));
                break;
            case 'calendat_available_days':
                $rez = $this->dressup_model->calendat_available_days($this->input->post('date'), $this->input->post('user'));
                break;

            //sharing dressup
            case 'load_items':
                $this->dressup_model->load_my_items($this->input->post('category'));
                break;
            case 'share_email_dressup':
                $rez = $this->dressup_model->share_email_dressup($this->input->post('id'), $this->input->post('share_emails'), $this->input->post('mode'));
                break;
            case 'send_share_fb':
                $rez = $this->dressup_model->send_share_fb($this->input->post('id'), $this->input->post('description'), $this->input->post('mode'));
                break;
            case 'send_share_tw':
                $rez = $this->dressup_model->send_share_tw($this->input->post('id'), $this->input->post('description'), $this->input->post('mode'));
                break;
            case 'like_add':
                $rez = $this->dressup_model->like_add($this->input->post('id'));
                break;
            case 'like_remove':
                $rez = $this->dressup_model->like_remove($this->input->post('id'));
                break;

            //dressup process
            case 'add_item':
                $rez = $this->dressup_model->add_item($this->input->post('item'));
                break;
            case 'change_bg':
                $rez = $this->dressup_model->change_bg($this->input->post('item'));
                break;
            case 'change_hair':
                $rez = $this->dressup_model->change_hair($this->input->post('item'));
                break;
            case 'change_skin':
                $rez = $this->dressup_model->change_skin($this->input->post('item'));
                break;
            case 'change_face':
                $rez = $this->dressup_model->change_face($this->input->post('item'));
                break;
            case 'delete_item':
                $rez = $this->dressup_model->delete_item($this->input->post('item'));
                break;
            case 'change_arm_layer':
                $rez = $this->dressup_model->change_arm_layer($this->input->post('arms_layer'));
                break;
            case 'sort_items':
                $rez = $this->dressup_model->sort_items($this->input->post('items'));
                break;
            case 'save_look':
                $rez = $this->dressup_model->save_look($this->input->post('outfit'));
                break;
        }
        if (!empty($rez)) {
            echo json_encode($rez);
        }
    }

}