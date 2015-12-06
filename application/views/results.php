<title> Search Engine </title>

<?php
echo form_submit('submit', 'Facet');
?>

<div id="facets">

<?php
echo "<br/>";
if(!empty($facets)) {

	for($i = 0; $i < count($facets); $i=$i+2) {
		$facetID = "facet".$i;
		echo form_checkbox($facetID, "True", isset($_POST[$facetID])?$_POST[$facetID]:'');
		echo $facets[$i]."[".$facets[$i+1]."]";
		echo "<br/>";
	}
}
?>
</div>

<div id="results">
<?php
echo "About ".count($resultset)." results";
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
