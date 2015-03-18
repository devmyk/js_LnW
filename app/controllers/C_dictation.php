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
		$is_access = false;
		$param = func_get_args();	// code 만 받기
		$s = $this->session->all_userdata();
		if (sizeof($param) == 1 && isset($s['uid'])) {
			$c_code = $this->m_dictation->get_user_category_by_fld($s['uid'], 'code');
			if (in_array($param[0], $c_code)) {
				$is_access = true;
			}
		}
		if (! $is_access) {
			echo "ERROR";
			exit;
		}
		// 그동안 통계 정보 뿌리고
		//		full, word : 정답 / 오답 / 패스 (총공부양)
		//		월별 통계
		//		목표도 넣어야하나 D-day 같은 <- 추후 추가하자
		$data = array(
			'category' => $this->session->userdata('category')
		);
		$this->load->view('v_dictation_stat',$data);
		
	}
	public function dialog() {
		
	}
}
