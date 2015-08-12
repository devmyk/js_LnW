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

	public function get_date($fm="Y-m-d H:i:s") {
		$dt = date($fm, strtotime("+9 hours")); // 한국 표준시 (KST)
		if (is_test() !== false) {
			$dt = date($fm);
		}
		return $dt;
	}
			
	public function get_user_info($data) {
		// 모드로 나눠서 이미 등록된 정보를 찾는 용과 DB에서 가져오는 용으로 나눠야 할듯
		// 빈값
		if (trim($data['id']) == "" || trim($data['pw']) == "") {
			return false;
		}
		// DB 검사
		$q_user = $this->db->query(
			sprintf("select * from user where email='%s' and pwd=password('%s')", $data['id'], $data['pw']));
		if($q_user->num_rows() != 1) {
			return false;
		} else {
			$row = $q_user->row();
			$p_dt= $row->login_dt;
			if (is_test() == false) {
//				$p_dt = date("Y-m-d H:i:s", strtotime($p_dt) + (60*60*9));
			}
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
					'pre_login_dt'	=> $p_dt,
					'login_dt'		=> ''
				),
				'category'		=> array(),
				'co'			=> array(),
				'log_word'		=> array(),
				'log_full'		=> array()
			);
			return $res;
		}
	}
	public function set_user_login_dt($uid) {
		$login_dt = date("Y-m-d H:i:s");
		$this->db->where('seq', $uid);
		$this->db->update('user', array('login_dt' => $login_dt));
		return $login_dt;
	}

	public function update_user_pw($uid, $pwd) {
		$this->db->query(sprintf("update user set pwd=password('%s')", $pwd));
	}

	public function set_user_info($uid, $data) {
		$this->db->where('seq', $uid);
		$this->db->update('user', $data);
	}

	public function deco_dir($dir) {
		$dir = preg_replace(array("/^\//", "/\/$/"), array("", ""), $dir);
		return "/$dir/";
	}

	public function get_user_category($uid,$is_deco=true) {
		$category = array();
		$this->db->where('seq', $uid);
		$q_u = $this->db->get('user');
		if ($q_u->num_rows() != 1) return $category;
		$permit = 1;
		foreach($q_u->result() as $v) {
			$permit = $v->permit;
		}
		$is_admin = ($permit == 9);
//		$is_admin = $this->is_admin(); // 세션으로 확인하기 때문에 로그인 시 미작동
		
		$qi_uc = sprintf(" select code from user_category uc where uc.user='%s' ", $uid);
		$qi_file = " and a.dir <> '' and a.js <> '' ";
		if ($is_admin) {
			$qi_uc = " select code from category where depth='3' ";
			$qi_file = "";
		}
		$sql = " select b.seq, b.code, b.name, b.pcode, b.pname, b.ppcode, c.name as ppname, b.dir, b.js from ("
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
			. " ";
		$q_uc = $this->db->query($sql);

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
						$no_js = 0;
//						(empty($is_file(sprintf(".%s%s",$row->dir,$row->js));
//						((int)(empty($row['dir']) || empty($row['js']) || ! is_file(".{$row['dir']}{$row['js']}")))
						$category[$ppcode]['list'][$pcode]['list'][$code] = array(
							'pcode' => $pcode
							, 'code' => $code
							, 'name' => $row->name
							, 'dir'	=> $row->dir
							, 'js'	=> $row->js
							, 'no_js' => $no_js
						);
					}
				}
			} else {
				foreach ($q_uc->result_array() as $row) {
					$row['no_js'] = (empty($row['dir']) || empty($row['js']) || ! is_file(".{$row['dir']}{$row['js']}")) ? 1 : 0;
					$category[] = $row;
				}
			}
		}
		return $category;
	}

	public function get_script($code, $no = 0) {
		$res = array();
//		$q = $this->db->query("select * from script where code='{$code}' order by seq limit {$no}, 1");
//		$q = $this->db->query("select * from script where code='{$code}' order by no");
		$this->db->where('code', $code);
		$this->db->order_by("no", "asc");
		$q = $this->db->get('script');
		if ($q->num_rows() > 0) {
			$info = $q->result_array();
//			$res = $info[$no];
			foreach ($info as $row) {
				if ($row['no'] == ($no+1)) {
					$res = $row;
					break;
				}
			}
			if (! empty($res)) {
				$last = array_pop($info);
				$res['sum'] = (int)$last['no'];
				$res['curr'] = $no;
			}
		}
		return $res;
	}

	// 코드의 스크립트 유효성
	// type : str , arr
	public function get_script_validity($code, $type="str") {
		$this->db->where('code', $code);
		$this->db->where("script <> ''");
		$this->db->where("mp3 <> ''");
		$this->db->order_by("no", "asc");
		$q = $this->db->get('script');
		$res_arr = array();
		if ($q->num_rows() > 0) {
			$info = $q->result_array();
			$tmp = array();
			foreach ($info as $row) {
				$tmp[] = (int)$row['no'];
			}
			$last = array_pop($info);
			$total = (int)$last['no'];
//			$total = (int)$info[(sizeof($info)-1)]['no'];

			for ($i=0; $i<$total; $i++) {
				// i == v 일 때 1 / 없으면 0
				if (in_array(($i+1), $tmp)) {
					$res_arr[$i] = 1;
				} else {
					$res_arr[$i] = 0;
				}
			};
		}
		if ($type == "str") return implode(",", $res_arr);
		else if ($type == "arr") return $res_arr;
		else return $res_arr;
	}

	public function get_category($code) {
		$res = array();
		$q = $this->db->query(sprintf(
			"select c.*, d.name as ppname"
			." from ("
				." select a.*, b.name as pname, b.pcode as ppcode"
				." from ("
					." select * from category where code='%s'"
				." ) a "
				." left join category b"
				." on b.code=a.pcode"
			." ) as c"
			." left join category d"
			." on d.code=c.ppcode"
			,$code));
				
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
//				$res['dir'] = preg_replace(array("/^\//", "/\/$/"), array("", ""), $res['dir']);
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

	public function get_mark($uid, $code) {
		$mark = "";
//		$q = $this->db->query(sprintf("select mark from user_mark where uid='%s' and code='%s'", $u['uid'], $code));
		$this->db->select('mark');
		$this->db->where("uid", $uid);
		$this->db->where("code", $code);
		$q = $this->db->get("user_mark");
		if ($q->num_rows() > 0) {
			foreach ($q->result() as $row) {
				$mark = $row->mark;
			}
		}
		return $mark;
	}

	public function set_log($data) {
		$data['ip'] = $_SERVER['REMOTE_ADDR'];
		$data['log_dt'] = date("Y-m-d H:i:s"); // 기록은 서버기준으로
		// user_seq, script_seq, code, corr, answer, log_dt, mode, ip
		$q = $this->db->insert_string('user_logs', $data);
		$this->db->query($q);
	}

	public function get_all_logs($uid, $code) // code의 path는 3
	{
		$this->db->where("user_seq", $uid);
		$this->db->where("code", $code);
		return $this->db->get('user_logs')->result_array();
	}

	public function get_log_by_script_seq($uid, $seq, $add_ts=0) { // 60*60*6 = -21600
		$now = date("Y-m-d");
		if (is_test() == false) {
			$add_ts = $add_ts - (60*60*9);
		}
		$ts = strtotime($now) + $add_ts;

		// mode 로 그룹해야함
		$this->db->where("user_seq", $uid);
		$this->db->where("script_seq", $seq);
		$this->db->where("log_dt >", date("Y-m-d H:i:s", $ts));
		$this->db->order_by("seq", "desc");
		$q = $this->db->get('user_logs', 1);

		$res = array();
		if ($q->num_rows() > 0) {
			$arr = $q->result_array();
			$res = array_pop($arr);
			if (is_test() == false) {
				$res['log_dt'] = date("Y-m-d H:i:s", (strtotime($res['log_dt']) + (60*60*9)));
			}
		}
		return $res;
	}

	public function get_week_log($uid, $code)
	{
		$res = array();
		$query = "select seq, script_seq, corr, mode, log_dt, unix_timestamp(log_dt) as log_ts "
			." from user_logs where user_seq='$uid' and code='$code'";

		$dt = date("Y-m-d H:i:s", strtotime("-1 week"));
		$query .= " and log_dt >= '$dt'";

		$q = $this->db->query($query);
		if ($q->num_rows() > 0) {
			$res = $q->result_array();
		}
		return $res;
	}

	public function get_logs($uid, $code, $is_detail=false) // code의 path는 3
	{
		$res = array();
		$query = "select * from user_logs where user_seq='$uid' and code='$code'";

		$dt = date("Y-m-d H:i:s", strtotime("-1 week"));
		$query .= " and log_dt >= '$dt'";

		$q = $this->db->query($query);
		if ($q->num_rows() > 0) {
			$res = $q->result_array();
		}
		return $res;

		// 아 맞다 날짜로 그룹화 해야한다..
		/*
		$this->db->where("user_seq", $uid);
		$this->db->where("code", $code);
		$q = $this->db->get('user_logs');
		if ($is_detail) {
			return $q->result_array();
		} else {
			$res = array('date'=>'', 'total'=>0, 'corr'=>0, 'incorr'=>0, 'pass'=>0);
			if ($q->num_rows() == 0)
				return $res;
			$res['total'] = $q->num_rows();
			foreach ($q->result() as $row) {
				if ($row->corr == 1) $res['corr']++;
				else $res['incorr']++;
			}
			return $res;
		}
		*/
	}
	public function is_admin() {
		$u = $this->session->userdata('u');
		if (empty($u)) return false;
		if (! isset($u['permit']) || ! isset($u['uid']))
			return false;
		else if ($u['permit'] == 9)
			return true;

		return false;
	}
	public function replace_script($str) {
		return str_replace(
			array("'",'"',"\n","\t"),
			array("\'", "\'"," ", " "),
			$str);
	}
	public function make_js($code) {
		// category 있는지 확인
//		$qc = $this->db->query("select * from category where dir<>'' and js<>'' and depth='3' and code='{$code}'");
		$this->db->where("dir <> ''");
		$this->db->where("js <> ''");
		$this->db->where("depth", 3);
		$this->db->where("code", $code);
		$qc = $this->db->get('category');

		if ($qc->num_rows() !== 1)
			return "ERROR : wrong code";

		// 파일 유효성 검사
		$start_no = 0;
		$dir = $js = $path = "";
		foreach($qc->result() as $c) {
			$dir = preg_replace(array("/^\//", "/\/$/"), array("", ""), $c->dir);
			$js = $c->js;
			$start_no = (int)$c->start_no;
		}
		if (substr($dir,0,4) != "data" || substr($js, -3) != ".js") {
			return "ERROR : wrong file";
		}

		// script 있는지 확인
//		$qs = $this->db->query("select * from script where code='{$code}' and mp3<>'' order by no asc");
		$this->db->where("code", $code);
		$this->db->where("mp3 <> ''");
		$this->db->order_by("no", "asc");
		$qs = $this->db->get('script');

		$num_rows = $qs->num_rows();
		if ($num_rows <= 0)
			return "ERROR : empty script";

		// 파일이 있는지 확인
		$path = "./{$dir}/{$js}";

		// 있으면 <파일명.js>.YYYYMMDD.HH.ii.ss.bak 로 변경하여 백업
		if (is_file($path)) {
			$dt = date('Ymd.H.i.s');
			$new_path = "{$path}.{$dt}.bak";
			if (! copy($path, $new_path)) {
				return "ERROR : file to back up file ({$path} -> {$new_path})";
			}
		}
		
		// 파일 생성
		$fc = array(); // file contents
		$fc[] = "startSeq = {$start_no};";
		$fc[] = "code = \"{$code}\";";
		// seq / mp3 / from / to / speaker / script / trans^n
		foreach ($qs->result_array() as $k=>$v) {
			$v['to'] = ($v['to'] == 0 ? "" : $v['to']);
			$v['script'] = $this->replace_script($v['script']);
			$v['trans'] = $this->replace_script($v['trans']);

			$text = "{$v['seq']}\t{$v['mp3']}\t{$v['from']}\t{$v['to']}\t{$v['speaker']}\t{$v['script']}\t{$v['trans']}";
			if ($k === 0)
				$fc[] = sprintf("var list = \"%s^n\\",$text);
			else if ($k === ($num_rows-1))
				$fc[] = sprintf("%s\";",$text);
			else
				$fc[] = sprintf("%s^n\\",$text);
		}
		$fc[] = "setData();";

		if (! write_file($path, implode("\n",$fc))) {
			return "ERROR : fail to write file";
		} else {
			return array("SUCCESS : {$path}", implode("\n", $fc));
		}
	}
}
