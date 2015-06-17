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
	public function sql() {
		$u = $this->session->userdata('u');
		if (empty($u)) {
			redirect('/C_dictation', 'refresh');
		} else {
			if ($u['permit'] != 9) {
				exit();
			}
			$q = $this->input->post('q');
			$q = trim(str_replace("\n", "", $q));
			$data = array('sql'=>$q);
			if (! empty($q)) {
//	$q = $this->db->query("SELECT s.code, count(*), c.code FROM script s left join category c on c.code = s.code where c.code is null group by s.code order by s.code");
				$data['res'] = $this->db->query($q)->result();
			}
			$this->load->view('test', $data);
		}
	}
	public function db() {
//		$this->db->query("update category set dir='/data/en/ted/', js='30days.js' where code='en001_001'");
//		$this->db->query("update script set mp3='http://pds27.egloos.com/pds/201506/01/47/ted_30days_MattCutts.mp3' where code='en001_001'");
		exit();

//		$tables = $this->db->list_tables();
//		debug($tables);

//		debug($this->db->query("desc user_logs")->result());
//		debug($this->db->query("desc category")->result());
//		debug($this->db->query("select * from category where depth='3'")->result());

		$script = $this->db->query("select * from script where code='en003_001'")->result_array();
		debug($script);
exit();

		foreach($script as $s) {
			$this->db->where('seq', $s['seq']);
			$this->db->update('script', array('dir'=>''));
		}
		exit();


		$query = $this->db->query("SELECT * FROM  category where pcode='en005'");
		echo "<xml>";
		foreach($row = $query->result_array() as $v){
			echo "{$v['code']}\t{$v['name']}<br>";
		}
		echo "</xml>";
	}
	public function admin()
	{
		$this->load->view("dictation/admin/test");
		/*
		$u = $this->session->userdata('u');
		$is_admin = (! empty($u) && isset($u['permit']) && isset($u['uid']) && $u['permit'] == 9);
		if (! $is_admin) {
			$this->load->view('dictation/login');
			exit();
		}

		$this->load->view("dictation/admin");
		*/
	}
	public function offline() {
		$this->load->view("offline");
	}
}
