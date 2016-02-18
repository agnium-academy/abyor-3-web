<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "guruinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$guru_add = NULL; // Initialize page object first

class cguru_add extends cguru {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{82E7E539-D0AE-473F-AA71-5A043814ED93}";

	// Table name
	var $TableName = 'guru';

	// Page object name
	var $PageObjName = 'guru_add';

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

		// Table object (guru)
		if (!isset($GLOBALS["guru"]) || get_class($GLOBALS["guru"]) == "cguru") {
			$GLOBALS["guru"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["guru"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'guru', TRUE);

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

		// Create form object
		$objForm = new cFormObj();
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
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["guru_id"] != "") {
				$this->guru_id->setQueryStringValue($_GET["guru_id"]);
				$this->setKey("guru_id", $this->guru_id->CurrentValue); // Set up key
			} else {
				$this->setKey("guru_id", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
				$this->LoadDefaultValues(); // Load default values
			}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("gurulist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "guruview.php")
						$sReturnUrl = $this->GetViewUrl(); // View paging, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD;  // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->nip->CurrentValue = NULL;
		$this->nip->OldValue = $this->nip->CurrentValue;
		$this->nama->CurrentValue = NULL;
		$this->nama->OldValue = $this->nama->CurrentValue;
		$this->tempatLahir->CurrentValue = NULL;
		$this->tempatLahir->OldValue = $this->tempatLahir->CurrentValue;
		$this->tanggalLahir->CurrentValue = NULL;
		$this->tanggalLahir->OldValue = $this->tanggalLahir->CurrentValue;
		$this->alamat->CurrentValue = NULL;
		$this->alamat->OldValue = $this->alamat->CurrentValue;
		$this->agama->CurrentValue = NULL;
		$this->agama->OldValue = $this->agama->CurrentValue;
		$this->noHp->CurrentValue = NULL;
		$this->noHp->OldValue = $this->noHp->CurrentValue;
		$this->_email->CurrentValue = NULL;
		$this->_email->OldValue = $this->_email->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->nip->FldIsDetailKey) {
			$this->nip->setFormValue($objForm->GetValue("x_nip"));
		}
		if (!$this->nama->FldIsDetailKey) {
			$this->nama->setFormValue($objForm->GetValue("x_nama"));
		}
		if (!$this->tempatLahir->FldIsDetailKey) {
			$this->tempatLahir->setFormValue($objForm->GetValue("x_tempatLahir"));
		}
		if (!$this->tanggalLahir->FldIsDetailKey) {
			$this->tanggalLahir->setFormValue($objForm->GetValue("x_tanggalLahir"));
			$this->tanggalLahir->CurrentValue = ew_UnFormatDateTime($this->tanggalLahir->CurrentValue, 5);
		}
		if (!$this->alamat->FldIsDetailKey) {
			$this->alamat->setFormValue($objForm->GetValue("x_alamat"));
		}
		if (!$this->agama->FldIsDetailKey) {
			$this->agama->setFormValue($objForm->GetValue("x_agama"));
		}
		if (!$this->noHp->FldIsDetailKey) {
			$this->noHp->setFormValue($objForm->GetValue("x_noHp"));
		}
		if (!$this->_email->FldIsDetailKey) {
			$this->_email->setFormValue($objForm->GetValue("x__email"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->nip->CurrentValue = $this->nip->FormValue;
		$this->nama->CurrentValue = $this->nama->FormValue;
		$this->tempatLahir->CurrentValue = $this->tempatLahir->FormValue;
		$this->tanggalLahir->CurrentValue = $this->tanggalLahir->FormValue;
		$this->tanggalLahir->CurrentValue = ew_UnFormatDateTime($this->tanggalLahir->CurrentValue, 5);
		$this->alamat->CurrentValue = $this->alamat->FormValue;
		$this->agama->CurrentValue = $this->agama->FormValue;
		$this->noHp->CurrentValue = $this->noHp->FormValue;
		$this->_email->CurrentValue = $this->_email->FormValue;
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
		$this->guru_id->setDbValue($rs->fields('guru_id'));
		$this->nip->setDbValue($rs->fields('nip'));
		$this->nama->setDbValue($rs->fields('nama'));
		$this->tempatLahir->setDbValue($rs->fields('tempatLahir'));
		$this->tanggalLahir->setDbValue($rs->fields('tanggalLahir'));
		$this->alamat->setDbValue($rs->fields('alamat'));
		$this->agama->setDbValue($rs->fields('agama'));
		$this->noHp->setDbValue($rs->fields('noHp'));
		$this->_email->setDbValue($rs->fields('email'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->guru_id->DbValue = $row['guru_id'];
		$this->nip->DbValue = $row['nip'];
		$this->nama->DbValue = $row['nama'];
		$this->tempatLahir->DbValue = $row['tempatLahir'];
		$this->tanggalLahir->DbValue = $row['tanggalLahir'];
		$this->alamat->DbValue = $row['alamat'];
		$this->agama->DbValue = $row['agama'];
		$this->noHp->DbValue = $row['noHp'];
		$this->_email->DbValue = $row['email'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("guru_id")) <> "")
			$this->guru_id->CurrentValue = $this->getKey("guru_id"); // guru_id
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$this->OldRecordset = ew_LoadRecordset($sSql);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// guru_id
		// nip
		// nama
		// tempatLahir
		// tanggalLahir
		// alamat
		// agama
		// noHp
		// email

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// guru_id
			$this->guru_id->ViewValue = $this->guru_id->CurrentValue;
			$this->guru_id->ViewCustomAttributes = "";

			// nip
			$this->nip->ViewValue = $this->nip->CurrentValue;
			$this->nip->ViewCustomAttributes = "";

			// nama
			$this->nama->ViewValue = $this->nama->CurrentValue;
			$this->nama->ViewCustomAttributes = "";

			// tempatLahir
			$this->tempatLahir->ViewValue = $this->tempatLahir->CurrentValue;
			$this->tempatLahir->ViewCustomAttributes = "";

			// tanggalLahir
			$this->tanggalLahir->ViewValue = $this->tanggalLahir->CurrentValue;
			$this->tanggalLahir->ViewValue = ew_FormatDateTime($this->tanggalLahir->ViewValue, 5);
			$this->tanggalLahir->ViewCustomAttributes = "";

			// alamat
			$this->alamat->ViewValue = $this->alamat->CurrentValue;
			$this->alamat->ViewCustomAttributes = "";

			// agama
			$this->agama->ViewValue = $this->agama->CurrentValue;
			$this->agama->ViewCustomAttributes = "";

			// noHp
			$this->noHp->ViewValue = $this->noHp->CurrentValue;
			$this->noHp->ViewCustomAttributes = "";

			// email
			$this->_email->ViewValue = $this->_email->CurrentValue;
			$this->_email->ViewCustomAttributes = "";

			// nip
			$this->nip->LinkCustomAttributes = "";
			$this->nip->HrefValue = "";
			$this->nip->TooltipValue = "";

			// nama
			$this->nama->LinkCustomAttributes = "";
			$this->nama->HrefValue = "";
			$this->nama->TooltipValue = "";

			// tempatLahir
			$this->tempatLahir->LinkCustomAttributes = "";
			$this->tempatLahir->HrefValue = "";
			$this->tempatLahir->TooltipValue = "";

			// tanggalLahir
			$this->tanggalLahir->LinkCustomAttributes = "";
			$this->tanggalLahir->HrefValue = "";
			$this->tanggalLahir->TooltipValue = "";

			// alamat
			$this->alamat->LinkCustomAttributes = "";
			$this->alamat->HrefValue = "";
			$this->alamat->TooltipValue = "";

			// agama
			$this->agama->LinkCustomAttributes = "";
			$this->agama->HrefValue = "";
			$this->agama->TooltipValue = "";

			// noHp
			$this->noHp->LinkCustomAttributes = "";
			$this->noHp->HrefValue = "";
			$this->noHp->TooltipValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";
			$this->_email->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// nip
			$this->nip->EditCustomAttributes = "";
			$this->nip->EditValue = ew_HtmlEncode($this->nip->CurrentValue);
			$this->nip->PlaceHolder = ew_RemoveHtml($this->nip->FldCaption());

			// nama
			$this->nama->EditCustomAttributes = "";
			$this->nama->EditValue = ew_HtmlEncode($this->nama->CurrentValue);
			$this->nama->PlaceHolder = ew_RemoveHtml($this->nama->FldCaption());

			// tempatLahir
			$this->tempatLahir->EditCustomAttributes = "";
			$this->tempatLahir->EditValue = ew_HtmlEncode($this->tempatLahir->CurrentValue);
			$this->tempatLahir->PlaceHolder = ew_RemoveHtml($this->tempatLahir->FldCaption());

			// tanggalLahir
			$this->tanggalLahir->EditCustomAttributes = "";
			$this->tanggalLahir->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->tanggalLahir->CurrentValue, 5));
			$this->tanggalLahir->PlaceHolder = ew_RemoveHtml($this->tanggalLahir->FldCaption());

			// alamat
			$this->alamat->EditCustomAttributes = "";
			$this->alamat->EditValue = ew_HtmlEncode($this->alamat->CurrentValue);
			$this->alamat->PlaceHolder = ew_RemoveHtml($this->alamat->FldCaption());

			// agama
			$this->agama->EditCustomAttributes = "";
			$this->agama->EditValue = ew_HtmlEncode($this->agama->CurrentValue);
			$this->agama->PlaceHolder = ew_RemoveHtml($this->agama->FldCaption());

			// noHp
			$this->noHp->EditCustomAttributes = "";
			$this->noHp->EditValue = ew_HtmlEncode($this->noHp->CurrentValue);
			$this->noHp->PlaceHolder = ew_RemoveHtml($this->noHp->FldCaption());

			// email
			$this->_email->EditCustomAttributes = "";
			$this->_email->EditValue = ew_HtmlEncode($this->_email->CurrentValue);
			$this->_email->PlaceHolder = ew_RemoveHtml($this->_email->FldCaption());

			// Edit refer script
			// nip

			$this->nip->HrefValue = "";

			// nama
			$this->nama->HrefValue = "";

			// tempatLahir
			$this->tempatLahir->HrefValue = "";

			// tanggalLahir
			$this->tanggalLahir->HrefValue = "";

			// alamat
			$this->alamat->HrefValue = "";

			// agama
			$this->agama->HrefValue = "";

			// noHp
			$this->noHp->HrefValue = "";

			// email
			$this->_email->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!$this->nip->FldIsDetailKey && !is_null($this->nip->FormValue) && $this->nip->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nip->FldCaption());
		}
		if (!$this->nama->FldIsDetailKey && !is_null($this->nama->FormValue) && $this->nama->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nama->FldCaption());
		}
		if (!$this->tempatLahir->FldIsDetailKey && !is_null($this->tempatLahir->FormValue) && $this->tempatLahir->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->tempatLahir->FldCaption());
		}
		if (!$this->tanggalLahir->FldIsDetailKey && !is_null($this->tanggalLahir->FormValue) && $this->tanggalLahir->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->tanggalLahir->FldCaption());
		}
		if (!ew_CheckDate($this->tanggalLahir->FormValue)) {
			ew_AddMessage($gsFormError, $this->tanggalLahir->FldErrMsg());
		}
		if (!$this->alamat->FldIsDetailKey && !is_null($this->alamat->FormValue) && $this->alamat->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->alamat->FldCaption());
		}
		if (!$this->agama->FldIsDetailKey && !is_null($this->agama->FormValue) && $this->agama->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->agama->FldCaption());
		}
		if (!$this->noHp->FldIsDetailKey && !is_null($this->noHp->FormValue) && $this->noHp->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->noHp->FldCaption());
		}
		if (!$this->_email->FldIsDetailKey && !is_null($this->_email->FormValue) && $this->_email->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->_email->FldCaption());
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Add record
	function AddRow($rsold = NULL) {
		global $conn, $Language, $Security;

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// nip
		$this->nip->SetDbValueDef($rsnew, $this->nip->CurrentValue, "", FALSE);

		// nama
		$this->nama->SetDbValueDef($rsnew, $this->nama->CurrentValue, "", FALSE);

		// tempatLahir
		$this->tempatLahir->SetDbValueDef($rsnew, $this->tempatLahir->CurrentValue, "", FALSE);

		// tanggalLahir
		$this->tanggalLahir->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->tanggalLahir->CurrentValue, 5), ew_CurrentDate(), FALSE);

		// alamat
		$this->alamat->SetDbValueDef($rsnew, $this->alamat->CurrentValue, "", FALSE);

		// agama
		$this->agama->SetDbValueDef($rsnew, $this->agama->CurrentValue, "", FALSE);

		// noHp
		$this->noHp->SetDbValueDef($rsnew, $this->noHp->CurrentValue, "", FALSE);

		// email
		$this->_email->SetDbValueDef($rsnew, $this->_email->CurrentValue, "", FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}

		// Get insert id if necessary
		if ($AddRow) {
			$this->guru_id->setDbValue($conn->Insert_ID());
			$rsnew['guru_id'] = $this->guru_id->DbValue;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("list", $this->TableVar, "gurulist.php", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, ew_CurrentUrl());
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

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($guru_add)) $guru_add = new cguru_add();

// Page init
$guru_add->Page_Init();

// Page main
$guru_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$guru_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var guru_add = new ew_Page("guru_add");
guru_add.PageID = "add"; // Page ID
var EW_PAGE_ID = guru_add.PageID; // For backward compatibility

// Form object
var fguruadd = new ew_Form("fguruadd");

// Validate form
fguruadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nip");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($guru->nip->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nama");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($guru->nama->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_tempatLahir");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($guru->tempatLahir->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_tanggalLahir");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($guru->tanggalLahir->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_tanggalLahir");
			if (elm && !ew_CheckDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($guru->tanggalLahir->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_alamat");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($guru->alamat->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_agama");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($guru->agama->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_noHp");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($guru->noHp->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "__email");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($guru->_email->FldCaption()) ?>");

			// Set up row object
			ew_ElementsToRow(fobj);

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
fguruadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fguruadd.ValidateRequired = true;
<?php } else { ?>
fguruadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $guru_add->ShowPageHeader(); ?>
<?php
$guru_add->ShowMessage();
?>
<form name="fguruadd" id="fguruadd" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="guru">
<input type="hidden" name="a_add" id="a_add" value="A">
<table class="ewGrid"><tr><td>
<table id="tbl_guruadd" class="table table-bordered table-striped">
<?php if ($guru->nip->Visible) { // nip ?>
	<tr id="r_nip">
		<td><span id="elh_guru_nip"><?php echo $guru->nip->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $guru->nip->CellAttributes() ?>>
<span id="el_guru_nip" class="control-group">
<input type="text" data-field="x_nip" name="x_nip" id="x_nip" size="30" maxlength="8" placeholder="<?php echo ew_HtmlEncode($guru->nip->PlaceHolder) ?>" value="<?php echo $guru->nip->EditValue ?>"<?php echo $guru->nip->EditAttributes() ?>>
</span>
<?php echo $guru->nip->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($guru->nama->Visible) { // nama ?>
	<tr id="r_nama">
		<td><span id="elh_guru_nama"><?php echo $guru->nama->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $guru->nama->CellAttributes() ?>>
<span id="el_guru_nama" class="control-group">
<input type="text" data-field="x_nama" name="x_nama" id="x_nama" size="30" maxlength="200" placeholder="<?php echo ew_HtmlEncode($guru->nama->PlaceHolder) ?>" value="<?php echo $guru->nama->EditValue ?>"<?php echo $guru->nama->EditAttributes() ?>>
</span>
<?php echo $guru->nama->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($guru->tempatLahir->Visible) { // tempatLahir ?>
	<tr id="r_tempatLahir">
		<td><span id="elh_guru_tempatLahir"><?php echo $guru->tempatLahir->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $guru->tempatLahir->CellAttributes() ?>>
<span id="el_guru_tempatLahir" class="control-group">
<input type="text" data-field="x_tempatLahir" name="x_tempatLahir" id="x_tempatLahir" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($guru->tempatLahir->PlaceHolder) ?>" value="<?php echo $guru->tempatLahir->EditValue ?>"<?php echo $guru->tempatLahir->EditAttributes() ?>>
</span>
<?php echo $guru->tempatLahir->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($guru->tanggalLahir->Visible) { // tanggalLahir ?>
	<tr id="r_tanggalLahir">
		<td><span id="elh_guru_tanggalLahir"><?php echo $guru->tanggalLahir->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $guru->tanggalLahir->CellAttributes() ?>>
<span id="el_guru_tanggalLahir" class="control-group">
<input type="text" data-field="x_tanggalLahir" name="x_tanggalLahir" id="x_tanggalLahir" placeholder="<?php echo ew_HtmlEncode($guru->tanggalLahir->PlaceHolder) ?>" value="<?php echo $guru->tanggalLahir->EditValue ?>"<?php echo $guru->tanggalLahir->EditAttributes() ?>>
<?php if (!$guru->tanggalLahir->ReadOnly && !$guru->tanggalLahir->Disabled && @$guru->tanggalLahir->EditAttrs["readonly"] == "" && @$guru->tanggalLahir->EditAttrs["disabled"] == "") { ?>
<button id="cal_x_tanggalLahir" name="cal_x_tanggalLahir" class="btn" type="button"><img src="phpimages/calendar.png" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("fguruadd", "x_tanggalLahir", "%Y/%m/%d");
</script>
<?php } ?>
</span>
<?php echo $guru->tanggalLahir->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($guru->alamat->Visible) { // alamat ?>
	<tr id="r_alamat">
		<td><span id="elh_guru_alamat"><?php echo $guru->alamat->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $guru->alamat->CellAttributes() ?>>
<span id="el_guru_alamat" class="control-group">
<input type="text" data-field="x_alamat" name="x_alamat" id="x_alamat" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($guru->alamat->PlaceHolder) ?>" value="<?php echo $guru->alamat->EditValue ?>"<?php echo $guru->alamat->EditAttributes() ?>>
</span>
<?php echo $guru->alamat->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($guru->agama->Visible) { // agama ?>
	<tr id="r_agama">
		<td><span id="elh_guru_agama"><?php echo $guru->agama->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $guru->agama->CellAttributes() ?>>
<span id="el_guru_agama" class="control-group">
<input type="text" data-field="x_agama" name="x_agama" id="x_agama" size="30" maxlength="15" placeholder="<?php echo ew_HtmlEncode($guru->agama->PlaceHolder) ?>" value="<?php echo $guru->agama->EditValue ?>"<?php echo $guru->agama->EditAttributes() ?>>
</span>
<?php echo $guru->agama->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($guru->noHp->Visible) { // noHp ?>
	<tr id="r_noHp">
		<td><span id="elh_guru_noHp"><?php echo $guru->noHp->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $guru->noHp->CellAttributes() ?>>
<span id="el_guru_noHp" class="control-group">
<input type="text" data-field="x_noHp" name="x_noHp" id="x_noHp" size="30" maxlength="15" placeholder="<?php echo ew_HtmlEncode($guru->noHp->PlaceHolder) ?>" value="<?php echo $guru->noHp->EditValue ?>"<?php echo $guru->noHp->EditAttributes() ?>>
</span>
<?php echo $guru->noHp->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($guru->_email->Visible) { // email ?>
	<tr id="r__email">
		<td><span id="elh_guru__email"><?php echo $guru->_email->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $guru->_email->CellAttributes() ?>>
<span id="el_guru__email" class="control-group">
<input type="text" data-field="x__email" name="x__email" id="x__email" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($guru->_email->PlaceHolder) ?>" value="<?php echo $guru->_email->EditValue ?>"<?php echo $guru->_email->EditAttributes() ?>>
</span>
<?php echo $guru->_email->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
fguruadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$guru_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$guru_add->Page_Terminate();
?>
