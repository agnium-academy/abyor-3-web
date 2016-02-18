<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "nilaiinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$nilai_delete = NULL; // Initialize page object first

class cnilai_delete extends cnilai {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{82E7E539-D0AE-473F-AA71-5A043814ED93}";

	// Table name
	var $TableName = 'nilai';

	// Page object name
	var $PageObjName = 'nilai_delete';

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

		// Table object (nilai)
		if (!isset($GLOBALS["nilai"]) || get_class($GLOBALS["nilai"]) == "cnilai") {
			$GLOBALS["nilai"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["nilai"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'nilai', TRUE);

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
			$this->Page_Terminate("nilailist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in nilai class, nilaiinfo.php

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
		$this->nilai_id->setDbValue($rs->fields('nilai_id'));
		$this->nis->setDbValue($rs->fields('nis'));
		$this->nip->setDbValue($rs->fields('nip'));
		$this->semester->setDbValue($rs->fields('semester'));
		$this->kelas->setDbValue($rs->fields('kelas'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nilai_id->DbValue = $row['nilai_id'];
		$this->nis->DbValue = $row['nis'];
		$this->nip->DbValue = $row['nip'];
		$this->semester->DbValue = $row['semester'];
		$this->kelas->DbValue = $row['kelas'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// nilai_id
		// nis
		// nip
		// semester
		// kelas

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nilai_id
			$this->nilai_id->ViewValue = $this->nilai_id->CurrentValue;
			$this->nilai_id->ViewCustomAttributes = "";

			// nis
			if (strval($this->nis->CurrentValue) <> "") {
				$sFilterWrk = "`nis`" . ew_SearchString("=", $this->nis->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT `nis`, `nama` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `siswa`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nis, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nis->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nis->ViewValue = $this->nis->CurrentValue;
				}
			} else {
				$this->nis->ViewValue = NULL;
			}
			$this->nis->ViewCustomAttributes = "";

			// nip
			if (strval($this->nip->CurrentValue) <> "") {
				$sFilterWrk = "`nip`" . ew_SearchString("=", $this->nip->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT `nip`, `nama` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `guru`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nip, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nip->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nip->ViewValue = $this->nip->CurrentValue;
				}
			} else {
				$this->nip->ViewValue = NULL;
			}
			$this->nip->ViewCustomAttributes = "";

			// semester
			$this->semester->ViewValue = $this->semester->CurrentValue;
			$this->semester->ViewCustomAttributes = "";

			// kelas
			$this->kelas->ViewValue = $this->kelas->CurrentValue;
			$this->kelas->ViewCustomAttributes = "";

			// nis
			$this->nis->LinkCustomAttributes = "";
			$this->nis->HrefValue = "";
			$this->nis->TooltipValue = "";

			// nip
			$this->nip->LinkCustomAttributes = "";
			$this->nip->HrefValue = "";
			$this->nip->TooltipValue = "";

			// semester
			$this->semester->LinkCustomAttributes = "";
			$this->semester->HrefValue = "";
			$this->semester->TooltipValue = "";

			// kelas
			$this->kelas->LinkCustomAttributes = "";
			$this->kelas->HrefValue = "";
			$this->kelas->TooltipValue = "";
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
				$sThisKey .= $row['nilai_id'];
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
		$Breadcrumb->Add("list", $this->TableVar, "nilailist.php", $this->TableVar, TRUE);
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
if (!isset($nilai_delete)) $nilai_delete = new cnilai_delete();

// Page init
$nilai_delete->Page_Init();

// Page main
$nilai_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$nilai_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var nilai_delete = new ew_Page("nilai_delete");
nilai_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = nilai_delete.PageID; // For backward compatibility

// Form object
var fnilaidelete = new ew_Form("fnilaidelete");

// Form_CustomValidate event
fnilaidelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fnilaidelete.ValidateRequired = true;
<?php } else { ?>
fnilaidelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fnilaidelete.Lists["x_nis"] = {"LinkField":"x_nis","Ajax":null,"AutoFill":false,"DisplayFields":["x_nama","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fnilaidelete.Lists["x_nip"] = {"LinkField":"x_nip","Ajax":null,"AutoFill":false,"DisplayFields":["x_nama","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($nilai_delete->Recordset = $nilai_delete->LoadRecordset())
	$nilai_deleteTotalRecs = $nilai_delete->Recordset->RecordCount(); // Get record count
if ($nilai_deleteTotalRecs <= 0) { // No record found, exit
	if ($nilai_delete->Recordset)
		$nilai_delete->Recordset->Close();
	$nilai_delete->Page_Terminate("nilailist.php"); // Return to list
}
?>
<?php $Breadcrumb->Render(); ?>
<?php $nilai_delete->ShowPageHeader(); ?>
<?php
$nilai_delete->ShowMessage();
?>
<form name="fnilaidelete" id="fnilaidelete" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="nilai">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($nilai_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_nilaidelete" class="ewTable ewTableSeparate">
<?php echo $nilai->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($nilai->nis->Visible) { // nis ?>
		<td><span id="elh_nilai_nis" class="nilai_nis"><?php echo $nilai->nis->FldCaption() ?></span></td>
<?php } ?>
<?php if ($nilai->nip->Visible) { // nip ?>
		<td><span id="elh_nilai_nip" class="nilai_nip"><?php echo $nilai->nip->FldCaption() ?></span></td>
<?php } ?>
<?php if ($nilai->semester->Visible) { // semester ?>
		<td><span id="elh_nilai_semester" class="nilai_semester"><?php echo $nilai->semester->FldCaption() ?></span></td>
<?php } ?>
<?php if ($nilai->kelas->Visible) { // kelas ?>
		<td><span id="elh_nilai_kelas" class="nilai_kelas"><?php echo $nilai->kelas->FldCaption() ?></span></td>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$nilai_delete->RecCnt = 0;
$i = 0;
while (!$nilai_delete->Recordset->EOF) {
	$nilai_delete->RecCnt++;
	$nilai_delete->RowCnt++;

	// Set row properties
	$nilai->ResetAttrs();
	$nilai->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$nilai_delete->LoadRowValues($nilai_delete->Recordset);

	// Render row
	$nilai_delete->RenderRow();
?>
	<tr<?php echo $nilai->RowAttributes() ?>>
<?php if ($nilai->nis->Visible) { // nis ?>
		<td<?php echo $nilai->nis->CellAttributes() ?>>
<span id="el<?php echo $nilai_delete->RowCnt ?>_nilai_nis" class="control-group nilai_nis">
<span<?php echo $nilai->nis->ViewAttributes() ?>>
<?php echo $nilai->nis->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($nilai->nip->Visible) { // nip ?>
		<td<?php echo $nilai->nip->CellAttributes() ?>>
<span id="el<?php echo $nilai_delete->RowCnt ?>_nilai_nip" class="control-group nilai_nip">
<span<?php echo $nilai->nip->ViewAttributes() ?>>
<?php echo $nilai->nip->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($nilai->semester->Visible) { // semester ?>
		<td<?php echo $nilai->semester->CellAttributes() ?>>
<span id="el<?php echo $nilai_delete->RowCnt ?>_nilai_semester" class="control-group nilai_semester">
<span<?php echo $nilai->semester->ViewAttributes() ?>>
<?php echo $nilai->semester->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($nilai->kelas->Visible) { // kelas ?>
		<td<?php echo $nilai->kelas->CellAttributes() ?>>
<span id="el<?php echo $nilai_delete->RowCnt ?>_nilai_kelas" class="control-group nilai_kelas">
<span<?php echo $nilai->kelas->ViewAttributes() ?>>
<?php echo $nilai->kelas->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$nilai_delete->Recordset->MoveNext();
}
$nilai_delete->Recordset->Close();
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
fnilaidelete.Init();
</script>
<?php
$nilai_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$nilai_delete->Page_Terminate();
?>
