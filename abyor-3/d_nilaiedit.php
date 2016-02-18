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

$d_nilai_edit = NULL; // Initialize page object first

class cd_nilai_edit extends cd_nilai {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{82E7E539-D0AE-473F-AA71-5A043814ED93}";

	// Table name
	var $TableName = 'd_nilai';

	// Page object name
	var $PageObjName = 'd_nilai_edit';

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
			define("EW_PAGE_ID", 'edit', TRUE);

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

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->detail_nilai_id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		if (@$_GET["detail_nilai_id"] <> "") {
			$this->detail_nilai_id->setQueryStringValue($_GET["detail_nilai_id"]);
		}

		// Set up master detail parameters
		$this->SetUpMasterParms();

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
		if ($this->detail_nilai_id->CurrentValue == "")
			$this->Page_Terminate("d_nilailist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("d_nilailist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "d_nilaiview.php")
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
		if (!$this->detail_nilai_id->FldIsDetailKey)
			$this->detail_nilai_id->setFormValue($objForm->GetValue("x_detail_nilai_id"));
		if (!$this->id_mapel->FldIsDetailKey) {
			$this->id_mapel->setFormValue($objForm->GetValue("x_id_mapel"));
		}
		if (!$this->nilai_afektif->FldIsDetailKey) {
			$this->nilai_afektif->setFormValue($objForm->GetValue("x_nilai_afektif"));
		}
		if (!$this->nilai_komulatif->FldIsDetailKey) {
			$this->nilai_komulatif->setFormValue($objForm->GetValue("x_nilai_komulatif"));
		}
		if (!$this->nilai_psikomotorik->FldIsDetailKey) {
			$this->nilai_psikomotorik->setFormValue($objForm->GetValue("x_nilai_psikomotorik"));
		}
		if (!$this->nilai_rata2Drata->FldIsDetailKey) {
			$this->nilai_rata2Drata->setFormValue($objForm->GetValue("x_nilai_rata2Drata"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->detail_nilai_id->CurrentValue = $this->detail_nilai_id->FormValue;
		$this->id_mapel->CurrentValue = $this->id_mapel->FormValue;
		$this->nilai_afektif->CurrentValue = $this->nilai_afektif->FormValue;
		$this->nilai_komulatif->CurrentValue = $this->nilai_komulatif->FormValue;
		$this->nilai_psikomotorik->CurrentValue = $this->nilai_psikomotorik->FormValue;
		$this->nilai_rata2Drata->CurrentValue = $this->nilai_rata2Drata->FormValue;
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

			// detail_nilai_id
			$this->detail_nilai_id->LinkCustomAttributes = "";
			$this->detail_nilai_id->HrefValue = "";
			$this->detail_nilai_id->TooltipValue = "";

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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// detail_nilai_id
			$this->detail_nilai_id->EditCustomAttributes = "";
			$this->detail_nilai_id->EditValue = $this->detail_nilai_id->CurrentValue;
			$this->detail_nilai_id->ViewCustomAttributes = "";

			// id_mapel
			$this->id_mapel->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT `id_mapel`, `namaMapel` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `mapel`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_mapel, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->id_mapel->EditValue = $arwrk;

			// nilai_afektif
			$this->nilai_afektif->EditCustomAttributes = "";
			$this->nilai_afektif->EditValue = ew_HtmlEncode($this->nilai_afektif->CurrentValue);
			$this->nilai_afektif->PlaceHolder = ew_RemoveHtml($this->nilai_afektif->FldCaption());

			// nilai_komulatif
			$this->nilai_komulatif->EditCustomAttributes = "";
			$this->nilai_komulatif->EditValue = ew_HtmlEncode($this->nilai_komulatif->CurrentValue);
			$this->nilai_komulatif->PlaceHolder = ew_RemoveHtml($this->nilai_komulatif->FldCaption());

			// nilai_psikomotorik
			$this->nilai_psikomotorik->EditCustomAttributes = "";
			$this->nilai_psikomotorik->EditValue = ew_HtmlEncode($this->nilai_psikomotorik->CurrentValue);
			$this->nilai_psikomotorik->PlaceHolder = ew_RemoveHtml($this->nilai_psikomotorik->FldCaption());

			// nilai_rata-rata
			$this->nilai_rata2Drata->EditCustomAttributes = "";
			$this->nilai_rata2Drata->EditValue = ew_HtmlEncode($this->nilai_rata2Drata->CurrentValue);
			$this->nilai_rata2Drata->PlaceHolder = ew_RemoveHtml($this->nilai_rata2Drata->FldCaption());

			// Edit refer script
			// detail_nilai_id

			$this->detail_nilai_id->HrefValue = "";

			// id_mapel
			$this->id_mapel->HrefValue = "";

			// nilai_afektif
			$this->nilai_afektif->HrefValue = "";

			// nilai_komulatif
			$this->nilai_komulatif->HrefValue = "";

			// nilai_psikomotorik
			$this->nilai_psikomotorik->HrefValue = "";

			// nilai_rata-rata
			$this->nilai_rata2Drata->HrefValue = "";
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
		if (!$this->id_mapel->FldIsDetailKey && !is_null($this->id_mapel->FormValue) && $this->id_mapel->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->id_mapel->FldCaption());
		}
		if (!$this->nilai_afektif->FldIsDetailKey && !is_null($this->nilai_afektif->FormValue) && $this->nilai_afektif->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nilai_afektif->FldCaption());
		}
		if (!ew_CheckInteger($this->nilai_afektif->FormValue)) {
			ew_AddMessage($gsFormError, $this->nilai_afektif->FldErrMsg());
		}
		if (!$this->nilai_komulatif->FldIsDetailKey && !is_null($this->nilai_komulatif->FormValue) && $this->nilai_komulatif->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nilai_komulatif->FldCaption());
		}
		if (!ew_CheckInteger($this->nilai_komulatif->FormValue)) {
			ew_AddMessage($gsFormError, $this->nilai_komulatif->FldErrMsg());
		}
		if (!$this->nilai_psikomotorik->FldIsDetailKey && !is_null($this->nilai_psikomotorik->FormValue) && $this->nilai_psikomotorik->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nilai_psikomotorik->FldCaption());
		}
		if (!ew_CheckInteger($this->nilai_psikomotorik->FormValue)) {
			ew_AddMessage($gsFormError, $this->nilai_psikomotorik->FldErrMsg());
		}
		if (!$this->nilai_rata2Drata->FldIsDetailKey && !is_null($this->nilai_rata2Drata->FormValue) && $this->nilai_rata2Drata->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nilai_rata2Drata->FldCaption());
		}
		if (!ew_CheckInteger($this->nilai_rata2Drata->FormValue)) {
			ew_AddMessage($gsFormError, $this->nilai_rata2Drata->FldErrMsg());
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

			// id_mapel
			$this->id_mapel->SetDbValueDef($rsnew, $this->id_mapel->CurrentValue, 0, $this->id_mapel->ReadOnly);

			// nilai_afektif
			$this->nilai_afektif->SetDbValueDef($rsnew, $this->nilai_afektif->CurrentValue, 0, $this->nilai_afektif->ReadOnly);

			// nilai_komulatif
			$this->nilai_komulatif->SetDbValueDef($rsnew, $this->nilai_komulatif->CurrentValue, 0, $this->nilai_komulatif->ReadOnly);

			// nilai_psikomotorik
			$this->nilai_psikomotorik->SetDbValueDef($rsnew, $this->nilai_psikomotorik->CurrentValue, 0, $this->nilai_psikomotorik->ReadOnly);

			// nilai_rata-rata
			$this->nilai_rata2Drata->SetDbValueDef($rsnew, $this->nilai_rata2Drata->CurrentValue, 0, $this->nilai_rata2Drata->ReadOnly);

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

	// Set up master/detail based on QueryString
	function SetUpMasterParms() {
		$bValidMaster = FALSE;

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_MASTER])) {
			$sMasterTblVar = $_GET[EW_TABLE_SHOW_MASTER];
			if ($sMasterTblVar == "") {
				$bValidMaster = TRUE;
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
			}
			if ($sMasterTblVar == "nilai") {
				$bValidMaster = TRUE;
				if (@$_GET["nilai_id"] <> "") {
					$GLOBALS["nilai"]->nilai_id->setQueryStringValue($_GET["nilai_id"]);
					$this->nilai_id->setQueryStringValue($GLOBALS["nilai"]->nilai_id->QueryStringValue);
					$this->nilai_id->setSessionValue($this->nilai_id->QueryStringValue);
					if (!is_numeric($GLOBALS["nilai"]->nilai_id->QueryStringValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
		}
		if ($bValidMaster) {

			// Save current master table
			$this->setCurrentMasterTable($sMasterTblVar);

			// Reset start record counter (new master key)
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);

			// Clear previous master key from Session
			if ($sMasterTblVar <> "nilai") {
				if ($this->nilai_id->QueryStringValue == "") $this->nilai_id->setSessionValue("");
			}
		}
		$this->DbMasterFilter = $this->GetMasterFilter(); //  Get master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Get detail filter
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("list", $this->TableVar, "d_nilailist.php", $this->TableVar, TRUE);
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
if (!isset($d_nilai_edit)) $d_nilai_edit = new cd_nilai_edit();

// Page init
$d_nilai_edit->Page_Init();

// Page main
$d_nilai_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$d_nilai_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var d_nilai_edit = new ew_Page("d_nilai_edit");
d_nilai_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = d_nilai_edit.PageID; // For backward compatibility

// Form object
var fd_nilaiedit = new ew_Form("fd_nilaiedit");

// Validate form
fd_nilaiedit.Validate = function() {
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
fd_nilaiedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fd_nilaiedit.ValidateRequired = true;
<?php } else { ?>
fd_nilaiedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fd_nilaiedit.Lists["x_id_mapel"] = {"LinkField":"x_id_mapel","Ajax":null,"AutoFill":false,"DisplayFields":["x_namaMapel","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $d_nilai_edit->ShowPageHeader(); ?>
<?php
$d_nilai_edit->ShowMessage();
?>
<form name="fd_nilaiedit" id="fd_nilaiedit" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="d_nilai">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table class="ewGrid"><tr><td>
<table id="tbl_d_nilaiedit" class="table table-bordered table-striped">
<?php if ($d_nilai->detail_nilai_id->Visible) { // detail_nilai_id ?>
	<tr id="r_detail_nilai_id">
		<td><span id="elh_d_nilai_detail_nilai_id"><?php echo $d_nilai->detail_nilai_id->FldCaption() ?></span></td>
		<td<?php echo $d_nilai->detail_nilai_id->CellAttributes() ?>>
<span id="el_d_nilai_detail_nilai_id" class="control-group">
<span<?php echo $d_nilai->detail_nilai_id->ViewAttributes() ?>>
<?php echo $d_nilai->detail_nilai_id->EditValue ?></span>
</span>
<input type="hidden" data-field="x_detail_nilai_id" name="x_detail_nilai_id" id="x_detail_nilai_id" value="<?php echo ew_HtmlEncode($d_nilai->detail_nilai_id->CurrentValue) ?>">
<?php echo $d_nilai->detail_nilai_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($d_nilai->id_mapel->Visible) { // id_mapel ?>
	<tr id="r_id_mapel">
		<td><span id="elh_d_nilai_id_mapel"><?php echo $d_nilai->id_mapel->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $d_nilai->id_mapel->CellAttributes() ?>>
<span id="el_d_nilai_id_mapel" class="control-group">
<select data-field="x_id_mapel" id="x_id_mapel" name="x_id_mapel"<?php echo $d_nilai->id_mapel->EditAttributes() ?>>
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
?>
</select>
<script type="text/javascript">
fd_nilaiedit.Lists["x_id_mapel"].Options = <?php echo (is_array($d_nilai->id_mapel->EditValue)) ? ew_ArrayToJson($d_nilai->id_mapel->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $d_nilai->id_mapel->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($d_nilai->nilai_afektif->Visible) { // nilai_afektif ?>
	<tr id="r_nilai_afektif">
		<td><span id="elh_d_nilai_nilai_afektif"><?php echo $d_nilai->nilai_afektif->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $d_nilai->nilai_afektif->CellAttributes() ?>>
<span id="el_d_nilai_nilai_afektif" class="control-group">
<input type="text" data-field="x_nilai_afektif" name="x_nilai_afektif" id="x_nilai_afektif" size="30" placeholder="<?php echo ew_HtmlEncode($d_nilai->nilai_afektif->PlaceHolder) ?>" value="<?php echo $d_nilai->nilai_afektif->EditValue ?>"<?php echo $d_nilai->nilai_afektif->EditAttributes() ?>>
</span>
<?php echo $d_nilai->nilai_afektif->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($d_nilai->nilai_komulatif->Visible) { // nilai_komulatif ?>
	<tr id="r_nilai_komulatif">
		<td><span id="elh_d_nilai_nilai_komulatif"><?php echo $d_nilai->nilai_komulatif->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $d_nilai->nilai_komulatif->CellAttributes() ?>>
<span id="el_d_nilai_nilai_komulatif" class="control-group">
<input type="text" data-field="x_nilai_komulatif" name="x_nilai_komulatif" id="x_nilai_komulatif" size="30" placeholder="<?php echo ew_HtmlEncode($d_nilai->nilai_komulatif->PlaceHolder) ?>" value="<?php echo $d_nilai->nilai_komulatif->EditValue ?>"<?php echo $d_nilai->nilai_komulatif->EditAttributes() ?>>
</span>
<?php echo $d_nilai->nilai_komulatif->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($d_nilai->nilai_psikomotorik->Visible) { // nilai_psikomotorik ?>
	<tr id="r_nilai_psikomotorik">
		<td><span id="elh_d_nilai_nilai_psikomotorik"><?php echo $d_nilai->nilai_psikomotorik->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $d_nilai->nilai_psikomotorik->CellAttributes() ?>>
<span id="el_d_nilai_nilai_psikomotorik" class="control-group">
<input type="text" data-field="x_nilai_psikomotorik" name="x_nilai_psikomotorik" id="x_nilai_psikomotorik" size="30" placeholder="<?php echo ew_HtmlEncode($d_nilai->nilai_psikomotorik->PlaceHolder) ?>" value="<?php echo $d_nilai->nilai_psikomotorik->EditValue ?>"<?php echo $d_nilai->nilai_psikomotorik->EditAttributes() ?>>
</span>
<?php echo $d_nilai->nilai_psikomotorik->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($d_nilai->nilai_rata2Drata->Visible) { // nilai_rata-rata ?>
	<tr id="r_nilai_rata2Drata">
		<td><span id="elh_d_nilai_nilai_rata2Drata"><?php echo $d_nilai->nilai_rata2Drata->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $d_nilai->nilai_rata2Drata->CellAttributes() ?>>
<span id="el_d_nilai_nilai_rata2Drata" class="control-group">
<input type="text" data-field="x_nilai_rata2Drata" name="x_nilai_rata2Drata" id="x_nilai_rata2Drata" size="30" placeholder="<?php echo ew_HtmlEncode($d_nilai->nilai_rata2Drata->PlaceHolder) ?>" value="<?php echo $d_nilai->nilai_rata2Drata->EditValue ?>"<?php echo $d_nilai->nilai_rata2Drata->EditAttributes() ?>>
</span>
<?php echo $d_nilai->nilai_rata2Drata->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("EditBtn") ?></button>
</form>
<script type="text/javascript">
fd_nilaiedit.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$d_nilai_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$d_nilai_edit->Page_Terminate();
?>
