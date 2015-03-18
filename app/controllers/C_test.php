<?php defined('BASEPATH') OR exit('No direct script access allowed');

class C_test extends CI_Controller {
	public function __construct() {
		parent::__construct();
	}
	public function index() {
		debug("test");
	}
	public function args() {
		debug(func_get_args());
	}
	public function phpinfo() {
		echo phpinfo();
	}
	public function db() {
		$query = $this->db->query('SELECT * FROM  category LIMIT 0 , 10');
		debug($query->result_array());
	}
}