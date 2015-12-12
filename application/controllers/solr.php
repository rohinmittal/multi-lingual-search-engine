<?php
class Solr extends CI_Controller{
	public function index(){
		$this->load->helper('form');
		$this->config->load('solr');
		$client = $this->config->item('solr_client');
		$serverName = $client['host'].":".$client['port']."/solr/".$client['core']."/select/?wt=json&facet=true&facet.field=content_tags&facet.field=lang&f.content_tags.facet.mincount=1&rows=10000&fl=username&fl=text&fl=translated_text&fl=location&fl=tweet_hashtags&fl=tweet_urls&facet.limit=10&fl=content_tags&q=";

echo $this->input->post('facet2');
		$GLOBALS['queryString']='';

		//translatedText : english version of all text_* | stemmed 
		//text_* : native laguage text | stemmed
		//text : orginal text | not stemmed

		if($this->input->post('query') != '') {
			//make it translatedText later
			$GLOBALS['queryString'] = 'translated_text:'.rawurlencode($this->input->post('query')); 
		}

		if($this->input->post('language') != 'default') {
			// language will be a filter
			$GLOBALS['queryString'] = $GLOBALS['queryString'].'&fq=lang:'.rawurlencode($this->input->post('language')); 
		}

		if($this->input->post('exactWord') != '') {
			//exact word 
			$GLOBALS['queryString'] = $GLOBALS['queryString'].'&fq=translated_text:'.rawurlencode($this->input->post('exactWord')); 
		}

		if($this->input->post('noneWord') != '') {
			//none of the words word 
			$GLOBALS['queryString'] = $GLOBALS['queryString'].'&fq=-translated_text:'.rawurlencode($this->input->post('noneWord')); 
		}

		$json = $serverName.$GLOBALS['queryString'];

		$jsonfile = file_get_contents($json);
		echo $json;
		$responseData = json_decode($jsonfile, TRUE);
		$data=array('httpLink' => $json, 'resultset' => $responseData['response']['docs'], 'facets' => $responseData['facet_counts']['facet_fields']['content_tags'], 'languages' => $responseData['facet_counts']['facet_fields']['lang']);

		$this->load->view('header');
		$this->load->view('results', $data);
		$this->load->view('footer');
	}
	
	public function facet() {
		$GLOBALS['facets'] = '';
		$this->load->helper('form');
		for($i=0; $i < $this->input->post('facetCount'); $i=$i+2) {
			if($this->input->post('facet'.$i) != '') {
				$GLOBALS['facets'] = $GLOBALS['facets'].'&fq=content_tags:'.$this->input->post('facet'.$i);
			}
		}
		for($i=0; $i < $this->input->post('langCount'); $i=$i+2) {
					if($this->input->post('language'.$i) !=''){
				$GLOBALS['facets'] = $GLOBALS['facets'].'&fq=lang:'.$this->input->post('language'.$i);
			}
		}
		
		$facetLink = $this->input->post('httpLink').$GLOBALS['facets'];
		echo $facetLink;
		$jsonfile = file_get_contents($facetLink);
		$responseData = json_decode($jsonfile, TRUE);

		$data=array('httpLink' => $this->input->post('httpLink'), 'resultset' => $responseData['response']['docs'], 'facets' => $this->input->post('facets'), 'languages' => $this->input->post('languages'));
		$this->load->view('header');
		$this->load->view('results', $data);
		$this->load->view('footer');
	}
}
