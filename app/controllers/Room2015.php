<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Room2015 extends CI_Controller {
	public function index()
	{
		$data = $list = array();

		$order = $this->input->get('order');

		if (empty($order)) {
			$this->db->order_by("seq", "desc");
		} else {
			$this->db->order_by("addr", "asc");
		}
		$q = $this->db->get('room2015');
		if (empty($order)) {
			$data['list'] = $q->result_array();
		} else {
			$data['list'] = $a_lean_ne = $a_lean_yu = array();
			foreach ($q->result_array() as $r) {
				if ($r['is_end'] == 'Y' || $r['lean'] == 'N') {
					$a_lean_ne[] = $r;
				} else {
					$a_lean_yu[] = $r;
				}
			}
			$data['list'] = array_merge($a_lean_yu, $a_lean_ne);
		}

		$this->load->view('room2015', $data);
	}
	public function m()
	{
		$this->load->view('room2015_m');
	}
	public function add()
	{
		$post = $this->input->post();
		$q = $this->db->insert_string('room2015', $post);
		$this->db->query($q);

		redirect('/room2015', 'refresh');
	}
	public function mod()
	{
		$this->db->where('seq', $uid);
		$this->db->update('room2015', $data);
	}
}
