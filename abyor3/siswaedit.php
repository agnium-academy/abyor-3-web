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

$siswa_edit = NULL; // Initialize page object first

class csiswa_edit extends csiswa {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{207FBEFE-7CD7-4EAA-9B3B-698CCFB88A2B}";

	// Table name
	var $TableName = 'siswa';

	// Page object name
	var $PageObjName = 'siswa_edit';

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
			define("EW_PAGE_ID", 'edit', TRUE);

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

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->id_siswa->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load key from QueryString
		if (@$_GET["id_siswa"] <> "") {
			$this->id_siswa->setQueryStringValue($_GET["id_siswa"]);
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->id_siswa->CurrentValue == "")
			$this->Page_Terminate("siswalist.php"); // Invalid key, return to list

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("siswalist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "siswaview.php")
						$sReturnUrl = $this->GetViewUrl(); // View paging, return to View page directly
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm;

		// Get upload data
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->id_siswa->FldIsDetailKey)
			$this->id_siswa->setFormValue($objForm->GetValue("x_id_siswa"));
		if (!$this->nis->FldIsDetailKey) {
			$this->nis->setFormValue($objForm->GetValue("x_nis"));
		}
		if (!$this->nama->FldIsDetailKey) {
			$this->nama->setFormValue($objForm->GetValue("x_nama"));
		}
		if (!$this->tempat_lahir->FldIsDetailKey) {
			$this->tempat_lahir->setFormValue($objForm->GetValue("x_tempat_lahir"));
		}
		if (!$this->tanggal_lahir->FldIsDetailKey) {
			$this->tanggal_lahir->setFormValue($objForm->GetValue("x_tanggal_lahir"));
			$this->tanggal_lahir->CurrentValue = ew_UnFormatDateTime($this->tanggal_lahir->CurrentValue, 5);
		}
		if (!$this->alamat->FldIsDetailKey) {
			$this->alamat->setFormValue($objForm->GetValue("x_alamat"));
		}
		if (!$this->agama->FldIsDetailKey) {
			$this->agama->setFormValue($objForm->GetValue("x_agama"));
		}
		if (!$this->no_hp->FldIsDetailKey) {
			$this->no_hp->setFormValue($objForm->GetValue("x_no_hp"));
		}
		if (!$this->_email->FldIsDetailKey) {
			$this->_email->setFormValue($objForm->GetValue("x__email"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->id_siswa->CurrentValue = $this->id_siswa->FormValue;
		$this->nis->CurrentValue = $this->nis->FormValue;
		$this->nama->CurrentValue = $this->nama->FormValue;
		$this->tempat_lahir->CurrentValue = $this->tempat_lahir->FormValue;
		$this->tanggal_lahir->CurrentValue = $this->tanggal_lahir->FormValue;
		$this->tanggal_lahir->CurrentValue = ew_UnFormatDateTime($this->tanggal_lahir->CurrentValue, 5);
		$this->alamat->CurrentValue = $this->alamat->FormValue;
		$this->agama->CurrentValue = $this->agama->FormValue;
		$this->no_hp->CurrentValue = $this->no_hp->FormValue;
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

			// id_siswa
			$this->id_siswa->LinkCustomAttributes = "";
			$this->id_siswa->HrefValue = "";
			$this->id_siswa->TooltipValue = "";

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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id_siswa
			$this->id_siswa->EditCustomAttributes = "";
			$this->id_siswa->EditValue = $this->id_siswa->CurrentValue;
			$this->id_siswa->ViewCustomAttributes = "";

			// nis
			$this->nis->EditCustomAttributes = "";
			$this->nis->EditValue = ew_HtmlEncode($this->nis->CurrentValue);
			$this->nis->PlaceHolder = ew_RemoveHtml($this->nis->FldCaption());

			// nama
			$this->nama->EditCustomAttributes = "";
			$this->nama->EditValue = ew_HtmlEncode($this->nama->CurrentValue);
			$this->nama->PlaceHolder = ew_RemoveHtml($this->nama->FldCaption());

			// tempat_lahir
			$this->tempat_lahir->EditCustomAttributes = "";
			$this->tempat_lahir->EditValue = ew_HtmlEncode($this->tempat_lahir->CurrentValue);
			$this->tempat_lahir->PlaceHolder = ew_RemoveHtml($this->tempat_lahir->FldCaption());

			// tanggal_lahir
			$this->tanggal_lahir->EditCustomAttributes = "";
			$this->tanggal_lahir->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->tanggal_lahir->CurrentValue, 5));
			$this->tanggal_lahir->PlaceHolder = ew_RemoveHtml($this->tanggal_lahir->FldCaption());

			// alamat
			$this->alamat->EditCustomAttributes = "";
			$this->alamat->EditValue = ew_HtmlEncode($this->alamat->CurrentValue);
			$this->alamat->PlaceHolder = ew_RemoveHtml($this->alamat->FldCaption());

			// agama
			$this->agama->EditCustomAttributes = "";
			$this->agama->EditValue = ew_HtmlEncode($this->agama->CurrentValue);
			$this->agama->PlaceHolder = ew_RemoveHtml($this->agama->FldCaption());

			// no_hp
			$this->no_hp->EditCustomAttributes = "";
			$this->no_hp->EditValue = ew_HtmlEncode($this->no_hp->CurrentValue);
			$this->no_hp->PlaceHolder = ew_RemoveHtml($this->no_hp->FldCaption());

			// email
			$this->_email->EditCustomAttributes = "";
			$this->_email->EditValue = ew_HtmlEncode($this->_email->CurrentValue);
			$this->_email->PlaceHolder = ew_RemoveHtml($this->_email->FldCaption());

			// Edit refer script
			// id_siswa

			$this->id_siswa->HrefValue = "";

			// nis
			$this->nis->HrefValue = "";

			// nama
			$this->nama->HrefValue = "";

			// tempat_lahir
			$this->tempat_lahir->HrefValue = "";

			// tanggal_lahir
			$this->tanggal_lahir->HrefValue = "";

			// alamat
			$this->alamat->HrefValue = "";

			// agama
			$this->agama->HrefValue = "";

			// no_hp
			$this->no_hp->HrefValue = "";

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
		if (!$this->nis->FldIsDetailKey && !is_null($this->nis->FormValue) && $this->nis->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nis->FldCaption());
		}
		if (!$this->nama->FldIsDetailKey && !is_null($this->nama->FormValue) && $this->nama->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nama->FldCaption());
		}
		if (!$this->tempat_lahir->FldIsDetailKey && !is_null($this->tempat_lahir->FormValue) && $this->tempat_lahir->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->tempat_lahir->FldCaption());
		}
		if (!$this->tanggal_lahir->FldIsDetailKey && !is_null($this->tanggal_lahir->FormValue) && $this->tanggal_lahir->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->tanggal_lahir->FldCaption());
		}
		if (!ew_CheckDate($this->tanggal_lahir->FormValue)) {
			ew_AddMessage($gsFormError, $this->tanggal_lahir->FldErrMsg());
		}
		if (!$this->alamat->FldIsDetailKey && !is_null($this->alamat->FormValue) && $this->alamat->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->alamat->FldCaption());
		}
		if (!$this->agama->FldIsDetailKey && !is_null($this->agama->FormValue) && $this->agama->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->agama->FldCaption());
		}
		if (!$this->no_hp->FldIsDetailKey && !is_null($this->no_hp->FormValue) && $this->no_hp->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->no_hp->FldCaption());
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

	// Update record based on key values
	function EditRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// nis
			$this->nis->SetDbValueDef($rsnew, $this->nis->CurrentValue, "", $this->nis->ReadOnly);

			// nama
			$this->nama->SetDbValueDef($rsnew, $this->nama->CurrentValue, "", $this->nama->ReadOnly);

			// tempat_lahir
			$this->tempat_lahir->SetDbValueDef($rsnew, $this->tempat_lahir->CurrentValue, "", $this->tempat_lahir->ReadOnly);

			// tanggal_lahir
			$this->tanggal_lahir->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->tanggal_lahir->CurrentValue, 5), ew_CurrentDate(), $this->tanggal_lahir->ReadOnly);

			// alamat
			$this->alamat->SetDbValueDef($rsnew, $this->alamat->CurrentValue, "", $this->alamat->ReadOnly);

			// agama
			$this->agama->SetDbValueDef($rsnew, $this->agama->CurrentValue, "", $this->agama->ReadOnly);

			// no_hp
			$this->no_hp->SetDbValueDef($rsnew, $this->no_hp->CurrentValue, "", $this->no_hp->ReadOnly);

			// email
			$this->_email->SetDbValueDef($rsnew, $this->_email->CurrentValue, "", $this->_email->ReadOnly);

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = 'ew_ErrorFn';
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("list", $this->TableVar, "siswalist.php", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, ew_CurrentUrl());
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
if (!isset($siswa_edit)) $siswa_edit = new csiswa_edit();

// Page init
$siswa_edit->Page_Init();

// Page main
$siswa_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$siswa_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var siswa_edit = new ew_Page("siswa_edit");
siswa_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = siswa_edit.PageID; // For backward compatibility

// Form object
var fsiswaedit = new ew_Form("fsiswaedit");

// Validate form
fsiswaedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nis");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($siswa->nis->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nama");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($siswa->nama->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_tempat_lahir");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($siswa->tempat_lahir->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_tanggal_lahir");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($siswa->tanggal_lahir->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_tanggal_lahir");
			if (elm && !ew_CheckDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($siswa->tanggal_lahir->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_alamat");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($siswa->alamat->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_agama");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($siswa->agama->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_no_hp");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($siswa->no_hp->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "__email");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($siswa->_email->FldCaption()) ?>");

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
fsiswaedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsiswaedit.ValidateRequired = true;
<?php } else { ?>
fsiswaedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $siswa_edit->ShowPageHeader(); ?>
<?php
$siswa_edit->ShowMessage();
?>
<form name="fsiswaedit" id="fsiswaedit" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="siswa">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table class="ewGrid"><tr><td>
<table id="tbl_siswaedit" class="table table-bordered table-striped">
<?php if ($siswa->id_siswa->Visible) { // id_siswa ?>
	<tr id="r_id_siswa">
		<td><span id="elh_siswa_id_siswa"><?php echo $siswa->id_siswa->FldCaption() ?></span></td>
		<td<?php echo $siswa->id_siswa->CellAttributes() ?>>
<span id="el_siswa_id_siswa" class="control-group">
<span<?php echo $siswa->id_siswa->ViewAttributes() ?>>
<?php echo $siswa->id_siswa->EditValue ?></span>
</span>
<input type="hidden" data-field="x_id_siswa" name="x_id_siswa" id="x_id_siswa" value="<?php echo ew_HtmlEncode($siswa->id_siswa->CurrentValue) ?>">
<?php echo $siswa->id_siswa->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($siswa->nis->Visible) { // nis ?>
	<tr id="r_nis">
		<td><span id="elh_siswa_nis"><?php echo $siswa->nis->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $siswa->nis->CellAttributes() ?>>
<span id="el_siswa_nis" class="control-group">
<input type="text" data-field="x_nis" name="x_nis" id="x_nis" size="30" maxlength="8" placeholder="<?php echo ew_HtmlEncode($siswa->nis->PlaceHolder) ?>" value="<?php echo $siswa->nis->EditValue ?>"<?php echo $siswa->nis->EditAttributes() ?>>
</span>
<?php echo $siswa->nis->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($siswa->nama->Visible) { // nama ?>
	<tr id="r_nama">
		<td><span id="elh_siswa_nama"><?php echo $siswa->nama->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $siswa->nama->CellAttributes() ?>>
<span id="el_siswa_nama" class="control-group">
<input type="text" data-field="x_nama" name="x_nama" id="x_nama" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($siswa->nama->PlaceHolder) ?>" value="<?php echo $siswa->nama->EditValue ?>"<?php echo $siswa->nama->EditAttributes() ?>>
</span>
<?php echo $siswa->nama->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($siswa->tempat_lahir->Visible) { // tempat_lahir ?>
	<tr id="r_tempat_lahir">
		<td><span id="elh_siswa_tempat_lahir"><?php echo $siswa->tempat_lahir->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $siswa->tempat_lahir->CellAttributes() ?>>
<span id="el_siswa_tempat_lahir" class="control-group">
<input type="text" data-field="x_tempat_lahir" name="x_tempat_lahir" id="x_tempat_lahir" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($siswa->tempat_lahir->PlaceHolder) ?>" value="<?php echo $siswa->tempat_lahir->EditValue ?>"<?php echo $siswa->tempat_lahir->EditAttributes() ?>>
</span>
<?php echo $siswa->tempat_lahir->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($siswa->tanggal_lahir->Visible) { // tanggal_lahir ?>
	<tr id="r_tanggal_lahir">
		<td><span id="elh_siswa_tanggal_lahir"><?php echo $siswa->tanggal_lahir->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $siswa->tanggal_lahir->CellAttributes() ?>>
<span id="el_siswa_tanggal_lahir" class="control-group">
<input type="text" data-field="x_tanggal_lahir" name="x_tanggal_lahir" id="x_tanggal_lahir" placeholder="<?php echo ew_HtmlEncode($siswa->tanggal_lahir->PlaceHolder) ?>" value="<?php echo $siswa->tanggal_lahir->EditValue ?>"<?php echo $siswa->tanggal_lahir->EditAttributes() ?>>
<?php if (!$siswa->tanggal_lahir->ReadOnly && !$siswa->tanggal_lahir->Disabled && @$siswa->tanggal_lahir->EditAttrs["readonly"] == "" && @$siswa->tanggal_lahir->EditAttrs["disabled"] == "") { ?>
<button id="cal_x_tanggal_lahir" name="cal_x_tanggal_lahir" class="btn" type="button"><img src="phpimages/calendar.png" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("fsiswaedit", "x_tanggal_lahir", "%Y/%m/%d");
</script>
<?php } ?>
</span>
<?php echo $siswa->tanggal_lahir->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($siswa->alamat->Visible) { // alamat ?>
	<tr id="r_alamat">
		<td><span id="elh_siswa_alamat"><?php echo $siswa->alamat->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $siswa->alamat->CellAttributes() ?>>
<span id="el_siswa_alamat" class="control-group">
<input type="text" data-field="x_alamat" name="x_alamat" id="x_alamat" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($siswa->alamat->PlaceHolder) ?>" value="<?php echo $siswa->alamat->EditValue ?>"<?php echo $siswa->alamat->EditAttributes() ?>>
</span>
<?php echo $siswa->alamat->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($siswa->agama->Visible) { // agama ?>
	<tr id="r_agama">
		<td><span id="elh_siswa_agama"><?php echo $siswa->agama->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $siswa->agama->CellAttributes() ?>>
<span id="el_siswa_agama" class="control-group">
<input type="text" data-field="x_agama" name="x_agama" id="x_agama" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($siswa->agama->PlaceHolder) ?>" value="<?php echo $siswa->agama->EditValue ?>"<?php echo $siswa->agama->EditAttributes() ?>>
</span>
<?php echo $siswa->agama->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($siswa->no_hp->Visible) { // no_hp ?>
	<tr id="r_no_hp">
		<td><span id="elh_siswa_no_hp"><?php echo $siswa->no_hp->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $siswa->no_hp->CellAttributes() ?>>
<span id="el_siswa_no_hp" class="control-group">
<input type="text" data-field="x_no_hp" name="x_no_hp" id="x_no_hp" size="30" maxlength="15" placeholder="<?php echo ew_HtmlEncode($siswa->no_hp->PlaceHolder) ?>" value="<?php echo $siswa->no_hp->EditValue ?>"<?php echo $siswa->no_hp->EditAttributes() ?>>
</span>
<?php echo $siswa->no_hp->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($siswa->_email->Visible) { // email ?>
	<tr id="r__email">
		<td><span id="elh_siswa__email"><?php echo $siswa->_email->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $siswa->_email->CellAttributes() ?>>
<span id="el_siswa__email" class="control-group">
<input type="text" data-field="x__email" name="x__email" id="x__email" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($siswa->_email->PlaceHolder) ?>" value="<?php echo $siswa->_email->EditValue ?>"<?php echo $siswa->_email->EditAttributes() ?>>
</span>
<?php echo $siswa->_email->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("EditBtn") ?></button>
</form>
<script type="text/javascript">
fsiswaedit.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$siswa_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$siswa_edit->Page_Terminate();
?>
