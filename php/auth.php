<?php include("include_header.php");?>
<main class="cd-main-content">
		<div class="cd-scrolling-bg cd-color-2">
			<div class="cd-container">
<?php

include("connect.php");
require_once("common.php");

if(isset($_GET['authid'])){$authid = $_GET['authid'];}else{$authid = '';}
if(isset($_GET['author'])){$authorname = $_GET['author'];}else{$authorname = '';}

echo '<h1 class="clr1 gapBelowSmall">सङ्ग्रहः &gt; लेखकाः &gt; ' . $authorname . '</h1>';

$authorname = entityReferenceReplace($authorname);

if(!(isValidAuthid($authid) && isValidAuthor($authorname)))
{
	echo '<span class="aFeature clr2">Invalid URL</span>';
	echo '</div> <!-- cd-container -->';
	echo '</div> <!-- cd-scrolling-bg -->';
	echo '</main> <!-- cd-main-content -->';
	include("include_footer.php");

    exit(1);
}

$query = 'select * from article where authid like \'%' . $authid . '%\'';

$result = $db->query($query); 
$num_rows = $result ? $result->num_rows : 0;

if($num_rows > 0)
{
	while($row = $result->fetch_assoc())
	{
		$query3 = 'select feat_name from feature where featid=\'' . $row['featid'] . '\'';
		$result3 = $db->query($query3); 
		$row3 = $result3->fetch_assoc();

		$dissue = preg_replace("/^0/", "", $row['issue']);
		$dissue = preg_replace("/\-0/", "-", $dissue);

		if($result3){$result3->free();}
		
		echo '<div class="article">';
		echo '<div class="gapBelowSmall">';
		echo ($row3['feat_name'] != '') ? ' <span class="aFeature clr2"><a href="feat.php?feature=' . urlencode($row3['feat_name']) . '&amp;featid=' . $row['featid'] . '">' . $row3['feat_name'] . '</a></span> | ' : '';
		echo '<span class="aIssue clr5"><a href="toc.php?vol=' . $row['volume'] . '&amp;issue=' . $row['issue'] . '">';
		echo ($row['issue'] == '99') ? '(Volume ' . intval($row['volume']) . ', Special Issue' : getMonth($row['month']) . ' ' . $row['year'] . '  (सम्पुटाः ' . convert_devanagari($row['volume']) . ', सञ्चिका ' . convert_devanagari($dissue);
		echo ')</a></span>';
		echo '</div>';
		$issue = ($row['issue'] == '99') ? 'SpecialIssue' : $row['issue'];
		echo '<span class="aTitle"><a target="_blank" href="../Volumes/djvu/' . $row['volume'] . '/' . $issue . '/index.djvu?djvuopts&amp;page=' . $row['page'] . '.djvu&amp;zoom=page">' . $row['title'] . '</a></span><br/>';
		echo "<span class=\"download\"><a href=\"downloadPdf.php?titleid=" . $row['titleid'] . "\" target=\"_blank\">Download Pdf</a></span>";
		echo '</div>';
	}
}

if($result){$result->free();}
$db->close();

?>
			</div> <!-- cd-container -->
		</div> <!-- cd-scrolling-bg -->
	</main> <!-- cd-main-content -->
<?php include("include_footer.php");?>
