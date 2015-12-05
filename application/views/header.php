<html>
<title> Search Engine </title>

<style type="text/css">
<?php include 'css/style.css'; ?>
</style>

<script type="text/javascript">
function toggleMe(advancedSearch){
	var e=document.getElementById(advancedSearch);
	if(!e)return true;
	if(e.style.display=="none"){
		e.style.display="block"
	}
	else{
		e.style.display="none"
	}
	return true;
}
</script>

<body>

<div id="searchBar">
<?php
echo form_open('Solr/index');
$options = array('style'=>'width:50%');
echo form_input('query', set_value("", isset($_POST['query'])?$_POST['query']:''), $options);
echo '<br>';
echo form_submit('submit', 'Search');
?>
<input type="button" onclick="return toggleMe('advancedSearch')" value="Advanced Search">
</div>

<!-- 
Display advanced search only if the user has used any of its field. Else no point showing it.
-->

<p id="advancedSearch" style=<?php if(!empty($_POST['exactWord']) || !empty($_POST['anyWord']) || !empty($_POST['nonWord']) || (isset($_POST['language']) && $_POST['language'] != 'default')) echo "display:block"; else echo "display:none"; ?>>

Exact word / phrase: <?php echo form_input('exactWord', isset($_POST['exactWord'])?$_POST['exactWord']:''); ?><br>
Any of these words: <?php echo form_input('anyWord', isset($_POST['anyWord'])?$_POST['anyWord']:''); ?> <br>
None of these words: <?php echo form_input('noneWord', isset($_POST['noneWord'])?$_POST['noneWord']:''); ?> <br>
<?
//hardcoded right now, make it dynamic later on. fetch solr data and show the list accordingly
$languages = array('default' => 'All' , 'en' => 'English', 'fr' => 'French', 'es' => 'Spanish', 'de' => 'German', 'ru' => 'Russian');
?>
Language: <?php echo form_dropdown('language', $languages); ?>

</p>

<? 
echo form_close();
?>

