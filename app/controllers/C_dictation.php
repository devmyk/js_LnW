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
			$this->load->view('dictation/main', $data);
		} else {
			$this->load->view('dictation/login');
		}
	}

	public function js_redirect($url, $err) {
		echo "<html><head><script>";
		if (!empty($url)) {
			echo sprintf("alert(\"%s\");\n",htmlspecialchars(implode("\n",$err)));
		}
		echo sprintf("document.location = \"%s\"\n", base_url($url));
		echo "</script></head><body></body></html>";
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
//			$category = $this->md->get_user_category($uid);
//			$co = $this->md->get_user_category($uid, false);
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

	public function dictation() { // 스크립트 각각으로 실행할거임..
		$u = $this->session->userdata('u');
		if (! isset($u['uid'])) {
			redirect('/c_dictation', 'refresh');
			exit;
		}
		
		$param = func_get_args();	// code, no 만 받기
		if (sizeof($param) > 2 || sizeof($param) < 1) {
			$this->js_redirect("/c_dictation", "잘못된 접근입니다");
			exit;
		}

		$code = $param[0];
		$no = (isset($param[1]) ? $param[1] : 0);
		
		$c_code = $this->md->get_user_category_by_fld($u['uid'], 'code');
		if (! in_array($code, $c_code)) {
			$this->js_redirect("/c_dictation", "권한이 없습니다");
			exit;
		}

		$ci = $this->md->get_category($code);
		if (empty($ci)) {
			$this->js_redirect("/c_dictation", "잘못된 접근입니다");
			exit;
		}
		$script_info = $this->md->get_script($code, $no);

		// log 를 문자열로
		// 1. 세션에 log[code] 가 있는 지 확인
		// 2. 있으면 문자열로 그대로 전송(ex 정답:1,오답:0,미시도:빈값)
		$lw = $this->session->userdata('log_word');
		$lf = $this->session->userdata('log_full');

		// str_pad() 이건 문자열 자리수 채우기
		if (isset($lw[$code])) $log_word = $lw[$code];
		else {
			$log_word = str_repeat(",", ($script_info['sum']-1));
			$lw[$code] = $log_word;
			$this->session->set_userdata('log_word', $lw);
		}
		if (isset($lf[$code])) $log_full = $lf[$code];
		else {
			$log_full = str_repeat(",", ($script_info['sum']-1));
			$lf[$code] = $log_full;
			$this->session->set_userdata('log_full', $lf);
		}

		// 3. 없으면 DB에서 최신으로 가져오기
/*		if (empty($log) || ! isset($log[$code])) {
		}
		$dt = $this->md->get_date("Y-m-d");
		$q = $this->db->query(sprintf("select * from user_logs "
				." where user_seq='%s' and code='%s' and log_dt>'%s' "
//				." group by script_seq "
				." order by script_seq"
				, $u['uid'], $code, $dt));

		foreach ($script_info as $k=>$v) {
		if ($q->num_rows() > 0) {
			$logs = $q->result_array();
		}
		*/

		// 북마크
		$mark = "";
		$q = $this->db->query(sprintf("select mark from user_mark where uid='%s' and code='%s'", $u['uid'], $code));
		if ($q->num_rows() > 0) {
			foreach ($q->result() as $row) {
				$mark = $row->mark;
			}
		}

		$data = array(
			'u'			=> $u,
			'info'		=> $ci,
			'is_admin'	=> (bool)$this->md->is_admin(),
			'co'		=> $this->session->userdata('co'),
			'category'	=> $this->session->userdata('category'),
			'scr_info'	=> $script_info,
			'log_word'	=> $log_word,
			'log_full'	=> $log_full,
			'mark'		=> $mark
			,'sess'		=> array("lw"=>$this->session->userdata('log_word'),
								"lf"=>$this->session->userdata('log_full'))
		);

		$this->load->view('dictation/dictation',$data);
	}

	public function set_log() {
		$u = $this->session->userdata('u');
		$code = $this->input->post('l_code');

		do {
			if (! isset($u['uid'])) {
				echo "세션만료";
				break;
			}

			$c_code = $this->md->get_user_category_by_fld($u['uid'], 'code');
			if (empty($code) || ! in_array($code, $c_code))
			{
				echo "no code";
				break;
			}

			$answer = trim($this->input->post('l_answer'));
			$mode = $this->input->post('l_mode');
			$seq = $this->input->post('l_db_seq');
			$no = $this->input->post('l_no');
			if (empty($no)) $no = "0";
			$correct = $this->input->post('l_correct');
			$correct = empty($correct) ? "0" : "1";

			if (empty($seq)) {
				echo "no script seq";
				break;
			}

			// db 에 기록
			$data = array(
				"user_seq"=>$u['uid']
				,"script_seq"=>$seq
				,"code"=>$code
				,"corr"=>$correct
				,"answer"=>str_replace(array("'", '"', "\t", "\n"), array("\'", '\"', " ", " "), $answer)
				,"mode"=>$mode
			);
			$this->md->set_log($data);

			// 세션 log_word 나 log_full 에 기록
			$sess = "log_full";
			if ($mode == "word") {
				$sess = "log_word";
			}
			$log = $this->session->userdata($sess);
			$lo = $log[$code]; // log_org
			$la = explode(",", $lo);
			$la[$no] = $correct;
			$log[$code] = implode(",", $la);
			$this->session->set_userdata($sess, $log);
		} while (0);
	}

	public function setting() {
		$u = $this->session->userdata('u');
		if (! isset($u['uid'])) {
			redirect('/c_dictation', 'refresh');
			exit;
		}
		$data = array(
			'u'			=> $u,
			'is_admin'	=> (bool)$this->md->is_admin(),
			'co'		=> $this->session->userdata('co'),
			'category'	=> $this->session->userdata('category'),
		);
		$this->load->view('dictation/setting',$data);
	}

	public function setting_save() {
		$u = $this->session->userdata('u');
		do {
			if (! isset($u['uid'])) break;

			//  wordMax / fullMax / defaultMode / mode
			$mode = $this->input->post('mode');
			$word = $this->input->post('wordMax');
			$full = $this->input->post('fullMax');
			if ($mode!="word" && $mode !="full") $mode = "word";
			if(empty($word)) $word = 3;
			else if($word > 10) $word = 10;
			if(empty($full)) $full = 3;
			else if($full > 10) $full = 10;

			$u['defaultmode']	= $mode;
			$u['maxfull']		= $full;
			$u['maxword']		= $word;
			$this->session->set_userdata('u', $u);

			$this->md->set_user_info($u['uid'], array(
				'defaultmode'	=> $mode,
				'maxfull'		=> $full,
				'maxword'		=> $word
			));
		} while(0);
		redirect('/c_dictation', 'refresh');
	}

	public function ch_pwd() {
		// 토큰확인
		// 암호 복호화

	}


//////////////////////////////////////////

	public function set_mark() {
		$u = $this->session->userdata('u');
		$code = $this->input->post('l_code');
		$marks = $this->input->post('l_marks');
		// 있는지 확인 하고 insert or update
	}

	public function dictation_org() {
		$u = $this->session->userdata('u');
		if (! isset($u['uid'])) {
			redirect('/c_dictation', 'refresh');
			exit;
		}
		
		$param = func_get_args();	// code 만 받기
		if (sizeof($param) != 1) {
			$this->js_redirect("/c_dictation", "잘못된 접근입니다");
			exit;
		}

		$code = $param[0];
		$c_code = $this->md->get_user_category_by_fld($u['uid'], 'code');
		if (! in_array($code, $c_code)) {
			$this->js_redirect("/c_dictation", "권한이 없습니다");
			exit;
		}

		$ci = $this->md->get_category($code);
		if (empty($ci)) {
			$this->js_redirect("/c_dictation", "잘못된 접근입니다");
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
			if ($this->md->is_admin()) {
				echo "ERROR : 파일이 없습니다.";
				debug($data['info']);
			} else {
				$this->js_redirect("/c_dictation", "잘못된 접근입니다");
			}
			exit;
		} else {
			$this->load->view('dictation/dictation',$data);
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
			$this->js_redirect("/c_dictation", "잘못된 접근입니다");
			exit;
		}
		$code = $param[0];

		$c_code = $this->md->get_user_category_by_fld($u['uid'], 'code');
		if (! in_array($code, $c_code)) {
			$this->js_redirect("/c_dictation", "권한이 없습니다");
			exit;
		}

		$ci = $this->md->get_category($code);
		if (empty($ci)) {
			$this->js_redirect("/c_dictation", "잘못된 접근입니다");
			exit;
		}
		else if (! isset($ci['dir']) || empty($ci['dir']) || ! isset($ci['js']) || empty($ci['js'])) {
			if ($this->md->is_admin()) {
				echo "ERROR : 파일 정보가 없습니다.";
				debug($ci); 
			} else {
				$this->js_redirect("/c_dictation", "잘못된 접근입니다");
			}
			exit;
		}
		
		$ci['dir'] = preg_replace(array("/^\//", "/\/$/"), array("", ""), $ci['dir']);
		$path = "./{$ci['dir']}/{$ci['js']}";

		if (! is_file($path)) {
			if ($this->md->is_admin()) {
				echo "ERROR : 파일이 없습니다.";
				debug($path); 
			} else {
				$this->js_redirect("/c_dictation", "잘못된 접근입니다");
			}
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
		
		$dt = $this->md->get_date();
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
				'mode'		=> ($mode == 'full') ? 'full' : 'word'
			);
		}
		
		if (! empty($save)) {
			$this->db->insert_batch('user_logs', $save);
			echo "succes";
		} else {
			debug($save);
		}
	}

///////////// admin ///////////////////////

	public function ad_sql() {
		if (! $this->md->is_admin()) {
			redirect('/c_dictation', 'refresh');
			exit();
		}
		$q = $this->input->post('q');
		$q = trim(str_replace("\n", " ", $q));
		$data = array('sql'=>$q);
		if (! empty($q)) {
			$data['res'] = $this->db->query($q)->result();
		}
		$this->load->view('/dictation/admin/sql', $data);
	}

	// script insert
	// javascript 로 설정한 후 모아서 insert
	// code 가 category 테이블에 없는 경우 유효성 검사 후 추가해야함
	public function ad_script_frm() {
		if (! $this->md->is_admin()) {
			redirect('/c_dictation', 'refresh');
			exit();
		}
		$data = array();
//		$code = $this->input->post('code');
//		if (!empty($code)) {
//		}
		$this->load->view("dictation/admin/script_frm",$data);
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
		$data['js_file'] = "";
		if (empty($code)) {
			$data['list'] = array();
		} else {
			$q = $this->db->query("select * from script where code='{$code}' order by seq");
			$data['list'] = $q->result_array();
			foreach($data['co'] as $c) {
				if ($c['code'] == $code) $data['info'] = $c;
			}
			if (isset($data['info']['js']) && !empty($data['info']['js'])) {
				$dir = preg_replace(array("/^\//", "/\/$/"), array("", ""), $data['info']['dir']);
				$path = "./{$dir}/{$data['info']['js']}";
				$data['js_file'] = (int)is_file($path);
			}
		}
		$this->load->view("dictation/admin/script_view",$data);
	}

	public function ad_mk_js() {
		if (! $this->md->is_admin()) {
			redirect('/c_dictation', 'refresh');
			exit();
		}
		$code = $this->input->post('code');
		// code 정보
		$ci = $this->md->get_category($code);
		if (empty($ci)) {
			echo "ERROR : 잘못된 접근입니다";
			exit;
		}
		// db 에 script 있는 지 확인
		$q = $this->db->query("select * from script where code='{$code}' order by seq");
		if($q->num_rows() < 1) {
			echo "ERROR : no script";
			exit;
		}
		$list = $q->result_array();

		// js 파일 있으면 백업파일 만들기
		$ci['dir'] = preg_replace(array("/^\//", "/\/$/"), array("", ""), $ci['dir']);
		$path = "./{$ci['dir']}/{$ci['js']}";
		if (is_file($path)) {
			$dt = $this->md->get_date("Ymd_His");
			$fn = preg_replace("/\.js$/", "_$dt.js", $path);
			copy($path, $fn);
		}
		// 새 js 파일 만들기
		$data = array();
		$data[] = "startSeq = 0;";
		$data[] = "code = \"$code\";";
		$n = 1;
		$sum = sizeof($list);
		foreach ($list as $k=>$l) {
			// dbseq / mp3 / from / to / speaker / script / trans^n\
			$txt = sprintf("%s\t%s\t%s\t%s\t%s\t%s\t%s"
					,$l['seq'],$l['mp3'], $l['from'], (empty($l['to']) ? "" : $l['to']), $l['speaker']
					,str_replace(array("'", '"', "\t", "\n"), array("\'",'\"', " ", " "), $l['script'])
					,str_replace(array("'", '"', "\t", "\n"), array("\'",'\"', " ", " "), $l['trans'])
			);
			if ($n == 1) $txt = "var list = \"".$txt."^n\\";
			else if ($n == sizeof($list)) $txt = $txt."\";";
			else $txt = $txt."^n\\";
			$data[] = $txt;
			$n++;
		}
		$data[] = "setData();";
		write_file($path, implode("\n",$data));
		debug($data);
		// script_view 로 이동

	}

	public function ad_mk_irc() {
		if (! $this->md->is_admin()) {
			redirect('/c_dictation', 'refresh');
			exit();
		}
		$code = $this->input->get('code');
		// code 정보
		$ci = $this->md->get_category($code);
		if (empty($ci)) {
			echo "ERROR : 잘못된 접근입니다";
			exit;
		}
		// db 에 script 있는 지 확인
		$q = $this->db->query("select * from script where code='{$code}' order by seq");
		if($q->num_rows() < 1) {
			echo "ERROR : no script";
			exit;
		}
		$list = $q->result_array();
		if ($list[0]['from'] == $list[0]['to']) {
			echo "ERROR : from == to";
			exit;
		}

		$data = array();
		foreach ($list as $k=>$l) {
			$txt = sprintf("[%02d:%05.2f]%s"
					,($l['from']/60000), (($l['from']%60000)/1000)
					,str_replace(array("\t", "\n"), array(" ", " "), $l['script'])
			);
			$data[] = $txt;
		}
		if ($this->input->get('debug') == 1) {
			echo implode("<br>", $data);
			exit;
		}

		header("Content-type: text/plain"); 
		header("Pragma: no-cache"); 
		header("Expires: 0");
		header("Content-disposition: filename=$code.irc"); 
		header("Content-Disposition: attachment; filename=$code.irc");
		echo implode("\n", $data);
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
