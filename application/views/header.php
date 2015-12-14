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

$_POST=array();
$languages = array('default' => 'All' , 'en' => 'English', 'fr' => 'French', 'es' => 'Spanish', 'de' => 'German', 'ru' => 'Russian');

$options = array('style'=>'width:50%');
echo form_input('query', '', $options).'     ';
echo form_dropdown('language', $languages);
echo '<br>';
echo form_checkbox('exactWord', '1').' Exact Word or phrase. ';
echo form_checkbox('noneWord', '1').'None of these words. ';
echo '<br/>';
echo form_submit('submit', 'Search');
echo form_close();
?>
</div>
