<title> Search Engine </title>
<?php

echo "About ".count($resultset)." results";
?>

<div id="facets">
</div>

<div id="results">
<?

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

