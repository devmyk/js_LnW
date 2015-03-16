<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class C_test extends CI_Controller {

	public function index() {
		echo "test<br>";
//        echo BASEPATH."<br>";
//        echo VIEWPATH."<br>";
//        echo getcwd()."<br>";
//        echo SELF."<br>";
	}

	# test ##################################################

	public function phpinfo() {
		echo phpinfo();
	}

	public function db() {
		/*
		  // 최상위 카테고리
		  $q_category1 = $this->db->query("select * from category where pcode=''");
		  $top_category = $q_category1->result_array();

		  // 중분류 카테고리
		  $q_category2 = $this->db->query("select * from category where path='2'");
		  $sub_category = $q_category2->result_array();
		  i */
		// 유저 카테고리
		$q_uc = $this->db->query(""
				. " select b.code, b.name, b.pcode, b.pname, b.ppcode, c.name as ppname from ("
				. " select a.code, a.name, a.pcode, c.name as pname, c.pcode as ppcode from ("
				. " select c.path, c.pcode, ifnull(c.code, '') as code, c.name"
				. " from (select code from user_category uc where uc.user='1') uc"
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
				. " ");
		$uc = $q_uc->result_array();
		$this->debug($uc);
		exit;

		$category = array();
		// 보여줄 카테고리 정리
		if ($q_uc->num_rows() > 0) {
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
		}

		$this->debug($category);
		exit;

		// 추가
//        $dt = date('Y-m-d H:i:s');
//        $this->db->query("insert into user_category(user, code, add_dt) values('1','it000001','{$dt}')");
		// 쿼리 테스트
		$q = $this->db->query(""
				. " select c.path, c.pcode, c.code, c.name"
				. " from (select code from user_category uc where uc.user='1') uc"
				. " left join category c"
				. " on uc.code=c.code"
				. " order by path, pcode"
				. " ");

		$this->debug($q->result());
	}

}
