<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class C_Dictation extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('m_dictation');
    }
	public function index() {
		$s = $this->session->all_userdata();
		$is_login = (! empty($s['uid']));
		if ($is_login) {
			$data = array(
				// 카테고리, 출석, 일별 학습상황 등등..
				'category' => $s['category']
			);
			$this->load->view('v_dictation_main', $data);
		} else {
			$this->load->view('v_dictation_login');
		}
	}

	public function login() {

		// 받아온 값 유효성 체크
		$post = $this->input->post();
		$user_info = $this->m_dictation->get_user_info($post);

		if ($user_info != false) {
			$uid = $user_info['uid'];
			// 로그인 시간 저장
			$user_info['login_dt'] = $this->m_dictation->set_user_login_dt($uid);
			// 카테고리
			$user_info['category'] = $this->m_dictation->get_user_category($uid);
			// 세션에 유저정보 저장
			$this->session->set_userdata($user_info);
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
		$s = $this->session->all_userdata();
		if (! isset($s['uid'])) {
			redirect('/c_dictation', 'refresh');
			exit;
		}

		$param = func_get_args();	// code 만 받기
		if (sizeof($param) != 1) {
			echo "ERROR : 잘못된 접근입니다";
			exit;
		}
		$code = $param[0];
		$c_code = $this->m_dictation->get_user_category_by_fld($s['uid'], 'code');
		if (! in_array($code, $c_code)) {
			echo "ERROR : 권한이 없습니다";
			exit;
		}
		// 그동안 통계 정보 뿌리고
		//		full, word : 정답 / 오답 / 패스 (총공부양)
		//		월별 통계
		//		목표도 넣어야하나 D-day 같은 <- 추후 추가하자
		$data = array(
			'code'		=> $code,
			'category'	=> $s['category'],
			'permit'	=> $s['permit']
		);
		$this->load->view('v_dictation_stat',$data);
		
	}
	public function dictation() {
		$s = $this->session->all_userdata();
		if (! isset($s['uid'])) {
			redirect('/c_dictation', 'refresh');
			exit;
		}
		
		$param = func_get_args();	// code 만 받기
		if (sizeof($param) != 1) {
			echo "ERROR : 잘못된 접근입니다";
			exit;
		}

		$c_code = $this->m_dictation->get_user_category_by_fld($s['uid'], 'code');
		if (! in_array($param[0], $c_code)) {
			echo "ERROR : 권한이 없습니다";
			exit;
		}

		$data = array(
			'category'		=> $s['category'],
			'defaultmode'	=> $s['defaultmode']
		);
		$this->load->view('v_dictation',$data);
		
	}

	public function dialog() {
		$s = $this->session->all_userdata();
		if (! isset($s['uid'])) {
			redirect('/c_dictation', 'refresh');
			exit;
		}
		
		$param = func_get_args();	// code 만 받기
		if (sizeof($param) != 1) {
			echo "ERROR : 잘못된 접근입니다";
			exit;
		}
		$code = $param[0];

		$c_code = $this->m_dictation->get_user_category_by_fld($s['uid'], 'code');
		if (! in_array($code, $c_code)) {
			echo "ERROR : 권한이 없습니다";
			exit;
		}

		$code_info = $this->m_dictation->get_category($code);
		if (empty($code_info)) {
			echo "ERROR : 잘못된 접근입니다";
			exit;
		}
		else if (! isset($code_info['file']) || empty($code_info['file'])) {
			// db 에 저장된 값 없음
			redirect('/c_dictation', 'refresh');
			exit;
		}
		
		if (! file_exists(".{$code_info['file']}")) {
			// db 에 저장된 파일이 존재하지 않음
			redirect('/c_dictation', 'refresh');
			exit;
		}

		$data = array(
			'category'		=> $s['category'],
			'code_info'		=> $code_info,
			'defaultmode'	=> $s['defaultmode']
		);
		$this->load->view('v_dictation_dialog',$data);
	}

	public function setlog() {
        $this->load->model('m_dictation','md');

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
}
