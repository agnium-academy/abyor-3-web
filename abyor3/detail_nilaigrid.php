<?php

// Create page object
if (!isset($detail_nilai_grid)) $detail_nilai_grid = new cdetail_nilai_grid();

// Page init
$detail_nilai_grid->Page_Init();

// Page main
$detail_nilai_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$detail_nilai_grid->Page_Render();
?>
<?php if ($detail_nilai->Export == "") { ?>
<script type="text/javascript">

// Page object
var detail_nilai_grid = new ew_Page("detail_nilai_grid");
detail_nilai_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = detail_nilai_grid.PageID; // For backward compatibility

// Form object
var fdetail_nilaigrid = new ew_Form("fdetail_nilaigrid");
fdetail_nilaigrid.FormKeyCountName = '<?php echo $detail_nilai_grid->FormKeyCountName ?>';

// Validate form
fdetail_nilaigrid.Validate = function() {
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
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($detail_nilai->id_mapel->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nilai_afektif");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($detail_nilai->nilai_afektif->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nilai_afektif");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($detail_nilai->nilai_afektif->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_nilai_komulatif");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($detail_nilai->nilai_komulatif->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nilai_komulatif");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($detail_nilai->nilai_komulatif->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_nilai_psikomotorik");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($detail_nilai->nilai_psikomotorik->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nilai_psikomotorik");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($detail_nilai->nilai_psikomotorik->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_nilai_rata_rata");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($detail_nilai->nilai_rata_rata->FldErrMsg()) ?>");

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
fdetail_nilaigrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "id_mapel", false)) return false;
	if (ew_ValueChanged(fobj, infix, "nilai_afektif", false)) return false;
	if (ew_ValueChanged(fobj, infix, "nilai_komulatif", false)) return false;
	if (ew_ValueChanged(fobj, infix, "nilai_psikomotorik", false)) return false;
	if (ew_ValueChanged(fobj, infix, "nilai_rata_rata", false)) return false;
	return true;
}

// Form_CustomValidate event
fdetail_nilaigrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fdetail_nilaigrid.ValidateRequired = true;
<?php } else { ?>
fdetail_nilaigrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fdetail_nilaigrid.Lists["x_id_mapel"] = {"LinkField":"x_id_mapel","Ajax":null,"AutoFill":false,"DisplayFields":["x_nama_mapel","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php if ($detail_nilai->getCurrentMasterTable() == "" && $detail_nilai_grid->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $detail_nilai_grid->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
if ($detail_nilai->CurrentAction == "gridadd") {
	if ($detail_nilai->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$detail_nilai_grid->TotalRecs = $detail_nilai->SelectRecordCount();
			$detail_nilai_grid->Recordset = $detail_nilai_grid->LoadRecordset($detail_nilai_grid->StartRec-1, $detail_nilai_grid->DisplayRecs);
		} else {
			if ($detail_nilai_grid->Recordset = $detail_nilai_grid->LoadRecordset())
				$detail_nilai_grid->TotalRecs = $detail_nilai_grid->Recordset->RecordCount();
		}
		$detail_nilai_grid->StartRec = 1;
		$detail_nilai_grid->DisplayRecs = $detail_nilai_grid->TotalRecs;
	} else {
		$detail_nilai->CurrentFilter = "0=1";
		$detail_nilai_grid->StartRec = 1;
		$detail_nilai_grid->DisplayRecs = $detail_nilai->GridAddRowCount;
	}
	$detail_nilai_grid->TotalRecs = $detail_nilai_grid->DisplayRecs;
	$detail_nilai_grid->StopRec = $detail_nilai_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$detail_nilai_grid->TotalRecs = $detail_nilai->SelectRecordCount();
	} else {
		if ($detail_nilai_grid->Recordset = $detail_nilai_grid->LoadRecordset())
			$detail_nilai_grid->TotalRecs = $detail_nilai_grid->Recordset->RecordCount();
	}
	$detail_nilai_grid->StartRec = 1;
	$detail_nilai_grid->DisplayRecs = $detail_nilai_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$detail_nilai_grid->Recordset = $detail_nilai_grid->LoadRecordset($detail_nilai_grid->StartRec-1, $detail_nilai_grid->DisplayRecs);
}
$detail_nilai_grid->RenderOtherOptions();
?>
<?php $detail_nilai_grid->ShowPageHeader(); ?>
<?php
$detail_nilai_grid->ShowMessage();
?>
<table class="ewGrid"><tr><td class="ewGridContent">
<div id="fdetail_nilaigrid" class="ewForm form-inline">
<?php if ($detail_nilai_grid->ShowOtherOptions) { ?>
<div class="ewGridUpperPanel ewListOtherOptions">
<?php
	foreach ($detail_nilai_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<?php } ?>
<div id="gmp_detail_nilai" class="ewGridMiddlePanel">
<table id="tbl_detail_nilaigrid" class="ewTable ewTableSeparate">
<?php echo $detail_nilai->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$detail_nilai_grid->RenderListOptions();

// Render list options (header, left)
$detail_nilai_grid->ListOptions->Render("header", "left");
?>
<?php if ($detail_nilai->id_mapel->Visible) { // id_mapel ?>
	<?php if ($detail_nilai->SortUrl($detail_nilai->id_mapel) == "") { ?>
		<td><div id="elh_detail_nilai_id_mapel" class="detail_nilai_id_mapel"><div class="ewTableHeaderCaption"><?php echo $detail_nilai->id_mapel->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_detail_nilai_id_mapel" class="detail_nilai_id_mapel">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detail_nilai->id_mapel->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detail_nilai->id_mapel->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detail_nilai->id_mapel->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($detail_nilai->nilai_afektif->Visible) { // nilai_afektif ?>
	<?php if ($detail_nilai->SortUrl($detail_nilai->nilai_afektif) == "") { ?>
		<td><div id="elh_detail_nilai_nilai_afektif" class="detail_nilai_nilai_afektif"><div class="ewTableHeaderCaption"><?php echo $detail_nilai->nilai_afektif->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_detail_nilai_nilai_afektif" class="detail_nilai_nilai_afektif">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detail_nilai->nilai_afektif->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detail_nilai->nilai_afektif->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detail_nilai->nilai_afektif->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($detail_nilai->nilai_komulatif->Visible) { // nilai_komulatif ?>
	<?php if ($detail_nilai->SortUrl($detail_nilai->nilai_komulatif) == "") { ?>
		<td><div id="elh_detail_nilai_nilai_komulatif" class="detail_nilai_nilai_komulatif"><div class="ewTableHeaderCaption"><?php echo $detail_nilai->nilai_komulatif->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_detail_nilai_nilai_komulatif" class="detail_nilai_nilai_komulatif">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detail_nilai->nilai_komulatif->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detail_nilai->nilai_komulatif->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detail_nilai->nilai_komulatif->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($detail_nilai->nilai_psikomotorik->Visible) { // nilai_psikomotorik ?>
	<?php if ($detail_nilai->SortUrl($detail_nilai->nilai_psikomotorik) == "") { ?>
		<td><div id="elh_detail_nilai_nilai_psikomotorik" class="detail_nilai_nilai_psikomotorik"><div class="ewTableHeaderCaption"><?php echo $detail_nilai->nilai_psikomotorik->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_detail_nilai_nilai_psikomotorik" class="detail_nilai_nilai_psikomotorik">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detail_nilai->nilai_psikomotorik->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detail_nilai->nilai_psikomotorik->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detail_nilai->nilai_psikomotorik->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($detail_nilai->nilai_rata_rata->Visible) { // nilai_rata_rata ?>
	<?php if ($detail_nilai->SortUrl($detail_nilai->nilai_rata_rata) == "") { ?>
		<td><div id="elh_detail_nilai_nilai_rata_rata" class="detail_nilai_nilai_rata_rata"><div class="ewTableHeaderCaption"><?php echo $detail_nilai->nilai_rata_rata->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_detail_nilai_nilai_rata_rata" class="detail_nilai_nilai_rata_rata">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detail_nilai->nilai_rata_rata->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detail_nilai->nilai_rata_rata->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detail_nilai->nilai_rata_rata->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$detail_nilai_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$detail_nilai_grid->StartRec = 1;
$detail_nilai_grid->StopRec = $detail_nilai_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($detail_nilai_grid->FormKeyCountName) && ($detail_nilai->CurrentAction == "gridadd" || $detail_nilai->CurrentAction == "gridedit" || $detail_nilai->CurrentAction == "F")) {
		$detail_nilai_grid->KeyCount = $objForm->GetValue($detail_nilai_grid->FormKeyCountName);
		$detail_nilai_grid->StopRec = $detail_nilai_grid->StartRec + $detail_nilai_grid->KeyCount - 1;
	}
}
$detail_nilai_grid->RecCnt = $detail_nilai_grid->StartRec - 1;
if ($detail_nilai_grid->Recordset && !$detail_nilai_grid->Recordset->EOF) {
	$detail_nilai_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $detail_nilai_grid->StartRec > 1)
		$detail_nilai_grid->Recordset->Move($detail_nilai_grid->StartRec - 1);
} elseif (!$detail_nilai->AllowAddDeleteRow && $detail_nilai_grid->StopRec == 0) {
	$detail_nilai_grid->StopRec = $detail_nilai->GridAddRowCount;
}

// Initialize aggregate
$detail_nilai->RowType = EW_ROWTYPE_AGGREGATEINIT;
$detail_nilai->ResetAttrs();
$detail_nilai_grid->RenderRow();
if ($detail_nilai->CurrentAction == "gridadd")
	$detail_nilai_grid->RowIndex = 0;
if ($detail_nilai->CurrentAction == "gridedit")
	$detail_nilai_grid->RowIndex = 0;
while ($detail_nilai_grid->RecCnt < $detail_nilai_grid->StopRec) {
	$detail_nilai_grid->RecCnt++;
	if (intval($detail_nilai_grid->RecCnt) >= intval($detail_nilai_grid->StartRec)) {
		$detail_nilai_grid->RowCnt++;
		if ($detail_nilai->CurrentAction == "gridadd" || $detail_nilai->CurrentAction == "gridedit" || $detail_nilai->CurrentAction == "F") {
			$detail_nilai_grid->RowIndex++;
			$objForm->Index = $detail_nilai_grid->RowIndex;
			if ($objForm->HasValue($detail_nilai_grid->FormActionName))
				$detail_nilai_grid->RowAction = strval($objForm->GetValue($detail_nilai_grid->FormActionName));
			elseif ($detail_nilai->CurrentAction == "gridadd")
				$detail_nilai_grid->RowAction = "insert";
			else
				$detail_nilai_grid->RowAction = "";
		}

		// Set up key count
		$detail_nilai_grid->KeyCount = $detail_nilai_grid->RowIndex;

		// Init row class and style
		$detail_nilai->ResetAttrs();
		$detail_nilai->CssClass = "";
		if ($detail_nilai->CurrentAction == "gridadd") {
			if ($detail_nilai->CurrentMode == "copy") {
				$detail_nilai_grid->LoadRowValues($detail_nilai_grid->Recordset); // Load row values
				$detail_nilai_grid->SetRecordKey($detail_nilai_grid->RowOldKey, $detail_nilai_grid->Recordset); // Set old record key
			} else {
				$detail_nilai_grid->LoadDefaultValues(); // Load default values
				$detail_nilai_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$detail_nilai_grid->LoadRowValues($detail_nilai_grid->Recordset); // Load row values
		}
		$detail_nilai->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($detail_nilai->CurrentAction == "gridadd") // Grid add
			$detail_nilai->RowType = EW_ROWTYPE_ADD; // Render add
		if ($detail_nilai->CurrentAction == "gridadd" && $detail_nilai->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$detail_nilai_grid->RestoreCurrentRowFormValues($detail_nilai_grid->RowIndex); // Restore form values
		if ($detail_nilai->CurrentAction == "gridedit") { // Grid edit
			if ($detail_nilai->EventCancelled) {
				$detail_nilai_grid->RestoreCurrentRowFormValues($detail_nilai_grid->RowIndex); // Restore form values
			}
			if ($detail_nilai_grid->RowAction == "insert")
				$detail_nilai->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$detail_nilai->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($detail_nilai->CurrentAction == "gridedit" && ($detail_nilai->RowType == EW_ROWTYPE_EDIT || $detail_nilai->RowType == EW_ROWTYPE_ADD) && $detail_nilai->EventCancelled) // Update failed
			$detail_nilai_grid->RestoreCurrentRowFormValues($detail_nilai_grid->RowIndex); // Restore form values
		if ($detail_nilai->RowType == EW_ROWTYPE_EDIT) // Edit row
			$detail_nilai_grid->EditRowCnt++;
		if ($detail_nilai->CurrentAction == "F") // Confirm row
			$detail_nilai_grid->RestoreCurrentRowFormValues($detail_nilai_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$detail_nilai->RowAttrs = array_merge($detail_nilai->RowAttrs, array('data-rowindex'=>$detail_nilai_grid->RowCnt, 'id'=>'r' . $detail_nilai_grid->RowCnt . '_detail_nilai', 'data-rowtype'=>$detail_nilai->RowType));

		// Render row
		$detail_nilai_grid->RenderRow();

		// Render list options
		$detail_nilai_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($detail_nilai_grid->RowAction <> "delete" && $detail_nilai_grid->RowAction <> "insertdelete" && !($detail_nilai_grid->RowAction == "insert" && $detail_nilai->CurrentAction == "F" && $detail_nilai_grid->EmptyRow())) {
?>
	<tr<?php echo $detail_nilai->RowAttributes() ?>>
<?php

// Render list options (body, left)
$detail_nilai_grid->ListOptions->Render("body", "left", $detail_nilai_grid->RowCnt);
?>
	<?php if ($detail_nilai->id_mapel->Visible) { // id_mapel ?>
		<td<?php echo $detail_nilai->id_mapel->CellAttributes() ?>>
<?php if ($detail_nilai->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detail_nilai_grid->RowCnt ?>_detail_nilai_id_mapel" class="control-group detail_nilai_id_mapel">
<select data-field="x_id_mapel" id="x<?php echo $detail_nilai_grid->RowIndex ?>_id_mapel" name="x<?php echo $detail_nilai_grid->RowIndex ?>_id_mapel"<?php echo $detail_nilai->id_mapel->EditAttributes() ?>>
<?php
if (is_array($detail_nilai->id_mapel->EditValue)) {
	$arwrk = $detail_nilai->id_mapel->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($detail_nilai->id_mapel->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $detail_nilai->id_mapel->OldValue = "";
?>
</select>
<script type="text/javascript">
fdetail_nilaigrid.Lists["x_id_mapel"].Options = <?php echo (is_array($detail_nilai->id_mapel->EditValue)) ? ew_ArrayToJson($detail_nilai->id_mapel->EditValue, 1) : "[]" ?>;
</script>
</span>
<input type="hidden" data-field="x_id_mapel" name="o<?php echo $detail_nilai_grid->RowIndex ?>_id_mapel" id="o<?php echo $detail_nilai_grid->RowIndex ?>_id_mapel" value="<?php echo ew_HtmlEncode($detail_nilai->id_mapel->OldValue) ?>">
<?php } ?>
<?php if ($detail_nilai->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detail_nilai_grid->RowCnt ?>_detail_nilai_id_mapel" class="control-group detail_nilai_id_mapel">
<select data-field="x_id_mapel" id="x<?php echo $detail_nilai_grid->RowIndex ?>_id_mapel" name="x<?php echo $detail_nilai_grid->RowIndex ?>_id_mapel"<?php echo $detail_nilai->id_mapel->EditAttributes() ?>>
<?php
if (is_array($detail_nilai->id_mapel->EditValue)) {
	$arwrk = $detail_nilai->id_mapel->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($detail_nilai->id_mapel->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $detail_nilai->id_mapel->OldValue = "";
?>
</select>
<script type="text/javascript">
fdetail_nilaigrid.Lists["x_id_mapel"].Options = <?php echo (is_array($detail_nilai->id_mapel->EditValue)) ? ew_ArrayToJson($detail_nilai->id_mapel->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } ?>
<?php if ($detail_nilai->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $detail_nilai->id_mapel->ViewAttributes() ?>>
<?php echo $detail_nilai->id_mapel->ListViewValue() ?></span>
<input type="hidden" data-field="x_id_mapel" name="x<?php echo $detail_nilai_grid->RowIndex ?>_id_mapel" id="x<?php echo $detail_nilai_grid->RowIndex ?>_id_mapel" value="<?php echo ew_HtmlEncode($detail_nilai->id_mapel->FormValue) ?>">
<input type="hidden" data-field="x_id_mapel" name="o<?php echo $detail_nilai_grid->RowIndex ?>_id_mapel" id="o<?php echo $detail_nilai_grid->RowIndex ?>_id_mapel" value="<?php echo ew_HtmlEncode($detail_nilai->id_mapel->OldValue) ?>">
<?php } ?>
<a id="<?php echo $detail_nilai_grid->PageObjName . "_row_" . $detail_nilai_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($detail_nilai->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_id_detail_nilai" name="x<?php echo $detail_nilai_grid->RowIndex ?>_id_detail_nilai" id="x<?php echo $detail_nilai_grid->RowIndex ?>_id_detail_nilai" value="<?php echo ew_HtmlEncode($detail_nilai->id_detail_nilai->CurrentValue) ?>">
<input type="hidden" data-field="x_id_detail_nilai" name="o<?php echo $detail_nilai_grid->RowIndex ?>_id_detail_nilai" id="o<?php echo $detail_nilai_grid->RowIndex ?>_id_detail_nilai" value="<?php echo ew_HtmlEncode($detail_nilai->id_detail_nilai->OldValue) ?>">
<?php } ?>
<?php if ($detail_nilai->RowType == EW_ROWTYPE_EDIT || $detail_nilai->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_id_detail_nilai" name="x<?php echo $detail_nilai_grid->RowIndex ?>_id_detail_nilai" id="x<?php echo $detail_nilai_grid->RowIndex ?>_id_detail_nilai" value="<?php echo ew_HtmlEncode($detail_nilai->id_detail_nilai->CurrentValue) ?>">
<?php } ?>
	<?php if ($detail_nilai->nilai_afektif->Visible) { // nilai_afektif ?>
		<td<?php echo $detail_nilai->nilai_afektif->CellAttributes() ?>>
<?php if ($detail_nilai->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detail_nilai_grid->RowCnt ?>_detail_nilai_nilai_afektif" class="control-group detail_nilai_nilai_afektif">
<input type="text" data-field="x_nilai_afektif" name="x<?php echo $detail_nilai_grid->RowIndex ?>_nilai_afektif" id="x<?php echo $detail_nilai_grid->RowIndex ?>_nilai_afektif" size="30" placeholder="<?php echo ew_HtmlEncode($detail_nilai->nilai_afektif->PlaceHolder) ?>" value="<?php echo $detail_nilai->nilai_afektif->EditValue ?>"<?php echo $detail_nilai->nilai_afektif->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_nilai_afektif" name="o<?php echo $detail_nilai_grid->RowIndex ?>_nilai_afektif" id="o<?php echo $detail_nilai_grid->RowIndex ?>_nilai_afektif" value="<?php echo ew_HtmlEncode($detail_nilai->nilai_afektif->OldValue) ?>">
<?php } ?>
<?php if ($detail_nilai->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detail_nilai_grid->RowCnt ?>_detail_nilai_nilai_afektif" class="control-group detail_nilai_nilai_afektif">
<input type="text" data-field="x_nilai_afektif" name="x<?php echo $detail_nilai_grid->RowIndex ?>_nilai_afektif" id="x<?php echo $detail_nilai_grid->RowIndex ?>_nilai_afektif" size="30" placeholder="<?php echo ew_HtmlEncode($detail_nilai->nilai_afektif->PlaceHolder) ?>" value="<?php echo $detail_nilai->nilai_afektif->EditValue ?>"<?php echo $detail_nilai->nilai_afektif->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($detail_nilai->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $detail_nilai->nilai_afektif->ViewAttributes() ?>>
<?php echo $detail_nilai->nilai_afektif->ListViewValue() ?></span>
<input type="hidden" data-field="x_nilai_afektif" name="x<?php echo $detail_nilai_grid->RowIndex ?>_nilai_afektif" id="x<?php echo $detail_nilai_grid->RowIndex ?>_nilai_afektif" value="<?php echo ew_HtmlEncode($detail_nilai->nilai_afektif->FormValue) ?>">
<input type="hidden" data-field="x_nilai_afektif" name="o<?php echo $detail_nilai_grid->RowIndex ?>_nilai_afektif" id="o<?php echo $detail_nilai_grid->RowIndex ?>_nilai_afektif" value="<?php echo ew_HtmlEncode($detail_nilai->nilai_afektif->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detail_nilai->nilai_komulatif->Visible) { // nilai_komulatif ?>
		<td<?php echo $detail_nilai->nilai_komulatif->CellAttributes() ?>>
<?php if ($detail_nilai->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detail_nilai_grid->RowCnt ?>_detail_nilai_nilai_komulatif" class="control-group detail_nilai_nilai_komulatif">
<input type="text" data-field="x_nilai_komulatif" name="x<?php echo $detail_nilai_grid->RowIndex ?>_nilai_komulatif" id="x<?php echo $detail_nilai_grid->RowIndex ?>_nilai_komulatif" size="30" placeholder="<?php echo ew_HtmlEncode($detail_nilai->nilai_komulatif->PlaceHolder) ?>" value="<?php echo $detail_nilai->nilai_komulatif->EditValue ?>"<?php echo $detail_nilai->nilai_komulatif->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_nilai_komulatif" name="o<?php echo $detail_nilai_grid->RowIndex ?>_nilai_komulatif" id="o<?php echo $detail_nilai_grid->RowIndex ?>_nilai_komulatif" value="<?php echo ew_HtmlEncode($detail_nilai->nilai_komulatif->OldValue) ?>">
<?php } ?>
<?php if ($detail_nilai->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detail_nilai_grid->RowCnt ?>_detail_nilai_nilai_komulatif" class="control-group detail_nilai_nilai_komulatif">
<input type="text" data-field="x_nilai_komulatif" name="x<?php echo $detail_nilai_grid->RowIndex ?>_nilai_komulatif" id="x<?php echo $detail_nilai_grid->RowIndex ?>_nilai_komulatif" size="30" placeholder="<?php echo ew_HtmlEncode($detail_nilai->nilai_komulatif->PlaceHolder) ?>" value="<?php echo $detail_nilai->nilai_komulatif->EditValue ?>"<?php echo $detail_nilai->nilai_komulatif->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($detail_nilai->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $detail_nilai->nilai_komulatif->ViewAttributes() ?>>
<?php echo $detail_nilai->nilai_komulatif->ListViewValue() ?></span>
<input type="hidden" data-field="x_nilai_komulatif" name="x<?php echo $detail_nilai_grid->RowIndex ?>_nilai_komulatif" id="x<?php echo $detail_nilai_grid->RowIndex ?>_nilai_komulatif" value="<?php echo ew_HtmlEncode($detail_nilai->nilai_komulatif->FormValue) ?>">
<input type="hidden" data-field="x_nilai_komulatif" name="o<?php echo $detail_nilai_grid->RowIndex ?>_nilai_komulatif" id="o<?php echo $detail_nilai_grid->RowIndex ?>_nilai_komulatif" value="<?php echo ew_HtmlEncode($detail_nilai->nilai_komulatif->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detail_nilai->nilai_psikomotorik->Visible) { // nilai_psikomotorik ?>
		<td<?php echo $detail_nilai->nilai_psikomotorik->CellAttributes() ?>>
<?php if ($detail_nilai->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detail_nilai_grid->RowCnt ?>_detail_nilai_nilai_psikomotorik" class="control-group detail_nilai_nilai_psikomotorik">
<input type="text" data-field="x_nilai_psikomotorik" name="x<?php echo $detail_nilai_grid->RowIndex ?>_nilai_psikomotorik" id="x<?php echo $detail_nilai_grid->RowIndex ?>_nilai_psikomotorik" size="30" placeholder="<?php echo ew_HtmlEncode($detail_nilai->nilai_psikomotorik->PlaceHolder) ?>" value="<?php echo $detail_nilai->nilai_psikomotorik->EditValue ?>"<?php echo $detail_nilai->nilai_psikomotorik->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_nilai_psikomotorik" name="o<?php echo $detail_nilai_grid->RowIndex ?>_nilai_psikomotorik" id="o<?php echo $detail_nilai_grid->RowIndex ?>_nilai_psikomotorik" value="<?php echo ew_HtmlEncode($detail_nilai->nilai_psikomotorik->OldValue) ?>">
<?php } ?>
<?php if ($detail_nilai->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detail_nilai_grid->RowCnt ?>_detail_nilai_nilai_psikomotorik" class="control-group detail_nilai_nilai_psikomotorik">
<input type="text" data-field="x_nilai_psikomotorik" name="x<?php echo $detail_nilai_grid->RowIndex ?>_nilai_psikomotorik" id="x<?php echo $detail_nilai_grid->RowIndex ?>_nilai_psikomotorik" size="30" placeholder="<?php echo ew_HtmlEncode($detail_nilai->nilai_psikomotorik->PlaceHolder) ?>" value="<?php echo $detail_nilai->nilai_psikomotorik->EditValue ?>"<?php echo $detail_nilai->nilai_psikomotorik->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($detail_nilai->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $detail_nilai->nilai_psikomotorik->ViewAttributes() ?>>
<?php echo $detail_nilai->nilai_psikomotorik->ListViewValue() ?></span>
<input type="hidden" data-field="x_nilai_psikomotorik" name="x<?php echo $detail_nilai_grid->RowIndex ?>_nilai_psikomotorik" id="x<?php echo $detail_nilai_grid->RowIndex ?>_nilai_psikomotorik" value="<?php echo ew_HtmlEncode($detail_nilai->nilai_psikomotorik->FormValue) ?>">
<input type="hidden" data-field="x_nilai_psikomotorik" name="o<?php echo $detail_nilai_grid->RowIndex ?>_nilai_psikomotorik" id="o<?php echo $detail_nilai_grid->RowIndex ?>_nilai_psikomotorik" value="<?php echo ew_HtmlEncode($detail_nilai->nilai_psikomotorik->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detail_nilai->nilai_rata_rata->Visible) { // nilai_rata_rata ?>
		<td<?php echo $detail_nilai->nilai_rata_rata->CellAttributes() ?>>
<?php if ($detail_nilai->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detail_nilai_grid->RowCnt ?>_detail_nilai_nilai_rata_rata" class="control-group detail_nilai_nilai_rata_rata">
<input type="text" data-field="x_nilai_rata_rata" name="x<?php echo $detail_nilai_grid->RowIndex ?>_nilai_rata_rata" id="x<?php echo $detail_nilai_grid->RowIndex ?>_nilai_rata_rata" size="30" placeholder="<?php echo ew_HtmlEncode($detail_nilai->nilai_rata_rata->PlaceHolder) ?>" value="<?php echo $detail_nilai->nilai_rata_rata->EditValue ?>"<?php echo $detail_nilai->nilai_rata_rata->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_nilai_rata_rata" name="o<?php echo $detail_nilai_grid->RowIndex ?>_nilai_rata_rata" id="o<?php echo $detail_nilai_grid->RowIndex ?>_nilai_rata_rata" value="<?php echo ew_HtmlEncode($detail_nilai->nilai_rata_rata->OldValue) ?>">
<?php } ?>
<?php if ($detail_nilai->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detail_nilai_grid->RowCnt ?>_detail_nilai_nilai_rata_rata" class="control-group detail_nilai_nilai_rata_rata">
<input type="text" data-field="x_nilai_rata_rata" name="x<?php echo $detail_nilai_grid->RowIndex ?>_nilai_rata_rata" id="x<?php echo $detail_nilai_grid->RowIndex ?>_nilai_rata_rata" size="30" placeholder="<?php echo ew_HtmlEncode($detail_nilai->nilai_rata_rata->PlaceHolder) ?>" value="<?php echo $detail_nilai->nilai_rata_rata->EditValue ?>"<?php echo $detail_nilai->nilai_rata_rata->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($detail_nilai->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $detail_nilai->nilai_rata_rata->ViewAttributes() ?>>
<?php echo $detail_nilai->nilai_rata_rata->ListViewValue() ?></span>
<input type="hidden" data-field="x_nilai_rata_rata" name="x<?php echo $detail_nilai_grid->RowIndex ?>_nilai_rata_rata" id="x<?php echo $detail_nilai_grid->RowIndex ?>_nilai_rata_rata" value="<?php echo ew_HtmlEncode($detail_nilai->nilai_rata_rata->FormValue) ?>">
<input type="hidden" data-field="x_nilai_rata_rata" name="o<?php echo $detail_nilai_grid->RowIndex ?>_nilai_rata_rata" id="o<?php echo $detail_nilai_grid->RowIndex ?>_nilai_rata_rata" value="<?php echo ew_HtmlEncode($detail_nilai->nilai_rata_rata->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$detail_nilai_grid->ListOptions->Render("body", "right", $detail_nilai_grid->RowCnt);
?>
	</tr>
<?php if ($detail_nilai->RowType == EW_ROWTYPE_ADD || $detail_nilai->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fdetail_nilaigrid.UpdateOpts(<?php echo $detail_nilai_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($detail_nilai->CurrentAction <> "gridadd" || $detail_nilai->CurrentMode == "copy")
		if (!$detail_nilai_grid->Recordset->EOF) $detail_nilai_grid->Recordset->MoveNext();
}
?>
<?php
	if ($detail_nilai->CurrentMode == "add" || $detail_nilai->CurrentMode == "copy" || $detail_nilai->CurrentMode == "edit") {
		$detail_nilai_grid->RowIndex = '$rowindex$';
		$detail_nilai_grid->LoadDefaultValues();

		// Set row properties
		$detail_nilai->ResetAttrs();
		$detail_nilai->RowAttrs = array_merge($detail_nilai->RowAttrs, array('data-rowindex'=>$detail_nilai_grid->RowIndex, 'id'=>'r0_detail_nilai', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($detail_nilai->RowAttrs["class"], "ewTemplate");
		$detail_nilai->RowType = EW_ROWTYPE_ADD;

		// Render row
		$detail_nilai_grid->RenderRow();

		// Render list options
		$detail_nilai_grid->RenderListOptions();
		$detail_nilai_grid->StartRowCnt = 0;
?>
	<tr<?php echo $detail_nilai->RowAttributes() ?>>
<?php

// Render list options (body, left)
$detail_nilai_grid->ListOptions->Render("body", "left", $detail_nilai_grid->RowIndex);
?>
	<?php if ($detail_nilai->id_mapel->Visible) { // id_mapel ?>
		<td>
<?php if ($detail_nilai->CurrentAction <> "F") { ?>
<span id="el$rowindex$_detail_nilai_id_mapel" class="control-group detail_nilai_id_mapel">
<select data-field="x_id_mapel" id="x<?php echo $detail_nilai_grid->RowIndex ?>_id_mapel" name="x<?php echo $detail_nilai_grid->RowIndex ?>_id_mapel"<?php echo $detail_nilai->id_mapel->EditAttributes() ?>>
<?php
if (is_array($detail_nilai->id_mapel->EditValue)) {
	$arwrk = $detail_nilai->id_mapel->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($detail_nilai->id_mapel->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $detail_nilai->id_mapel->OldValue = "";
?>
</select>
<script type="text/javascript">
fdetail_nilaigrid.Lists["x_id_mapel"].Options = <?php echo (is_array($detail_nilai->id_mapel->EditValue)) ? ew_ArrayToJson($detail_nilai->id_mapel->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } else { ?>
<span id="el$rowindex$_detail_nilai_id_mapel" class="control-group detail_nilai_id_mapel">
<span<?php echo $detail_nilai->id_mapel->ViewAttributes() ?>>
<?php echo $detail_nilai->id_mapel->ViewValue ?></span>
</span>
<input type="hidden" data-field="x_id_mapel" name="x<?php echo $detail_nilai_grid->RowIndex ?>_id_mapel" id="x<?php echo $detail_nilai_grid->RowIndex ?>_id_mapel" value="<?php echo ew_HtmlEncode($detail_nilai->id_mapel->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_id_mapel" name="o<?php echo $detail_nilai_grid->RowIndex ?>_id_mapel" id="o<?php echo $detail_nilai_grid->RowIndex ?>_id_mapel" value="<?php echo ew_HtmlEncode($detail_nilai->id_mapel->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detail_nilai->nilai_afektif->Visible) { // nilai_afektif ?>
		<td>
<?php if ($detail_nilai->CurrentAction <> "F") { ?>
<span id="el$rowindex$_detail_nilai_nilai_afektif" class="control-group detail_nilai_nilai_afektif">
<input type="text" data-field="x_nilai_afektif" name="x<?php echo $detail_nilai_grid->RowIndex ?>_nilai_afektif" id="x<?php echo $detail_nilai_grid->RowIndex ?>_nilai_afektif" size="30" placeholder="<?php echo ew_HtmlEncode($detail_nilai->nilai_afektif->PlaceHolder) ?>" value="<?php echo $detail_nilai->nilai_afektif->EditValue ?>"<?php echo $detail_nilai->nilai_afektif->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_detail_nilai_nilai_afektif" class="control-group detail_nilai_nilai_afektif">
<span<?php echo $detail_nilai->nilai_afektif->ViewAttributes() ?>>
<?php echo $detail_nilai->nilai_afektif->ViewValue ?></span>
</span>
<input type="hidden" data-field="x_nilai_afektif" name="x<?php echo $detail_nilai_grid->RowIndex ?>_nilai_afektif" id="x<?php echo $detail_nilai_grid->RowIndex ?>_nilai_afektif" value="<?php echo ew_HtmlEncode($detail_nilai->nilai_afektif->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nilai_afektif" name="o<?php echo $detail_nilai_grid->RowIndex ?>_nilai_afektif" id="o<?php echo $detail_nilai_grid->RowIndex ?>_nilai_afektif" value="<?php echo ew_HtmlEncode($detail_nilai->nilai_afektif->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detail_nilai->nilai_komulatif->Visible) { // nilai_komulatif ?>
		<td>
<?php if ($detail_nilai->CurrentAction <> "F") { ?>
<span id="el$rowindex$_detail_nilai_nilai_komulatif" class="control-group detail_nilai_nilai_komulatif">
<input type="text" data-field="x_nilai_komulatif" name="x<?php echo $detail_nilai_grid->RowIndex ?>_nilai_komulatif" id="x<?php echo $detail_nilai_grid->RowIndex ?>_nilai_komulatif" size="30" placeholder="<?php echo ew_HtmlEncode($detail_nilai->nilai_komulatif->PlaceHolder) ?>" value="<?php echo $detail_nilai->nilai_komulatif->EditValue ?>"<?php echo $detail_nilai->nilai_komulatif->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_detail_nilai_nilai_komulatif" class="control-group detail_nilai_nilai_komulatif">
<span<?php echo $detail_nilai->nilai_komulatif->ViewAttributes() ?>>
<?php echo $detail_nilai->nilai_komulatif->ViewValue ?></span>
</span>
<input type="hidden" data-field="x_nilai_komulatif" name="x<?php echo $detail_nilai_grid->RowIndex ?>_nilai_komulatif" id="x<?php echo $detail_nilai_grid->RowIndex ?>_nilai_komulatif" value="<?php echo ew_HtmlEncode($detail_nilai->nilai_komulatif->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nilai_komulatif" name="o<?php echo $detail_nilai_grid->RowIndex ?>_nilai_komulatif" id="o<?php echo $detail_nilai_grid->RowIndex ?>_nilai_komulatif" value="<?php echo ew_HtmlEncode($detail_nilai->nilai_komulatif->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detail_nilai->nilai_psikomotorik->Visible) { // nilai_psikomotorik ?>
		<td>
<?php if ($detail_nilai->CurrentAction <> "F") { ?>
<span id="el$rowindex$_detail_nilai_nilai_psikomotorik" class="control-group detail_nilai_nilai_psikomotorik">
<input type="text" data-field="x_nilai_psikomotorik" name="x<?php echo $detail_nilai_grid->RowIndex ?>_nilai_psikomotorik" id="x<?php echo $detail_nilai_grid->RowIndex ?>_nilai_psikomotorik" size="30" placeholder="<?php echo ew_HtmlEncode($detail_nilai->nilai_psikomotorik->PlaceHolder) ?>" value="<?php echo $detail_nilai->nilai_psikomotorik->EditValue ?>"<?php echo $detail_nilai->nilai_psikomotorik->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_detail_nilai_nilai_psikomotorik" class="control-group detail_nilai_nilai_psikomotorik">
<span<?php echo $detail_nilai->nilai_psikomotorik->ViewAttributes() ?>>
<?php echo $detail_nilai->nilai_psikomotorik->ViewValue ?></span>
</span>
<input type="hidden" data-field="x_nilai_psikomotorik" name="x<?php echo $detail_nilai_grid->RowIndex ?>_nilai_psikomotorik" id="x<?php echo $detail_nilai_grid->RowIndex ?>_nilai_psikomotorik" value="<?php echo ew_HtmlEncode($detail_nilai->nilai_psikomotorik->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nilai_psikomotorik" name="o<?php echo $detail_nilai_grid->RowIndex ?>_nilai_psikomotorik" id="o<?php echo $detail_nilai_grid->RowIndex ?>_nilai_psikomotorik" value="<?php echo ew_HtmlEncode($detail_nilai->nilai_psikomotorik->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detail_nilai->nilai_rata_rata->Visible) { // nilai_rata_rata ?>
		<td>
<?php if ($detail_nilai->CurrentAction <> "F") { ?>
<span id="el$rowindex$_detail_nilai_nilai_rata_rata" class="control-group detail_nilai_nilai_rata_rata">
<input type="text" data-field="x_nilai_rata_rata" name="x<?php echo $detail_nilai_grid->RowIndex ?>_nilai_rata_rata" id="x<?php echo $detail_nilai_grid->RowIndex ?>_nilai_rata_rata" size="30" placeholder="<?php echo ew_HtmlEncode($detail_nilai->nilai_rata_rata->PlaceHolder) ?>" value="<?php echo $detail_nilai->nilai_rata_rata->EditValue ?>"<?php echo $detail_nilai->nilai_rata_rata->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_detail_nilai_nilai_rata_rata" class="control-group detail_nilai_nilai_rata_rata">
<span<?php echo $detail_nilai->nilai_rata_rata->ViewAttributes() ?>>
<?php echo $detail_nilai->nilai_rata_rata->ViewValue ?></span>
</span>
<input type="hidden" data-field="x_nilai_rata_rata" name="x<?php echo $detail_nilai_grid->RowIndex ?>_nilai_rata_rata" id="x<?php echo $detail_nilai_grid->RowIndex ?>_nilai_rata_rata" value="<?php echo ew_HtmlEncode($detail_nilai->nilai_rata_rata->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nilai_rata_rata" name="o<?php echo $detail_nilai_grid->RowIndex ?>_nilai_rata_rata" id="o<?php echo $detail_nilai_grid->RowIndex ?>_nilai_rata_rata" value="<?php echo ew_HtmlEncode($detail_nilai->nilai_rata_rata->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$detail_nilai_grid->ListOptions->Render("body", "right", $detail_nilai_grid->RowCnt);
?>
<script type="text/javascript">
fdetail_nilaigrid.UpdateOpts(<?php echo $detail_nilai_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($detail_nilai->CurrentMode == "add" || $detail_nilai->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $detail_nilai_grid->FormKeyCountName ?>" id="<?php echo $detail_nilai_grid->FormKeyCountName ?>" value="<?php echo $detail_nilai_grid->KeyCount ?>">
<?php echo $detail_nilai_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($detail_nilai->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $detail_nilai_grid->FormKeyCountName ?>" id="<?php echo $detail_nilai_grid->FormKeyCountName ?>" value="<?php echo $detail_nilai_grid->KeyCount ?>">
<?php echo $detail_nilai_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($detail_nilai->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fdetail_nilaigrid">
</div>
<?php

// Close recordset
if ($detail_nilai_grid->Recordset)
	$detail_nilai_grid->Recordset->Close();
?>
<?php if ($detail_nilai_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel ewListOtherOptions">
<?php
	foreach ($detail_nilai_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<?php } ?>
</div>
</td></tr></table>
<?php if ($detail_nilai->Export == "") { ?>
<script type="text/javascript">
fdetail_nilaigrid.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$detail_nilai_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$detail_nilai_grid->Page_Terminate();
?>
