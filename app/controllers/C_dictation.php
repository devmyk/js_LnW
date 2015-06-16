<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class C_Dictation extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('m_dictation','md');
    }
	public function index() {
		$u = $this->session->userdata('u');
		$is_login = (! empty($u['uid']));
		if ($is_login) {
			$data = array(
				// 카테고리, 출석, 일별 학습상황 등등..
				'is_admin'	=> (bool)$this->md->is_admin(),
				'co'		=> $this->session->userdata('co'),
				'category'	=> $this->session->userdata('category')
			);
			$data['test'] = $this->md->get_user_category($u['uid'], false);
			$this->load->view('dictation/main', $data);
		} else {
			$this->load->view('dictation/login');
		}
	}

	public function login() {

		// 받아온 값 유효성 체크
		$post = $this->input->post();
		$ui = $this->md->get_user_info($post);

		if ($ui != false) {
			$uid = $ui['u']['uid'];
			// 로그인 시간 저장
			$ui['u']['login_dt'] = $this->md->set_user_login_dt($uid);
			// 카테고리
			$ui['category'] = $this->md->get_user_category($uid);
			$ui['co'] = $this->md->get_user_category($uid, false);
			// 세션에 유저정보 저장
			$this->session->set_userdata($ui);
		}
		// 메인으로 이동
		redirect('/c_dictation', 'refresh');
	}

	public function logout() {
		session_unset();
		$this->session->set_userdata(array());
		redirect('/c_dictation', 'refresh');
	}
	public function stat() {
		$u = $this->session->userdata('u');
		if (! isset($u['uid'])) {
			redirect('/c_dictation', 'refresh');
			exit;
		}

		$param = func_get_args();	// code 만 받기
		if (sizeof($param) != 1) {
			echo "ERROR : 잘못된 접근입니다";
			exit;
		}
		$code = $param[0];
		$c_code = $this->md->get_user_category_by_fld($u['uid'], 'code');
		if (! in_array($code, $c_code)) {
			echo "ERROR : 권한이 없습니다";
			exit;
		}
		// 그동안 통계 정보 뿌리고
		//		full, word : 정답 / 오답 / 패스 (총공부양)
		//		월별 통계
		//		목표도 넣어야하나 D-day 같은 <- 추후 추가하자
		$logs = $this->md->get_logs($u['uid'], $code);
		$data = array(
			'is_admin'	=> (bool)$this->md->is_admin(),
			'code'		=> $code,
			'category'	=> $this->session->userdata('category'),
			'permit'	=> $u['permit'],
			'logs'		=> $logs
		);
		$this->load->view('dictation/stat',$data);
		
	}
	public function dictation() {
		$u = $this->session->userdata('u');
		if (! isset($u['uid'])) {
			redirect('/c_dictation', 'refresh');
			exit;
		}
		
		$param = func_get_args();	// code 만 받기
		if (sizeof($param) != 1) {
			echo "ERROR : 잘못된 접근입니다";
			exit;
		}

		$code = $param[0];
		$c_code = $this->md->get_user_category_by_fld($u['uid'], 'code');
		if (! in_array($code, $c_code)) {
			echo "ERROR : 권한이 없습니다";
			exit;
		}

		$ci = $this->md->get_category($code);
		if (empty($ci)) {
			echo "ERROR : 잘못된 접근입니다";
			exit;
		}

		$data = array(
			'u'			=> $u,
			'info'		=> $ci,
			'is_admin'	=> (bool)$this->md->is_admin(),
			'co'		=> $this->session->userdata('co'),
			'category'	=> $this->session->userdata('category')
		);

		$ci['dir'] = preg_replace(array("/^\//", "/\/$/"), array("", ""), $ci['dir']);
		if (! is_file("./{$ci['dir']}/{$ci['js']}")) {
			debug($data['info']);
			echo "ERROR : 파일이 없습니다.";
			exit;
		} else {
			$this->load->view('dictation/dictaion',$data);
		}
	}

	public function dialog() {
		$u = $this->session->userdata('u');
		if (! isset($u['uid'])) {
			redirect('/c_dictation', 'refresh');
			exit;
		}
		
		$param = func_get_args();	// code 만 받기
		if (sizeof($param) != 1) {
			echo "ERROR : 잘못된 접근입니다";
			exit;
		}
		$code = $param[0];

		$c_code = $this->md->get_user_category_by_fld($u['uid'], 'code');
		if (! in_array($code, $c_code)) {
			echo "ERROR : 권한이 없습니다";
			exit;
		}

		$ci = $this->md->get_category($code);
		if (empty($ci)) {
			echo "ERROR : 잘못된 접근입니다";
			exit;
		}
		else if (! isset($ci['dir']) || empty($ci['dir']) || ! isset($ci['js']) || empty($ci['js'])) {
			echo "ERROR : 파일 정보가 없습니다.";
			debug($ci); 
			exit;
		}
		
		$ci['dir'] = preg_replace(array("/^\//", "/\/$/"), array("", ""), $ci['dir']);
		$path = "./{$ci['dir']}/{$ci['js']}";

		if (! is_file($path)) {
			echo "ERROR : 파일이 없습니다.<br>{$path}";
			exit;
		}

		$data = array(
			'u'			=> $u,
			'ci'		=> $ci,
			'is_admin'	=> (bool)$this->md->is_admin(),
			'category'	=> $this->session->userdata('category'),
			'info'		=> array('dir'=>"/{$ci['dir']}/", 'js'=>$ci['js'])
		);
		$this->load->view('dictation/dialog',$data);
	}

	public function setlog() {

		$email = $this->input->post('user_email');
		$code = $this->input->post('code');
		$txt = $this->input->post('txt');
		$mode = $this->input->post('md');
		
		if (! $email || ! $code || ! $txt) {
			echo "no data!!";
			return;
		}
		$ui = $this->md->chk_email($email);
		$ci = $this->md->get_category($code);
		if (empty($ui) || empty($ci) || empty($txt)) {
			echo "error";
			return;
		}
		
		// 데이터 확인
		$d = explode("\n", $txt);
		
		//$dt = date('Y-m-d H:i:s');
		$dt = date("Y-m-d H:i:s",strtotime ("+9 hours")); // 한국 표준시 (KST)
		$save = array();
		foreach ($d as $v) {
			if (empty($v)) { continue; }
			// code, seq, corr, answer
			$tmp = explode("/", $v);
			if ($tmp[0] == "") { continue; }
			// 가공 user_logs
			// user_seq | script_seq | code | corr | answer | log_dt
			$save[] = array(
				'user_seq'	=> $ui['uid'],
				'script_seq' => $tmp[0],
				'code'		=> $code,
				'corr'		=> (empty($tmp[1]) ? "0" : $tmp[1]),
				'answer'	=> (empty($tmp[2]) ? "" : $tmp[2]),
				'log_dt'	=> $dt,
				'mode'		=> ($mode == 'full') ? 'full' : 'words'
			);
		}
		
		if (! empty($save)) {
			$this->db->insert_batch('user_logs', $save);
			echo "succes";
		} else {
			debug($save);
		}
	}

	public function ad_script() {
		if (! $this->md->is_admin()) {
			redirect('/c_dictation', 'refresh');
			exit();
		}

		$code = $this->input->post('code');
		$data = array();
		$data['co'] = $this->session->userdata('co');
		$data['category'] = $this->session->userdata('category');
		$data['info'] = array('code'=>"", 'pcode'=>"", 'ppcode'=>"");
		if (empty($code)) {
			$data['list'] = array();
		} else {
			$q = $this->db->query("select * from script where code='{$code}' order by seq");
			$data['list'] = $q->result_array();
			foreach($data['co'] as $c) {
				if ($c['code'] == $code) $data['info'] = $c;
			}
		}
		$this->load->view("dictation/admin/script_view",$data);
	}

	public function ad_script_edit() {
		if (! $this->md->is_admin()) {
			redirect('/c_dictation', 'refresh');
			exit();
		}
		$seq = $this->input->post('seq');
		$code = $this->input->post('code');

		$from = $this->input->post('from');
		$to = $this->input->post('to');
		$mp3 = $this->input->post('mp3');
//		$mp3 = str_replace(array("'", '"', "\t", "\n"), array("\'", '\"', "", ""), $this->input->post('mp3'));

		$data = array('from'=>$from, 'to'=>$to, 'mp3'=>$mp3);

		debug($seq, $code, $data);
//		$this->db->query("update");
//		$this->db->where('seq', $seq);
//		$this->db->update('script', $data);

	}

	public function ad_table() {
		if (! $this->md->is_admin()) {
			redirect('/c_dictation', 'refresh');
			exit();
		}
		$table = $this->input->post('table');
		if (empty($table)) $table = "category";
/*
		$data = array();
		$this->load->view("dictation/admin/database",$data);
*/
	}
}
