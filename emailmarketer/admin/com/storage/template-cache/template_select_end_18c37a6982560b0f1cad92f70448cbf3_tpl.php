<?php $IEM = $tpl->Get('IEM'); ?></select>
<script>
	function ShowPreview() {
		Template = document.getElementById('TemplateID');
		selectedTemplate = Template.selectedIndex;
		if (selectedTemplate == -1 || selectedTemplate == 0) {
			alert('<?php print GetLang('SelectTemplate'); ?>');
			document.getElementById('TemplateID').focus();
			return false;
		}
		selectedTemplateID = Template.options[Template.selectedIndex].value;
		url = 'index.php?Page=Templates&Action=View&id=' + selectedTemplateID;
		window.open(url);
	}
</script>

