<title> Search Engine </title>
<div id="facets">

<?php
echo "<br/>";
if(!empty($facets)) {
	echo form_open('Solr/facet');
	for($i = 0; $i < count($facets); $i=$i+2) {
		$facetID = "facet".$i;
		echo form_checkbox($facetID, $facets[$i], isset($_POST[$facetID])?$_POST[$facetID]:'');
		echo $facets[$i]."[".$facets[$i+1]."]";
		echo "<br/>";
	}
	echo form_hidden('httpLink', $httpLink);
	echo count($facets);
	echo form_hidden('facetCount', count($facets));
	echo form_hidden('facets', $facets);
	echo form_submit('submit', 'Facet');
	echo form_close();
}
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

		echo '<tr><th>' . $field . '</th><td>' . $value . '</td></tr>';
	}

	echo '</table>';
}
?>
</div>
