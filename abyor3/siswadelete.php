<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "siswainfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$siswa_delete = NULL; // Initialize page object first

class csiswa_delete extends csiswa {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{207FBEFE-7CD7-4EAA-9B3B-698CCFB88A2B}";

	// Table name
	var $TableName = 'siswa';

	// Page object name
	var $PageObjName = 'siswa_delete';

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

		// Table object (siswa)
		if (!isset($GLOBALS["siswa"]) || get_class($GLOBALS["siswa"]) == "csiswa") {
			$GLOBALS["siswa"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["siswa"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'siswa', TRUE);

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
			$this->Page_Terminate("siswalist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in siswa class, siswainfo.php

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
		$this->id_siswa->setDbValue($rs->fields('id_siswa'));
		$this->nis->setDbValue($rs->fields('nis'));
		$this->nama->setDbValue($rs->fields('nama'));
		$this->tempat_lahir->setDbValue($rs->fields('tempat_lahir'));
		$this->tanggal_lahir->setDbValue($rs->fields('tanggal_lahir'));
		$this->alamat->setDbValue($rs->fields('alamat'));
		$this->agama->setDbValue($rs->fields('agama'));
		$this->no_hp->setDbValue($rs->fields('no_hp'));
		$this->_email->setDbValue($rs->fields('email'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id_siswa->DbValue = $row['id_siswa'];
		$this->nis->DbValue = $row['nis'];
		$this->nama->DbValue = $row['nama'];
		$this->tempat_lahir->DbValue = $row['tempat_lahir'];
		$this->tanggal_lahir->DbValue = $row['tanggal_lahir'];
		$this->alamat->DbValue = $row['alamat'];
		$this->agama->DbValue = $row['agama'];
		$this->no_hp->DbValue = $row['no_hp'];
		$this->_email->DbValue = $row['email'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id_siswa
		// nis
		// nama
		// tempat_lahir
		// tanggal_lahir
		// alamat
		// agama
		// no_hp
		// email

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id_siswa
			$this->id_siswa->ViewValue = $this->id_siswa->CurrentValue;
			$this->id_siswa->ViewCustomAttributes = "";

			// nis
			$this->nis->ViewValue = $this->nis->CurrentValue;
			$this->nis->ViewCustomAttributes = "";

			// nama
			$this->nama->ViewValue = $this->nama->CurrentValue;
			$this->nama->ViewCustomAttributes = "";

			// tempat_lahir
			$this->tempat_lahir->ViewValue = $this->tempat_lahir->CurrentValue;
			$this->tempat_lahir->ViewCustomAttributes = "";

			// tanggal_lahir
			$this->tanggal_lahir->ViewValue = $this->tanggal_lahir->CurrentValue;
			$this->tanggal_lahir->ViewValue = ew_FormatDateTime($this->tanggal_lahir->ViewValue, 5);
			$this->tanggal_lahir->ViewCustomAttributes = "";

			// alamat
			$this->alamat->ViewValue = $this->alamat->CurrentValue;
			$this->alamat->ViewCustomAttributes = "";

			// agama
			$this->agama->ViewValue = $this->agama->CurrentValue;
			$this->agama->ViewCustomAttributes = "";

			// no_hp
			$this->no_hp->ViewValue = $this->no_hp->CurrentValue;
			$this->no_hp->ViewCustomAttributes = "";

			// email
			$this->_email->ViewValue = $this->_email->CurrentValue;
			$this->_email->ViewCustomAttributes = "";

			// nis
			$this->nis->LinkCustomAttributes = "";
			$this->nis->HrefValue = "";
			$this->nis->TooltipValue = "";

			// nama
			$this->nama->LinkCustomAttributes = "";
			$this->nama->HrefValue = "";
			$this->nama->TooltipValue = "";

			// tempat_lahir
			$this->tempat_lahir->LinkCustomAttributes = "";
			$this->tempat_lahir->HrefValue = "";
			$this->tempat_lahir->TooltipValue = "";

			// tanggal_lahir
			$this->tanggal_lahir->LinkCustomAttributes = "";
			$this->tanggal_lahir->HrefValue = "";
			$this->tanggal_lahir->TooltipValue = "";

			// alamat
			$this->alamat->LinkCustomAttributes = "";
			$this->alamat->HrefValue = "";
			$this->alamat->TooltipValue = "";

			// agama
			$this->agama->LinkCustomAttributes = "";
			$this->agama->HrefValue = "";
			$this->agama->TooltipValue = "";

			// no_hp
			$this->no_hp->LinkCustomAttributes = "";
			$this->no_hp->HrefValue = "";
			$this->no_hp->TooltipValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";
			$this->_email->TooltipValue = "";
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
				$sThisKey .= $row['id_siswa'];
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
		$Breadcrumb->Add("list", $this->TableVar, "siswalist.php", $this->TableVar, TRUE);
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
if (!isset($siswa_delete)) $siswa_delete = new csiswa_delete();

// Page init
$siswa_delete->Page_Init();

// Page main
$siswa_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$siswa_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var siswa_delete = new ew_Page("siswa_delete");
siswa_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = siswa_delete.PageID; // For backward compatibility

// Form object
var fsiswadelete = new ew_Form("fsiswadelete");

// Form_CustomValidate event
fsiswadelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsiswadelete.ValidateRequired = true;
<?php } else { ?>
fsiswadelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($siswa_delete->Recordset = $siswa_delete->LoadRecordset())
	$siswa_deleteTotalRecs = $siswa_delete->Recordset->RecordCount(); // Get record count
if ($siswa_deleteTotalRecs <= 0) { // No record found, exit
	if ($siswa_delete->Recordset)
		$siswa_delete->Recordset->Close();
	$siswa_delete->Page_Terminate("siswalist.php"); // Return to list
}
?>
<?php $Breadcrumb->Render(); ?>
<?php $siswa_delete->ShowPageHeader(); ?>
<?php
$siswa_delete->ShowMessage();
?>
<form name="fsiswadelete" id="fsiswadelete" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="siswa">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($siswa_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_siswadelete" class="ewTable ewTableSeparate">
<?php echo $siswa->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($siswa->nis->Visible) { // nis ?>
		<td><span id="elh_siswa_nis" class="siswa_nis"><?php echo $siswa->nis->FldCaption() ?></span></td>
<?php } ?>
<?php if ($siswa->nama->Visible) { // nama ?>
		<td><span id="elh_siswa_nama" class="siswa_nama"><?php echo $siswa->nama->FldCaption() ?></span></td>
<?php } ?>
<?php if ($siswa->tempat_lahir->Visible) { // tempat_lahir ?>
		<td><span id="elh_siswa_tempat_lahir" class="siswa_tempat_lahir"><?php echo $siswa->tempat_lahir->FldCaption() ?></span></td>
<?php } ?>
<?php if ($siswa->tanggal_lahir->Visible) { // tanggal_lahir ?>
		<td><span id="elh_siswa_tanggal_lahir" class="siswa_tanggal_lahir"><?php echo $siswa->tanggal_lahir->FldCaption() ?></span></td>
<?php } ?>
<?php if ($siswa->alamat->Visible) { // alamat ?>
		<td><span id="elh_siswa_alamat" class="siswa_alamat"><?php echo $siswa->alamat->FldCaption() ?></span></td>
<?php } ?>
<?php if ($siswa->agama->Visible) { // agama ?>
		<td><span id="elh_siswa_agama" class="siswa_agama"><?php echo $siswa->agama->FldCaption() ?></span></td>
<?php } ?>
<?php if ($siswa->no_hp->Visible) { // no_hp ?>
		<td><span id="elh_siswa_no_hp" class="siswa_no_hp"><?php echo $siswa->no_hp->FldCaption() ?></span></td>
<?php } ?>
<?php if ($siswa->_email->Visible) { // email ?>
		<td><span id="elh_siswa__email" class="siswa__email"><?php echo $siswa->_email->FldCaption() ?></span></td>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$siswa_delete->RecCnt = 0;
$i = 0;
while (!$siswa_delete->Recordset->EOF) {
	$siswa_delete->RecCnt++;
	$siswa_delete->RowCnt++;

	// Set row properties
	$siswa->ResetAttrs();
	$siswa->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$siswa_delete->LoadRowValues($siswa_delete->Recordset);

	// Render row
	$siswa_delete->RenderRow();
?>
	<tr<?php echo $siswa->RowAttributes() ?>>
<?php if ($siswa->nis->Visible) { // nis ?>
		<td<?php echo $siswa->nis->CellAttributes() ?>>
<span id="el<?php echo $siswa_delete->RowCnt ?>_siswa_nis" class="control-group siswa_nis">
<span<?php echo $siswa->nis->ViewAttributes() ?>>
<?php echo $siswa->nis->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($siswa->nama->Visible) { // nama ?>
		<td<?php echo $siswa->nama->CellAttributes() ?>>
<span id="el<?php echo $siswa_delete->RowCnt ?>_siswa_nama" class="control-group siswa_nama">
<span<?php echo $siswa->nama->ViewAttributes() ?>>
<?php echo $siswa->nama->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($siswa->tempat_lahir->Visible) { // tempat_lahir ?>
		<td<?php echo $siswa->tempat_lahir->CellAttributes() ?>>
<span id="el<?php echo $siswa_delete->RowCnt ?>_siswa_tempat_lahir" class="control-group siswa_tempat_lahir">
<span<?php echo $siswa->tempat_lahir->ViewAttributes() ?>>
<?php echo $siswa->tempat_lahir->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($siswa->tanggal_lahir->Visible) { // tanggal_lahir ?>
		<td<?php echo $siswa->tanggal_lahir->CellAttributes() ?>>
<span id="el<?php echo $siswa_delete->RowCnt ?>_siswa_tanggal_lahir" class="control-group siswa_tanggal_lahir">
<span<?php echo $siswa->tanggal_lahir->ViewAttributes() ?>>
<?php echo $siswa->tanggal_lahir->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($siswa->alamat->Visible) { // alamat ?>
		<td<?php echo $siswa->alamat->CellAttributes() ?>>
<span id="el<?php echo $siswa_delete->RowCnt ?>_siswa_alamat" class="control-group siswa_alamat">
<span<?php echo $siswa->alamat->ViewAttributes() ?>>
<?php echo $siswa->alamat->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($siswa->agama->Visible) { // agama ?>
		<td<?php echo $siswa->agama->CellAttributes() ?>>
<span id="el<?php echo $siswa_delete->RowCnt ?>_siswa_agama" class="control-group siswa_agama">
<span<?php echo $siswa->agama->ViewAttributes() ?>>
<?php echo $siswa->agama->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($siswa->no_hp->Visible) { // no_hp ?>
		<td<?php echo $siswa->no_hp->CellAttributes() ?>>
<span id="el<?php echo $siswa_delete->RowCnt ?>_siswa_no_hp" class="control-group siswa_no_hp">
<span<?php echo $siswa->no_hp->ViewAttributes() ?>>
<?php echo $siswa->no_hp->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($siswa->_email->Visible) { // email ?>
		<td<?php echo $siswa->_email->CellAttributes() ?>>
<span id="el<?php echo $siswa_delete->RowCnt ?>_siswa__email" class="control-group siswa__email">
<span<?php echo $siswa->_email->ViewAttributes() ?>>
<?php echo $siswa->_email->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$siswa_delete->Recordset->MoveNext();
}
$siswa_delete->Recordset->Close();
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
fsiswadelete.Init();
</script>
<?php
$siswa_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$siswa_delete->Page_Terminate();
?>
