<?php
require $_SERVER["DOCUMENT_ROOT"].'/board2/lib/_include.php';
require "prayServer.php";
?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>기도구문 암기</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="/board2/cmn/cmn.css" />
	<link rel="stylesheet" href="style.css" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<script src="/board2/cmn/cmn.js"></script>
</head>
<body>

<div style="padding:0 20px;">
	<h1>기도구문 암기</h1>
	<h2><a href="pray.php">페이지 갱신</a><h2>
</div>

<hr />
<div style="padding:0 20px;">
	<p>
		<h2 id="bookTitleArea">북마크</h2>
		<div id="bookListArea">
			<a href="javascript:void(0);" style="text-decoration:underline;"><?php echo date("Y-m-d H:i:s"); ?> (현재시간)</a><br />
			<!-- !!! --->
			<?php
			if($bookmarkListCount > 0){
				foreach ($bookmarkList as $index => $row) {
					$number = $index + 1;
			?>
			<a href="#prayPos<?php echo nvl($row["pr_seq"]); ?>"><?php if($number > 1){ ?><br /><?php }//if ?><?php echo nvl($row["prh_date_str"]); ?> (<?php echo nvl($row["pr_no"]); ?>번구문)
			<?php
				}#foreach
			}#if
			?></a>
		</div>
	</p>
</div>

<?php
if($prayListCount > 0){
	foreach ($prayList as $index => $row) {
		$number = $index + 1;
		$prSeq = nvl($row["pr_seq"]);
		$prContent = nvl($row["pr_content"]);
		$editPrContent = "";
		#---
		$editPrContent = $prContent;
		$editPrContent = str_replace("\r\n","\n",$editPrContent);
		$editPrContent = str_replace("\n","<br />",$editPrContent);
		#---
?>
<a name="prayPos<?php echo nvl($row["pr_seq"]); ?>"></a>
<hr />
<div style="padding:0 20px;">
	<p>
		<h3><a href="javascript:fnAddBookmark('<?php echo nvl($row["pr_seq"]); ?>','<?php echo $number; ?>');"><?php echo nvl($row["pr_title"]); ?></a></h3>
		<div>
			<?php echo $editPrContent; ?>
		</div>
	</p>
</div>
<?php
	}#foreach
}#if
?>

<br /><br /><br />

<?php require "prayScript.php"; ?>
</body>
</html>