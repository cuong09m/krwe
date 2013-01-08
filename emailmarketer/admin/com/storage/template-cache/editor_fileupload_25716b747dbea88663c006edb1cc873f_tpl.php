<?php $IEM = $tpl->Get('IEM'); ?><html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo strtolower(defined("SENDSTUDIO_CHARSET") ? SENDSTUDIO_CHARSET : "UTF-8"); ?>">
<link rel="stylesheet" href="<?php if(isset($GLOBALS['APPLICATION_URL'])) print $GLOBALS['APPLICATION_URL']; ?>/admin/includes/styles/stylesheet.css" type="text/css">
<script src="<?php if(isset($GLOBALS['APPLICATION_URL'])) print $GLOBALS['APPLICATION_URL']; ?>/admin/includes/js/jquery.js"></script>
<script src="<?php if(isset($GLOBALS['APPLICATION_URL'])) print $GLOBALS['APPLICATION_URL']; ?>/admin/includes/js/javascript.js"></script>
<script>
	function UploadFile() {
		if (document.getElementById('newsletterfile').value == '') {
			alert('<?php print GetLang('Editor_ChooseFileToUpload'); ?>');
			return false;
		}
		Butt = document.getElementById('uploadButton');
		Butt.value = '<?php print GetLang('Editor_Import_File_Wait'); ?>';
		Butt.style.width = "150px";
		Butt.disabled = true;
		return true;
	}
</script>
<body style="margin: 0px; padding: 0px; background-color: #F9F9F9; background-image: none;">
<form STYLE="margin: 0px; padding: 0px;" method="post" action="<?php if(isset($GLOBALS['APPLICATION_URL'])) print $GLOBALS['APPLICATION_URL']; ?>/admin/functions/remote.php?ImportType=<?php if(isset($GLOBALS['ImportType'])) print $GLOBALS['ImportType']; ?>" enctype="multipart/form-data" onsubmit="return UploadFile();">
<input type="hidden" name="what" value="importfile">
<input type="file" name="newsletterfile" id="newsletterfile" value="" class="Field" style="font-size:13px;">
<input class="FormButton" type="submit" id="uploadButton" name="upload" value="<?php print GetLang('UploadNewsletter'); ?>" style="width:60px">
</form>
</body>
</html>
