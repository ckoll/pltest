<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tpl {

    public $gtpl, $ltpl, $data;
    protected $CI;

    public function __construct() {
        $this->gtpl = 'main';
        $this->CI = & get_instance();
    }

    public function show($data = array()) {
        $this->data = $data;
        $this->CI->load->view($this->gtpl . '.php', $data);
    }

    public function show_block($block) {
        if (is_array($this->ltpl[$block])) {
            foreach ($this->ltpl[$block] as $val) {
                if (is_file(APPPATH . 'views/' . $block . '/' . @$val . '.php')) {
                    $this->CI->load->view($block . '/' . $val . '.php', $this->data);
                }
            }
        }elseif(isset($this->ltpl[$block])) {
            if (is_file(APPPATH . 'views/' . $block . '/' . @$this->ltpl[$block] . '.php')) {
                $this->CI->load->view($block . '/' . $this->ltpl[$block] . '.php', $this->data);
            } else {
                echo 'File "' . $block . '/' . $this->ltpl[$block] . '.php" NOT FOUND';
            }
        } else {
            foreach($this->ltpl as $dir => $file) {
                $this->CI->load->view($dir . '/' . $file . '.php', $this->data);
            }
        }
    }

    public function render_blocks($block) {
        $this->CI->load->library($block);
        if (is_array($this->ltpl[$block])) {
            foreach ($this->ltpl[$block] as $val) {
                $data = $this->CI->$block->$val();
                $this->CI->load->view($block . '/' . $val . '.php', $data);
            }
        } else {
            $method = $this->ltpl[$block];
            $data = $this->CI->$block->$method();
            $this->CI->load->view($block . '/' . $method . '.php', $data);
        }
    }

}