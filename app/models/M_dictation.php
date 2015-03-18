<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_Dictation extends CI_Model {
    function __construct() {
        parent::__construct();
    }
	public function get_user_info($data) {
		// 모드로 나눠서 이미 등록된 정보를 찾는 용과 DB에서 가져오는 용으로 나눠야 할듯
		// 빈값
		if (trim($data['id']) == "" || trim($data['pw']) == "") {
			return false;
		}
		// DB 검사
		$this->db->where('email', $data['id']);
		$this->db->where('pwd', $data['pw']);
		$q_user = $this->db->get('user');
		if($q_user->num_rows() != 1) {
			return false;
		} else {
			$row = $q_user->row();
			$res = array(
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
				'login_dt'		=> '',
				'category'		=> array()
			);
			return $res;
		}
	}
	public function set_user_login_dt($seq) {
		$login_dt = date('Y-m-d H:i:s');
		$this->db->where('seq', $seq);
		$this->db->update('user', array('login_dt' => $login_dt));
		return $login_dt;
	}
	public function get_user_category($seq,$is_deco=true) {
		$category = array();
		$q_uc = $this->db->query(""
			. " select b.seq, b.code, b.name, b.pcode, b.pname, b.ppcode, c.name as ppname from ("
				. " select a.seq, a.code, a.name, a.pcode, c.name as pname, c.pcode as ppcode from ("
					. " select c.seq, c.path, c.pcode, ifnull(c.code, '') as code, c.name"
					. " from (select code from user_category uc where uc.user='{$seq}') uc"
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
			if ($is_deco) {
				foreach ($q_uc->result() as $row) {
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
	public function get_user_category_by_fld($seq, $fld='') {
		$category = $this->get_user_category($seq, false);
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
