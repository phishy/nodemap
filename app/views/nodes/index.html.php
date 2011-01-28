<?php print $this->html->script('jquery/js/jquery-1.4.4.min.js') ?>
<?php print $this->html->script('jquery/js/jquery-ui-1.8.9.custom.min.js') ?>
<?php print $this->html->script('jquery.form.js') ?>
<?php print $this->html->script('jsTree/_lib/jquery.cookie.js') ?>
<?php print $this->html->script('jquery.treeview/jquery.treeview.js') ?>
<?php print $this->html->script('jquery.treeview/jquery.treeview.async.js') ?>
<?php print $this->html->style('/js/jquery/css/smoothness/jquery-ui-1.8.9.custom.css')?>
<?php print $this->html->style('/js/jquery.treeview/jquery.treeview.css')?>

<script>

function init() {
	$('#tree').empty();
	$("#tree").treeview({
		url: '/lithium/nodes/get'
	});
}

$(function() {
	$('button, input[type=submit]').button();
	
	$('input').autocomplete({
		source: '/lithium/nodes/autocomplete'
	});

	$('#fNodeAdd').ajaxForm(function(){
		$('#wNodeAdd').dialog('close');
		$('#fNodeAdd input').val('');
		init();
	});

	$('#bNodeAdd').click(function(){
		$('#wNodeAdd').dialog({
			title: 'Define Dependency'
		});
	});

	init();
	//$('#tree').jstree({'plugins' : [ 'cookies', 'html_data', 'themeroller' ]});
});
</script>

<style>
.input {
	
}
.input label {
	display: block;
}
</style>

<div id="wNodeAdd" class="window" style="display:none">
	<?=$this->form->create(null, array('id' => 'fNodeAdd', 'action' => 'add'))?>
	<div class="input"><label>Owner</label><?=$this->form->text('owner')?></div>
	<div class="input"><label>Type</label><?=$this->form->text('type')?></div>
	<div class="input"><label>Name</label><?=$this->form->text('name')?></div>
	<div class="input"><label>Dependency Type</label><?=$this->form->text('dtype')?></div>
	<div class="input"><label>Dependency Name</label><?=$this->form->text('dname')?></div>
	<?=$this->form->submit('Add')?>
	<?=$this->form->end()?>
</div>

<div class="main">

	
	<button id="bNodeAdd">Add Node</button>
	
	<ul id="tree">
	</ul>

</div>


	