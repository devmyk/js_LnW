<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_Dictation extends CI_Model {
    function __construct() {
        parent::__construct();
    }
	public function chk_email($email) {
		$res = array();
		$this->db->where('email', $email);
		$q_user = $this->db->get('user');
		if($q_user->num_rows() == 1) {
			$row = $q_user->row();
			$res = array(
				'uid'		=> $row->seq,
				'email'		=> $row->email,
				'name'		=> $row->name,
				'permit'	=> $row->permit
			);
		}
		return $res;
	}
			
	public function get_user_info($data) {
		// 모드로 나눠서 이미 등록된 정보를 찾는 용과 DB에서 가져오는 용으로 나눠야 할듯
		// 빈값
		if (trim($data['id']) == "" || trim($data['pw']) == "") {
			return false;
		}
		// DB 검사
		$q_user = $this->db->query("select * from user where email='{$data['id']}' and pwd=password('{$data['pw']}')");
		if($q_user->num_rows() != 1) {
			return false;
		} else {
			$row = $q_user->row();
			$res = array(
				'u' => array(
					'uid'		=> $row->seq,
					'email'		=> $row->email,
					'name'		=> $row->name,
					'permit'	=> $row->permit,
					'autoplay'	=> $row->autoplay,
					'autopass'	=> $row->autopass,
					'autoplaycount'	=> $row->autoplaycount,
					'defaultmode'	=> $row->defaultmode,
					'maxfull'		=> $row->maxfull,
					'maxword'		=> $row->maxword,
					'pre_login_dt'	=> $row->login_dt,
					'login_dt'		=> ''
				),
				'category'		=> array(),
				'co'			=> array()
			);
			return $res;
		}
	}
	public function set_user_login_dt($uid) {
		//$login_dt = date('Y-m-d H:i:s');
		$login_dt = date("Y-m-d H:i:s",strtotime ("+9 hours")); // 한국 표준시 (KST)
		$this->db->where('seq', $uid);
		$this->db->update('user', array('login_dt' => $login_dt));
		return $login_dt;
	}
	public function get_user_category($uid,$is_deco=true) {
		$category = array();
		$is_admin = false;
		$q_u = $this->db->query("select permit from user where seq='{$uid}'");
		if ($q_u->num_rows() != 1) return $category;
		foreach ($q_u->result() as $row) {
			$is_admin = ((int)$row->permit == 9);
		}
		
		$qi_uc = "select code from user_category uc where uc.user='{$uid}'";
		$qi_file = " and a.dir <> '' and a.js <> ''";
		if ($is_admin) {
			$qi_uc = "select code from category where depth='3'";
			$qi_file = "";
		}
		$q_uc = $this->db->query(""
			. " select b.seq, b.code, b.name, b.pcode, b.pname, b.ppcode, c.name as ppname, b.dir, b.js from ("
				. " select a.seq, a.code, a.name, a.pcode, c.name as pname, c.pcode as ppcode, a.dir, a.js from ("
					. " select c.seq, c.depth, c.pcode, ifnull(c.code, '') as code, c.name, c.dir, c.js"
					. " from ({$qi_uc}) uc"
					. " left join category c"
					. " on uc.code=c.code"
					. " order by depth, pcode"
				. ") a"
				. " left join category c"
				. " on a.pcode=c.code"
				. " where a.code <> '' {$qi_file}"
			. " ) b "
			. " left join category c"
			. " on b.ppcode=c.code"
			. " order by b.seq"
			. " ");
		// 보여줄 카테고리 정리
		// 배열 구조를 바꿔야 할 듯
		if ($q_uc->num_rows() > 0) {
			if ($is_deco) {
				foreach ($q_uc->result() as $row) {
					if (! is_file(sprintf(".%s%s",$row->dir,$row->js)) && ! $is_admin)
						continue;
					$ppcode = $row->ppcode;
					$pcode = $row->pcode;
					$code = $row->code;
					if (! isset($category[$ppcode])) {
						$category[$ppcode] = array(
							'code' => $ppcode
							, 'name' => $row->ppname
							, 'list' => array()
						);
					}
					if (! isset($category[$ppcode]['list'][$pcode])) {
						$category[$ppcode]['list'][$pcode] = array(
							'pcode' => $ppcode
							, 'code' => $pcode
							, 'name' => $row->pname
							, 'list' => array()
						);
					}
					if (! isset($category[$ppcode]['list'][$pcode]['list'][$code])) {
						$category[$ppcode]['list'][$pcode]['list'][$code] = array(
							'pcode' => $pcode
							, 'code' => $code
							, 'name' => $row->name
							, 'dir'	=> $row->dir
							, 'js'	=> $row->js
						);
					}
				}
			} else {
				foreach ($q_uc->result_array() as $row) {
					$category[] = $row;
				}
			}
		}
		return $category;
	}

	public function get_category($code) {
		$res = array();
		$q = $this->db->query("select c.*, d.name as ppname"
			." from ("
				." select a.*, b.name as pname, b.pcode as ppcode"
				." from ("
					." select * from category where code='{$code}'"
				." ) a "
				." left join category b"
				." on b.code=a.pcode"
			." ) as c"
			." left join category d"
			." on d.code=c.ppcode");
				
		if ($q->num_rows() > 0) {
			foreach ($q->result() as $row) {
				$res = array(
					'seq'	=> $row->seq,
					'depth'	=> $row->depth,
					'code'	=> $row->code,
					'name'	=> $row->name,
					'dir'	=> $row->dir,
					'js'	=> $row->js,
					'pcode'	=> $row->pcode,
					'pname' => $row->pname,
					'ppcode' => $row->ppcode,
					'ppname' => $row->ppname
				);
			}
		}
		return $res;
	}
	public function get_user_category_by_fld($uid, $fld='') {
		$category = $this->get_user_category($uid, false);
		$f = array("seq","code","name","pcode","pname","ppcode","ppname");
		if (in_array($fld,$f)) {
			$res = array();
			foreach($category as $v) {
				$res[] = $v[$fld];
			}
			return array_unique($res);
		} else {
			return $category;
		}
	}
}
