<?php




if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once APPPATH . '/libraries/controllers/User_controller.php';

class Upload extends User_controller {

    public $data;

    public function __construct() {
        parent::__construct();
        $this->data['styles'] = array();
        $this->data['scripts'] = array('upload-init.js');
        $this->load->model('upload_model');
    }

    public function index() {
        if (is_file($_FILES['photo']['tmp_name'])) {
            $id = $this->upload_model->upload_img();
            redirect('/upload/photo_upload/' . $id . '/edit');
        }
        $this->tpl->ltpl = array('main' => 'upload_home', 'lmenu' => array('my_info'));
        $this->tpl->show($this->data);
    }

    public function photo_upload($id) {
        if ($this->uri->segment(4) == 'edit') {
            //edit photo
            if ($this->input->post('save')) {
                $this->upload_model->save_photo_tags($id);
                redirect('/upload/photo_upload/' . $id);
            }
            $this->data['styles'] = array_merge($this->data['styles'], array('chosen.css','imgareaselect.css'));
            $this->data['scripts'] = array_merge($this->data['scripts'], array('chosen.jquery.min.js','jquery.imgareaselect.pack.js'));
            $this->tpl->ltpl = array('main' => 'upload_photo_edit', 'lmenu' => array('my_info'));
        } else {
            //show/share photo
            $this->data['scripts'] = array_merge($this->data['scripts'], array('jquery.numeric.js','dressup-init.js'));
            $this->tpl->ltpl = array('main' => 'upload_photo_share', 'lmenu' => array('my_info')); 
        }
        $this->data['photo'] = $this->db->query('SELECT * FROM upload_photo WHERE CONCAT(id,rand_num)="' . $id . '"')->row_array();
        if (empty($this->data['photo'])) {
            show_404();
        }
        $this->data['tags'] = $this->upload_model->get_tags($id);
        $this->data['brands'] = $this->upload_model->get_brands();
        $this->tpl->show($this->data);
    }

    public function ajax() {
        switch ($_POST['func']) {
            case 'del_uploaded_img':
                $this->upload_model->del_uploaded_img($this->input->post('id'));
                break;
            case 'like_add':
                $rez = $this->upload_model->like_add($this->input->post('id'));
                break;
        }
        if (!empty($rez)) {
            echo json_encode($rez);
        }
    }

}