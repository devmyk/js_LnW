<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class C_Dictation extends CI_Controller {

//    function __construct() {
//        parent::__construct();
//    }
	public function index() {
		$s = $this->session->all_uiserdata();
		if (!empty($s['uid'])) {
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
		// 빈값
		if (trim($post['id']) == "" || trim($post['pw']) == "") {
			redirect('/c_dictation', 'refresh');
			exit;
		}
		// 이메일 형태가 아님
		// db 체크
		// table : user
		// structure : seq / email / name / pwd / login_dt / permit / autoplay / autopass / autoplaycount / defaultmode / maxfull / maxword 
		$this->db->where('email', $post['id']);
		$this->db->where('pwd', $post['pw']);
		$q_user = $this->db->get('user');

		$num = $q_user->num_rows();
		if ($num != 1) {
			redirect('/c_dictation', 'refresh');
			exit;
		} else {
			// 로그인 시간 저장
			$login_dt = date('Y-m-d H:i:s');
			$this->db->where('email', $post['id']);
			$this->db->update('user', array('login_dt' => $login_dt));
			// 세션에 유저정보 저장
			$row = $q_user->row();
			$data = array(
				'uid' => $row->seq,
				'email' => $row->email,
				'name' => $row->name,
				'login_dt' => $login_dt,
				'permit' => $row->permit,
				'autoplay' => $row->autoplay,
				'autopass' => $row->autopass,
				'autoplaycount' => $row->autoplaycount,
				'defaultmode' => $row->defaultmode,
				'maxfull' => $row->maxfull,
				'maxword' => $row->maxword,
				'category' => array()
			);

			$q_uc = $this->db->query(""
			. " select b.seq, b.code, b.name, b.pcode, b.pname, b.ppcode, c.name as ppname from ("
				. " select a,seq, a.code, a.name, a.pcode, c.name as pname, c.pcode as ppcode from ("
					. " select c.seq, c.path, c.pcode, ifnull(c.code, '') as code, c.name"
					. " from (select code from user_category uc where uc.user='{$row->seq}') uc"
					. " left join category c"
					. " on uc.code=c.code"
					. " order by path, pcode"
				. ") a"
				. " left join category c"
				. " on a.pcode=c.code"
				. " where a.code <> '' "
			. " ) b "
			. " left join category c"
			. " on b.ppcode=c.code"
			. " order by ppcode, pcode, code, seq"
			. " ");

			// 보여줄 카테고리 정리
			if ($q_uc->num_rows() > 0) {
				$category = array();
				foreach ($q_uc->result() as $row) {
					$ppcode = $row->ppcode;
					$pcode = $row->pcode;
					$code = $row->code;
					if (!isset($category[$ppcode])) {
						$category[$ppcode] = array(
							'code' => $ppcode
							, 'name' => $row->ppname
							, 'list' => array()
						);
					}
					if (!isset($category[$ppcode]['list'][$pcode])) {
						$category[$ppcode]['list'][$pcode] = array(
							'pcode' => $ppcode
							, 'code' => $pcode
							, 'name' => $row->pname
							, 'list' => array()
						);
					}
					if (!isset($category[$ppcode]['list'][$pcode]['list'][$code])) {
						$category[$ppcode]['list'][$pcode]['list'][$code] = array(
							'pcode' => $pcode
							, 'code' => $code
							, 'name' => $row->name
						);
					}
				}
				$data['category'] = $category;
			}

			// 세션에 저장
			$this->session->set_userdata($data);
			// 메인으로 이동
			redirect('/c_dictation', 'refresh');
		}
	}

	public function logout() {
		session_unset();
		$this->session->set_userdata(array());
		redirect('/c_dictation', 'refresh');
	}

}
