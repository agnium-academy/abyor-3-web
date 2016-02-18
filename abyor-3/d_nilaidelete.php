<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "d_nilaiinfo.php" ?>
<?php include_once "nilaiinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$d_nilai_delete = NULL; // Initialize page object first

class cd_nilai_delete extends cd_nilai {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{82E7E539-D0AE-473F-AA71-5A043814ED93}";

	// Table name
	var $TableName = 'd_nilai';

	// Page object name
	var $PageObjName = 'd_nilai_delete';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-error ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<table class=\"ewStdTable\"><tr><td><div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div></td></tr></table>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		$GLOBALS["Page"] = &$this;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (d_nilai)
		if (!isset($GLOBALS["d_nilai"]) || get_class($GLOBALS["d_nilai"]) == "cd_nilai") {
			$GLOBALS["d_nilai"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["d_nilai"];
		}

		// Table object (nilai)
		if (!isset($GLOBALS['nilai'])) $GLOBALS['nilai'] = new cnilai();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'd_nilai', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if (!$Security->IsLoggedIn()) {
			$Security->SaveLastUrl();
			$this->Page_Terminate("login.php");
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $conn;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();
		$this->Page_Redirecting($url);

		 // Close connection
		$conn->Close();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("d_nilailist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in d_nilai class, d_nilaiinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		switch ($this->CurrentAction) {
			case "D": // Delete
				$this->SendEmail = TRUE; // Send email on delete success
				if ($this->DeleteRows()) { // Delete rows
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
					$this->Page_Terminate($this->getReturnUrl()); // Return to caller
				}
		}
	}

// No functions
	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {
		global $conn;

		// Call Recordset Selecting event
		$this->Recordset_Selecting($this->CurrentFilter);

		// Load List page SQL
		$sSql = $this->SelectSQL();
		if ($offset > -1 && $rowcnt > -1)
			$sSql .= " LIMIT $rowcnt OFFSET $offset";

		// Load recordset
		$rs = ew_LoadRecordset($sSql);

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
	}

	// Load row based on key values
	function LoadRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		global $conn;
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->detail_nilai_id->setDbValue($rs->fields('detail_nilai_id'));
		$this->id_mapel->setDbValue($rs->fields('id_mapel'));
		$this->nilai_afektif->setDbValue($rs->fields('nilai_afektif'));
		$this->nilai_komulatif->setDbValue($rs->fields('nilai_komulatif'));
		$this->nilai_psikomotorik->setDbValue($rs->fields('nilai_psikomotorik'));
		$this->nilai_rata2Drata->setDbValue($rs->fields('nilai_rata-rata'));
		$this->nilai_id->setDbValue($rs->fields('nilai_id'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->detail_nilai_id->DbValue = $row['detail_nilai_id'];
		$this->id_mapel->DbValue = $row['id_mapel'];
		$this->nilai_afektif->DbValue = $row['nilai_afektif'];
		$this->nilai_komulatif->DbValue = $row['nilai_komulatif'];
		$this->nilai_psikomotorik->DbValue = $row['nilai_psikomotorik'];
		$this->nilai_rata2Drata->DbValue = $row['nilai_rata-rata'];
		$this->nilai_id->DbValue = $row['nilai_id'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// detail_nilai_id
		// id_mapel
		// nilai_afektif
		// nilai_komulatif
		// nilai_psikomotorik
		// nilai_rata-rata
		// nilai_id

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// detail_nilai_id
			$this->detail_nilai_id->ViewValue = $this->detail_nilai_id->CurrentValue;
			$this->detail_nilai_id->ViewCustomAttributes = "";

			// id_mapel
			if (strval($this->id_mapel->CurrentValue) <> "") {
				$sFilterWrk = "`id_mapel`" . ew_SearchString("=", $this->id_mapel->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `id_mapel`, `namaMapel` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `mapel`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_mapel, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_mapel->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->id_mapel->ViewValue = $this->id_mapel->CurrentValue;
				}
			} else {
				$this->id_mapel->ViewValue = NULL;
			}
			$this->id_mapel->ViewCustomAttributes = "";

			// nilai_afektif
			$this->nilai_afektif->ViewValue = $this->nilai_afektif->CurrentValue;
			$this->nilai_afektif->ViewCustomAttributes = "";

			// nilai_komulatif
			$this->nilai_komulatif->ViewValue = $this->nilai_komulatif->CurrentValue;
			$this->nilai_komulatif->ViewCustomAttributes = "";

			// nilai_psikomotorik
			$this->nilai_psikomotorik->ViewValue = $this->nilai_psikomotorik->CurrentValue;
			$this->nilai_psikomotorik->ViewCustomAttributes = "";

			// nilai_rata-rata
			$this->nilai_rata2Drata->ViewValue = $this->nilai_rata2Drata->CurrentValue;
			$this->nilai_rata2Drata->ViewCustomAttributes = "";

			// nilai_id
			if (strval($this->nilai_id->CurrentValue) <> "") {
				$sFilterWrk = "`nilai_id`" . ew_SearchString("=", $this->nilai_id->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `nilai_id`, `nilai_id` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `nilai`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nilai_id, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nilai_id->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nilai_id->ViewValue = $this->nilai_id->CurrentValue;
				}
			} else {
				$this->nilai_id->ViewValue = NULL;
			}
			$this->nilai_id->ViewCustomAttributes = "";

			// id_mapel
			$this->id_mapel->LinkCustomAttributes = "";
			$this->id_mapel->HrefValue = "";
			$this->id_mapel->TooltipValue = "";

			// nilai_afektif
			$this->nilai_afektif->LinkCustomAttributes = "";
			$this->nilai_afektif->HrefValue = "";
			$this->nilai_afektif->TooltipValue = "";

			// nilai_komulatif
			$this->nilai_komulatif->LinkCustomAttributes = "";
			$this->nilai_komulatif->HrefValue = "";
			$this->nilai_komulatif->TooltipValue = "";

			// nilai_psikomotorik
			$this->nilai_psikomotorik->LinkCustomAttributes = "";
			$this->nilai_psikomotorik->HrefValue = "";
			$this->nilai_psikomotorik->TooltipValue = "";

			// nilai_rata-rata
			$this->nilai_rata2Drata->LinkCustomAttributes = "";
			$this->nilai_rata2Drata->HrefValue = "";
			$this->nilai_rata2Drata->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $conn, $Language, $Security;
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$conn->BeginTrans();

		// Clone old rows
		$rsold = ($rs) ? $rs->GetRows() : array();
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['detail_nilai_id'];
				$conn->raiseErrorFn = 'ew_ErrorFn';
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("list", $this->TableVar, "d_nilailist.php", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, ew_CurrentUrl());
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($d_nilai_delete)) $d_nilai_delete = new cd_nilai_delete();

// Page init
$d_nilai_delete->Page_Init();

// Page main
$d_nilai_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$d_nilai_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var d_nilai_delete = new ew_Page("d_nilai_delete");
d_nilai_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = d_nilai_delete.PageID; // For backward compatibility

// Form object
var fd_nilaidelete = new ew_Form("fd_nilaidelete");

// Form_CustomValidate event
fd_nilaidelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fd_nilaidelete.ValidateRequired = true;
<?php } else { ?>
fd_nilaidelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fd_nilaidelete.Lists["x_id_mapel"] = {"LinkField":"x_id_mapel","Ajax":null,"AutoFill":false,"DisplayFields":["x_namaMapel","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($d_nilai_delete->Recordset = $d_nilai_delete->LoadRecordset())
	$d_nilai_deleteTotalRecs = $d_nilai_delete->Recordset->RecordCount(); // Get record count
if ($d_nilai_deleteTotalRecs <= 0) { // No record found, exit
	if ($d_nilai_delete->Recordset)
		$d_nilai_delete->Recordset->Close();
	$d_nilai_delete->Page_Terminate("d_nilailist.php"); // Return to list
}
?>
<?php $Breadcrumb->Render(); ?>
<?php $d_nilai_delete->ShowPageHeader(); ?>
<?php
$d_nilai_delete->ShowMessage();
?>
<form name="fd_nilaidelete" id="fd_nilaidelete" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="d_nilai">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($d_nilai_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_d_nilaidelete" class="ewTable ewTableSeparate">
<?php echo $d_nilai->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($d_nilai->id_mapel->Visible) { // id_mapel ?>
		<td><span id="elh_d_nilai_id_mapel" class="d_nilai_id_mapel"><?php echo $d_nilai->id_mapel->FldCaption() ?></span></td>
<?php } ?>
<?php if ($d_nilai->nilai_afektif->Visible) { // nilai_afektif ?>
		<td><span id="elh_d_nilai_nilai_afektif" class="d_nilai_nilai_afektif"><?php echo $d_nilai->nilai_afektif->FldCaption() ?></span></td>
<?php } ?>
<?php if ($d_nilai->nilai_komulatif->Visible) { // nilai_komulatif ?>
		<td><span id="elh_d_nilai_nilai_komulatif" class="d_nilai_nilai_komulatif"><?php echo $d_nilai->nilai_komulatif->FldCaption() ?></span></td>
<?php } ?>
<?php if ($d_nilai->nilai_psikomotorik->Visible) { // nilai_psikomotorik ?>
		<td><span id="elh_d_nilai_nilai_psikomotorik" class="d_nilai_nilai_psikomotorik"><?php echo $d_nilai->nilai_psikomotorik->FldCaption() ?></span></td>
<?php } ?>
<?php if ($d_nilai->nilai_rata2Drata->Visible) { // nilai_rata-rata ?>
		<td><span id="elh_d_nilai_nilai_rata2Drata" class="d_nilai_nilai_rata2Drata"><?php echo $d_nilai->nilai_rata2Drata->FldCaption() ?></span></td>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$d_nilai_delete->RecCnt = 0;
$i = 0;
while (!$d_nilai_delete->Recordset->EOF) {
	$d_nilai_delete->RecCnt++;
	$d_nilai_delete->RowCnt++;

	// Set row properties
	$d_nilai->ResetAttrs();
	$d_nilai->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$d_nilai_delete->LoadRowValues($d_nilai_delete->Recordset);

	// Render row
	$d_nilai_delete->RenderRow();
?>
	<tr<?php echo $d_nilai->RowAttributes() ?>>
<?php if ($d_nilai->id_mapel->Visible) { // id_mapel ?>
		<td<?php echo $d_nilai->id_mapel->CellAttributes() ?>>
<span id="el<?php echo $d_nilai_delete->RowCnt ?>_d_nilai_id_mapel" class="control-group d_nilai_id_mapel">
<span<?php echo $d_nilai->id_mapel->ViewAttributes() ?>>
<?php echo $d_nilai->id_mapel->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($d_nilai->nilai_afektif->Visible) { // nilai_afektif ?>
		<td<?php echo $d_nilai->nilai_afektif->CellAttributes() ?>>
<span id="el<?php echo $d_nilai_delete->RowCnt ?>_d_nilai_nilai_afektif" class="control-group d_nilai_nilai_afektif">
<span<?php echo $d_nilai->nilai_afektif->ViewAttributes() ?>>
<?php echo $d_nilai->nilai_afektif->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($d_nilai->nilai_komulatif->Visible) { // nilai_komulatif ?>
		<td<?php echo $d_nilai->nilai_komulatif->CellAttributes() ?>>
<span id="el<?php echo $d_nilai_delete->RowCnt ?>_d_nilai_nilai_komulatif" class="control-group d_nilai_nilai_komulatif">
<span<?php echo $d_nilai->nilai_komulatif->ViewAttributes() ?>>
<?php echo $d_nilai->nilai_komulatif->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($d_nilai->nilai_psikomotorik->Visible) { // nilai_psikomotorik ?>
		<td<?php echo $d_nilai->nilai_psikomotorik->CellAttributes() ?>>
<span id="el<?php echo $d_nilai_delete->RowCnt ?>_d_nilai_nilai_psikomotorik" class="control-group d_nilai_nilai_psikomotorik">
<span<?php echo $d_nilai->nilai_psikomotorik->ViewAttributes() ?>>
<?php echo $d_nilai->nilai_psikomotorik->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($d_nilai->nilai_rata2Drata->Visible) { // nilai_rata-rata ?>
		<td<?php echo $d_nilai->nilai_rata2Drata->CellAttributes() ?>>
<span id="el<?php echo $d_nilai_delete->RowCnt ?>_d_nilai_nilai_rata2Drata" class="control-group d_nilai_nilai_rata2Drata">
<span<?php echo $d_nilai->nilai_rata2Drata->ViewAttributes() ?>>
<?php echo $d_nilai->nilai_rata2Drata->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$d_nilai_delete->Recordset->MoveNext();
}
$d_nilai_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</td></tr></table>
<div class="btn-group ewButtonGroup">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fd_nilaidelete.Init();
</script>
<?php
$d_nilai_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$d_nilai_delete->Page_Terminate();
?>
