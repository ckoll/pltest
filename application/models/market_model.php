<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Market_model extends CI_Model {

    public function shop_subcategories($default = false) {
        $all = array();
        $def_where = (!$default) ? ' AND pid!=1 ' : ''; // remove default categories
        $subcats_rez = $this->db->query('SELECT * FROM dressup_category WHERE pid!=0 ' . $def_where)->result_array();
        $subcats = array();
        if (!empty($subcats_rez)) {
            foreach ($subcats_rez as $val) {
                $subcats[$val['pid']] = $val['id'];
                $all[$val['id']] = $val;
            }
        }

        $cats_rez = $this->db->query('SELECT * FROM dressup_category WHERE shortname!="default" AND pid NOT IN ("' . implode('","', array_keys($subcats)) . '") ' . $def_where)->result_array();

        if (!empty($cats_rez)) {
            foreach ($cats_rez as $val) {
                $all[$val['id']] = $val;
            }
        }
        return $all;
    }

    public function shop_up_categories($default = false) {
        $all = array();
        $disabled = array('haircolors', 'n/a', 'face', 'mouth', 'eyes', 'skin');
        foreach ($disabled as $val) {
            $where .= ' AND shortname != "' . $val . '"';
        }
        $def_where = (!$default) ? ' AND pid!=1 AND id!=1' : ''; // remove default categories
        $cats_rez = $this->db->query('SELECT * FROM dressup_category WHERE pid=0 ' . $def_where . $where)->result_array();

        if (!empty($cats_rez)) {
            foreach ($cats_rez as $val) {
                $all[$val['id']] = $val;
            }
        }
        return $all;
    }

    public function shop_categories() {
        $rez = array();
        $all = $this->db->query('SELECT * FROM dressup_category ORDER BY `order`,pid')->result_array();
        if (!empty($all)) {
            foreach ($all as $val) {
                $rez[$val['pid']][] = $val;
            }
        }
        return $rez;
    }

    public function body_part_categories() {
        $rez = array();
        $all = $this->db->query('SELECT * FROM body_parts_category ORDER BY pid')->result_array();
        if (!empty($all)) {
            foreach ($all as $val) {
                $rez[$val['pid']][] = $val;
            }
        }
        return $rez;
    }

    public function category_count_items($avaliable = NULL) {
        $where_avaliable = '';
        $rez = $this->db->query('SELECT COUNT(DISTINCT item_id) item_c, dressup_category.* FROM user_items 
            LEFT JOIN dressup_items ON user_items.item_id=dressup_items.id 
            LEFT JOIN dressup_category ON dressup_category.shortname=dressup_items.category 
            WHERE uid="' . $this->user['id'] . '" ' . $where_avaliable . ' 
                GROUP BY dressup_category.id')->result_array();
        if (!empty($rez)) {
            foreach ($rez as $val) {
                $all[$val['id']] = $val['item_c'];
            }
            return $all;
        }
    }

    public function category_count_default($items){
        $rez = $this->db->query('SELECT COUNT(dressup_items.id) counts, dressup_category.id FROM dressup_items LEFT JOIN dressup_category ON dressup_category.shortname=dressup_items.category WHERE category = "default" GROUP BY dressup_category.id')->result_array();
        if (!empty($rez)) {
            foreach ($rez as $val) {
                $items[$val['id']] = $val['counts'];
            }
        }
        return $items;
    }
    
    public function count_body_parts() {
        //add body parts
        $items = array();
        $dressup = $this->session->userdata('dressup');
        $doll_skin = $dressup['doll']['skincolor'];
        $parts = $this->db->query('SELECT * FROM dressup_body_parts WHERE skincolor="' . $doll_skin . '" OR (skincolor!="' . $doll_skin . '" AND `type`="skincolor")')->result_array();

        //add body categories
        $cat = $this->db->query('SELECT * FROM body_parts_category')->result_array();
        foreach ($cat as $v) {
            $categories[$v['shortname']] = $v['id'];
        }
        if (!empty($parts)) {
            foreach ($parts as $val) {
                if ($val['type'] == 'skincolor') {
                    //skin colors
                    $items[$categories['skin']] = intval($items[$categories['skin']]) + 1;
                } elseif ($val['type'] == 'eyes') {
                    //eyes
                    $items[$categories['eyes']] = intval($items[$categories['eyes']]) + 1;
                } elseif ($val['type'] == 'mouth') {
                    //mouth
                    $items[$categories['mouth']] = intval($items[$categories['mouth']]) + 1;
                }
            }
        }
        return $items;
    }

    public function get_pidcats($category) {
        $cat_id = $this->db->query('SELECT id FROM dressup_category WHERE shortname="' . $category . '"')->row()->id;
        $pid_cats = $this->db->query('SELECT shortname FROM dressup_category WHERE pid=' . $cat_id)->result_array();
        $return = array();
        if (empty($pid_cats)) {
            $return[] = $category;
        } else {
            foreach ($pid_cats as $pid_cat) {
                $return[] = $pid_cat['shortname'];
            }
        }
        return $return;
    }

    public function get_items($for_page, $page, $category, $shop = NULL, $username = NULL) {
        $order = $where = '';
        $disabled = array('haircolors', 'n/a');
        foreach ($disabled as $val) {
            $where .= ' AND dressup_items.type != "' . $val . '"';
        }

        $market_filter = $this->session->userdata('market_filter');
        if (!empty($market_filter) && !empty($shop)) {
            $market_filter = ($market_filter == 'buttons') ? 'price_b' : 'price_j';
        }

        if (!empty($category)) {
            $category = $this->get_pidcats($category);
            $where .= ' AND dressup_items.category IN ("' . implode('","', $category) . '")';
        }
        $market_sort = $this->session->userdata('market_sort');
        if (!empty($market_sort)) {
            if (!empty($market_filter)) {
                $ord_col = ($market_sort == 'price1') ? $market_filter : $market_filter . ' DESC';
            } else {
                $ord_col = ($market_sort == 'price1') ? 'price_j, price_b' : 'price_j DESC, price_b DESC';
            }
            $order = ' ORDER BY ' . $ord_col;
        }
        $begin = $page * $for_page;

        //remove default items 
        $where .= ' AND dressup_items.category !="default" AND category!="n/a"';

        if (empty($shop)) {
            //Perfectlook items
            if (!empty($market_filter)) {
                //buttons ot jewels filter
                $where .= ' AND `' . $market_filter . '`!=""';
            }
            $where .= ' AND CONCAT(items_shop.limit,items_shop.price_b,items_shop.price_j) != "000"';
            $items = $this->db->query('SELECT SQL_CALC_FOUND_ROWS item_id id, dressup_items.*, items_shop.* FROM dressup_items LEFT JOIN items_shop ON dressup_items.id=items_shop.item_id WHERE 1=1' . $where . $order . ' LIMIT ' . $begin . ',' . $for_page)->result_array();
        } elseif ($shop == 'users') {
            //User items
            if (!empty($market_filter)) {
                //buttons ot jewels filter
                $where .= ' AND user_items.`' . $market_filter . '`!=""';
            }
            $where .= ' AND status="sell"';
            $where .= ' AND user_items.uid!="' . $this->user['id'] . '"'; //Not show my items in usershop
            $items = $this->db->query('SELECT SQL_CALC_FOUND_ROWS user_items.id, dressup_items.*, user_items.id , user_items.price_b, users.username, dressup_items.id item_id FROM user_items LEFT JOIN dressup_items ON dressup_items.id=user_items.item_id LEFT JOIN users ON users.id=user_items.uid WHERE 1=1' . $where . $order . ' LIMIT ' . $begin . ',' . $for_page)->result_array();
        } elseif ($shop == 'one_user_shop') {
            //Get user id
            $this->load->model('user_model');
            $user = $this->user_model->get_user_info('username', $username);
            //User items
            if (!empty($market_filter)) {
                //buttons ot jewels filter
                $where .= ' AND user_items.`' . $market_filter . '`!=""';
            }
            $where .= ' AND status="sell" AND user_items.uid="' . $user['id'] . '"';
            $items = $this->db->query('SELECT SQL_CALC_FOUND_ROWS user_items.id, dressup_items.*, user_items.id , user_items.price_b, users.username, dressup_items.id item_id FROM user_items LEFT JOIN dressup_items ON dressup_items.id=user_items.item_id LEFT JOIN users ON users.id=user_items.uid WHERE 1=1' . $where . $order . ' LIMIT ' . $begin . ',' . $for_page)->result_array();
        } elseif ($shop == 'userauction' || $shop == 'one_userauction') {
            //User items
            if (!empty($market_filter)) {
                //buttons ot jewels filter
                $where .= ' AND user_items.`' . $market_filter . '`!=""';
            }
            $where .= ' AND status="auction"';
            $where .= ' AND (user_items.auction_date_end > "' . date('Y-m-d H:i:s') . '" || user_items.auction_reserve> IF(price_b,price_b,price_j))';
            if ($shop == 'userauction') {
                $where .= ' AND user_items.uid!="' . $this->user['id'] . '"'; //Not show my items in usershop
            } else {
                $this->load->model('user_model');
                $user = $this->user_model->get_user_info('username', $username);
                $where .= ' AND user_items.uid="' . $user['id'] . '"';
            }
            $items = $this->db->query('SELECT SQL_CALC_FOUND_ROWS user_items.id, dressup_items.*, user_items.id ui_id, price_b, price_j, user_items.auction_date_price, user_items.auction_date_end, user_items.auction_reserve, users.username, dressup_items.id item_id FROM user_items LEFT JOIN dressup_items ON dressup_items.id=user_items.item_id LEFT JOIN users ON users.id=user_items.uid WHERE 1=1' . $where . $order . ' LIMIT ' . $begin . ',' . $for_page)->result_array();
        } elseif ($shop == 'auction') {
            //User items
            if (!empty($market_filter)) {
                //buttons ot jewels filter
                $where .= ' AND `' . $market_filter . '`!=""';
            }
            $where .= ' AND items_auction.date_price!=""';
            $where .= ' AND (items_auction.date_end > "' . date('Y-m-d H:i:s') . '" || reserve> IF(price_b,price_b,price_j))';
            $items = $this->db->query('SELECT SQL_CALC_FOUND_ROWS dressup_items.*, items_auction.* FROM items_auction LEFT JOIN dressup_items ON items_auction.item_id = dressup_items.id WHERE 1=1' . $where . $order . ' LIMIT ' . $begin . ',' . $for_page)->result_array();
        }
        return $items;
    }

    public function get_usernames_array($user_ids) {
        $users = $this->db->query('SELECT id,username FROM users WHERE id IN (' . join(',', $user_ids) . ') ')->result_array();
        $return = array();
        foreach ($users as $user) {
            $return[$user['id']] = $user['username'];
        }
        return $return;
    }

    public function get_auction_item($id, $ret = false) {
        $item = $this->db->query('SELECT dressup_items.*, items_auction.* FROM items_auction LEFT JOIN dressup_items ON items_auction.item_id=dressup_items.id WHERE 1=1 AND items_auction.item_id = "' . $id . '"')->row_array();
        $return['preview'] = $item['directory'] . '/' . (($item['profileimage_dir'] == '[default]') ? 'profilepics' : $item['profileimage_dir']) . '/' . $item['profileimage'];
        $return['title'] = $item['item_name'];
        $return['bids'] = unserialize($item['date_price']);
        $return['bid_history'] = array();
        $bid_users = array();
        foreach ($return['bids'] as $v) {
            $bid_users[] = $v['id'];
        }
        $bid_users = $this->get_usernames_array($bid_users);

        foreach ($return['bids'] as $k => $v) {
            $return['bid_history'][$k] = $v;
            $return['bid_history'][$k]['username'] = $bid_users[$v['id']];
        }

        $last_bid = array_pop(unserialize($item['date_price']));
        $return['last_bid'] = $last_bid;
        $return['current_bid'] = $last_bid['price'];
        $return['count_bids'] = count($return['bids']) - 1;
        $return['duration'] = (($item['price_j'] == 0) ? 'buttons' : 'jewels');

        $return['reserve'] = $item['reserve'];

        $return['time_left'] = unix_left_time_to_date($item['date_end']);
        if (!$ret)
            echo json_encode($return);
        else
            return $return;
    }

    public function get_userauction_item($id, $ret = false) {
        $item = $this->db->query('SELECT user_items.id, dressup_items.*, user_items.id, user_items.price_b, user_items.price_j, user_items.auction_date_price, user_items.auction_date_end, user_items.auction_reserve, users.username, dressup_items.id item_id FROM user_items LEFT JOIN dressup_items ON dressup_items.id=user_items.item_id LEFT JOIN users ON users.id=user_items.uid WHERE 1=1 AND user_items.id = "' . $id . '"')->row_array();
        $return['preview'] = $item['directory'] . '/' . (($item['profileimage_dir'] == '[default]') ? 'profilepics' : $item['profileimage_dir']) . '/' . $item['profileimage'];
        $return['title'] = $item['item_name'];
        $return['bids'] = unserialize($item['auction_date_price']);
        $return['bid_history'] = array();
        $bid_users = array();
        foreach ($return['bids'] as $v) {
            $bid_users[] = $v['id'];
        }

        $bid_users = $this->get_usernames_array($bid_users);

        foreach ($return['bids'] as $k => $v) {
            $return['bid_history'][$k] = $v;
            $return['bid_history'][$k]['username'] = $bid_users[$v['id']];
        }

        $last_bid = array_pop(unserialize($item['auction_date_price']));
        $return['last_bid'] = $last_bid;
        $return['current_bid'] = $last_bid['price'];
        $return['count_bids'] = count($return['bids']) - 1;
        $return['duration'] = (!empty($item['price_b']) ? 'buttons' : 'jewels');

        $return['reserve'] = $item['auction_reserve'];

        $return['time_left'] = unix_left_time_to_date($item['auction_date_end']);
        if (!$ret)
            echo json_encode($return);
        else
            return $return;
    }

    public function item_info($item_id) {
        return $this->db->query('SELECT dressup_items.*, items_shop.* FROM items_shop LEFT JOIN dressup_items ON dressup_items.id=items_shop.item_id WHERE item_id="' . $item_id . '"')->row_array();
    }

    public function auction_item_info($item_id) {
        return $this->db->query('SELECT items_auction.*, dressup_items.item_name,dressup_items.category,dressup_items.directory,dressup_items.profileimage_dir,dressup_items.profileimage FROM items_auction LEFT JOIN dressup_items ON dressup_items.id=items_auction.item_id WHERE items_auction.item_id="' . $item_id . '"')->row_array();
    }

    public function user_item_info($item_id) {
        return $this->db->query('SELECT user_items.*,dressup_items.item_name,dressup_items.id item_id,dressup_items.category,dressup_items.directory,dressup_items.profileimage_dir,dressup_items.profileimage,dressup_items.shortname FROM user_items LEFT JOIN dressup_items ON dressup_items.id=user_items.item_id WHERE user_items.id="' . $item_id . '"')->row_array();
    }

    public function market_filter($type) {
        $this->session->set_userdata(array('market_filter' => $type));
        //redirect page
        $page = $this->_redirect_page();
        echo json_encode($page);
    }

    public function market_sort($sort) {
        $this->session->set_userdata(array('market_sort' => $sort));
    }

    public function check_buy_item($item_id, $return = false) {
        $rez = array();
        $item = $this->item_info($item_id);

        $preview = ($item['profileimage_dir'] == '[default]') ? 'profilepics' : $item['profileimage_dir'];
        $preview .= '/' . $item['profileimage'];

        if ($item['limit'] == 0) {
            $rez['err'] = 'Sorry, not available';
            $rez['errtype'] = 'available';
        } elseif (!empty($item['price_b'])) {
            if ($this->user['buttons'] < $item['price_b']) {
                $rez['err'] = 'Sorry, but you don\'t have enough buttons :(';
                $rez['errtype'] = 'buttons';
            } else {

                $rez['data'] = array('price' => $item['price_b'] . ' buttons', 'title' => $item['item_name'], 'category' => $item['category'], 'preview' => 'items/' . $item['directory'] . '/' . $preview, 'id' => $item['id'], 'item_id' => $item['id']);
            }
        } elseif (!empty($item['price_j'])) {
            if ($this->user['jewels'] < $item['price_j']) {
                $rez['err'] = 'Sorry, but you don\'t have enough jewels :(';
                $rez['errtype'] = 'jewels';
            } else {
                $rez['data'] = array('price' => $item['price_j'] . ' jewels', 'title' => $item['item_name'], 'category' => $item['category'], 'preview' => 'items/' . $item['directory'] . '/' . $preview, 'id' => $item['id'], 'item_id' => $item['id']);
            }
        } else {
            $rez['data'] = array('price' => 'free', 'title' => $item['item_name'], 'category' => $item['category'], 'id' => $item['id'], 'preview' => 'items/' . $item['directory'] . '/' . $preview, 'item_id' => $item['id']);
        }
        if ($return) {
            return $rez;
        } else {
            echo json_encode($rez);
        }
    }

    public function buy_item($item_id) {
        $this->load->library('buttons');
        $rez = $this->check_buy_item($item_id, true);
        if (empty($rez['err'])) {
            $item = $this->item_info($item_id);
            if (!empty($item['price_b'])) {
                $type = 'buttons';
                $count = $item['price_b'];
            } elseif (!empty($item['price_j'])) {
                $type = 'jewels';
                $count = $item['price_j'];
            } else {
                $type = 'buttons';
                $count = 0;
            }
            $this->buttons->remove_money($this->user['id'], $count, $type);
            $this->buttons->write_history($this->user['id'], array('action' => 'buy_item', 'jewels' => $this->user['jewels'], 'now_jewels' => (($type == 'jewels') ? ($this->user['jewels'] - $count) : $this->user['jewels']), 'buttons' => $this->user['buttons'], 'now_buttons' => (($type == 'buttons') ? ($this->user['buttons'] - $count) : $this->user['buttons']), 'description' => 'Buying item: <a href="/item/' . $item['shortname'] . '" target="_blank">' . $item['item_name'] . '</a>'));
            $ins_item = array('uid' => $this->user['id'], 'item_id' => $item['id']);
            $this->db->insert('user_items', $ins_item); //add item for me
            $this->db->query('UPDATE items_shop SET `limit`=`limit`-1 WHERE item_id="' . $item_id . '"'); //limit - 1
            echo json_encode(array('ok' => 1));
        } else {
            echo json_encode(array('err' => 1));
        }
    }

    public function check_buy_useritem($item_id, $return = false) {
        $rez = array();
        $item = $this->user_item_info($item_id);

        $preview = ($item['profileimage_dir'] == '[default]') ? 'profilepics' : $item['profileimage_dir'];
        $preview .= '/' . $item['profileimage'];

        if (!empty($item['price_b'])) {
            if ($this->user['buttons'] < $item['price_b']) {
                $rez['err'] = 'Sorry, but you don\'t have enough buttons :(';
                $rez['errtype'] = 'buttons';
            } else {
                $rez['data'] = array('price' => $item['price_b'] . ' buttons', 'title' => $item['item_name'], 'category' => $item['category'], 'id' => $item['id'], 'item_id' => $item['item_id'], 'preview' => 'items/' . $item['directory'] . '/' . $preview);
            }
        } elseif (!empty($item['price_j'])) {
            if ($this->user['jewels'] < $item['price_j']) {
                $rez['err'] = 'Sorry, but you don\'t have enough jewels :(';
                $rez['errtype'] = 'jewels';
            } else {
                $rez['data'] = array('price' => $item['price_j'] . ' jewels', 'title' => $item['item_name'], 'category' => $item['category'], 'id' => $item['id'], 'item_id' => $item['item_id'], 'preview' => 'items/' . $item['directory'] . '/' . $preview);
            }
        } else {
            $rez['data'] = array('price' => 'free', 'title' => $item['item_name'], 'category' => $item['category'], 'id' => $item['id'], 'item_id' => $item['item_id'], 'preview' => 'items/' . $item['directory'] . '/' . $preview);
        }

        if ($return) {
            return $rez;
        } else {
            echo json_encode($rez);
        }
    }

    public function buy_user_item($item_id) {
        $this->load->library('buttons');
        $rez = $this->check_buy_useritem($item_id, true);
        if (empty($rez['err'])) {
            $item = $this->user_item_info($item_id);
            if (!empty($item['price_b'])) {
                $type = 'buttons';
                $count = $item['price_b'];
            } elseif (!empty($item['price_j'])) {
                $type = 'jewels';
                $count = $item['price_j'];
            } else {
                $type = 'buttons';
                $count = 0;
            }

            $user_seller = $this->user_model->get_user_info('id', $item['uid']);

            $this->buttons->write_history($this->user['id'], array('action' => 'buy_user_item', 'jewels' => $this->user['jewels'], 'now_jewels' => (($type == 'jewels') ? ($this->user['jewels'] - $count) : $this->user['jewels']), 'buttons' => $this->user['buttons'], 'now_buttons' => (($type == 'buttons') ? ($this->user['buttons'] - $count) : $this->user['buttons']), 'description' => 'Buying user item: <a href="/item/' . $item['shortname'] . '" target="_blank">' . $item['item_name'] . '</a>'));
            $this->buttons->write_history($item['uid'], array('action' => 'sold_item', 'jewels' => $user_seller['jewels'], 'now_jewels' => (($type == 'jewels') ? ($user_seller['jewels'] + $count) : $user_seller['jewels']), 'buttons' => $user_seller['buttons'], 'now_buttons' => (($type == 'buttons') ? ($user_seller['buttons'] + $count) : $user_seller['buttons']), 'description' => 'Sold item: <a href="/item/' . $item['shortname'] . '" target="_blank">' . $item['item_name'] . '</a>'));

            $this->buttons->remove_money($this->user['id'], $count, $type);
            $this->buttons->add_money($item['uid'], $count, $type);

            $this->db->where('id', $item_id);
            $this->db->update('user_items', array('uid' => $this->user['id'], 'status' => '', 'price_b' => 0, 'price_j' => 0));
            //send notification for seller
            echo json_encode(array('ok' => 1));
        } else {
            echo json_encode(array('err' => 1));
        }
    }

    public function check_bid_item($item_id, $auction_type) {
        if ($auction_type == 'userauction')
            $item = $this->get_userauction_item($item_id, true);
        else
            $item = $this->get_auction_item($item_id, true);
        $err = '';
        if ($item['time_left'] < 1 && $item['last_bid']['price'] > $item['reserve']) {
            $err = 'Auction closed.';
        } elseif ($item['last_bid']['price'] >= $this->input->post('bid') && $item['last_bid']['price'] < 100) {
            $err = 'You bid must be greater than last';
        } elseif ($item['last_bid']['price'] > 99 && $item['last_bid']['price'] < 1000 && $this->input->post('bid') < ($item['last_bid']['price'] + 5)) {
            $err = 'You bid should be greater than ' . ($item['last_bid']['price'] + 5) . ' ' . $item['duration'];
        } elseif ($item['last_bid']['price'] >= 1000 && $this->input->post('bid') < ($item['last_bid']['price'] + 10)) {
            $err = 'You bid should be greater than ' . ($item['last_bid']['price'] + 10) . ' ' . $item['duration'];
        } elseif ($item['duration'] == 'buttons' && $this->user['buttons'] < $this->input->post('bid')) {
            $err = 'Sorry, but you don\'t have enough buttons :(';
        } elseif ($item['duration'] == 'jewels' && $this->user['jewels'] < $this->input->post('bid')) {
            $err = 'Sorry, but you don\'t have enough jewels :(';
        }

        return $err;
    }

    public function find_sell_item($id) {
        $shop = $this->db->query('SELECT user_items.*,users.username FROM user_items LEFT JOIN users ON users.id=user_items.uid WHERE item_id="' . $id . '" AND status="sell" ')->result_array(); //AND uid!="'.$this->user['id'].'"
        $auction = $this->db->query('SELECT user_items.*,users.username FROM user_items LEFT JOIN users ON users.id=user_items.uid WHERE item_id="' . $id . '" AND status="auction" ')->result_array();
        return array('shop' => $shop, 'auction' => $auction);
    }

    public function bid_item($item_id, $auction_type) {
        $this->load->library('buttons');

        if ($auction_type == 'userauction') {
            $item = $this->user_item_info($item_id);
        } else {
            $item = $this->auction_item_info($item_id);
        }

        $rez = $this->check_bid_item($item_id, $auction_type);
        $pref = ($auction_type == 'userauction') ? 'auction_' : '';

        if (empty($rez)) {
            $bids = unserialize($item[$pref . 'date_price']);
            $type = ((empty($item['price_j'])) ? 'buttons' : 'jewels');
            $db_col_type = ((empty($item['price_j'])) ? 'price_b' : 'price_j');


            if (count($bids) > 1) {
                $last_bid = array_pop(unserialize($item[$pref . 'date_price']));
                $last_bid_id = $last_bid['id'];
                $last_bid_price = $last_bid['price'];

                $this->buttons->add_money($last_bid_id, $last_bid_price, $type);
            }
            $bids[date('Y-m-d H:i:s')] = array('id' => $this->user['id'], 'price' => $this->input->post('bid'));

            $this->buttons->remove_money($this->user['id'], $this->input->post('bid'), $type);
            $bids = serialize($bids);
            $table = (($auction_type == 'auction') ? 'items_auction' : 'user_items');
            $key = (($auction_type == 'auction') ? 'item_id' : 'id');
            $this->db->query('UPDATE `' . $table . '` SET `' . $pref . 'date_price` = "' . mysql_real_escape_string($bids) . '", ' . $db_col_type . '="' . $this->input->post('bid') . '" WHERE ' . $key . '="' . ((($auction_type == 'auction') ? $item_id : $item['id'])) . '"');
            echo json_encode(array('ok' => 1));
        } else {
            echo json_encode(array('err' => $rez));
        }
    }

    public function _redirect_page() {
        if (strpos($_SERVER['HTTP_REFERER'], 'page=') == false) {
            return $_SERVER['HTTP_REFERER'];
        }
        $url = explode('?', $_SERVER['HTTP_REFERER']);
        if (!empty($url[1])) {
            $params = explode('&', $url[1]);
            $result = array();
            foreach ($params as $val) {
                if (strpos($val, 'page') === false) {
                    $result[] = $val;
                }
            }
            return $url[0] . '?' . implode('&', $result);
        } else {
            return $_SERVER['HTTP_REFERER'];
        }
    }

    public function calck_counts($items) {
        $all = $sell = $dress = 0;
        if (!empty($items)) {
            foreach ($items as $val) {
                $all += $val['counts'];
                if ($val['status'] == 'sell') {
                    $sell += $val['counts'];
                } elseif ($val['status'] == 'dress') {
                    $dress += $val['counts'];
                }
            }
        }
        return array('all' => $all, 'sell' => $sell, 'dress' => $dress);
    }

    public function get_my_items($category = NULL) {
        if (!empty($category)) {
            $subcat = $this->db->query('SELECT shortname FROM dressup_category WHERE pid=(SELECT id FROM dressup_category WHERE shortname="'.$category.'")')->result_array();
            if(!empty($subcat)){
                foreach($subcat as $val){
                    $cats[] = $val['shortname'];
                }
                $where = ' AND category IN ("' . implode('","',$cats) . '")';
            }else{
                $where = ' AND category="' . $category . '"';
            }           
        }
        $rez = $this->db->query('SELECT user_items.status,dressup_items.*, COUNT(DISTINCT dressup_items.id) counts,user_items.price_b,user_items.price_j, user_items.auction_date_end, dressup_items.profileimage_dir, dressup_items.profileimage, dressup_items.directory 
            FROM user_items LEFT JOIN dressup_items ON dressup_items.id=user_items.item_id 
            WHERE uid="' . $this->user['id'] . '" ' . $where . ' AND category!="default" GROUP BY item_id, status')->result_array();
        return $rez;
    }

    public function add_default_items($items, $category = NULL) {
        if (!empty($category)) {
            $where = ' AND category="' . $category . '"';
        }
        $def = $this->db->query('SELECT "default" status, dressup_items.*,"1" counts FROM dressup_items WHERE category ="default" ' . $where)->result_array();
        return array_merge($items, $def);
    }

    public function calck_all_items() {
        $count1 = $this->db->query('SELECT user_items.id FROM user_items WHERE uid="' . $this->user['id'] . '"')->num_rows();
        $count2 = $this->db->query('SELECT 1 FROM dressup_items WHERE category="default"')->num_rows();
        return $count1 + $count2;
    }

    public function calck_enabled_items($item_id) {
        return $this->db->query('SELECT 1 FROM user_items WHERE uid="' . $this->user['id'] . '" AND item_id="' . $item_id . '" AND (status="" || status="sell")')->num_rows();
    }

    public function calck_one_items($item_id) {
        return $this->db->query('SELECT 1 FROM user_items WHERE uid="' . $this->user['id'] . '" and item_id="' . $item_id . '"')->num_rows();
    }

    public function delete_item($id) {
        $this->db->query('DELETE FROM user_items WHERE uid="' . $this->user['id'] . '" AND item_id="' . $id . '" AND status="" LIMIT 1');
    }

    public function rem_selling($id) {
        $this->db->query('UPDATE user_items SET status="", price_b=0, price_j=0 WHERE uid="' . $this->user['id'] . '" AND item_id="' . $id . '" AND status="sell" LIMIT 1');
    }

    public function rem_auction($id) {
        $this->db->query('UPDATE user_items SET status="", price_b=0, price_j=0,  auction_date_price="", auction_date_end=0, auction_reserve=0 WHERE uid="' . $this->user['id'] . '" AND item_id="' . $id . '" AND status="auction" LIMIT 1');
    }

    public function item_average_price($item) {
        $rez = $this->db->query('SELECT AVG(price_b) `aver` FROM user_items WHERE price_b!=0 AND item_id="' . $item . '" AND uid!="' . $this->user['id'] . '"')->row_array();
        if ($rez['aver'] == 0) {
            $rez['aver'] = $this->db->query('SELECT price_b FROM items_shop WHERE item_id="' . $item . '"')->row()->price_b;
            if (empty($rez['aver'])) {
                $rez['aver'] = 10; //default price
            }
        }
        $price = ceil($rez['aver'] * 0.1);
        return array('price' => $price);
    }

    public function sell_option1($count, $id) {
        $err = '';
        $price = $this->item_average_price($id);
        $item_info = $this->item_info($id);
        $all = $this->db->query('SELECT 1 FROM user_items WHERE item_id="' . $id . '" AND status="" AND uid="' . $this->user['id'] . '"')->num_rows();
        if ($all < $count) {
            $err = true;
        } else {
            $this->load->library('buttons');
            $this->db->query('DELETE FROM user_items WHERE item_id="' . $id . '" AND status="" AND uid="' . $this->user['id'] . '" LIMIT ' . $count);
            $total_price = $price['price'] * $count;
            $this->buttons->add_money($this->user['id'], $total_price);
            $this->buttons->write_history($this->user['id'], array('action' => 'sell_option1', 'jewels' => $this->user['jewels'], 'now_jewels' => $this->user['jewels'], 'buttons' => $this->user['buttons'], 'now_buttons' => ($this->user['buttons'] + $total_price), 'description' => 'Sold ' . $count . ' item\'s: <a href="/item/' . $item_info['shortname'] . '" target="_blank">' . $item_info['item_name'] . '</a>'));
        }
        return array('err' => $err);
    }

    public function sell_option2($count, $id, $price) {
        $err = '';
        $all = $this->db->query('SELECT 1 FROM user_items WHERE item_id="' . $id . '" AND status="" AND uid="' . $this->user['id'] . '"')->num_rows();
        if ($all < $count) {
            $err = true;
        } else {
            $this->db->query('UPDATE user_items SET price_b="' . $price . '", status="sell" WHERE item_id="' . $id . '" AND status="" AND uid="' . $this->user['id'] . '" LIMIT ' . $count);
        }
        return array('err' => $err);
    }

    public function edit_sell_option2($count, $id, $price) {
        $all = $this->db->query('SELECT 1 FROM user_items WHERE item_id="' . $id . '" AND status="sell" AND uid="' . $this->user['id'] . '"')->num_rows();
        if ($count == $all) {
            $this->db->query('UPDATE user_items SET price_b="' . $price . '" WHERE item_id="' . $id . '" AND status="sell" AND uid="' . $this->user['id'] . '"');
        } elseif ($count > $all) {
            $more = $count - $all;
            $this->db->query('UPDATE user_items SET price_b="' . $price . '" WHERE item_id="' . $id . '" AND status="sell" AND uid="' . $this->user['id'] . '"');
            $this->db->query('UPDATE user_items SET price_b="' . $price . '",status="sell" WHERE item_id="' . $id . '" AND status="" AND uid="' . $this->user['id'] . '" LIMIT ' . $more);
        } else {
            $more = $all - $count;
            $this->db->query('UPDATE user_items SET price_b=0, status="" WHERE item_id="' . $id . '" AND status="sell" AND uid="' . $this->user['id'] . '" LIMIT ' . $more);
            $this->db->query('UPDATE user_items SET price_b="' . $price . '" WHERE item_id="' . $id . '" AND status="sell" AND uid="' . $this->user['id'] . '"');
        }
        return ;
    }

    public function add_auction($id, $start_price, $reserve, $price_type, $duration) {
        $err = '';
        $all = $this->db->query('SELECT 1 FROM user_items WHERE item_id="' . $id . '" AND status="" AND uid="' . $this->user['id'] . '"')->num_rows();
        if ($all < 1) {
            $err = 'Sorry, you haven\'t item';
        } else {
            if ($start_price <= 0)
                $err = 'Start price must be greater than 0';
            elseif ($reserve < 0)
                $err = 'Reserve must be greater than 0 or 0 if you would no met reserve';
            elseif ($price_type != 'price_b' && $price_type != 'price_j')
                $err = 'Price type must be buttons or jewels';
            elseif ($duration != 6 && $duration != 12 && $duration != 1 && $duration != 2 && $duration != 3)
                $err = 'Duration is not availible';
            else {
                $sql_interval = null;
                switch ($duration) {
                    case 1:case 2:case 3:
                        $sql_interval = ' DAY';
                        break;
                    case 6:case 12:
                        $sql_interval = ' HOUR';
                        break;
                }
                $auction_date_price = mysql_real_escape_string(serialize(array(date('Y-m-d H-i-s') => array('id' => $this->user['id'], 'price' => $start_price))));
                $this->db->query('UPDATE user_items SET status="auction", `' . $price_type . '`=' . $start_price . ', auction_date_end = DATE_ADD("' . date('Y-m-d H:i:s') . '", INTERVAL ' . $duration . $sql_interval . '), auction_reserve=' . $reserve . ', auction_date_price = "' . $auction_date_price . '" WHERE item_id="' . $id . '" AND status="" AND uid="' . $this->user['id'] . '" LIMIT 1');
            }
        }
        return array('err' => $err);
    }

    /* -------------- Buy Jewels width PayPal ---------------- */

    function buy_jewels_paypal() {

        $jewels_price = array('10' => 1, '25' => 2, '75' => 5, '150' => 10, '400' => 20);
        $count_jewels = $this->input->post('jewels_count');
        if (array_key_exists($count_jewels, $jewels_price)) {

            $total_price = $jewels_price[$count_jewels];
            $db_order_data = array(
                'uid' => $this->user['id'],
                'total_price' => $total_price,
                'date' => date('Y-m-d H:i:s')
            );
            $this->db->insert('order_jewels', $db_order_data);
            $order_id = $this->db->insert_id();

            $requestParams = array(
                'RETURNURL' => base_url() . 'explore/buy_jewels/?id=' . $order_id,
                'CANCELURL' => base_url() . 'explore/buy_jewels/'
            );
            $orderParams = array(
                'PAYMENTREQUEST_0_AMT' => number_format($total_price, 2),
                'PAYMENTREQUEST_0_SHIPPINGAMT' => '0',
                'PAYMENTREQUEST_0_CURRENCYCODE' => 'USD',
                'PAYMENTREQUEST_0_ITEMAMT' => number_format($total_price, 2),
                'PAYMENTREQUEST_0_SHIPDISCAMT' => '0',
                'PAYMENTREQUEST_0_PAYMENTREQUESTID' => $order_id,
                'PAYMENTREQUEST_0_PAYMENTACTION' => 'Sale',
                'L_PAYMENTREQUEST_0_NAME0' => 'Buy jewels',
                'L_PAYMENTREQUEST_0_AMT0' => number_format($total_price, 2),
                'L_PAYMENTREQUEST_0_QTY0' => 1
            );



            $this->load->library('paypal');
            $response = $this->paypal->request('SetExpressCheckout', $requestParams + $orderParams);

            if (is_array($response) && $response['ACK'] == 'Success') {
                $token = $response['TOKEN'];
                //                  header('Location: https://www.paypal.com/webscr?cmd=_express-checkout&token=' . urlencode($token));
                header('Location: https://www.sandbox.paypal.com/webscr?cmd=_express-checkout&token=' . urlencode($token));
                exit;
            } else {
                header('Location: /explore/buy_jewels');
                exit;
            }
        } else {
            return false;
        }
    }

    function buy_jewels_order_check_paypal() {
        if (isset($_GET['token']) && !empty($_GET['token'])) {
            $this->load->library('paypal');
            $checkoutDetails = $this->paypal->request('GetExpressCheckoutDetails', array('TOKEN' => $_GET['token']));
            $requestParams = array(
                'PAYMENTREQUEST_0_PAYMENTACTION' => 'Sale',
                'PAYERID' => $_GET['PayerID'],
                'TOKEN' => $_GET['token'],
                'PAYMENTREQUEST_0_AMT' => $checkoutDetails['PAYMENTREQUEST_0_AMT']
            );
            $response = $this->paypal->request('DoExpressCheckoutPayment', $requestParams);

            if (is_array($response) && $response['ACK'] == 'Success') {

//              $transactionId = $response['PAYMENTINFO_0_TRANSACTIONID'];
                $id = @$checkoutDetails['PAYMENTREQUESTINFO_0_PAYMENTREQUESTID'];
                if (!empty($id)) {
                    $jewels_price = array('10' => 1, '25' => 2, '75' => 5, '150' => 10, '400' => 20);
                    $order = $this->db->query('SELECT total_price FROM order_jewels WHERE id=' . $_GET['id'] . ' AND uid=' . $this->user['id'])->row_array();
                    $count_jewels = array_search($order['total_price'], $jewels_price);
                    $this->load->library('buttons');
                    $this->buttons->add_money($this->user['id'], $count_jewels, 'jewels');
                    $this->buttons->write_history($this->user['id'], array('action' => 'order_jewels', 'jewels' => $this->user['jewels'], 'now_jewels' => ($count_jewels + $this->user['jewels']), 'buttons' => $this->user['buttons'], 'now_buttons' => $this->user['buttons'], 'description' => 'Buy width PayPal'));
                    $this->db->update('order_jewels', array('status' => 1), array('id' => $_GET['id']));

                    header('Location: /explore/buy_jewels/?order=1');
                    exit;
                } else {
                    header('Location: /explore/buy_jewels/?order=0');
                    exit;
                }
            }
        }
    }

    public function buy_jewels_order_check_2checkout() {
        $db_2co_sett = array('secret_word' => 'tango');
        $prID_Jewels = array('2' => 10, '3' => 25, '4' => 75, '5' => 150, '6' => 400);
        $respons_array = array('sid' => $this->input->get('sid'), 'product_id' => $this->input->get('product_id'), 'order_number' => $this->input->get('order_number'), 'total' => $this->input->get('total'), 'key' => $this->input->get('key'));
        if ($this->input->get('demo') == 'Y') {
            $respons_array['order_number'] = 1;
        }

        $md5hash = strtoupper(md5($db_2co_sett['secret_word'] . $respons_array['sid'] . $respons_array['order_number'] . $respons_array['total']));
        if ($respons_array['key'] == $md5hash) {
            $count_jewels = $prID_Jewels[$respons_array['product_id']] * ($this->input->get('quantity'));
            $this->load->library('buttons');
            $this->buttons->add_money($this->user['id'], $count_jewels, 'jewels');
            $this->buttons->write_history($this->user['id'], array('action' => 'order_jewels', 'jewels' => $this->user['jewels'], 'now_jewels' => ($count_jewels + $this->user['jewels']), 'buttons' => $this->user['buttons'], 'buttons' => $this->user['buttons'], 'description' => 'Buy width 2checkout'));
            header('Location: /explore/buy_jewels/?order=1');
            exit;
        } else {
            header('Location: /explore/buy_jewels/?order=0');
            exit;
        }
    }

    /* -------------- END --- Buy Jewels width PayPal -------- */
}
