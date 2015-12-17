<?php
echo form_open('Solr/facet');
echo form_hidden('httpLink', $httpLink);
echo form_hidden('query', $queryString);
echo form_hidden('noneWord', $noneWord);
echo form_hidden('exactWord', $exactWord);
echo form_hidden('langCount', count($languages));
echo form_hidden('languages', $languages);
echo form_hidden('content_tagsCount', count($content_tags));
echo form_hidden('content_tags', $content_tags);
echo form_hidden('usernames_count', count($usernames));
echo form_hidden('usernames', $usernames);
echo form_hidden('hashTags_count', count($hashtags));
echo form_hidden('hashtags', $hashtags);
?>

<div id="facets">
	<div class="sideHeaderButton" align="center">
		<b>Filters</b>
		<br>
	</div>
	<br>
	<br>

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
		}
		?>
	</p>

	<br>

	<input type="button" onclick="return toggleMe('content_tags')" value="Content Tags:" class="myButton">
	<p id="content_tags" style="display:none">
		<?php
		if(!empty($content_tags)) {
			for($i = 0; $i < count($content_tags); $i=$i+2) {
				if($content_tags[$i+1] > 0) {
					$tagID = "content_tag".$i;
					echo form_checkbox($tagID, $content_tags[$i], isset($_POST[$tagID])?$_POST[$tagID]:'');
					echo $content_tags[$i]." [".$content_tags[$i+1]."]";
					echo "<br/>";
				}
			}
		}
		?>
	</p>

	<br>

	<input type="button" onclick="return toggleMe('usernames')" value="Usernames:" class="myButton">
	<p id="usernames" style="display:none">
		<?php
		if(!empty($usernames)) {
			echo '<br/>';
			for($i = 0; $i < count($usernames); $i=$i+2) {
				if($usernames[$i+1] > 0) {
					$usernameID = "username".$i;
					echo form_checkbox($usernameID, $usernames[$i], isset($_POST[$usernameID])?$_POST[$usernameID]:'');
					echo $usernames[$i]." [".$usernames[$i+1]."]";
					echo "<br/>";
				}
			}
		}
		?>
	</p>

	<br>
	<br>

	<input type="submit" name="submit" value="Filter Results" class="refineButton" />

	<?
	echo form_close();
	?>
</div>
<!-- div=facet end-->

<div id='trends'>
	<div class="sideHeaderButton" align="center">
		Top Trends
	</div>
	<br>

	<?php
	if(!empty($hashtags)) {
		echo '<br/>';
		echo '<b><u><i>Top hashtags: </b></u></i>';
		echo '<br/>';
		for($i = 0; $i < count($hashtags); $i=$i+2) {
			if($hashtags[$i+1] > 0) {
				echo $hashtags[$i];
				echo "<br/>";
			}
		}
	}
	?>

	<?php
	if(!empty($usernames)) {
		echo '<br/>';
		echo '<b><u><i>Top Tweeters: </i></u></b>';
		echo '<br/>';
		for($i = 0; $i < count($usernames); $i=$i+2) {
			if($usernames[$i+1] > 0) {
				echo $usernames[$i];
				echo "<br/>";
			}
		}
	}
	?>
</div>

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
		if($field == 'username') {
			$field = 'Username:';
		}
		else if($field == 'text') {
			$field = 'Tweet:';
		}
		else if($field == 'content_tags') {
			$field = 'Tags:';
		}
		else if($field == 'tweet_hashtags') {
			$field = 'Hashtags:';
		}
		else if($field == 'tweet_urls') {
			$field = 'URL:';
		}
		else if($field == 'translated_text') {
			$field = 'English Translation:';
		}

		if($field == 'English Translation:') {
		}
		else if($field !='URL:') {
			echo '<tr><th>' . $field . '</th><td>' . $value . '</td></tr>';
		}
		else {
			echo '<tr><th>' . $field . '</th><td> <a href='.$value.' target=_blank>'.$value.'</a>  </td></tr>';
		}
	}
	echo '</table>';
}
?>
</div>
