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
	
	$('#Search').focus();
	$('#Search').keyup(function(){
		$('#tree').load('/lithium/nodes/build/' + $('#Search').val());
	});
	
	/* render buttons */
	$('button, input[type=submit]').button();
	
	/* autocomplete */
	$('input.Node').autocomplete({
		source: '/lithium/nodes/autocomplete'
	});
	$('input.NodeType').autocomplete({
		source: '/lithium/nodes/node_types'
	});

	$('#fNodeAdd').ajaxForm(function(){
		$('#wNodeAdd').dialog('close');
		$('#fNodeAdd input').val('');
		init();
	});

	/* bind buttons to dialogs */
	$('#bNodeAdd').click(function(){
		$('#wNodeAdd').dialog({
			title: 'Define Dependency'
		});
	});

	/* initialize */
	init();

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
	<div class="input"><label>Owner</label><?=$this->form->text('owner', array('class' => 'Node'))?></div>
	<div class="input"><label>Type</label><?=$this->form->text('type', array('class' => 'NodeType'))?></div>
	<div class="input"><label>Name</label><?=$this->form->text('name', array('class' => 'Node'))?></div>
	<div class="input"><label>Dependency Type</label><?=$this->form->text('dtype', array('class' => 'NodeType'))?></div>
	<div class="input"><label>Dependency Name</label><?=$this->form->text('dname', array('class' => 'Node'))?></div>
	<?=$this->form->submit('Add')?>
	<?=$this->form->end()?>
</div>

<div class="main">

	<?=$this->form->create(null, array('id' => 'fNodeSearch', 'action' => 'search'))?>
	<div class="input"><label>Search</label><?=$this->form->text('search')?></div>
	<?=$this->form->end()?>

	<button id="bNodeAdd">Add Node</button>
	
	<ul id="tree">
	</ul>

</div>


	