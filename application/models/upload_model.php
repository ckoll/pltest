<?

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Upload_model extends CI_Model
{

    public $user;

    public function __construct()
    {
        parent::__construct();
        $this->user = $this->session->userdata('user');
    }

    public function get_image_type($filename)
    {
        $format = strtolower(substr(strrchr($filename, "."), 1));
        return ($format == 'jpg') ? 'jpeg' : $format;
    }

    public function upload_img()
    {
        $image_format = $this->get_image_type($_FILES['photo']['name']);
        $icfunc = 'imagecreatefrom' . $image_format;
        if (!function_exists($icfunc))
            return false;

        $this->load->library('Image_moo');
        $rand = rand(10000, 99999);
        $this->db->query('INSERT INTO upload_photo (rand_num, uid, date) VALUES ("' . $rand . '","' . $this->user['id'] . '", "' . date('Y-m-d H:i:s') . '")');
        $id = $this->db->insert_id() . $rand;

        $this->load->library('buttons');
        $this->buttons->add_money($this->user['id'], 10);
        $this->buttons->write_history($this->user['id'], array('action' => 'upload_bonus', 'jewels' => $this->user['jewels'], 'now_jewels' => $this->user['jewels'], 'buttons' => $this->user['buttons'], 'now_buttons' => ($this->user['buttons'] + 10), 'description' => 'Loaded photo: <a href="/' . $this->user['username'] . '/photo/' . $id . '" target="_blank">photo</a>'));
        $this->user_model->write_history_activity($this->user['id'], 'upload', $id);

        $upload_path = APPPATH . 'files/users/uploads/' . $this->user['id'] . '/';
        if (!file_exists($upload_path)) {
            mkdir($upload_path, 0777);
        }

        $img_size = getimagesize($_FILES['photo']['tmp_name']); //[0]-width,[1]-height
        if ($img_size[0] > $img_size[1]) {
            $width = 500;
            $height = $img_size[1] / $img_size[0] * 500;

            $sq_height = 500;
            $sq_width = $img_size[0] / $img_size[1] * 500;
        } else {
            $height = 500;
            $width = $img_size[0] / $img_size[1] * 500;

            $sq_width = 500;
            $sq_height = $img_size[1] / $img_size[0] * 500;
        }

        require_once APPPATH . 'libraries/ImageHandler.php';
        $ImageHandler = new ImageHandler();
        $ImageHandler->load($_FILES['photo']['tmp_name'])->resize($img_size[0], $img_size[1])->save($upload_path . $id . '_original.jpg', 2, 100);
        $ImageHandler->load($_FILES['photo']['tmp_name'])->resize($width, $height)->save($upload_path . $id . '_tmp.jpg', 2, 100);
        $ImageHandler->load($_FILES['photo']['tmp_name'])->resize($sq_width, $sq_height)->crop(500, 500, 0, 0)->save($upload_path . $id . '.jpg', 2, 100);


//        $original_img = imagecreatetruecolor($img_size[0], $img_size[1]);
//        $white = imagecolorallocate($original_img, 255, 255, 255);
//        imagefilledrectangle($original_img, 0, 0, $img_size[0], $img_size[1], $white);
//
//        $small_img = imagecreatetruecolor($width, $height);
//        $white = imagecolorallocate($small_img, 255, 255, 255);
//        imagefilledrectangle($small_img, 0, 0, $width, $height, $white);
//
//        $square_size = ($width > $height) ? $height : $width;
//        $square_img = imagecreatetruecolor($square_size, $square_size);
//        $white = imagecolorallocate($square_img, 255, 255, 255);
//        imagefilledrectangle($square_img, 0, 0, $width, $height, $white);
//
//        $from = $icfunc($_FILES['photo']['tmp_name']);
//
//        imagecopyresized($original_img, $from, 0, 0, 0, 0, $img_size[0], $img_size[1], $img_size[0], $img_size[1]);
//        imagecopyresized($small_img, $from, 0, 0, 0, 0, $width, $height, $img_size[0], $img_size[1]);
//        imagecopyresized($square_img, $small_img, 0, 0, 0, 0, $square_size, $square_size, $square_size, $square_size);
//
//        imagejpeg($original_img, $upload_path . $id . '_original.jpg', 100);
//        imagejpeg($small_img, $upload_path . $id . '_tmp.jpg', 100);
//        imagejpeg($square_img, $upload_path . $id . '.jpg', 100);
//
//        imagedestroy($original_img);
//        imagedestroy($small_img);
//        imagedestroy($square_img);
//        imagedestroy($from);
        return $id;
    }

    public function save_photo_tags($id)
    {

        $img_path = APPPATH . 'files/users/uploads/' . $this->user['id'] . '/' . $id . '.jpg';
        $img_path_tmp = APPPATH . 'files/users/uploads/' . $this->user['id'] . '/' . $id . '_tmp.jpg';
        $img_path_original = APPPATH . 'files/users/uploads/' . $this->user['id'] . '/' . $id . '_original.jpg';

        $orig_size = getimagesize($img_path_original);
        $size = getimagesize($img_path_tmp);
        $floor = $orig_size[0] / $size[0];

        $width = ($this->input->post('upload_x2') - $this->input->post('upload_x1')) * $floor;
        $height = ($this->input->post('upload_y2') - $this->input->post('upload_y1')) * $floor;

        $thumb_xy = array('x1' => $this->input->post('upload_x1'), 'y1' => $this->input->post('upload_y1'), 'x2' => $this->input->post('upload_x2'), 'y2' => $this->input->post('upload_y2'));

        require_once APPPATH . 'libraries/ImageHandler.php';
        $ImageHandler = new ImageHandler();
        $x = intval($this->input->post('upload_x1') * $floor);
        $y = $this->input->post('upload_y1') * $floor;

        $ImageHandler->load($img_path_original)->crop($width, $height, $x, $y)->resize(500, 500)->save($img_path, 2, 100);

        $caption = '';
        if ($this->input->post('caption')) {
            $caption = ', caption="' . mysql_real_escape_string(strip_tags($this->input->post('caption'))) . '"';
        }
        $this->db->query('UPDATE upload_photo SET thumb_xy = "' . mysql_real_escape_string(serialize($thumb_xy)) . '"' . $caption . ' WHERE CONCAT(id,rand_num)="' . intval($id) . '"');
        for ($x = 0; $x < 5; $x++) {
            $items = array('position' => strip_tags($_POST['pos'][$x]), 'title' => strip_tags($_POST['tagname'][$x]), 'brand_id' => intval($_POST['brand'][$x]), 'photo_id' => $id, 'uid' => $this->user['id']);

            if (!empty($_POST['edit'][$x])) {
                $this->db->where('id', $_POST['edit'][$x]);
                $this->db->update('brand_tags', $items);
            } else {
                $this->db->insert('brand_tags', $items);
            }
        }
    }

    public function del_uploaded_img($id)
    {
        $this->db->query('DELETE FROM brand_tags WHERE uid="' . $this->user['id'] . '" AND photo_id="' . $id . '"');
        $this->db->query('DELETE FROM upload_photo WHERE uid="' . $this->user['id'] . '" AND CONCAT(id,rand_num)="' . $id . '"');
        @unlink(APPPATH . 'files/users/uploads/' . $this->user['id'] . '/' . $id . '.jpg');
        @unlink(APPPATH . 'files/users/uploads/' . $this->user['id'] . '/' . $id . '_tmp.jpg');
        @unlink(APPPATH . 'files/users/uploads/' . $this->user['id'] . '/' . $id . '_original.jpg');
    }

    public function get_tags($id)
    {
        return $this->db->get_where('brand_tags', array('uid' => $this->user['id'], 'photo_id' => $id))->result_array();
    }

    public function get_brands()
    {
        $all = array();
        $brands = $this->db->get_where('brands')->result_array();
        if (!empty($brands)) {
            foreach ($brands as $val) {
                $all[$val['id']] = $val;
            }
            return $all;
        }
    }

    public function photo_details($id)
    {
        return $this->db->query('SELECT upload_photo.*, users.username FROM upload_photo LEFT JOIN users ON users.id = upload_photo.uid WHERE CONCAT(upload_photo.id,rand_num) = "' . $id . '"')->row_array();
    }

    public function like_add($id)
    {
        $photo = $this->photo_details($id);
        $user_photo = $this->user_model->get_user_info('id', $photo['uid']);

        $liked_users = empty($photo['like_users']) ? array() : explode(',', $photo['like_users']);
        if (!in_array($this->user['id'] . '_' . $this->user['username'], $liked_users)) {
            $liked_users[] = $this->user['id'] . '_' . $this->user['username'];
            $this->db->query('UPDATE upload_photo SET `like`=`like`+1, like_users="' . mysql_real_escape_string(implode(',', $liked_users)) . '", last_like="' . date('Y-m-d H:i:s') . '" WHERE CONCAT(id,rand_num)="' . $id . '"');
            if ($this->user['id'] != $photo['uid']) {
                $this->load->library('buttons');
                $this->buttons->add_money($photo['uid'], 1);
                $this->buttons->write_history($photo['uid'], array('action' => 'photo_like', 'jewels' => $user_photo['jewels'], 'now_jewels' => $user_photo['jewels'], 'buttons' => $user_photo['buttons'], 'now_buttons' => ($user_photo['buttons'] + 1), 'description' => 'Liked your photo (' . $this->user['username'] . '): <a href="/' . $photo['username'] . '/photo/' . $photo['id'] . $photo['rand_num'] . '" target="_blank">photo</a>'));
            }
            return;
        } else {
            return array('err' => 'You have already voted');
        }
    }

    public function user_photos($uid, $for_page, $page = 0)
    {
        $begin = intval($page * $for_page);
        return $this->db->query('SELECT SQL_CALC_FOUND_ROWS upload_photo.* FROM upload_photo WHERE uid="' . $uid . '" ORDER BY id DESC LIMIT ' . $begin . ',' . $for_page)->result_array();
    }

    public function latest_photos($for_page, $page = 0)
    {
        $begin = intval($page * $for_page);
        return $this->db->query('SELECT SQL_CALC_FOUND_ROWS upload_photo.*,users.username FROM upload_photo LEFT JOIN users ON users.id=upload_photo.uid ORDER BY id DESC LIMIT ' . $begin . ',' . $for_page)->result_array();
    }

    public function last_commented_details($photo)
    {
        $all = array();
        if (!empty($photo)) {
            foreach ($photo as $val) {
                $all_id[] = $val['id'];
            }
            $rez = $this->db->query('
            SELECT * FROM
            (SELECT
                photo_comments.*,
                users.username
                FROM photo_comments
                LEFT JOIN users on users.id=photo_comments.uid
                WHERE photo_id IN (' . implode(',', $all_id) . ')
                ORDER BY id DESC)
            d1
            GROUP BY photo_id
            ')->result_array();
            if (!empty($rez)) {
                foreach ($rez as $val) {
                    $all[$val['photo_id']] = $val;
                }
            }
        }
        return $all;
    }

    public function most_hearted_photos($user, $limit = FALSE)
    {
        $q = 'SELECT SQL_CALC_FOUND_ROWS CONCAT(upload_photo.id,upload_photo.rand_num) photo_id, upload_photo.uid, users.username FROM upload_photo LEFT JOIN users ON users.id=upload_photo.uid WHERE uid=' . $user['id'] . ' AND `like`>0 ORDER BY `like` DESC';
        if ($limit)
            $q .= ' LIMIT ' . $limit;
        return $this->db->query($q)->result_array();
    }

    public function last_hearted()
    {
        $hearts = $this->db->query('
        SELECT *,
        "photo" `type`,
        UNIX_TIMESTAMP(last_like) date_unix
        FROM upload_photo
        WHERE `like`!=0 AND uid="' . $this->user['id'] . '" AND last_like > "' . date('Y-m-d H:i:s') . '" - INTERVAL 1 DAY')->result_array();
        return $hearts;
    }


    public function get_all_last_hearted($limit, $page=1)
    {
        $offset = $limit * ($page - 1);

        $sql = 'SELECT *,
        "photo" `type`,
        UNIX_TIMESTAMP(last_like) date_unix
        FROM upload_photo
        WHERE `like`!=0 ORDER BY last_like DESC LIMIT '.$offset.','.$limit;

        return $this->db->query($sql)->result_array();
    }

    public function get_all_last_commented($limit, $page=1)
    {
        $offset = $limit * ($page - 1);

        $sql = 'SELECT *,
        "photo" `type`,
        UNIX_TIMESTAMP(last_like) date_unix
        FROM upload_photo
        WHERE `last_comment`!="0000-00-00 00:00:00" ORDER BY last_comment DESC LIMIT '.$offset.','.$limit;

        return $this->db->query($sql)->result_array();
    }

    public function last_commented()
    {
        $comments = $this->db->query('
        SELECT *, "photo" `type`, UNIX_TIMESTAMP(last_comment) date_unix
        FROM upload_photo WHERE `last_comment`!="0000-00-00 00:00:00" AND uid="' . $this->user['id'] . '" AND last_comment > "' . date('Y-m-d H:i:s') . '" - INTERVAL 1 DAY')->result_array();
        return $comments;
    }

    public function hearted_photos($user, $limit = FALSE)
    {
        $q = 'SELECT SQL_CALC_FOUND_ROWS CONCAT(upload_photo.id,upload_photo.rand_num) photo_id, users.username, upload_photo.uid FROM upload_photo LEFT JOIN users ON users.id = upload_photo.uid WHERE (like_users LIKE "' . $user['id'] . '_' . $user['username'] . '" OR like_users LIKE "%,' . $user['id'] . '_' . $user['username'] . '" OR like_users LIKE "' . $user['id'] . '_' . $user['username'] . ',%" OR like_users LIKE "%,' . $user['id'] . '_' . $user['username'] . ',%")  ORDER BY last_like DESC';
        if ($limit)
            $q .= ' LIMIT ' . $limit;
        return $this->db->query($q)->result_array();
    }

    public function most_commented_photos($user, $limit)
    {
        $q = 'SELECT SQL_CALC_FOUND_ROWS CONCAT(upload_photo.id,upload_photo.rand_num) photo_id, users.username, upload_photo.uid FROM upload_photo LEFT JOIN users ON users.id = upload_photo.uid WHERE upload_photo.uid=' . $user['id'] . ' AND upload_photo.comments>0 ORDER BY upload_photo.comments DESC';
        if ($limit)
            $q .= ' LIMIT ' . $limit;
        return $this->db->query($q)->result_array();
    }

    public function favorite_photos($user, $limit = FALSE)
    {
        $q = 'SELECT SQL_CALC_FOUND_ROWS upload_photo.uid, users.username, CONCAT(upload_photo.id,upload_photo.rand_num) photo_id FROM upload_photo LEFT JOIN users ON upload_photo.uid=users.id WHERE like_users LIKE "%' . ($user['id'] . '_' . $user['username']) . '" OR like_users LIKE "%' . ($user['id'] . '_' . $user['username']) . '%" OR like_users LIKE "' . ($user['id'] . '_' . $user['username']) . '%"';
        if ($limit)
            $q .= ' LIMIT ' . $limit;
        return $this->db->query($q)->result_array();
    }

    public function count_pages($for_page)
    {
        $count = $this->db->query('SELECT FOUND_ROWS() as result')->row()->result;
        return ceil($count / $for_page);
    }

    public function get_photo_comments($per_page, $page, $id)
    {
        $begin = $per_page * $page;
        $query = 'SELECT SQL_CALC_FOUND_ROWS *, users.username, photo_comments.id comment_id FROM photo_comments LEFT JOIN users ON photo_comments.uid = users.id WHERE photo_id=' . $id . ' ORDER BY photo_comments.id DESC LIMIT ' . $begin . ',' . $per_page;
        return $this->db->query($query)->result_array();
    }

    public function add_photo_comment($id)
    {
        $comment = trim(strip_tags($this->input->post('comment')));
        if (!empty($comment)) {
            $comment_data = array(
                'photo_id' => $id,
                'uid' => $this->user['id'],
                'comment' => $comment,
                'date' => date("Y-m-d H:i:s")
            );
            $this->db->insert('photo_comments', $comment_data);
            $this->db->query('UPDATE upload_photo SET comments = (comments+1), last_comment = "' . date("Y-m-d H:i:s") . '" WHERE id = "' . $id . '"');
        }
    }

    public function top_10_photos()
    {
        return $this->top_photos(10);
        //return array();
    }

    public function top_photos($limit, $page = 1)
    {
        $offset = $limit * ($page - 1);

        $sql = "SELECT
            upload_photo.*,
            users.username,
            (SELECT COUNT(1) FROM photo_comments WHERE photo_comments.photo_id=upload_photo.id) comments
            FROM upload_photo
            LEFT JOIN users ON users.id=upload_photo.uid
            ORDER BY `like` DESC LIMIT $offset,$limit";

        return $this->db->query($sql)->result_array();


    }


    public function featured_photos($for_page, $page)
    {
        $begin = intval($page * $for_page);
        return $this->db->query('SELECT SQL_CALC_FOUND_ROWS * FROM featured WHERE type="photo" ORDER BY id DESC LIMIT ' . $begin . ', ' . $for_page)->result_array();
    }

    public function remove_comment($id)
    {
        $photo = $this->db->query('SELECT * FROM photo_comments WHERE uid="' . $this->user['id'] . '" AND id = "' . mysql_real_escape_string($id) . '"')->row_array();
        if (!empty($photo)) {
            $this->db->query('DELETE FROM photo_comments WHERE id = "' . mysql_real_escape_string($id) . '"');
            $this->db->query('UPDATE upload_photo SET comments = comments-1 WHERE id="' . $photo['photo_id'] . '"');
        }
    }

}