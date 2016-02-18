<?php

// nis
// nip
// semester
// kelas

?>
<?php if ($nilai->Visible) { ?>
<table id="t_nilai" class="ewGrid"><tr><td>
<table id="tbl_nilaimaster" class="table table-bordered table-striped">
	<tbody>
<?php if ($nilai->nis->Visible) { // nis ?>
		<tr id="r_nis">
			<td><?php echo $nilai->nis->FldCaption() ?></td>
			<td<?php echo $nilai->nis->CellAttributes() ?>>
<span id="el_nilai_nis" class="control-group">
<span<?php echo $nilai->nis->ViewAttributes() ?>>
<?php echo $nilai->nis->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($nilai->nip->Visible) { // nip ?>
		<tr id="r_nip">
			<td><?php echo $nilai->nip->FldCaption() ?></td>
			<td<?php echo $nilai->nip->CellAttributes() ?>>
<span id="el_nilai_nip" class="control-group">
<span<?php echo $nilai->nip->ViewAttributes() ?>>
<?php echo $nilai->nip->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($nilai->semester->Visible) { // semester ?>
		<tr id="r_semester">
			<td><?php echo $nilai->semester->FldCaption() ?></td>
			<td<?php echo $nilai->semester->CellAttributes() ?>>
<span id="el_nilai_semester" class="control-group">
<span<?php echo $nilai->semester->ViewAttributes() ?>>
<?php echo $nilai->semester->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($nilai->kelas->Visible) { // kelas ?>
		<tr id="r_kelas">
			<td><?php echo $nilai->kelas->FldCaption() ?></td>
			<td<?php echo $nilai->kelas->CellAttributes() ?>>
<span id="el_nilai_kelas" class="control-group">
<span<?php echo $nilai->kelas->ViewAttributes() ?>>
<?php echo $nilai->kelas->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
</td></tr></table>
<?php } ?>
