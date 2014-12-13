<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');


// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_receivements/assets/css/list.css');
?>
<u>Selezione classi</u>

<form action="index.php" method="post" name="adminForm" onSubmit="return false">
		<table style="width:auto" class="front-end-list">
			<thead>
				<tr>
					<th><input type="checkbox" name="toggle" value="" onclick="checkAll(this);Implosion(this.form);" /></th>
					<th>CLASSE</th>
				</tr>
			</thead>
			<tbody>
<?php
		$i = 0;
		foreach ($this->items as &$row)
		{
		      $checked    = JHTML::_( 'grid.id', $i, $row->classe );
?>
				<tr class="row">
					<td><?=$checked?></td>
					<td><?=$row->classe?></td>
				</tr>
<?php
			$i++;
		}
?>
			</tbody>
		</table>

</form>
<script type="text/javascript">
var form = document.forms[0];
var values = window.parent.document.getElementById( 'jform_classi' ).value.split(",");
for (var i = 1, n = form.elements.length; i < n; i++) {
	var e = form.elements[i];
	if (e.type == 'checkbox') {
		e.onchange = function() {Implosion(this.form)};
		if (e.value != '' && values.indexOf(e.value) > -1) e.checked = "checked";
	}
}
function Implosion(form) {
        var result = "";
        for (var i = 1, n = form.elements.length; i < n; i++) {
		var e = form.elements[i];
		if (e.type == 'checkbox' && e.checked) {
		      if (result != "") result += ",";
		      result += e.value;
		}
	}
        window.parent.document.getElementById( 'jform_classi' ).value = result;
}
</script>
