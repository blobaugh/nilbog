<?php

$query = "SELECT * FROM `".DB_PREFIX."Pages` WHERE ParentPageId='0'";

$result = $Db->query($query);

echo "<ul>";
while($r = $result->fetch_assoc()) {
	//dBug($r);
	$path = HTTP_ROOT . str_replace(' ', '_', $r['Title']);
	$r['Title'] = ucfirst($r['Title']);
	echo "\n\t<li><a href=\"$path\" title=\"{$r['Title']}\">{$r['Title']}</a></li>";
	
	if(isset($ext_params['depth']) && $ext_params['depth'] > 0) {
		$query = "SELECT * FROM `".DB_PREFIX."Pages` WHERE ParentPageId='{$r['PageId']}'";
		$subresult = $Db->query($query);
		if($subresult->num_rows > 0) {
			echo "\n\t<ul>";
			while($s = $subresult->fetch_assoc()) {
				$sub = str_replace(' ', '_', $s['Title']);
				echo "\n\t\t<li><a href=\"$path/$sub\" title=\"{$s['Title']}\">{$s['Title']}</a></li>";
			}
			echo "\n\t</ul>";
		}
	}
}
echo "</ul>";