<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_Dictation extends CI_Controller {
//    function __construct() {
//        parent::__construct();
//    }
	public function index()
	{
        $s = $this->session->all_userdata();
        $is_login = !empty($s['uid']);
        if ($is_login) {
            $this->load->view('v_dictation_main', $s);
        } else {
            $this->load->view('v_dictation_login');
        }
	}
    public function login()
    {

        // 받아온 값 유효성 체크
        $post = $this->input->post();
        // 빈값
        if ( trim($post['id']) == "" || trim($post['pw']) == "" ) {
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
            $this->db->update('user', array('login_dt'=>$login_dt));
            // 세션에 유저정보 저장
            $row = $q_user->row();
            $data = array(
                'uid'   => $row->seq,
                'email' => $row->email,
                'name'  => $row->name,
                'login_dt'  => $login_dt,
                'permit'    => $row->permit,
                'autoplay'  => $row->autoplay,
                'autopass'  => $row->autopass,
                'autoplaycount' => $row->autoplaycount,
                'defaultmode'   => $row->defaultmode,
                'maxfull'   => $row->maxfull,
                'maxword'   => $row->maxword,
                'category'  => array()
            );

            // 최상위 카테고리
             $q_pcode = $this->db->query("select * from category where pcode=''");
            if ($q_pcode->num_rows() > 0) {
                $top_pcode = array();
                $top_category = array();
                foreach ($q_pcode->result() as $row) {
                    $top_pcode[] = $row->code;
                    $top_category[$row->code] = array(
                        'seq'   => $row->seq,
                        'code'  => $row->code,
                        'name'  => $row->name
                    );
                }
                // 보여줄 카테고리 정리
                $q_uc = $this->db->query(""
                ." select * from ("
                ." select c.path as path, c.pcode as pcode, ifnull(c.code, '') as code, c.name as name"
                ." from (select code from user_category uc where uc.user='{$data['uid']}') uc"
                ." left join category c"
                ." on uc.code=c.code"
                ." order by path, pcode"
                .") a where a.code <> '' "
                ." ");
            
                if ($q_uc->num_rows() > 0) {
                    $category = array();
                    foreach ($q_uc->result() as $row) {
                        $pcode = $row->pcode;
                        if (in_array($pcode, $top_pcode)) {
                            if (! isset($category[$pcode])) {
                                $category[$pcode] = array(
                                    'pcode'=>$pcode,
                                    'name'=>$top_category[$pcode]['name'],
                                    'list'=> array());
                            }
                            $category[$pcode]['list'][] = array(
                                'pcode'=>$pcode,
                                'code'=>$row->code,
                                'name'=>$row->name);
                        }
                    }
                    $data['category'] = $category;
                }
            } // 최상위 카테고리가 있을 때
           
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


    # test ##################################################
    public function phpinfo()
    {
        echo phpinfo();
    }
    public function db()
    {
        // 최상위 카테고리
        $q_pcode = $this->db->query("select * from category where pcode=''");
        $top_category = $q_pcode->result_array();
        
        // 보여줄 카테고리 정리
        $q_uc = $this->db->query(""
        ." select * from ("
        ." select c.path as path, c.pcode as pcode, ifnull(c.code, '') as code, c.name as name"
        ." from (select code from user_category uc where uc.user='1') uc"
        ." left join category c"
        ." on uc.code=c.code"
        ." order by path, pcode"
        .") a where a.code <> '' "
        ." ");
        $uc = $q_uc->result_array();
            
        $this->debug($top_category,'-----------------', $uc);
        exit;
        
        // 추가
//        $dt = date('Y-m-d H:i:s');
//        $this->db->query("insert into user_category(user, code, add_dt) values('1','it000001','{$dt}')");

        // 쿼리 테스트
        $q = $this->db->query(""
            ." select c.path, c.pcode, c.code, c.name"
            ." from (select code from user_category uc where uc.user='1') uc"
            ." left join category c"
            ." on uc.code=c.code"
            ." order by path, pcode"
            ." ");
        
        $this->debug($q->result());
    }
    public function debug($v) {
        $args = func_get_args();
        echo "<xmp>";
        foreach ($args as $arg){ var_dump($arg); }
        echo "</xmp>";
    }
}
