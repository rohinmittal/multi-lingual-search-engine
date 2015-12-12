<title> Search Engine </title>

<div id="facets">
<?php
echo "<br/>";
echo form_open('Solr/facet');
echo form_hidden('httpLink', $httpLink);
?>

<input type="button" onclick="return toggleMe('lang')" value="Languages:">
<p id="lang" style="display:none">
<?php
if(!empty($languages)) {
	if($languages[1] != 0) {
	echo form_open('Solr/facet');
	for($i = 0; $i < count($languages); $i=$i+2) {
		$langID = "language".$i;
		echo form_checkbox($langID, $languages[$i], isset($_POST[$langID])?$_POST[$langID]:'');
		echo $languages[$i]." [".$languages[$i+1]."]";
		echo "<br/>";
	}
	echo form_hidden('langCount', count($languages));
	echo form_hidden('languages', $languages);
	}
}
?>
</p>

<br>
<input type="button" onclick="return toggleMe('facet')" value="Content Tags:">
<p id="facet" style="display:none">
<?php
if(!empty($facets)) {
	for($i = 0; $i < count($facets); $i=$i+2) {
		$facetID = "facet".$i;
		echo form_checkbox($facetID, $facets[$i], isset($_POST[$facetID])?$_POST[$facetID]:'');
		echo $facets[$i]." [".$facets[$i+1]."]";
		echo "<br/>";
	}
	echo form_hidden('facetCount', count($facets));
	echo form_hidden('facets', $facets);
}
?>
</p>

<?php
echo "<br/>";
echo form_submit('submit', 'Refine Results');
echo form_close();

?>
</div>

<div id="results">
<?php
echo "About ".count($resultset)." results";
if(count($resultset) > 0)
foreach ($resultset as $document) {

	echo '<hr/><table>';

	// the documents are also iterable, to get all fields
	foreach ($document as $field => $value) {
		// this converts multivalue fields to a comma-separated string
		if (is_array($value)) {
			$value = implode(', ', $value);
		}
		if($field!='tweet_urls')
			echo '<tr><th>' . $field . '</th><td>' . $value . '</td></tr>';
		else
			echo '<tr><th>' . $field . '</th><td> <a href='.$value.' target=_blank>'.$value.'</a>  </td></tr>'; 
			
	}
	echo '</table>';
}
?>
</div>


