<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Brands_model extends CI_Model {

    public function get_all($for_page, $page) {
        $begin = $page * $for_page;
        return $this->db->query('SELECT SQL_CALC_FOUND_ROWS * FROM brands ORDER BY title LIMIT ' . $begin . ', ' . $for_page)->result_array();
    }

    public function get_one($id) {
        if (is_int($id)) {
            $rez = $this->db->get_where('brands', array('id' => $id))->row_array();
        } else {
            $name = str_replace('-', ' ', $id);
            $rez = $this->db->query('SELECT * FROM brands WHERE LOWER(title)="' . mysql_real_escape_string($name) . '"')->row_array();
        }
        return $rez;
    }

    public function get_images($id, $limit = NULL, $begin = 0) {
        if (empty($limit)) {
            $rez = $this->db->query('SELECT brand_tags.*, upload_photo.caption, users.username FROM brand_tags LEFT JOIN upload_photo ON brand_tags.photo_id = CONCAT(upload_photo.id,upload_photo.rand_num) LEFT JOIN users ON users.id = upload_photo.uid  WHERE brand_id=' . $id . ' GROUP BY brand_tags.photo_id ORDER BY upload_photo.id DESC')->result_array();
        } else {
            $begin*=$limit;
            $rez = $this->db->query('SELECT SQL_CALC_FOUND_ROWS brand_tags.*, upload_photo.caption caption, users.username FROM brand_tags LEFT JOIN upload_photo ON brand_tags.photo_id = CONCAT(upload_photo.id,upload_photo.rand_num) LEFT JOIN users ON users.id = upload_photo.uid  WHERE brand_id=' . $id . ' GROUP BY photo_id ORDER BY id DESC LIMIT ' . $begin . ',' . $limit)->result_array();
        }
        return $rez;
    }

    public function get_images3($brand_id, $photo_id) {

        $rez[0] = $this->db->query('SELECT * FROM brand_tags WHERE brand_id=' . $brand_id . ' AND photo_id>' . $photo_id . ' GROUP BY photo_id ORDER BY photo_id  LIMIT 1')->row_array();
        $rez[2] = $this->db->query('SELECT * FROM brand_tags WHERE brand_id=' . $brand_id . ' AND photo_id<' . $photo_id . ' GROUP BY photo_id  ORDER BY photo_id DESC  LIMIT 1')->row_array();
        $rez[1] = $this->db->query('SELECT brand_tags.*, upload_photo.caption, DATE_FORMAT(upload_photo.`date`, "%d.%m.%Y") date FROM brand_tags LEFT JOIN upload_photo ON brand_tags.photo_id = CONCAT(upload_photo.id,upload_photo.rand_num) WHERE brand_id=' . $brand_id . ' AND photo_id="' . $photo_id . '" LIMIT 1')->row_array();
        return $rez;
    }

    public function get_image_tags($image_id) {
        return $this->db->query('SELECT brand_id, position, brand_tags.title, brands.title brand_title FROM brand_tags LEFT JOIN brands ON brands.id = brand_tags.brand_id WHERE brand_tags.photo_id = ' . $image_id . ' AND brand_tags.title!="" AND brand_tags.position!="" AND brand_tags.brand_id!=0')->result_array();
    }

    public function add_to_favorite($brand_id) {
        if (!empty($brand_id)) {
            $this->db->query('INSERT INTO user_brand VALUES(' . $this->user['id'] . ', ' . $brand_id . ')');
            $this->user_model->write_history_activity($this->user['id'], 'brand', $brand_id);
        }
    }

    public function del_from_favorite($brand_id) {
        if (!empty($brand_id)) {
            $this->db->query('DELETE FROM user_brand WHERE uid=' . $this->user['id'] . ' AND brand_id=' . $brand_id);
        }
    }

    public function update($id = NULL) {
        if (!empty($id)) {
            $data_upd = array(
                'title' => $this->input->post('title'),
                'description' => $this->input->post('description'),
            );
            $this->db->where('id', $id);
            $this->db->update('brands', $data_upd);
        } else {
            $data_ins = array(
                'title' => $this->input->post('title'),
                'description' => $this->input->post('description')
            );
            $this->db->insert('brands', $data_ins);
            $id = $this->db->insert_id();
        }
        $this->upload_brand_photo($id);
    }

    public function upload_brand_photo($brand_id) {
        if (is_file($_FILES['photo']['tmp_name'])) {
            $this->load->library('Image_moo');

            $upload_path = APPPATH . 'files/brands/';
            if (!file_exists($upload_path)) {
                mkdir($upload_path, 0777);
            }
            $this->image_moo->load($_FILES['photo']['tmp_name'])->resize(250, 250)->set_jpeg_quality('100')->save($upload_path . $brand_id . '.jpg', TRUE);
        }
    }

    public function remove($id) {
        $this->db->where('id', $id);
        $this->db->delete('brands');
    }

    public function count_pages($for_page) {
        $count = $this->db->query('SELECT FOUND_ROWS() as result')->row()->result;
        return ceil($count / $for_page);
    }

    public function get_favorite($uid, $limit = NULL) {
        return $this->db->query('SELECT SQL_CALC_FOUND_ROWS user_brand.*, brands.* FROM user_brand LEFT JOIN brands ON brand_id=brands.id WHERE user_brand.uid="' . $uid . '"')->result_array();
    }

}