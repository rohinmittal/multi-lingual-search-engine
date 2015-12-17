<?php
class Solr extends CI_Controller{
	public function index(){
		$this->load->helper('form');
		$this->config->load('solr');
		$bingCred = array('clientID' => 'Blahhhhh', 'clientSecret' => 'QlBeF5hpqSbC4p3VELZ7LV92QtbtPi40MC/5MsZAFhk=');
		$this->load->library('Bing', $bingCred);
		$client = $this->config->item('solr_client');

		$serverName = $client['host'].":".$client['port']."/solr/".$client['core']."/select/?wt=json&facet=true&facet.field=content_tags&facet.field=lang&facet.field=username&facet.field=tweet_hashtags&f.content_tags.facet.mincount=1&rows=10000&fl=username&fl=text&fl=translated_text&fl=tweet_hashtags&fl=tweet_urls&facet.limit=10&fl=content_tags";

		$GLOBALS['queryString']='';
		if($this->input->post('query') != '') {
			// input query is mandatory.
			$inputQuery = $this->input->post('query');
			if($this->input->post('exactWord') == 1) {
				// if user selects exactWord checkbox
				$GLOBALS['queryString'] = $GLOBALS['queryString'].'&q=text:"'.rawurlencode($inputQuery).'"';
			}
			else if($this->input->post('noneWord') == 1) {
				// if useer selects none of these checkbox
				$GLOBALS['queryString'] = $GLOBALS['queryString'].'&q=-text:"'.rawurlencode($inputQuery).'"';
			}
			else {
				/*			$langDetect = 'http://ws.detectlanguage.com/0.2/detect?q='.rawurlencode($inputQuery).'&key=f1b2095ccfb94d062ed7b06032ad8555';
							$langResult = json_decode(file_get_contents($langDetect), TRUE);
							echo $langResult['data']['detections'][0]['language']; */
				$temp = $this->bing->getTranslation('auto', 'en', $inputQuery);
				if(substr_compare($temp, 'Argument ExceptionMethod', 0, 15) != 0) {
					// if translated
					$inputQuery = $temp;
					$GLOBALS['queryString'] = '&q=translated_text:'.rawurlencode($inputQuery);
				}
				else {
					$GLOBALS['queryString'] = '&q=text:'.rawurlencode($inputQuery);
				}
			}

			if($this->input->post('language') != 'default') {
				// language will be a filter
				$GLOBALS['queryString'] = $GLOBALS['queryString'].'&fq=lang:'.rawurlencode($this->input->post('language'));
			}
		}

		$json = $serverName.$GLOBALS['queryString'];
		$jsonfile = file_get_contents($json);
		$responseData = json_decode($jsonfile, TRUE);
		$data=array(
				'queryString' => $this->input->post('query'),
				'exactWord' => $this->input->post('exactWord'),
				'noneWord' => $this->input->post('noneWord'),
				'httpLink' => $json,
				'resultset' => $responseData['response']['docs'],
				'content_tags' => $responseData['facet_counts']['facet_fields']['content_tags'],
				'languages' => $responseData['facet_counts']['facet_fields']['lang'],
				'usernames' => $responseData['facet_counts']['facet_fields']['username'],
				'hashtags' => $responseData['facet_counts']['facet_fields']['tweet_hashtags']
			   );

		$this->load->view('header', $data);
		$this->load->view('results');
		$this->load->view('footer');
	}

	public function facet() {
		$GLOBALS['facets'] = '';
		$GLOBALS['usernames'] = '';
		$GLOBALS['content_tags'] = '';
		$GLOBALS['languages'] = '';

		$this->load->helper('form');

		for($i=0; $i < $this->input->post('content_tagsCount'); $i=$i+2) {
			if($this->input->post('content_tag'.$i) != '') {
				$GLOBALS['content_tags'] = $GLOBALS['content_tags'].'"'.rawurlencode($this->input->post('content_tag'.$i)).'"OR';
			}
		}

		if($GLOBALS['content_tags'] != '') {
			$GLOBALS['facets'] = $GLOBALS['facets'].'&fq=content_tags:('.$GLOBALS['content_tags'].'"")';
		}

		for($i=0; $i < $this->input->post('langCount'); $i=$i+2) {
			if($this->input->post('language'.$i) !=''){
				$GLOBALS['languages'] = $GLOBALS['languages'].'"'.rawurlencode($this->input->post('language'.$i)).'"OR';
			}
		}

		if($GLOBALS['languages'] != '') {
			$GLOBALS['facets'] = $GLOBALS['facets'].'&fq=lang:('.$GLOBALS['languages'].'"")';
		}

		for($i=0; $i < $this->input->post('usernames_count'); $i=$i+2) {
			if($this->input->post('username'.$i) !=''){
				$GLOBALS['usernames'] = $GLOBALS['usernames'].'"'.rawurlencode($this->input->post('username'.$i)).'"OR';
			}
		}

		if($GLOBALS['usernames'] != '') {
			$GLOBALS['facets'] = $GLOBALS['facets'].'&fq=username:('.$GLOBALS['usernames'].'"")';
		}

		$facetLink = $this->input->post('httpLink').$GLOBALS['facets'];
		$jsonfile = file_get_contents($facetLink);
		$responseData = json_decode($jsonfile, TRUE);

		$data=array(
				'httpLink' => $this->input->post('httpLink'),
				'queryString' => $this->input->post('query'),
				'exactWord' => $this->input->post('exactWord'),
				'noneWord' => $this->input->post('noneWord'),
				'resultset' => $responseData['response']['docs'],
				'content_tags' => $this->input->post('content_tags'),
				'languages' => $this->input->post('languages'),
				'usernames' => $this->input->post('usernames'),
				'hashtags' => $this->input->post('hashtags')
			   );

		$this->load->view('header', $data);
		$this->load->view('results');
		$this->load->view('footer');
	}
}
