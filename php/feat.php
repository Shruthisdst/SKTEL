<?php include("include_header.php");?>
<main class="cd-main-content">
		<div class="cd-scrolling-bg cd-color-2">
			<div class="cd-container">
<?php

include("connect.php");
require_once("common.php");

if(isset($_GET['feature'])){$feat_name = $_GET['feature'];}else{$feat_name = '';}
if(isset($_GET['featid'])){$featid = $_GET['featid'];}else{$featid = '';}

echo '<h1 class="clr1 gapBelowSmall">सङ्ग्रहः &gt; प्रधानविभागाः &gt; ' . $feat_name . '</h1>';

$feat_name = entityReferenceReplace($feat_name);

if(!(isValidFeature($feat_name) && isValidFeatid($featid)))
{
	echo '<span class="aFeature clr2">Invalid URL</span>';
	echo '</div> <!-- cd-container -->';
	echo '</div> <!-- cd-scrolling-bg -->';
	echo '</main> <!-- cd-main-content -->';
	include("include_footer.php");

    exit(1);
}

$query = 'select * from article where featid=\'' . $featid . '\' order by volume, issue, page';

$result = $db->query($query); 
$num_rows = $result ? $result->num_rows : 0;

if($num_rows > 0)
{
	while($row = $result->fetch_assoc())
	{
		$dissue = preg_replace("/^0/", "", $row['issue']);
		$dissue = preg_replace("/\-0/", "-", $dissue);
		
		echo '<div class="article">';
		echo '<div class="gapBelowSmall">';
		echo '<span class="aIssue clr5"><a href="toc.php?vol=' . $row['volume'] . '&amp;issue=' . $row['issue'] . '">';
		echo ($row['issue'] == '99') ? '(Volume ' . intval($row['volume']) . ', Special Issue ' : getMonth($row['month']) . ' ' . $row['year'] . '  (सम्पुटाः ' . convert_devanagari($row['volume']) . ', सञ्चिका ' . convert_devanagari($dissue);
		echo ')</a></span>';
		echo '</div>';
		$issue = ($row['issue'] == '99') ? 'SpecialIssue' : $row['issue'];
		echo '<span class="aTitle"><a target="_blank" href="../Volumes/djvu/' . $row['volume'] . '/' . $issue . '/index.djvu?djvuopts&amp;page=' . $row['page'] . '.djvu&amp;zoom=page">' . $row['title'] . '</a></span><br />';
		if($row['authid'] != 0) {

			echo '	<span class="aAuthor itl">by ';
			$authids = preg_split('/;/',$row['authid']);
			$authornames = preg_split('/;/',$row['authorname']);
			$a=0;
			foreach ($authids as $aid) {

				echo '<a href="auth.php?authid=' . $aid . '&amp;author=' . urlencode($authornames[$a]) . '">' . $authornames[$a] . '</a> ';
				$a++;
			}
			echo '</span><br/>';
		}
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
