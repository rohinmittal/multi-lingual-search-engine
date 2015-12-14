<div id="facets">
<?php
echo "<br/>";
echo form_open('Solr/facet');
echo form_hidden('httpLink', $httpLink);
?>

<input type="button" onclick="return toggleMe('lang')" value="Languages:" class="myButton">
<p id="lang" style="display:none">
<?php
if(!empty($languages)) {
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
?>
</p>

<br>

<input type="button" onclick="return toggleMe('content_tags')" value="Content Tags:" class="myButton">
<p id="content_tags" style="display:none">
<?php
if(!empty($content_tags)) {
	for($i = 0; $i < count($content_tags); $i=$i+2) {
		$tagID = "content_tag".$i;
		echo form_checkbox($tagID, $content_tags[$i], '');
		echo $content_tags[$i]." [".$content_tags[$i+1]."]";
		echo "<br/>";
	}
	echo form_hidden('content_tagsCount', count($content_tags));
	echo form_hidden('content_tags', $content_tags);
}
?>
</p>

<br>

<input type="button" onclick="return toggleMe('usernames')" value="Usernames:" class="myButton">
<p id="usernames" style="display:none">
<?php
if(!empty($usernames)) {
	echo 'Top Twitters for query: ';
	echo '<br/>';
	for($i = 0; $i < count($usernames); $i=$i+2) {
		if($usernames[$i+1] > 0) {
			echo '['.$usernames[$i+1].'] '.$usernames[$i];
			echo "<br/>";
		}
	}
	echo form_hidden('usernames_count', count($usernames));
	echo form_hidden('usernames', $usernames);
}
?>
</p>

<?php
echo "<br/>";
echo "<br/>";
echo "<br/>";
?>

<input type="submit" name="submit" value="RefineResults" class="refineButton" />

<?
echo form_close();
?>
</div>
<!-- div=facet end-->

<div id="results">
<?php
echo "About ".count($resultset)." results";
if(count($resultset) > 0)
foreach ($resultset as $document) {

	echo '<hr/><table>';
	foreach ($document as $field => $value) {
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

<div id='topTwitters'>
	<?php
	if(!empty($usernames)) {
		echo 'Top Twitters for query: ';
		echo '<br/>';
		for($i = 0; $i < count($usernames); $i=$i+2) {
			if($usernames[$i+1] > 0) {
				echo '['.$usernames[$i+1].'] '.$usernames[$i];
				echo "<br/>";
			}
		}
		echo form_hidden('usernames_count', count($usernames));
		echo form_hidden('usernames', $usernames);
	}

	if(!empty($hashtags)) {
		echo '<br/>';
		echo 'Top hashtags: ';
		echo '<br/>';
		for($i = 0; $i < count($hashtags); $i=$i+2) {
			if($hashtags[$i+1] > 0) {
				echo '['.$hashtags[$i+1].'] '.$hashtags[$i];
				echo "<br/>";
			}
		}
		echo form_hidden('hashTags_count', count($hashtags));
		echo form_hidden('hashtags', $hashtags);
	}
	?>
</div>
