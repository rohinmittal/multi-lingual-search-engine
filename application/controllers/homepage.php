<?php

class Homepage extends CI_Controller {
	public function index() {
		$this->load->helper('form');
		$this->load->view('header');
		$data=array('resultset' => array());
		$this->load->view('results', $data);
		$this->load->view('footer');
	}
}
