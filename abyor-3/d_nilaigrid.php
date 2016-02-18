<?php

// Create page object
if (!isset($d_nilai_grid)) $d_nilai_grid = new cd_nilai_grid();

// Page init
$d_nilai_grid->Page_Init();

// Page main
$d_nilai_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$d_nilai_grid->Page_Render();
?>
<?php if ($d_nilai->Export == "") { ?>
<script type="text/javascript">

// Page object
var d_nilai_grid = new ew_Page("d_nilai_grid");
d_nilai_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = d_nilai_grid.PageID; // For backward compatibility

// Form object
var fd_nilaigrid = new ew_Form("fd_nilaigrid");
fd_nilaigrid.FormKeyCountName = '<?php echo $d_nilai_grid->FormKeyCountName ?>';

// Validate form
fd_nilaigrid.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	this.PostAutoSuggest();
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
		var checkrow = (gridinsert) ? !this.EmptyRow(infix) : true;
		if (checkrow) {
			addcnt++;
			elm = this.GetElements("x" + infix + "_id_mapel");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($d_nilai->id_mapel->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nilai_afektif");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($d_nilai->nilai_afektif->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nilai_afektif");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($d_nilai->nilai_afektif->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_nilai_komulatif");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($d_nilai->nilai_komulatif->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nilai_komulatif");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($d_nilai->nilai_komulatif->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_nilai_psikomotorik");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($d_nilai->nilai_psikomotorik->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nilai_psikomotorik");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($d_nilai->nilai_psikomotorik->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_nilai_rata2Drata");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($d_nilai->nilai_rata2Drata->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nilai_rata2Drata");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($d_nilai->nilai_rata2Drata->FldErrMsg()) ?>");

			// Set up row object
			ew_ElementsToRow(fobj);

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
fd_nilaigrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "id_mapel", false)) return false;
	if (ew_ValueChanged(fobj, infix, "nilai_afektif", false)) return false;
	if (ew_ValueChanged(fobj, infix, "nilai_komulatif", false)) return false;
	if (ew_ValueChanged(fobj, infix, "nilai_psikomotorik", false)) return false;
	if (ew_ValueChanged(fobj, infix, "nilai_rata2Drata", false)) return false;
	return true;
}

// Form_CustomValidate event
fd_nilaigrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fd_nilaigrid.ValidateRequired = true;
<?php } else { ?>
fd_nilaigrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fd_nilaigrid.Lists["x_id_mapel"] = {"LinkField":"x_id_mapel","Ajax":null,"AutoFill":false,"DisplayFields":["x_namaMapel","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php if ($d_nilai->getCurrentMasterTable() == "" && $d_nilai_grid->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $d_nilai_grid->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
if ($d_nilai->CurrentAction == "gridadd") {
	if ($d_nilai->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$d_nilai_grid->TotalRecs = $d_nilai->SelectRecordCount();
			$d_nilai_grid->Recordset = $d_nilai_grid->LoadRecordset($d_nilai_grid->StartRec-1, $d_nilai_grid->DisplayRecs);
		} else {
			if ($d_nilai_grid->Recordset = $d_nilai_grid->LoadRecordset())
				$d_nilai_grid->TotalRecs = $d_nilai_grid->Recordset->RecordCount();
		}
		$d_nilai_grid->StartRec = 1;
		$d_nilai_grid->DisplayRecs = $d_nilai_grid->TotalRecs;
	} else {
		$d_nilai->CurrentFilter = "0=1";
		$d_nilai_grid->StartRec = 1;
		$d_nilai_grid->DisplayRecs = $d_nilai->GridAddRowCount;
	}
	$d_nilai_grid->TotalRecs = $d_nilai_grid->DisplayRecs;
	$d_nilai_grid->StopRec = $d_nilai_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$d_nilai_grid->TotalRecs = $d_nilai->SelectRecordCount();
	} else {
		if ($d_nilai_grid->Recordset = $d_nilai_grid->LoadRecordset())
			$d_nilai_grid->TotalRecs = $d_nilai_grid->Recordset->RecordCount();
	}
	$d_nilai_grid->StartRec = 1;
	$d_nilai_grid->DisplayRecs = $d_nilai_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$d_nilai_grid->Recordset = $d_nilai_grid->LoadRecordset($d_nilai_grid->StartRec-1, $d_nilai_grid->DisplayRecs);
}
$d_nilai_grid->RenderOtherOptions();
?>
<?php $d_nilai_grid->ShowPageHeader(); ?>
<?php
$d_nilai_grid->ShowMessage();
?>
<table class="ewGrid"><tr><td class="ewGridContent">
<div id="fd_nilaigrid" class="ewForm form-inline">
<?php if ($d_nilai_grid->ShowOtherOptions) { ?>
<div class="ewGridUpperPanel ewListOtherOptions">
<?php
	foreach ($d_nilai_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<?php } ?>
<div id="gmp_d_nilai" class="ewGridMiddlePanel">
<table id="tbl_d_nilaigrid" class="ewTable ewTableSeparate">
<?php echo $d_nilai->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$d_nilai_grid->RenderListOptions();

// Render list options (header, left)
$d_nilai_grid->ListOptions->Render("header", "left");
?>
<?php if ($d_nilai->id_mapel->Visible) { // id_mapel ?>
	<?php if ($d_nilai->SortUrl($d_nilai->id_mapel) == "") { ?>
		<td><div id="elh_d_nilai_id_mapel" class="d_nilai_id_mapel"><div class="ewTableHeaderCaption"><?php echo $d_nilai->id_mapel->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_d_nilai_id_mapel" class="d_nilai_id_mapel">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $d_nilai->id_mapel->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($d_nilai->id_mapel->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($d_nilai->id_mapel->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($d_nilai->nilai_afektif->Visible) { // nilai_afektif ?>
	<?php if ($d_nilai->SortUrl($d_nilai->nilai_afektif) == "") { ?>
		<td><div id="elh_d_nilai_nilai_afektif" class="d_nilai_nilai_afektif"><div class="ewTableHeaderCaption"><?php echo $d_nilai->nilai_afektif->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_d_nilai_nilai_afektif" class="d_nilai_nilai_afektif">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $d_nilai->nilai_afektif->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($d_nilai->nilai_afektif->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($d_nilai->nilai_afektif->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($d_nilai->nilai_komulatif->Visible) { // nilai_komulatif ?>
	<?php if ($d_nilai->SortUrl($d_nilai->nilai_komulatif) == "") { ?>
		<td><div id="elh_d_nilai_nilai_komulatif" class="d_nilai_nilai_komulatif"><div class="ewTableHeaderCaption"><?php echo $d_nilai->nilai_komulatif->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_d_nilai_nilai_komulatif" class="d_nilai_nilai_komulatif">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $d_nilai->nilai_komulatif->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($d_nilai->nilai_komulatif->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($d_nilai->nilai_komulatif->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($d_nilai->nilai_psikomotorik->Visible) { // nilai_psikomotorik ?>
	<?php if ($d_nilai->SortUrl($d_nilai->nilai_psikomotorik) == "") { ?>
		<td><div id="elh_d_nilai_nilai_psikomotorik" class="d_nilai_nilai_psikomotorik"><div class="ewTableHeaderCaption"><?php echo $d_nilai->nilai_psikomotorik->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_d_nilai_nilai_psikomotorik" class="d_nilai_nilai_psikomotorik">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $d_nilai->nilai_psikomotorik->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($d_nilai->nilai_psikomotorik->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($d_nilai->nilai_psikomotorik->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($d_nilai->nilai_rata2Drata->Visible) { // nilai_rata-rata ?>
	<?php if ($d_nilai->SortUrl($d_nilai->nilai_rata2Drata) == "") { ?>
		<td><div id="elh_d_nilai_nilai_rata2Drata" class="d_nilai_nilai_rata2Drata"><div class="ewTableHeaderCaption"><?php echo $d_nilai->nilai_rata2Drata->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_d_nilai_nilai_rata2Drata" class="d_nilai_nilai_rata2Drata">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $d_nilai->nilai_rata2Drata->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($d_nilai->nilai_rata2Drata->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($d_nilai->nilai_rata2Drata->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$d_nilai_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$d_nilai_grid->StartRec = 1;
$d_nilai_grid->StopRec = $d_nilai_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($d_nilai_grid->FormKeyCountName) && ($d_nilai->CurrentAction == "gridadd" || $d_nilai->CurrentAction == "gridedit" || $d_nilai->CurrentAction == "F")) {
		$d_nilai_grid->KeyCount = $objForm->GetValue($d_nilai_grid->FormKeyCountName);
		$d_nilai_grid->StopRec = $d_nilai_grid->StartRec + $d_nilai_grid->KeyCount - 1;
	}
}
$d_nilai_grid->RecCnt = $d_nilai_grid->StartRec - 1;
if ($d_nilai_grid->Recordset && !$d_nilai_grid->Recordset->EOF) {
	$d_nilai_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $d_nilai_grid->StartRec > 1)
		$d_nilai_grid->Recordset->Move($d_nilai_grid->StartRec - 1);
} elseif (!$d_nilai->AllowAddDeleteRow && $d_nilai_grid->StopRec == 0) {
	$d_nilai_grid->StopRec = $d_nilai->GridAddRowCount;
}

// Initialize aggregate
$d_nilai->RowType = EW_ROWTYPE_AGGREGATEINIT;
$d_nilai->ResetAttrs();
$d_nilai_grid->RenderRow();
if ($d_nilai->CurrentAction == "gridadd")
	$d_nilai_grid->RowIndex = 0;
if ($d_nilai->CurrentAction == "gridedit")
	$d_nilai_grid->RowIndex = 0;
while ($d_nilai_grid->RecCnt < $d_nilai_grid->StopRec) {
	$d_nilai_grid->RecCnt++;
	if (intval($d_nilai_grid->RecCnt) >= intval($d_nilai_grid->StartRec)) {
		$d_nilai_grid->RowCnt++;
		if ($d_nilai->CurrentAction == "gridadd" || $d_nilai->CurrentAction == "gridedit" || $d_nilai->CurrentAction == "F") {
			$d_nilai_grid->RowIndex++;
			$objForm->Index = $d_nilai_grid->RowIndex;
			if ($objForm->HasValue($d_nilai_grid->FormActionName))
				$d_nilai_grid->RowAction = strval($objForm->GetValue($d_nilai_grid->FormActionName));
			elseif ($d_nilai->CurrentAction == "gridadd")
				$d_nilai_grid->RowAction = "insert";
			else
				$d_nilai_grid->RowAction = "";
		}

		// Set up key count
		$d_nilai_grid->KeyCount = $d_nilai_grid->RowIndex;

		// Init row class and style
		$d_nilai->ResetAttrs();
		$d_nilai->CssClass = "";
		if ($d_nilai->CurrentAction == "gridadd") {
			if ($d_nilai->CurrentMode == "copy") {
				$d_nilai_grid->LoadRowValues($d_nilai_grid->Recordset); // Load row values
				$d_nilai_grid->SetRecordKey($d_nilai_grid->RowOldKey, $d_nilai_grid->Recordset); // Set old record key
			} else {
				$d_nilai_grid->LoadDefaultValues(); // Load default values
				$d_nilai_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$d_nilai_grid->LoadRowValues($d_nilai_grid->Recordset); // Load row values
		}
		$d_nilai->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($d_nilai->CurrentAction == "gridadd") // Grid add
			$d_nilai->RowType = EW_ROWTYPE_ADD; // Render add
		if ($d_nilai->CurrentAction == "gridadd" && $d_nilai->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$d_nilai_grid->RestoreCurrentRowFormValues($d_nilai_grid->RowIndex); // Restore form values
		if ($d_nilai->CurrentAction == "gridedit") { // Grid edit
			if ($d_nilai->EventCancelled) {
				$d_nilai_grid->RestoreCurrentRowFormValues($d_nilai_grid->RowIndex); // Restore form values
			}
			if ($d_nilai_grid->RowAction == "insert")
				$d_nilai->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$d_nilai->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($d_nilai->CurrentAction == "gridedit" && ($d_nilai->RowType == EW_ROWTYPE_EDIT || $d_nilai->RowType == EW_ROWTYPE_ADD) && $d_nilai->EventCancelled) // Update failed
			$d_nilai_grid->RestoreCurrentRowFormValues($d_nilai_grid->RowIndex); // Restore form values
		if ($d_nilai->RowType == EW_ROWTYPE_EDIT) // Edit row
			$d_nilai_grid->EditRowCnt++;
		if ($d_nilai->CurrentAction == "F") // Confirm row
			$d_nilai_grid->RestoreCurrentRowFormValues($d_nilai_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$d_nilai->RowAttrs = array_merge($d_nilai->RowAttrs, array('data-rowindex'=>$d_nilai_grid->RowCnt, 'id'=>'r' . $d_nilai_grid->RowCnt . '_d_nilai', 'data-rowtype'=>$d_nilai->RowType));

		// Render row
		$d_nilai_grid->RenderRow();

		// Render list options
		$d_nilai_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($d_nilai_grid->RowAction <> "delete" && $d_nilai_grid->RowAction <> "insertdelete" && !($d_nilai_grid->RowAction == "insert" && $d_nilai->CurrentAction == "F" && $d_nilai_grid->EmptyRow())) {
?>
	<tr<?php echo $d_nilai->RowAttributes() ?>>
<?php

// Render list options (body, left)
$d_nilai_grid->ListOptions->Render("body", "left", $d_nilai_grid->RowCnt);
?>
	<?php if ($d_nilai->id_mapel->Visible) { // id_mapel ?>
		<td<?php echo $d_nilai->id_mapel->CellAttributes() ?>>
<?php if ($d_nilai->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $d_nilai_grid->RowCnt ?>_d_nilai_id_mapel" class="control-group d_nilai_id_mapel">
<select data-field="x_id_mapel" id="x<?php echo $d_nilai_grid->RowIndex ?>_id_mapel" name="x<?php echo $d_nilai_grid->RowIndex ?>_id_mapel"<?php echo $d_nilai->id_mapel->EditAttributes() ?>>
<?php
if (is_array($d_nilai->id_mapel->EditValue)) {
	$arwrk = $d_nilai->id_mapel->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($d_nilai->id_mapel->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $d_nilai->id_mapel->OldValue = "";
?>
</select>
<script type="text/javascript">
fd_nilaigrid.Lists["x_id_mapel"].Options = <?php echo (is_array($d_nilai->id_mapel->EditValue)) ? ew_ArrayToJson($d_nilai->id_mapel->EditValue, 1) : "[]" ?>;
</script>
</span>
<input type="hidden" data-field="x_id_mapel" name="o<?php echo $d_nilai_grid->RowIndex ?>_id_mapel" id="o<?php echo $d_nilai_grid->RowIndex ?>_id_mapel" value="<?php echo ew_HtmlEncode($d_nilai->id_mapel->OldValue) ?>">
<?php } ?>
<?php if ($d_nilai->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $d_nilai_grid->RowCnt ?>_d_nilai_id_mapel" class="control-group d_nilai_id_mapel">
<select data-field="x_id_mapel" id="x<?php echo $d_nilai_grid->RowIndex ?>_id_mapel" name="x<?php echo $d_nilai_grid->RowIndex ?>_id_mapel"<?php echo $d_nilai->id_mapel->EditAttributes() ?>>
<?php
if (is_array($d_nilai->id_mapel->EditValue)) {
	$arwrk = $d_nilai->id_mapel->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($d_nilai->id_mapel->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $d_nilai->id_mapel->OldValue = "";
?>
</select>
<script type="text/javascript">
fd_nilaigrid.Lists["x_id_mapel"].Options = <?php echo (is_array($d_nilai->id_mapel->EditValue)) ? ew_ArrayToJson($d_nilai->id_mapel->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } ?>
<?php if ($d_nilai->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $d_nilai->id_mapel->ViewAttributes() ?>>
<?php echo $d_nilai->id_mapel->ListViewValue() ?></span>
<input type="hidden" data-field="x_id_mapel" name="x<?php echo $d_nilai_grid->RowIndex ?>_id_mapel" id="x<?php echo $d_nilai_grid->RowIndex ?>_id_mapel" value="<?php echo ew_HtmlEncode($d_nilai->id_mapel->FormValue) ?>">
<input type="hidden" data-field="x_id_mapel" name="o<?php echo $d_nilai_grid->RowIndex ?>_id_mapel" id="o<?php echo $d_nilai_grid->RowIndex ?>_id_mapel" value="<?php echo ew_HtmlEncode($d_nilai->id_mapel->OldValue) ?>">
<?php } ?>
<a id="<?php echo $d_nilai_grid->PageObjName . "_row_" . $d_nilai_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($d_nilai->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_detail_nilai_id" name="x<?php echo $d_nilai_grid->RowIndex ?>_detail_nilai_id" id="x<?php echo $d_nilai_grid->RowIndex ?>_detail_nilai_id" value="<?php echo ew_HtmlEncode($d_nilai->detail_nilai_id->CurrentValue) ?>">
<input type="hidden" data-field="x_detail_nilai_id" name="o<?php echo $d_nilai_grid->RowIndex ?>_detail_nilai_id" id="o<?php echo $d_nilai_grid->RowIndex ?>_detail_nilai_id" value="<?php echo ew_HtmlEncode($d_nilai->detail_nilai_id->OldValue) ?>">
<?php } ?>
<?php if ($d_nilai->RowType == EW_ROWTYPE_EDIT || $d_nilai->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_detail_nilai_id" name="x<?php echo $d_nilai_grid->RowIndex ?>_detail_nilai_id" id="x<?php echo $d_nilai_grid->RowIndex ?>_detail_nilai_id" value="<?php echo ew_HtmlEncode($d_nilai->detail_nilai_id->CurrentValue) ?>">
<?php } ?>
	<?php if ($d_nilai->nilai_afektif->Visible) { // nilai_afektif ?>
		<td<?php echo $d_nilai->nilai_afektif->CellAttributes() ?>>
<?php if ($d_nilai->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $d_nilai_grid->RowCnt ?>_d_nilai_nilai_afektif" class="control-group d_nilai_nilai_afektif">
<input type="text" data-field="x_nilai_afektif" name="x<?php echo $d_nilai_grid->RowIndex ?>_nilai_afektif" id="x<?php echo $d_nilai_grid->RowIndex ?>_nilai_afektif" size="30" placeholder="<?php echo ew_HtmlEncode($d_nilai->nilai_afektif->PlaceHolder) ?>" value="<?php echo $d_nilai->nilai_afektif->EditValue ?>"<?php echo $d_nilai->nilai_afektif->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_nilai_afektif" name="o<?php echo $d_nilai_grid->RowIndex ?>_nilai_afektif" id="o<?php echo $d_nilai_grid->RowIndex ?>_nilai_afektif" value="<?php echo ew_HtmlEncode($d_nilai->nilai_afektif->OldValue) ?>">
<?php } ?>
<?php if ($d_nilai->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $d_nilai_grid->RowCnt ?>_d_nilai_nilai_afektif" class="control-group d_nilai_nilai_afektif">
<input type="text" data-field="x_nilai_afektif" name="x<?php echo $d_nilai_grid->RowIndex ?>_nilai_afektif" id="x<?php echo $d_nilai_grid->RowIndex ?>_nilai_afektif" size="30" placeholder="<?php echo ew_HtmlEncode($d_nilai->nilai_afektif->PlaceHolder) ?>" value="<?php echo $d_nilai->nilai_afektif->EditValue ?>"<?php echo $d_nilai->nilai_afektif->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($d_nilai->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $d_nilai->nilai_afektif->ViewAttributes() ?>>
<?php echo $d_nilai->nilai_afektif->ListViewValue() ?></span>
<input type="hidden" data-field="x_nilai_afektif" name="x<?php echo $d_nilai_grid->RowIndex ?>_nilai_afektif" id="x<?php echo $d_nilai_grid->RowIndex ?>_nilai_afektif" value="<?php echo ew_HtmlEncode($d_nilai->nilai_afektif->FormValue) ?>">
<input type="hidden" data-field="x_nilai_afektif" name="o<?php echo $d_nilai_grid->RowIndex ?>_nilai_afektif" id="o<?php echo $d_nilai_grid->RowIndex ?>_nilai_afektif" value="<?php echo ew_HtmlEncode($d_nilai->nilai_afektif->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($d_nilai->nilai_komulatif->Visible) { // nilai_komulatif ?>
		<td<?php echo $d_nilai->nilai_komulatif->CellAttributes() ?>>
<?php if ($d_nilai->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $d_nilai_grid->RowCnt ?>_d_nilai_nilai_komulatif" class="control-group d_nilai_nilai_komulatif">
<input type="text" data-field="x_nilai_komulatif" name="x<?php echo $d_nilai_grid->RowIndex ?>_nilai_komulatif" id="x<?php echo $d_nilai_grid->RowIndex ?>_nilai_komulatif" size="30" placeholder="<?php echo ew_HtmlEncode($d_nilai->nilai_komulatif->PlaceHolder) ?>" value="<?php echo $d_nilai->nilai_komulatif->EditValue ?>"<?php echo $d_nilai->nilai_komulatif->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_nilai_komulatif" name="o<?php echo $d_nilai_grid->RowIndex ?>_nilai_komulatif" id="o<?php echo $d_nilai_grid->RowIndex ?>_nilai_komulatif" value="<?php echo ew_HtmlEncode($d_nilai->nilai_komulatif->OldValue) ?>">
<?php } ?>
<?php if ($d_nilai->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $d_nilai_grid->RowCnt ?>_d_nilai_nilai_komulatif" class="control-group d_nilai_nilai_komulatif">
<input type="text" data-field="x_nilai_komulatif" name="x<?php echo $d_nilai_grid->RowIndex ?>_nilai_komulatif" id="x<?php echo $d_nilai_grid->RowIndex ?>_nilai_komulatif" size="30" placeholder="<?php echo ew_HtmlEncode($d_nilai->nilai_komulatif->PlaceHolder) ?>" value="<?php echo $d_nilai->nilai_komulatif->EditValue ?>"<?php echo $d_nilai->nilai_komulatif->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($d_nilai->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $d_nilai->nilai_komulatif->ViewAttributes() ?>>
<?php echo $d_nilai->nilai_komulatif->ListViewValue() ?></span>
<input type="hidden" data-field="x_nilai_komulatif" name="x<?php echo $d_nilai_grid->RowIndex ?>_nilai_komulatif" id="x<?php echo $d_nilai_grid->RowIndex ?>_nilai_komulatif" value="<?php echo ew_HtmlEncode($d_nilai->nilai_komulatif->FormValue) ?>">
<input type="hidden" data-field="x_nilai_komulatif" name="o<?php echo $d_nilai_grid->RowIndex ?>_nilai_komulatif" id="o<?php echo $d_nilai_grid->RowIndex ?>_nilai_komulatif" value="<?php echo ew_HtmlEncode($d_nilai->nilai_komulatif->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($d_nilai->nilai_psikomotorik->Visible) { // nilai_psikomotorik ?>
		<td<?php echo $d_nilai->nilai_psikomotorik->CellAttributes() ?>>
<?php if ($d_nilai->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $d_nilai_grid->RowCnt ?>_d_nilai_nilai_psikomotorik" class="control-group d_nilai_nilai_psikomotorik">
<input type="text" data-field="x_nilai_psikomotorik" name="x<?php echo $d_nilai_grid->RowIndex ?>_nilai_psikomotorik" id="x<?php echo $d_nilai_grid->RowIndex ?>_nilai_psikomotorik" size="30" placeholder="<?php echo ew_HtmlEncode($d_nilai->nilai_psikomotorik->PlaceHolder) ?>" value="<?php echo $d_nilai->nilai_psikomotorik->EditValue ?>"<?php echo $d_nilai->nilai_psikomotorik->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_nilai_psikomotorik" name="o<?php echo $d_nilai_grid->RowIndex ?>_nilai_psikomotorik" id="o<?php echo $d_nilai_grid->RowIndex ?>_nilai_psikomotorik" value="<?php echo ew_HtmlEncode($d_nilai->nilai_psikomotorik->OldValue) ?>">
<?php } ?>
<?php if ($d_nilai->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $d_nilai_grid->RowCnt ?>_d_nilai_nilai_psikomotorik" class="control-group d_nilai_nilai_psikomotorik">
<input type="text" data-field="x_nilai_psikomotorik" name="x<?php echo $d_nilai_grid->RowIndex ?>_nilai_psikomotorik" id="x<?php echo $d_nilai_grid->RowIndex ?>_nilai_psikomotorik" size="30" placeholder="<?php echo ew_HtmlEncode($d_nilai->nilai_psikomotorik->PlaceHolder) ?>" value="<?php echo $d_nilai->nilai_psikomotorik->EditValue ?>"<?php echo $d_nilai->nilai_psikomotorik->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($d_nilai->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $d_nilai->nilai_psikomotorik->ViewAttributes() ?>>
<?php echo $d_nilai->nilai_psikomotorik->ListViewValue() ?></span>
<input type="hidden" data-field="x_nilai_psikomotorik" name="x<?php echo $d_nilai_grid->RowIndex ?>_nilai_psikomotorik" id="x<?php echo $d_nilai_grid->RowIndex ?>_nilai_psikomotorik" value="<?php echo ew_HtmlEncode($d_nilai->nilai_psikomotorik->FormValue) ?>">
<input type="hidden" data-field="x_nilai_psikomotorik" name="o<?php echo $d_nilai_grid->RowIndex ?>_nilai_psikomotorik" id="o<?php echo $d_nilai_grid->RowIndex ?>_nilai_psikomotorik" value="<?php echo ew_HtmlEncode($d_nilai->nilai_psikomotorik->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($d_nilai->nilai_rata2Drata->Visible) { // nilai_rata-rata ?>
		<td<?php echo $d_nilai->nilai_rata2Drata->CellAttributes() ?>>
<?php if ($d_nilai->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $d_nilai_grid->RowCnt ?>_d_nilai_nilai_rata2Drata" class="control-group d_nilai_nilai_rata2Drata">
<input type="text" data-field="x_nilai_rata2Drata" name="x<?php echo $d_nilai_grid->RowIndex ?>_nilai_rata2Drata" id="x<?php echo $d_nilai_grid->RowIndex ?>_nilai_rata2Drata" size="30" placeholder="<?php echo ew_HtmlEncode($d_nilai->nilai_rata2Drata->PlaceHolder) ?>" value="<?php echo $d_nilai->nilai_rata2Drata->EditValue ?>"<?php echo $d_nilai->nilai_rata2Drata->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_nilai_rata2Drata" name="o<?php echo $d_nilai_grid->RowIndex ?>_nilai_rata2Drata" id="o<?php echo $d_nilai_grid->RowIndex ?>_nilai_rata2Drata" value="<?php echo ew_HtmlEncode($d_nilai->nilai_rata2Drata->OldValue) ?>">
<?php } ?>
<?php if ($d_nilai->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $d_nilai_grid->RowCnt ?>_d_nilai_nilai_rata2Drata" class="control-group d_nilai_nilai_rata2Drata">
<input type="text" data-field="x_nilai_rata2Drata" name="x<?php echo $d_nilai_grid->RowIndex ?>_nilai_rata2Drata" id="x<?php echo $d_nilai_grid->RowIndex ?>_nilai_rata2Drata" size="30" placeholder="<?php echo ew_HtmlEncode($d_nilai->nilai_rata2Drata->PlaceHolder) ?>" value="<?php echo $d_nilai->nilai_rata2Drata->EditValue ?>"<?php echo $d_nilai->nilai_rata2Drata->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($d_nilai->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $d_nilai->nilai_rata2Drata->ViewAttributes() ?>>
<?php echo $d_nilai->nilai_rata2Drata->ListViewValue() ?></span>
<input type="hidden" data-field="x_nilai_rata2Drata" name="x<?php echo $d_nilai_grid->RowIndex ?>_nilai_rata2Drata" id="x<?php echo $d_nilai_grid->RowIndex ?>_nilai_rata2Drata" value="<?php echo ew_HtmlEncode($d_nilai->nilai_rata2Drata->FormValue) ?>">
<input type="hidden" data-field="x_nilai_rata2Drata" name="o<?php echo $d_nilai_grid->RowIndex ?>_nilai_rata2Drata" id="o<?php echo $d_nilai_grid->RowIndex ?>_nilai_rata2Drata" value="<?php echo ew_HtmlEncode($d_nilai->nilai_rata2Drata->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$d_nilai_grid->ListOptions->Render("body", "right", $d_nilai_grid->RowCnt);
?>
	</tr>
<?php if ($d_nilai->RowType == EW_ROWTYPE_ADD || $d_nilai->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fd_nilaigrid.UpdateOpts(<?php echo $d_nilai_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($d_nilai->CurrentAction <> "gridadd" || $d_nilai->CurrentMode == "copy")
		if (!$d_nilai_grid->Recordset->EOF) $d_nilai_grid->Recordset->MoveNext();
}
?>
<?php
	if ($d_nilai->CurrentMode == "add" || $d_nilai->CurrentMode == "copy" || $d_nilai->CurrentMode == "edit") {
		$d_nilai_grid->RowIndex = '$rowindex$';
		$d_nilai_grid->LoadDefaultValues();

		// Set row properties
		$d_nilai->ResetAttrs();
		$d_nilai->RowAttrs = array_merge($d_nilai->RowAttrs, array('data-rowindex'=>$d_nilai_grid->RowIndex, 'id'=>'r0_d_nilai', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($d_nilai->RowAttrs["class"], "ewTemplate");
		$d_nilai->RowType = EW_ROWTYPE_ADD;

		// Render row
		$d_nilai_grid->RenderRow();

		// Render list options
		$d_nilai_grid->RenderListOptions();
		$d_nilai_grid->StartRowCnt = 0;
?>
	<tr<?php echo $d_nilai->RowAttributes() ?>>
<?php

// Render list options (body, left)
$d_nilai_grid->ListOptions->Render("body", "left", $d_nilai_grid->RowIndex);
?>
	<?php if ($d_nilai->id_mapel->Visible) { // id_mapel ?>
		<td>
<?php if ($d_nilai->CurrentAction <> "F") { ?>
<span id="el$rowindex$_d_nilai_id_mapel" class="control-group d_nilai_id_mapel">
<select data-field="x_id_mapel" id="x<?php echo $d_nilai_grid->RowIndex ?>_id_mapel" name="x<?php echo $d_nilai_grid->RowIndex ?>_id_mapel"<?php echo $d_nilai->id_mapel->EditAttributes() ?>>
<?php
if (is_array($d_nilai->id_mapel->EditValue)) {
	$arwrk = $d_nilai->id_mapel->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($d_nilai->id_mapel->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $d_nilai->id_mapel->OldValue = "";
?>
</select>
<script type="text/javascript">
fd_nilaigrid.Lists["x_id_mapel"].Options = <?php echo (is_array($d_nilai->id_mapel->EditValue)) ? ew_ArrayToJson($d_nilai->id_mapel->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } else { ?>
<span id="el$rowindex$_d_nilai_id_mapel" class="control-group d_nilai_id_mapel">
<span<?php echo $d_nilai->id_mapel->ViewAttributes() ?>>
<?php echo $d_nilai->id_mapel->ViewValue ?></span>
</span>
<input type="hidden" data-field="x_id_mapel" name="x<?php echo $d_nilai_grid->RowIndex ?>_id_mapel" id="x<?php echo $d_nilai_grid->RowIndex ?>_id_mapel" value="<?php echo ew_HtmlEncode($d_nilai->id_mapel->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_id_mapel" name="o<?php echo $d_nilai_grid->RowIndex ?>_id_mapel" id="o<?php echo $d_nilai_grid->RowIndex ?>_id_mapel" value="<?php echo ew_HtmlEncode($d_nilai->id_mapel->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($d_nilai->nilai_afektif->Visible) { // nilai_afektif ?>
		<td>
<?php if ($d_nilai->CurrentAction <> "F") { ?>
<span id="el$rowindex$_d_nilai_nilai_afektif" class="control-group d_nilai_nilai_afektif">
<input type="text" data-field="x_nilai_afektif" name="x<?php echo $d_nilai_grid->RowIndex ?>_nilai_afektif" id="x<?php echo $d_nilai_grid->RowIndex ?>_nilai_afektif" size="30" placeholder="<?php echo ew_HtmlEncode($d_nilai->nilai_afektif->PlaceHolder) ?>" value="<?php echo $d_nilai->nilai_afektif->EditValue ?>"<?php echo $d_nilai->nilai_afektif->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_d_nilai_nilai_afektif" class="control-group d_nilai_nilai_afektif">
<span<?php echo $d_nilai->nilai_afektif->ViewAttributes() ?>>
<?php echo $d_nilai->nilai_afektif->ViewValue ?></span>
</span>
<input type="hidden" data-field="x_nilai_afektif" name="x<?php echo $d_nilai_grid->RowIndex ?>_nilai_afektif" id="x<?php echo $d_nilai_grid->RowIndex ?>_nilai_afektif" value="<?php echo ew_HtmlEncode($d_nilai->nilai_afektif->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nilai_afektif" name="o<?php echo $d_nilai_grid->RowIndex ?>_nilai_afektif" id="o<?php echo $d_nilai_grid->RowIndex ?>_nilai_afektif" value="<?php echo ew_HtmlEncode($d_nilai->nilai_afektif->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($d_nilai->nilai_komulatif->Visible) { // nilai_komulatif ?>
		<td>
<?php if ($d_nilai->CurrentAction <> "F") { ?>
<span id="el$rowindex$_d_nilai_nilai_komulatif" class="control-group d_nilai_nilai_komulatif">
<input type="text" data-field="x_nilai_komulatif" name="x<?php echo $d_nilai_grid->RowIndex ?>_nilai_komulatif" id="x<?php echo $d_nilai_grid->RowIndex ?>_nilai_komulatif" size="30" placeholder="<?php echo ew_HtmlEncode($d_nilai->nilai_komulatif->PlaceHolder) ?>" value="<?php echo $d_nilai->nilai_komulatif->EditValue ?>"<?php echo $d_nilai->nilai_komulatif->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_d_nilai_nilai_komulatif" class="control-group d_nilai_nilai_komulatif">
<span<?php echo $d_nilai->nilai_komulatif->ViewAttributes() ?>>
<?php echo $d_nilai->nilai_komulatif->ViewValue ?></span>
</span>
<input type="hidden" data-field="x_nilai_komulatif" name="x<?php echo $d_nilai_grid->RowIndex ?>_nilai_komulatif" id="x<?php echo $d_nilai_grid->RowIndex ?>_nilai_komulatif" value="<?php echo ew_HtmlEncode($d_nilai->nilai_komulatif->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nilai_komulatif" name="o<?php echo $d_nilai_grid->RowIndex ?>_nilai_komulatif" id="o<?php echo $d_nilai_grid->RowIndex ?>_nilai_komulatif" value="<?php echo ew_HtmlEncode($d_nilai->nilai_komulatif->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($d_nilai->nilai_psikomotorik->Visible) { // nilai_psikomotorik ?>
		<td>
<?php if ($d_nilai->CurrentAction <> "F") { ?>
<span id="el$rowindex$_d_nilai_nilai_psikomotorik" class="control-group d_nilai_nilai_psikomotorik">
<input type="text" data-field="x_nilai_psikomotorik" name="x<?php echo $d_nilai_grid->RowIndex ?>_nilai_psikomotorik" id="x<?php echo $d_nilai_grid->RowIndex ?>_nilai_psikomotorik" size="30" placeholder="<?php echo ew_HtmlEncode($d_nilai->nilai_psikomotorik->PlaceHolder) ?>" value="<?php echo $d_nilai->nilai_psikomotorik->EditValue ?>"<?php echo $d_nilai->nilai_psikomotorik->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_d_nilai_nilai_psikomotorik" class="control-group d_nilai_nilai_psikomotorik">
<span<?php echo $d_nilai->nilai_psikomotorik->ViewAttributes() ?>>
<?php echo $d_nilai->nilai_psikomotorik->ViewValue ?></span>
</span>
<input type="hidden" data-field="x_nilai_psikomotorik" name="x<?php echo $d_nilai_grid->RowIndex ?>_nilai_psikomotorik" id="x<?php echo $d_nilai_grid->RowIndex ?>_nilai_psikomotorik" value="<?php echo ew_HtmlEncode($d_nilai->nilai_psikomotorik->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nilai_psikomotorik" name="o<?php echo $d_nilai_grid->RowIndex ?>_nilai_psikomotorik" id="o<?php echo $d_nilai_grid->RowIndex ?>_nilai_psikomotorik" value="<?php echo ew_HtmlEncode($d_nilai->nilai_psikomotorik->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($d_nilai->nilai_rata2Drata->Visible) { // nilai_rata-rata ?>
		<td>
<?php if ($d_nilai->CurrentAction <> "F") { ?>
<span id="el$rowindex$_d_nilai_nilai_rata2Drata" class="control-group d_nilai_nilai_rata2Drata">
<input type="text" data-field="x_nilai_rata2Drata" name="x<?php echo $d_nilai_grid->RowIndex ?>_nilai_rata2Drata" id="x<?php echo $d_nilai_grid->RowIndex ?>_nilai_rata2Drata" size="30" placeholder="<?php echo ew_HtmlEncode($d_nilai->nilai_rata2Drata->PlaceHolder) ?>" value="<?php echo $d_nilai->nilai_rata2Drata->EditValue ?>"<?php echo $d_nilai->nilai_rata2Drata->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_d_nilai_nilai_rata2Drata" class="control-group d_nilai_nilai_rata2Drata">
<span<?php echo $d_nilai->nilai_rata2Drata->ViewAttributes() ?>>
<?php echo $d_nilai->nilai_rata2Drata->ViewValue ?></span>
</span>
<input type="hidden" data-field="x_nilai_rata2Drata" name="x<?php echo $d_nilai_grid->RowIndex ?>_nilai_rata2Drata" id="x<?php echo $d_nilai_grid->RowIndex ?>_nilai_rata2Drata" value="<?php echo ew_HtmlEncode($d_nilai->nilai_rata2Drata->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nilai_rata2Drata" name="o<?php echo $d_nilai_grid->RowIndex ?>_nilai_rata2Drata" id="o<?php echo $d_nilai_grid->RowIndex ?>_nilai_rata2Drata" value="<?php echo ew_HtmlEncode($d_nilai->nilai_rata2Drata->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$d_nilai_grid->ListOptions->Render("body", "right", $d_nilai_grid->RowCnt);
?>
<script type="text/javascript">
fd_nilaigrid.UpdateOpts(<?php echo $d_nilai_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($d_nilai->CurrentMode == "add" || $d_nilai->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $d_nilai_grid->FormKeyCountName ?>" id="<?php echo $d_nilai_grid->FormKeyCountName ?>" value="<?php echo $d_nilai_grid->KeyCount ?>">
<?php echo $d_nilai_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($d_nilai->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $d_nilai_grid->FormKeyCountName ?>" id="<?php echo $d_nilai_grid->FormKeyCountName ?>" value="<?php echo $d_nilai_grid->KeyCount ?>">
<?php echo $d_nilai_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($d_nilai->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fd_nilaigrid">
</div>
<?php

// Close recordset
if ($d_nilai_grid->Recordset)
	$d_nilai_grid->Recordset->Close();
?>
<?php if ($d_nilai_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel ewListOtherOptions">
<?php
	foreach ($d_nilai_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<?php } ?>
</div>
</td></tr></table>
<?php if ($d_nilai->Export == "") { ?>
<script type="text/javascript">
fd_nilaigrid.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$d_nilai_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$d_nilai_grid->Page_Terminate();
?>
