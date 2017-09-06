<?php

include("connect.php");
require_once("common.php");

if(isset($_GET['volume'])){$volume = $_GET['volume'];}else{$volume = '';}

if(!(isValidVolume($volume)))
{
	exit(1);
}

$query = "select distinct issue,month,year from article where volume='$volume' order by issue";
$result = $db->query($query); 
$num_rows = $result ? $result->num_rows : 0;

echo '<div id="issueHolder" class="issueHolder"><div class="issue">';

if($num_rows > 0)
{
	$isFirst = 1;
	while($row = $result->fetch_assoc())
	{
		$issue = $row['issue'];
		$dissue = preg_replace("/^0/", "", $issue);
		$dissue = preg_replace("/\-0/", "-", $dissue);
		echo (($row['month'] == '01') && ($isFirst == 0)) ? '<div class="deLimiter">|</div>' : '';
		$monthdetails = getMonth($row['month']) . ", " . $row['year'];
		$monthdetails = preg_replace('/^,/', '', $monthdetails);
		if($row['issue'] == 'Supplement|Supplement-I')
		{
			echo '<div class="aIssue"><a href="toc.php?vol=' . $volume . '&amp;issue=' . $row['issue'] . '" title=Special Issue>Supplementary</a></div>';
		}
		else
		{
			echo '<div class="aIssue"><a href="toc.php?vol=' . $volume . '&amp;issue=' . $row['issue'] . '" title="'. $monthdetails .'">स. ' . convert_devanagari($dissue) . '</a></div>';
		}
		$isFirst = 0;
	}
}

echo '</div></div>';

if($result){$result->free();}
$db->close();

?>
