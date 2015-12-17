<html>
<title> Search Engine </title>

<style type="text/css">
<?php include 'css/style.css'; ?>
</style>

<script type="text/javascript">
function toggleMe(arg){
	var e=document.getElementById(arg);
	if(!e)
		return true;
	if(e.style.display=="none"){
		e.style.display="block";
		if(arg != 'lang') {
			document.getElementById('lang').style.display="none";
		}
		if(arg != 'content_tags') {
			document.getElementById('content_tags').style.display="none";
		}
		if(arg != 'usernames') {
			document.getElementById('usernames').style.display="none";
		}
	}
	else{
		e.style.display="none";
	}
	return true;
}
</script>

<body>

<div id="searchBar">
<?php
echo form_open('Solr/index');

$languages = array(
	'default' => 'All' ,
	'en' => 'English',
	'fr' => 'French',
	'es' => 'Spanish',
	'de' => 'German',
	'ru' => 'Russian');

$options = array('style'=>'width:50%');
echo form_input('query', isset($_POST['query'])?$_POST['query']:'', $options);
echo form_dropdown('language', $languages);
echo '<br>';
echo form_checkbox('exactWord', '1', isset($_POST['exactWord'])?$_POST['exactWord']:'').' Exact Word or phrase. ';
echo form_checkbox('noneWord', '1', isset($_POST['noneWord'])?$_POST['noneWord']:'').'None of these words. ';
echo '<br/>';
echo form_submit('submit', 'Search');
echo form_close();
?>
</div>
