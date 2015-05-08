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
		$param = func_get_args();	// code 만 받기
		if ($param) {
			$query = $this->db->query("select * from category where code='{$param[0]}'");
			debug($query->result_array());
		} else {
	//		$query = $this->db->query("SELECT * FROM  category LIMIT 0 , 10");
			$query = $this->db->query("SELECT * FROM  category");
			debug($query->result_array());
		}
//		$this->db->query("update category set file='/date/en/movie/2008_kung_fu_panda_11.js' where code='en003_001'");
	}
	public function update() {
		$this->db->query("update category set file='/data/en/1001/ch07.js' where code='en002_007'");
	}
	public function calendar() {
		echo $this->calendar->generate();
	}
	public function get_sess() {
		$s = $this->session->all_userdata();
		debug($s);
	}
	public function get() {
		$gets = $this->input->get();
		debug($gets);
	}
	public function post() {
		$post = $this->input->post();
		debug($post);
	}
	public function test()
	{
		$s = $this->session->userdata('u');
        $this->load->model('m_dictation','md');
		$cate = $this->md->get_user_category2($s['uid']);
		debug($cate);

//		$a = func_get_args();
//		$hour = intval($a[0]);
//		$h = "+{$hour} hours";
//		debug(date("Y-m-d H:i:s",strtotime ($h)));
	}
	function get_script()
	{
		$param = func_get_args();
		
		if (! empty($param) && isset($param[0]) && ! empty($param[0]) ) {
			$query = $this->db->query("select * from script where code='{$param[0]}'");
		} else {
			$query = $this->db->query("select * from script");
		}
		$list = $query->result_array();
		echo "<table>";
		echo "<tr>";
		echo "<td>code</td><td>seq</td><td>mp3</td><td>from</td><td>to</td><td>script</td><td>trans</td>";
		echo "</tr>";
		foreach($list as $v) {
			echo "<tr>";
			echo "<td>{$v['code']}</td>";
			echo "<td>{$v['seq']}</td>";
			echo "<td>{$v['mp3']}</td>";
			echo "<td>{$v['from']}</td>";
			echo "<td>{$v['to']}</td>";
			echo "<td>{$v['script']}</td>";
			echo sprintf("<td>%s</td>",(empty($v['tran']) ? "&nbsp;" : $v['trans']));
			echo "</tr>";
		}
		echo "</table>";
	}
}
