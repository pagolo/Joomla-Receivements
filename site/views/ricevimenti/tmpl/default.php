<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
?>
<h1>Ricevimenti</h1>

<form action="index.php" method="post" name="publicForm">
	<div id="editcell">
		<table class='category'>
			<thead>
				<tr>
					<th>DOCENTE</th>
					<th>CLASSI</th>
					<th>GIORNO</th>
				</tr>
			</thead>
			<tbody>
<?php
		$k = 0;
		foreach ($this->items as &$row)
		{
?>
				<tr class="row">
					<td><?=$row->name?></td>
					<td><?=$row->classi?></td>
					<td><?=$row->giorno?></td>
				</tr>
<?php
			$k = 1 - $k;
		}
?>
			</tbody>
		</table>
	</div>
</form>