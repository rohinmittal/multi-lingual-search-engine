<?php
class Solr extends CI_Controller{
	public function index(){
		$this->load->helper('form');

		$GLOBALS['queryString']='';

		//translatedText : english version of all text_* | stemmed 
		//text_* : native laguage text | stemmed
		//text : orginal text | not stemmed

		if($this->input->post('query') != '') {
			//make it translatedText later
			$GLOBALS['queryString'] = 'text:'.rawurlencode($this->input->post('query')); 
		}

		if($this->input->post('language') != 'default') {
			// language will be a filter
			$GLOBALS['queryString'] = $GLOBALS['queryString'].'&fq=lang:'.rawurlencode($this->input->post('language')); 
		}

		if($this->input->post('exactWord') != '') {
			//exact word 
			$GLOBALS['queryString'] = $GLOBALS['queryString'].'&fq=text:'.rawurlencode($this->input->post('exactWord')); 
		}

		if($this->input->post('noneWord') != '') {
			//none of the words word 
			$GLOBALS['queryString'] = $GLOBALS['queryString'].'&fq=-text:'.rawurlencode($this->input->post('noneWord')); 
		}

		$json = "http://rohinmittal.koding.io:8983/solr/cse535/select/?wt=json&rows=10000&q=".$GLOBALS['queryString'];

		$jsonfile = file_get_contents($json);
		$responseData = json_decode($jsonfile, TRUE);
		$data=array('resultset' => $responseData['response']['docs']);

		foreach ($data['resultset'] as $document) {
			foreach ($document as $field => $value) {
				if (is_array($value)) {
					$value = implode(', ', $value);
				}   
			}   
		}

		$this->load->view('header');
		$this->load->view('results', $data);
		$this->load->view('footer');
	}
}
