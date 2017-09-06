<?php include("include_header.php");?>
<main class="cd-main-content">
		<div class="cd-scrolling-bg cd-color-2">
			<div class="cd-container">
				<h1 class="clr1">सङ्ग्रहः &gt; लेखाः</h1>
				<div class="alphabet gapBelowSmall gapAboveSmall">
					<span class="letter"><a href="articles.php?letter=अ">अ</a></span>
					<span class="letter"><a href="articles.php?letter=आ">आ</a></span>
					<span class="letter"><a href="articles.php?letter=इ">इ</a></span>
					<span class="letter"><a href="articles.php?letter=ई">ई</a></span>
					<span class="letter"><a href="articles.php?letter=उ">उ</a></span>
					<span class="letter"><a href="articles.php?letter=ऊ">ऊ</a></span>
					<span class="letter"><a href="articles.php?letter=ऋ">ऋ</a></span>
					<span class="letter"><a href="articles.php?letter=ए">ए</a></span>
					<span class="letter"><a href="articles.php?letter=ऐ">ऐ</a></span>
					<span class="letter"><a href="articles.php?letter=ओ">ओ</a></span>
					<span class="letter"><a href="articles.php?letter=औ">औ</a></span>

					<span class="letter"><a href="articles.php?letter=क">क</a></span>
					<span class="letter"><a href="articles.php?letter=ख">ख</a></span>
					<span class="letter"><a href="articles.php?letter=ग">ग</a></span>
					<span class="letter"><a href="articles.php?letter=घ">घ</a></span>

					<span class="letter"><a href="articles.php?letter=च">च</a></span>
					<span class="letter"><a href="articles.php?letter=छ">छ</a></span>
					<span class="letter"><a href="articles.php?letter=ज">ज</a></span>
					<span class="letter"><a href="articles.php?letter=झ">झ</a></span>

					<span class="letter"><a href="articles.php?letter=ट">ट</a></span>
					<span class="letter"><a href="articles.php?letter=ड">ड</a></span>

					<span class="letter"><a href="articles.php?letter=त">त</a></span>
					<span class="letter"><a href="articles.php?letter=थ">थ</a></span>
					<span class="letter"><a href="articles.php?letter=द">द</a></span>
					<span class="letter"><a href="articles.php?letter=ध">ध</a></span>
					<span class="letter"><a href="articles.php?letter=न">न</a></span>

					<span class="letter"><a href="articles.php?letter=प">प</a></span>
					<span class="letter"><a href="articles.php?letter=फ">फ</a></span>
					<span class="letter"><a href="articles.php?letter=ब">ब</a></span>
					<span class="letter"><a href="articles.php?letter=भ">भ</a></span>
					<span class="letter"><a href="articles.php?letter=म">म</a></span>

					<span class="letter"><a href="articles.php?letter=य">य</a></span>
					<span class="letter"><a href="articles.php?letter=र">र</a></span>
					<span class="letter"><a href="articles.php?letter=ल">ल</a></span>
					<span class="letter"><a href="articles.php?letter=व">व</a></span>
					<span class="letter"><a href="articles.php?letter=श">श</a></span>
					<span class="letter"><a href="articles.php?letter=ष">ष</a></span>
					<span class="letter"><a href="articles.php?letter=स">स</a></span>
					<span class="letter"><a href="articles.php?letter=ह">ह</a></span>
					<span class="letter"><a href="articles.php?letter=Special">#</a></span>
				</div>
<?php

include("connect.php");
require_once("common.php");


if(isset($_GET['letter']) && $_GET['letter'] != '')
{
	$letter = $_GET['letter'];
}
else
{
	$letter = 'अ';
}

if($letter == 'special')
{
	$query = "select * from article where title not regexp '^अ|आ|इ|ई|उ|ऊ|ऋ|ए|ऐ|ओ|औ|क|ख|ग|घ|च|छ|ज|झ|ट|ड|त|थ|द|ध|न|प|फ|ब|भ|म|य|र|ल|व|श|ष|स|ह' order by TRIM(BOTH '`' FROM TRIM(BOTH '``' FROM title))";
}
else
{
	$query = "select * from article where title like '$letter%' union select * from article where title like '``$letter%' union select * from article where title like '`$letter%' order by TRIM(BOTH '`' FROM TRIM(BOTH '``' FROM title))";
}

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
		echo '	<div class="gapBelowSmall">';
		echo ($row3['feat_name'] != '') ? '<span class="aFeature clr2"><a href="feat.php?feature=' . urlencode($row3['feat_name']) . '&amp;featid=' . $row['featid'] . '">' . $row3['feat_name'] . '</a></span> | ' : '';
		echo '<span class="aIssue clr5"><a href="toc.php?vol=' . $row['volume'] . '&amp;issue=' . $row['issue'] . '">';
		echo ($row['issue'] == '99') ? '(Volume ' . intval($row['volume']) . ', Special Issue' : getMonth($row['month']) . ' ' . $row['year'] . '  (सम्पुटाः ' . convert_devanagari($row['volume']) . ', सञ्चिका ' . convert_devanagari($dissue);
		echo ')</a></span>';
		echo '</div>';
		$issue = ($row['issue'] == '99') ? 'SpecialIssue' : $row['issue'];
		echo '<span class="aTitle"><a target="_blank" href="../Volumes/djvu/' . $row['volume'] . '/' . $issue . '/index.djvu?djvuopts&amp;page=' . $row['page'] . '.djvu&amp;zoom=page">' . $row['title'] . '</a></span><br />';
		if($row['authid'] != 0) {

			echo '<span class="aAuthor itl">by ';
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
else
{
	echo '<span class="sml">Sorry! No articles were found to begin with the letter \'' . $letter . '\' in Sahrudaya Journal</span>';
}

if($result){$result->free();}
$db->close();

?>
			</div> <!-- cd-container -->
		</div> <!-- cd-scrolling-bg -->
	</main> <!-- cd-main-content -->
<?php include("include_footer.php");?>
