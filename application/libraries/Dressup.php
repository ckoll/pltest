<?

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dressup_lib {

    public $items, $layers, $doll, $CI;
    private $prev_params;

    public function __construct() {
        $this->CI = &get_instance();
        $this->layers = array();
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == "XMLHttpRequest") {
            $dressup = $this->CI->session->userdata('dressup');
            if (!empty($dressup['items'])) {
                $this->add_items($dressup['items']);
                $this->doll = $dressup['doll'];
            } else {
                $this->default_parts();
                $this->create_default();
            }
        }
    }

    public function clear_doll() {
        $this->items = array();
        if (empty($this->doll)) {
            //get default parts
            $def_skin = $this->CI->db->get_where('dressup_body_parts', array('type' => 'skincolor', 'default' => 'default'))->row()->skincolor;
            $defaults = $this->CI->db->query('SELECT * FROM dressup_body_parts WHERE (`type`="skincolor" AND skincolor="' . $def_skin . '") OR (`type`!="skincolor" AND `default`="default" AND skincolor="' . $def_skin . '")')->result_array();

            foreach ($defaults as $val) {
                switch ($val['type']) {
                    case 'skincolor':
                        $body_default = $val['name'];
                        break;
                    case 'eyes':
                        $eyes_default = $val['name'];
                        break;
                    case 'mouth':
                        $mouth_default = $val['name'];
                        break;
                }
            }
            //get default background with id 1
            $this->CI->db->get_where('dressup_items', array('id' => 1))->result_array();

            $this->doll = array(
                'left' => 1,
                'right' => 1,
                'height' => 0,
                'arms' => 'forward',
                'skincolor' => $def_skin,
                'body' => $body_default,
                'eye' => $eyes_default,
                'mouth' => $mouth_default,
                'background_id' => 1,
                'hair' => 186); // default hair
        } else {
            $this->doll['left'] = 1;
            $this->doll['right'] = 1;
        }
        $this->layers = array();
        $this->update_session();
    }

    public function update_session() {
        $dressup = array('items' => array_keys($this->items), 'doll' => $this->doll);
        $this->CI->session->set_userdata(array('dressup' => $dressup));
    }

    public function default_parts() {
        $this->layers['doll_body'] = $this->get_body_layers();
        $this->layers['hair'] = $this->get_hair_layers();
    }

    public function create_default() {
        $this->add_items(array(53, 67));
    }

    public function add_default_top() {
        $this->add_items(array(53));
    }

    public function add_default_bottom() {
        $this->add_items(array(67));
    }

    public function get_body_layers() {
        $skin_parts = $this->CI->db->query('SELECT * FROM dressup_body_parts WHERE skincolor = "' . $this->doll['skincolor'] . '"')->result_array();
        if (!empty($skin_parts)) {
            foreach ($skin_parts as $val) {

                $val['directory'] = trim(str_replace('files/', '', $val['directory']));

                if ($this->doll['body'] == trim($val['name'])) {
                    $elem_k = explode(']', $val['files']);
                    $elem_files = explode('|', str_replace(array('<br />', ','), '|', nl2br($elem_k[1])));
                    $elem_keys = str_replace('[', '', $elem_k[0]);
                    $elem_keys = explode(',', $elem_keys);

                    foreach ($elem_keys as $key => $v) {
                        $arr_elem = array('img' => $val['directory'] . '/' . trim($elem_files[$key + 1]));

                        if ($this->doll['left'] == 2 && trim($v) == 'hold-left') {
                            $this->layers['hands']['left'] = $arr_elem;
                        } elseif ($this->doll['left'] == 1 && trim($v) == 'stand-left') {
                            $this->layers['hands']['left'] = $arr_elem;
                        }
                        if ($this->doll['right'] == 2 && trim($v) == 'hold-right') {
                            $this->layers['hands']['right'] = $arr_elem;
                        } elseif ($this->doll['right'] == 1 && trim($v) == 'stand-right') {
                            $this->layers['hands']['right'] = $arr_elem;
                        }

                        if ($this->doll['right'] == 2 && trim($v) == 'hold-right-fingers') {
                            $this->layers['fingers']['right'] = $arr_elem;
                        } elseif ($this->doll['left'] == 2 && trim($v) == 'hold-left-fingers') {
                            $this->layers['fingers']['left'] = $arr_elem;
                        }

                        if (trim($v) == 'body') {
                            $body['body'][] = $arr_elem;
                        }
                    }
                } elseif ($this->doll['eye'] == trim($val['name'])) {
                    if (trim($val['name']) == $this->doll['eye']) {

                        if (strpos($val['files'], '[') !== FALSE) {
                            $elem_k = explode(']', $val['files']);
                            $elem_files = explode('|', str_replace(array('<br />', ','), '|', nl2br($elem_k[1])));
                            $elem_keys = str_replace('[', '', $elem_k[0]);
                            $elem_keys = explode(',', $elem_keys);
                            foreach ($elem_keys as $k => $v) {
                                $body['eye'][trim($v)] = array('img' => $val['directory'] . '/' . trim($elem_files[$k + 1]));
                            }
                        } else {
                            $elem_files = explode('|', str_replace(array('<br />', ','), '|', nl2br($val['files'])));
                            foreach ($elem_files as $key) {
                                $body['eye'][] = array('img' => $val['directory'] . '/' . trim($key));
                            }
                        }
                    }
                } elseif ($this->doll['mouth'] == trim($val['name'])) {
                    if (trim($val['name']) == $this->doll['mouth']) {
                        if (strpos($val['files'], '[') !== FALSE) {
                            $elem_k = explode(']', $val['files']);
                            $elem_files = explode('|', str_replace(array('<br />', ','), '|', nl2br($elem_k[1])));
                            $elem_keys = str_replace('[', '', $elem_k[0]);
                            $elem_keys = explode(',', $elem_keys);
                            foreach ($elem_keys as $k) {
                                $body['mouth'][] = array('img' => $val['directory'] . '/' . $elem_files[trim($elem_files[$k + 1])]);
                            }
                        } else {
                            $elem_files = explode('|', str_replace(array('<br />', ','), '|', nl2br($val['files'])));
                            foreach ($elem_files as $k) {
                                $body['mouth'][] = array('img' => $val['directory'] . '/' . trim($k));
                            }
                        }
                    }
                } elseif (trim($val['type']) == 'face') {
                    $body['face'][] = array('img' => $val['directory'] . '/' . trim($val['files']));
                }
            }
            return $body;
        }
    }

    public function get_hair_layers() {
        $hair_layers = array();
        $hair = $this->CI->db->get_where('dressup_items', array('id' => $this->doll['hair']))->row_array();
        if (strpos($hair['files'], '[') !== FALSE) {
            $elem_k = explode(']', $hair['files']);
            $elem_files = explode('|', str_replace(array('<br />', ','), '|', nl2br($elem_k[1])));
            $elem_keys = str_replace('[', '', $elem_k[0]);
            $elem_keys = explode(',', $elem_keys);

            foreach ($elem_keys as $key => $val) {
                if ($val == 'back') {
                    $this->layers['back'][] = array('img' => 'items/' . $hair['directory'] . '/' . str_replace(' ', '%20', trim($elem_files[$key + 1])));
                } else {
                    $hair_layers[] = array('img' => 'items/' . $hair['directory'] . '/' . str_replace(' ', '%20', trim($elem_files[$key + 1])));
                }
            }
        } else {
            $hair_layers[] = array('img' => 'items/' . $hair['directory'] . '/' . str_replace(' ', '%20', trim($hair['files'])));
        }
        return $hair_layers;
    }

    public function add_items($items) {
        if (is_array($items)) {
            foreach ($items as $val) {
                $this->add_items($val);
            }
        } else {
            $item = $this->CI->db->query('SELECT * FROM dressup_items WHERE id="' . $items . '"')->row_array();

            // set components
            if (!empty($item['set_components']) && $item['set_components'] != 'n/a') {
                $components = explode('|', str_replace(array('<br />', ','), '|', nl2br($item['set_components'])));
                foreach ($components as $elem) {
                    $item_comp = $this->CI->db->get_where('dressup_items', array('shortname' => trim($elem)))->row_array();
                    $this->add_items($item_comp['id']);
                }
                return false;
            }

            //Remove same items for shoes
            if (!empty($this->items)) {
                foreach ($this->items as $i) {
                    if (($i['type'] == 'shoes' || $i['type'] == 'shoes, boots' || $i['type'] == 'boots') && in_array($item['type'], array('shoes', 'shoes, boots', 'boots'))) {
                        $this->remove_item($i['id']);
                    }
                }
            }

            if (!empty($item)) {
                $this->items[$item['id']] = $item;
                if ($item['leftright'] == 'left') {
                    $this->pose_change('left', 2);
                } elseif ($item['leftright'] == 'right') {
                    $this->pose_change('right', 2);
                }
                $this->update_session();
            }
        }
    }

    public function result_view() {

        /* TMP */
        if (empty($this->doll['skincolor'])) {
            if ($this->doll['body'] == 'olive skin') {
                $def_skin = 'olive';
                $body_default = 'olive skin';
                $eyes_default = 'olive skin eyes';
                $mouth_default = 'olive skin mouth #1 (default)';
            } elseif ($this->doll['body'] == 'normal skin') {
                $def_skin = 'normal';
                $body_default = 'normal skin';
                $eyes_default = 'normal skin eyes';
                $mouth_default = 'normal skin mouth #1';
            } elseif ($this->doll['body'] == 'dark skin') {
                $def_skin = 'dark';
                $body_default = 'dark skin';
                $eyes_default = 'dark skin eyes';
                $mouth_default = 'dark skin mouth #1 (default)';
            } else {
                $def_skin = 'normal';
                $body_default = 'normal skin';
                $eyes_default = 'normal skin eyes';
                $mouth_default = 'normal skin mouth #1';
            }
            if (empty($this->doll['background_id'])) {
                $bg = 1;
            } else {
                $bg = $this->doll['background_id'];
            }
            $hair = $this->doll['hair'];
            $left = $this->doll['left'];
            $right = $this->doll['right'];
            $arms = $this->doll['arms'];
            $this->doll = array(
                'left' => $left,
                'right' => $right,
                'height' => $arms,
                'arms' => 'forward',
                'skincolor' => $def_skin,
                'body' => $body_default,
                'eye' => $eyes_default,
                'mouth' => $mouth_default,
                'background_id' => $bg,
                'hair' => $hair);
        }
        /* TMP End */

        $this->default_parts();
        if (!empty($this->items)) {
            $all_items = array_reverse($this->items);

            foreach ($all_items as &$val) {
                //fw
                if ($val['files'] == '[shortname+.png]' || $val['files'] == '[default]' || $val['files'] == 'default') {
                    $val['files'] = $val['shortname'] . '.png';
                }
                if ($val['profileimage_dir'] == '[default]' || $val['profileimage_dir'] == 'default') {
                    $val['profileimage_dir'] = 'profilepics';
                }
                $clear = array('set_components', 'torso_squeeze_effect', 'leftright', 'squeeze', 'pants_clippings', 'torso_clippings', 'sleeve_clippings', 'poses', 'torso_img', 'stand_sleeves_img', 'hold_sleeves_img', 'torso_squeeze_effect', 'sleeve_squeeze_effect', 'tightness_effect', 'torso_slim_image', 'tucked_torso_image', 'boots', 'transparent_squeeze', 'transparent_clipping', 'clippings');
                foreach ($clear as $elem) {
                    if ($val[$elem] == 'n/a' || $val[$elem] == 'default' || $val[$elem] == '[default]' || $val[$elem] == 'auto') {
                        $val[$elem] = NULL;
                    }
                }
                //end fw
                if ($val['type'] == 'outer') {
                    $this->parts_add($val, 'top');
                }
                //hide fingers, if is Mittens (gloves)
                if ($val['type'] == 'gloves') {
                    $this->prev_params['hide_fingers'] = true;
                }
            }

            foreach ($all_items as &$val) {
                if ($val['type'] != 'outer') {
                    if ($val['type'] == 'pants') {
                        $this->prev_params['tucked'] = 'tucked';
                    }
                    $this->parts_add($val, 'bottom');
                }
            }


            if (!empty($this->layers)) {
//                if ($this->doll['arms'] == 'back') {
//                    $groups = array('fingers' => '', 'in_hands' => '', 'in_head' => '', 'hair' => '', 'body' => array('top', 'bottom'), 'slawes' => array('top', 'bottom'), 'hands' => '', 'under_hands' => '', 'doll_body' => array('mouth', 'eye', 'face', 'body'), 'back' => '');
//                } else {
//                    $groups = array('fingers' => '', 'in_hands' => '', 'in_head' => '', 'hair' => '', 'slawes' => array('top', 'bottom'), 'hands' => '', 'under_hands' => '', 'body' => array('top', 'bottom'), 'doll_body' => array('mouth', 'eye', 'face', 'body'), 'back' => '');
//                }

                if ($this->doll['arms'] == 'back') {
                    $groups = array('fingers' => '', 'in_hands' => '', 'in_head' => '', 'hair' => '', 'body' => array('top', 'bottom'), 'under_hands' => '', 'slawes' => array('top', 'bottom'), 'hands' => '', 'doll_body' => array('mouth', 'eye', 'face', 'body'), 'back' => '');
                } else {
                    $groups = array('fingers' => '', 'in_hands' => '', 'in_head' => '', 'hair' => '', 'under_hands' => '', 'slawes' => array('top', 'bottom'), 'body' => array('top', 'bottom'), 'hands' => '', 'doll_body' => array('mouth', 'eye', 'face', 'body'), 'back' => '');
                }
                $sorted_items = $code = array();

                if ($this->prev_params['hide_fingers']) {
                    unset($groups['fingers']);
                }

                foreach ($groups as $group_key => $group) {
                    if (!empty($this->layers[$group_key])) {
                        if (is_array($group)) {

                            foreach ($group as $g) {
                                if (!empty($this->layers[$group_key][$g])) {
                                    foreach ($this->layers[$group_key][$g] as $key => $val) {
                                        if (!empty($val['img'])) {
                                            array_unshift($code, str_replace(' ', '%20', $val['img']));
                                            if (!empty($val['item']) && $group_key != 'back') {
                                                array_unshift($sorted_items, $val['item']);
                                            }
                                        }
                                    }
                                }
                            }
                        } else {
                            foreach ($this->layers[$group_key] as $key => $val) {
                                if (!empty($val['img'])) {
                                    array_unshift($code, str_replace(' ', '%20', $val['img']));
                                    if (!empty($val['item']) && $group_key != 'back') {
                                        array_unshift($sorted_items, $val['item']);
                                    }
                                }
                            }
                        }
                    }
                }
            }

            $this->update_session();
            $used = array();
            foreach ($sorted_items as $val) {
                if (!in_array($val, $used)) {
                    $used[] = $val;
                    $items[] = $this->items[$val];
                }
            }

            //add background image
            $bg = $this->CI->db->get_where('dressup_items', array('id' => $this->doll['background_id']))->row_array();
            $this->doll['background'] = '500x350/' . $bg['files'];

            return array('code' => $code, 'items' => $items, 'doll' => $this->doll);
        } else {
            $this->default_parts();
            $this->create_default();
            return $this->result_view();
        }
    }

    public function parts_add($item, $type = 'bottom') {
        $finded_torso = $finded_slawes = 0;


        // tucked images
        if (!empty($this->prev_params['tucked'])) {
            if ($this->part_add_tucked($item, $type)) {
                $finded_torso = 1;
            }
        }
        // torso_squeeze
        if (!empty($this->prev_params['torso_squeeze'])) {
            if ($this->part_add_torso_squeeze($item, $type, $this->prev_params['torso_squeeze'])) {
                $finded_torso = 1;
            }
        }
        // tightness
        if (!empty($this->prev_params['tightness'])) {
            if ($this->part_add_tightness($item, $type)) {
                $finded_torso = 1;
            }
        }
        // sleeve squeeze
        if (!empty($this->prev_params['sleeve_squeeze'])) {
            if ($this->part_add_sleeve_squeeze($item, $type, $this->prev_params['sleeve_squeeze'])) {
                $finded_slawes = 1;
            }
        }
        // search by torso images
        if (empty($finded_torso)) {
            if ($this->part_add_torso($item, $type, $this->prev_params['torso_squeeze'])) {
                $finded_torso = 1;
            }
        }
        // search by default images
        if (empty($finded_torso)) {
            $this->part_add_default($item, $type);
        }
        // search by default slawes
        if (empty($finded_slawes)) {
            $this->part_add_default_slawes($item, $type, 'left');
            $this->part_add_default_slawes($item, $type, 'right');
        }

        //effects
        if (!empty($item['torso_squeeze_effect'])) {
            $this->prev_params['torso_squeeze'] = $item['torso_squeeze_effect'];
        }
        if (!empty($item['sleeve_squeeze_effect'])) {
            $this->prev_params['sleeve_squeeze'] = $item['sleeve_squeeze_effect'];
        }
        if (!empty($item['tightness_effect'])) {
            $this->prev_params['tightness'] = $item['tightness_effect'];
        }
    }

    public function part_add_torso($item, $type, $torso_squeeze = NULL) {
        $return = false;
        if (!empty($item['torso_img'])) {
            if (strpos($item['torso_img'], '[') !== FALSE) {

                $elem_k = explode(']', $item['torso_img']);
                $elem_files = explode('|', str_replace(array('<br />', ','), '|', nl2br($elem_k[1])));
                $elem_keys = str_replace('[', '', $elem_k[0]);
                $elem_keys = explode(',', $elem_keys);

                foreach ($elem_keys as $key => $val) {

                    $value = trim($val);
                    $arr_element = array('item' => $item['id'], 'img' => 'items/' . $item['directory'] . '/' . trim($elem_files[$key + 1]));

                    if (!empty($torso_squeeze) && $value == $torso_squeeze) {
                        $this->layers['body'][$type][] = $arr_element;
                        $return = true;
                    }
                    if ($value == 'back') {
                        $this->layers['back'][] = $arr_element;
                    }

                    if (($value == 'torso' || $value == 'default' || $value == 'normal') && empty($return)) {
                        $this->layers['body'][$type][] = $arr_element;
                        $return = true;
                    }
                }
            } elseif (!empty($item['torso_img'])) {
                $elements = explode('|', str_replace('<br />', '|', nl2br($item['torso_img'])));
                foreach ($elements as $val) {
                    $this->layers['body'][$type][] = array('item' => $item['id'], 'img' => 'items/' . $item['directory'] . '/' . trim($val));
                    $return = true;
                }
            }
        }
        return $return;
    }

    public function part_add_default($item, $type) {
        if (!empty($item['files'])) {
            if (strpos($item['files'], '[') !== FALSE) {
                $elem_k = explode(']', $item['files']);
                $elem_files = explode('|', str_replace(array('<br />', ','), '|', nl2br($elem_k[1])));

                $elem_keys = str_replace('[', '', $elem_k[0]);
                $elem_keys = explode(',', $elem_keys);

                foreach ($elem_keys as $key => $val) {
                    $value = trim($val);
                    $arr_element = array('item' => $item['id'], 'img' => 'items/' . $item['directory'] . '/' . trim($elem_files[$key + 1]));
                    if ($value == 'back') {
                        $this->layers['back'][] = $arr_element;
                    }
                    if ($value == 'front' || $value == 'set' || $value == 'normal') {
                        if ($item['type'] == 'handheld' || $item['type'] == 'handbag' || $item['type'] == 'object') {
                            if (!empty($item['leftright']) && $item['type'] == 'handbag') {
                                $this->layers['under_hands'][] = $arr_element;
                            } else {
                                $this->layers['in_hands'][] = $arr_element;
                            }
                        } elseif ($item['type'] == 'scarf') {
                            $this->layers['slawes']['top'][] = $arr_element;
                        } elseif ($item['type'] == 'hat') {
                            $this->layers['in_head'][] = $arr_element;
                        } else {
                            $this->layers['body'][$type][] = $arr_element;
                        }
                    }
                    if ($value == 'stand') { //&& $this->doll[$item['leftright']] == 2
                        $this->layers['slawes'][$type][] = $arr_element;
                    }
                    if ($value == 'hold') { //&& $this->doll[$item['leftright']] == 1
                        $this->layers['slawes'][$type][] = $arr_element;
                    }
                    if ($value == 'left') {
                        if($item['leftright'] == 'left or right' && empty($this->prev_params['leftright'])){
                            $this->prev_params['leftright'] = 'left';
                            $this->pose_change('left',2);
                            $this->layers['body'][$type][] = $arr_element;
                        }
                    }
                    if ($value == 'right' ) {
                        if($item['leftright'] == 'left or right' && empty($this->prev_params['leftright'])){
                            $this->prev_params['leftright'] = 'right';
                            $this->pose_change('right',2);
                            $this->layers['body'][$type][] = $arr_element;
                        }
                    }
                    if ($value == 'stand-left' && $this->doll['left'] == 1) {
                        $this->layers['body'][$type][] = $arr_element;
                    }
                    if ($value == 'hold-left' && $this->doll['left'] == 2) {
                        $this->layers['body'][$type][] = $arr_element;
                    }
                    if ($value == 'stand-right' && $this->doll['right'] == 1) {
                        $this->layers['body'][$type][] = $arr_element;
                    }
                    if ($value == 'hold-right' && $this->doll['right'] == 2) {
                        $this->layers['body'][$type][] = $arr_element;
                    }
                }
            } elseif (!empty($item['files'])) {
                $elements = explode('|', str_replace('<br />', '|', nl2br($item['files'])));
                foreach ($elements as $val) {
                    $arr_element = array('item' => $item['id'], 'img' => 'items/' . $item['directory'] . '/' . trim($val));
                    if ($item['type'] == 'handheld' || $item['type'] == 'handbag' || $item['type'] == 'object') {
                        if (!empty($item['leftright']) && $item['type'] == 'handbag') {
                            $this->layers['under_hands'][] = $arr_element;
                        } else {
                            $this->layers['in_hands'][] = $arr_element;
                        }
                    } elseif ($item['type'] == 'scarf') {
                        $this->layers['slawes']['top'][] = $arr_element;
                    } elseif ($item['type'] == 'hat') {
                        $this->layers['in_head'][] = $arr_element;
                    } else {
                        $this->layers['body'][$type][] = $arr_element;
                    }
                }
            } else {
                $arr_element = array('item' => $item['id'], 'img' => 'items/' . $item['directory'] . '/' . trim($item['shortname'] . '.png'));
                if ($item['type'] == 'handheld' || $item['type'] == 'handbag' || $item['type'] == 'object') {
                    if (!empty($item['leftright']) && $item['type'] == 'handbag') {
                        $this->layers['under_hands'][] = $arr_element;
                    } else {
                        $this->layers['in_hands'][] = $arr_element;
                    }
                } elseif ($item['type'] == 'scarf') {
                    $this->layers['slawes']['top'][] = $arr_element;
                } elseif ($item['type'] == 'hat') {
                    $this->layers['in_head'][] = $arr_element;
                } else {
                    $this->layers['body'][$type][] = $arr_element;
                }
            }
        }
    }

    public function part_add_default_slawes($item, $type, $hand) {
        $col = ($this->doll[$hand] == 2) ? 'hold_sleeves_img' : 'stand_sleeves_img';
        if (!empty($item[$col])) {
            $elem_k = explode(']', $item[$col]);
            $elem_files = explode('|', str_replace(array('<br />', ','), '|', nl2br($elem_k[1])));
            $elem_keys = str_replace('[', '', $elem_k[0]);
            $elem_keys = explode(',', $elem_keys);
            foreach ($elem_keys as $key => $val) {
                if ($hand == trim($val)) {
                    $this->layers['slawes'][$type][] = array('item' => $item['id'], 'img' => 'items/' . $item['directory'] . '/' . trim($elem_files[$key + 1]));
                }
            }
        }
    }

    public function part_add_torso_squeeze($item, $type, $effect) {
        $return = false;
        if (!empty($item['clippings'])) {
            if (strpos($item['clippings'], '[') !== FALSE) {
                $elem_k = explode(']', $item['clippings']);
                $elem_files = explode('|', str_replace(array('<br />', ','), '|', nl2br($elem_k[1])));
                $elem_keys = str_replace('[', '', $elem_k[0]);
                $elem_keys = explode(',', $elem_keys);

                foreach ($elem_keys as $key => $val) {
                    if ($effect == trim($val)) {
                        $this->layers['body'][$type][] = array('item' => $item['id'], 'img' => 'items/' . $item['directory'] . '/' . trim($elem_files[$key + 1]));
                        $return = true;
                    }
                    if (trim($val) == 'back') {
                        $this->layers['back'][] = array('item' => $item['id'], 'img' => 'items/' . $item['directory'] . '/' . trim($elem_files[$key + 1]));
                    }
                }
            } elseif (!empty($item['clippings'])) {
                $elem_files = explode('|', str_replace(array('<br />', ','), '|', nl2br($item['clippings'])));

                foreach ($elem_files as $val) {
                    if (!empty($val)) {
                        $this->layers['body'][$type][] = array('item' => $item['id'], 'img' => 'items/' . $item['directory'] . '/' . trim($val));
                        $return = true;
                    }
                }
            }
        }
        return $return;
    }

    public function part_add_tightness() {
        $return = false;

        return $return;
    }

    public function part_add_sleeve_squeeze($item, $type, $effect) {
        $return = false;
        if (!empty($item['sleeve_clippings'])) {
            $elem_k = explode(']', $item['sleeve_clippings']);
            $elem_files = explode('|', str_replace(array('<br />', ','), '|', nl2br($elem_k[1])));
            $elem_keys = str_replace('[', '', $elem_k[0]);
            $elem_keys = explode(',', $elem_keys);
            foreach ($elem_keys as $key => $val) {
                if ($effect == $val) {
                    $this->layers['slawes'][$type][] = array('item' => $item['id'], 'img' => 'items/' . $item['directory'] . '/' . trim($elem_files[$key + 1]));
                    $return = true;
                }
            }
        }
        return $return;
    }

    public function part_add_tucked($item, $type) {
        if (!empty($item['tucked_torso_image'])) {
            $elements = explode('|', str_replace(array('<br />', ','), '|', nl2br($item['tucked_torso_image'])));
            foreach ($elements as $val) {
                if (!empty($val)) {
                    $this->layers['body'][$type][] = array('item' => $item['id'], 'img' => 'items/' . $item['directory'] . '/' . trim($val));
                }
            }
            return true;
        }
        return false;
    }

    /* --- POSE --- */

    public function pose_change($key, $val) {
        $this->doll[$key] = $val;
        $this->default_parts();
    }

    public function pose_get($hand) {
        $pose = (empty($this->doll[$hand])) ? 1 : $this->doll[$hand];
        return $pose;
    }

    public function remove_item($item) {
        unset($this->items[$item]);
        $all_items = array_keys($this->items);

        $this->clear_doll();
        $this->add_items($all_items);
    }

    public function sort_items($items) {
        $this->clear_doll();
        $this->add_items($items);
    }

    public function change_bg($bg_id) {
        $bg = $this->CI->db->get_where('dressup_items', array('id' => $bg_id))->row_array();
        $this->doll['background_id'] = $bg_id;
        $this->update_session();
        $this->doll['background'] = $bg['directory'] . '/500x350/' . $bg['files'];
    }

    public function change_hair($hair_id) {
        $this->doll['hair'] = $hair_id;
        $this->update_session();
    }

    public function find_haircolors() {
        $hair = $this->CI->db->get_where('dressup_items', array('id' => $this->doll['hair']))->row_array();
        $hair_num = preg_replace('/[\D]+/i', '', $hair['shortname']);
        $rez = $this->CI->db->query('SELECT * FROM dressup_items WHERE `type`="haircolors" AND shortname LIKE("hair' . $hair_num . '-%") OR shortname LIKE("hairstyle' . $hair_num . '-%")')->result_array();
        return $rez;
    }

    public function change_skin($item) {
        $skin = $this->CI->db->query('SELECT * FROM dressup_body_parts WHERE name="' . $item . '"')->row_array();
        if (!empty($skin)) {
            $this->doll['skincolor'] = $skin['skincolor'];
            $this->doll['body'] = $skin['name'];
        }
        //add eye and mouth
        $defaults = $this->CI->db->query('SELECT * FROM dressup_body_parts WHERE `default`="default" AND (`type`="mouth" OR `type`="eyes") AND skincolor="' . $skin['skincolor'] . '"')->result_array();
        if (!empty($defaults)) {
            foreach ($defaults as $val) {
                if ($val['type'] == 'eyes') {
                    $this->doll['eye'] = trim($val['name']);
                } elseif ($val['type'] == 'mouth') {
                    $this->doll['mouth'] = trim($val['name']);
                }
            }
        }
        $this->update_session();
    }

    public function change_face($item) {
        $item_name = $this->CI->db->query('SELECT * FROM dressup_body_parts WHERE name="' . $item . '"')->row_array();
        if (!empty($item_name)) {
            if ($item_name['type'] == 'eyes') {
                $this->doll['eye'] = trim($item_name['name']);
            } elseif ($item_name['type'] == 'mouth') {
                $this->doll['mouth'] = trim($item_name['name']);
            }
            $this->update_session();
        }
    }

    public function change_arm_layer($layer) {
        $this->doll['arms'] = $layer;
        $this->update_session();
    }

}