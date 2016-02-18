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

$guru_search = NULL; // Initialize page object first

class cguru_search extends cguru {

	// Page ID
	var $PageID = 'search';

	// Project ID
	var $ProjectID = "{82E7E539-D0AE-473F-AA71-5A043814ED93}";

	// Table name
	var $TableName = 'guru';

	// Page object name
	var $PageObjName = 'guru_search';

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
			define("EW_PAGE_ID", 'search', TRUE);

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
		$this->guru_id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsSearchError;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();
		if ($this->IsPageRequest()) { // Validate request

			// Get action
			$this->CurrentAction = $objForm->GetValue("a_search");
			switch ($this->CurrentAction) {
				case "S": // Get search criteria

					// Build search string for advanced search, remove blank field
					$this->LoadSearchValues(); // Get search values
					if ($this->ValidateSearch()) {
						$sSrchStr = $this->BuildAdvancedSearch();
					} else {
						$sSrchStr = "";
						$this->setFailureMessage($gsSearchError);
					}
					if ($sSrchStr <> "") {
						$sSrchStr = $this->UrlParm($sSrchStr);
						$this->Page_Terminate("gurulist.php" . "?" . $sSrchStr); // Go to list page
					}
			}
		}

		// Restore search settings from Session
		if ($gsSearchError == "")
			$this->LoadAdvancedSearch();

		// Render row for search
		$this->RowType = EW_ROWTYPE_SEARCH;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Build advanced search
	function BuildAdvancedSearch() {
		$sSrchUrl = "";
		$this->BuildSearchUrl($sSrchUrl, $this->guru_id); // guru_id
		$this->BuildSearchUrl($sSrchUrl, $this->nip); // nip
		$this->BuildSearchUrl($sSrchUrl, $this->nama); // nama
		$this->BuildSearchUrl($sSrchUrl, $this->tempatLahir); // tempatLahir
		$this->BuildSearchUrl($sSrchUrl, $this->tanggalLahir); // tanggalLahir
		$this->BuildSearchUrl($sSrchUrl, $this->alamat); // alamat
		$this->BuildSearchUrl($sSrchUrl, $this->agama); // agama
		$this->BuildSearchUrl($sSrchUrl, $this->noHp); // noHp
		$this->BuildSearchUrl($sSrchUrl, $this->_email); // email
		if ($sSrchUrl <> "") $sSrchUrl .= "&";
		$sSrchUrl .= "cmd=search";
		return $sSrchUrl;
	}

	// Build search URL
	function BuildSearchUrl(&$Url, &$Fld, $OprOnly=FALSE) {
		global $objForm;
		$sWrk = "";
		$FldParm = substr($Fld->FldVar, 2);
		$FldVal = $objForm->GetValue("x_$FldParm");
		$FldOpr = $objForm->GetValue("z_$FldParm");
		$FldCond = $objForm->GetValue("v_$FldParm");
		$FldVal2 = $objForm->GetValue("y_$FldParm");
		$FldOpr2 = $objForm->GetValue("w_$FldParm");
		$FldVal = ew_StripSlashes($FldVal);
		if (is_array($FldVal)) $FldVal = implode(",", $FldVal);
		$FldVal2 = ew_StripSlashes($FldVal2);
		if (is_array($FldVal2)) $FldVal2 = implode(",", $FldVal2);
		$FldOpr = strtoupper(trim($FldOpr));
		$lFldDataType = ($Fld->FldIsVirtual) ? EW_DATATYPE_STRING : $Fld->FldDataType;
		if ($FldOpr == "BETWEEN") {
			$IsValidValue = ($lFldDataType <> EW_DATATYPE_NUMBER) ||
				($lFldDataType == EW_DATATYPE_NUMBER && $this->SearchValueIsNumeric($Fld, $FldVal) && $this->SearchValueIsNumeric($Fld, $FldVal2));
			if ($FldVal <> "" && $FldVal2 <> "" && $IsValidValue) {
				$sWrk = "x_" . $FldParm . "=" . urlencode($FldVal) .
					"&y_" . $FldParm . "=" . urlencode($FldVal2) .
					"&z_" . $FldParm . "=" . urlencode($FldOpr);
			}
		} else {
			$IsValidValue = ($lFldDataType <> EW_DATATYPE_NUMBER) ||
				($lFldDataType == EW_DATATYPE_NUMBER && $this->SearchValueIsNumeric($Fld, $FldVal));
			if ($FldVal <> "" && $IsValidValue && ew_IsValidOpr($FldOpr, $lFldDataType)) {
				$sWrk = "x_" . $FldParm . "=" . urlencode($FldVal) .
					"&z_" . $FldParm . "=" . urlencode($FldOpr);
			} elseif ($FldOpr == "IS NULL" || $FldOpr == "IS NOT NULL" || ($FldOpr <> "" && $OprOnly && ew_IsValidOpr($FldOpr, $lFldDataType))) {
				$sWrk = "z_" . $FldParm . "=" . urlencode($FldOpr);
			}
			$IsValidValue = ($lFldDataType <> EW_DATATYPE_NUMBER) ||
				($lFldDataType == EW_DATATYPE_NUMBER && $this->SearchValueIsNumeric($Fld, $FldVal2));
			if ($FldVal2 <> "" && $IsValidValue && ew_IsValidOpr($FldOpr2, $lFldDataType)) {
				if ($sWrk <> "") $sWrk .= "&v_" . $FldParm . "=" . urlencode($FldCond) . "&";
				$sWrk .= "y_" . $FldParm . "=" . urlencode($FldVal2) .
					"&w_" . $FldParm . "=" . urlencode($FldOpr2);
			} elseif ($FldOpr2 == "IS NULL" || $FldOpr2 == "IS NOT NULL" || ($FldOpr2 <> "" && $OprOnly && ew_IsValidOpr($FldOpr2, $lFldDataType))) {
				if ($sWrk <> "") $sWrk .= "&v_" . $FldParm . "=" . urlencode($FldCond) . "&";
				$sWrk .= "w_" . $FldParm . "=" . urlencode($FldOpr2);
			}
		}
		if ($sWrk <> "") {
			if ($Url <> "") $Url .= "&";
			$Url .= $sWrk;
		}
	}

	function SearchValueIsNumeric($Fld, $Value) {
		if (ew_IsFloatFormat($Fld->FldType)) $Value = ew_StrToFloat($Value);
		return is_numeric($Value);
	}

	//  Load search values for validation
	function LoadSearchValues() {
		global $objForm;

		// Load search values
		// guru_id

		$this->guru_id->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_guru_id"));
		$this->guru_id->AdvancedSearch->SearchOperator = $objForm->GetValue("z_guru_id");

		// nip
		$this->nip->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_nip"));
		$this->nip->AdvancedSearch->SearchOperator = $objForm->GetValue("z_nip");

		// nama
		$this->nama->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_nama"));
		$this->nama->AdvancedSearch->SearchOperator = $objForm->GetValue("z_nama");

		// tempatLahir
		$this->tempatLahir->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_tempatLahir"));
		$this->tempatLahir->AdvancedSearch->SearchOperator = $objForm->GetValue("z_tempatLahir");

		// tanggalLahir
		$this->tanggalLahir->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_tanggalLahir"));
		$this->tanggalLahir->AdvancedSearch->SearchOperator = $objForm->GetValue("z_tanggalLahir");

		// alamat
		$this->alamat->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_alamat"));
		$this->alamat->AdvancedSearch->SearchOperator = $objForm->GetValue("z_alamat");

		// agama
		$this->agama->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_agama"));
		$this->agama->AdvancedSearch->SearchOperator = $objForm->GetValue("z_agama");

		// noHp
		$this->noHp->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_noHp"));
		$this->noHp->AdvancedSearch->SearchOperator = $objForm->GetValue("z_noHp");

		// email
		$this->_email->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x__email"));
		$this->_email->AdvancedSearch->SearchOperator = $objForm->GetValue("z__email");
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

			// guru_id
			$this->guru_id->LinkCustomAttributes = "";
			$this->guru_id->HrefValue = "";
			$this->guru_id->TooltipValue = "";

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
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// guru_id
			$this->guru_id->EditCustomAttributes = "";
			$this->guru_id->EditValue = ew_HtmlEncode($this->guru_id->AdvancedSearch->SearchValue);
			$this->guru_id->PlaceHolder = ew_RemoveHtml($this->guru_id->FldCaption());

			// nip
			$this->nip->EditCustomAttributes = "";
			$this->nip->EditValue = ew_HtmlEncode($this->nip->AdvancedSearch->SearchValue);
			$this->nip->PlaceHolder = ew_RemoveHtml($this->nip->FldCaption());

			// nama
			$this->nama->EditCustomAttributes = "";
			$this->nama->EditValue = ew_HtmlEncode($this->nama->AdvancedSearch->SearchValue);
			$this->nama->PlaceHolder = ew_RemoveHtml($this->nama->FldCaption());

			// tempatLahir
			$this->tempatLahir->EditCustomAttributes = "";
			$this->tempatLahir->EditValue = ew_HtmlEncode($this->tempatLahir->AdvancedSearch->SearchValue);
			$this->tempatLahir->PlaceHolder = ew_RemoveHtml($this->tempatLahir->FldCaption());

			// tanggalLahir
			$this->tanggalLahir->EditCustomAttributes = "";
			$this->tanggalLahir->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->tanggalLahir->AdvancedSearch->SearchValue, 5), 5));
			$this->tanggalLahir->PlaceHolder = ew_RemoveHtml($this->tanggalLahir->FldCaption());

			// alamat
			$this->alamat->EditCustomAttributes = "";
			$this->alamat->EditValue = ew_HtmlEncode($this->alamat->AdvancedSearch->SearchValue);
			$this->alamat->PlaceHolder = ew_RemoveHtml($this->alamat->FldCaption());

			// agama
			$this->agama->EditCustomAttributes = "";
			$this->agama->EditValue = ew_HtmlEncode($this->agama->AdvancedSearch->SearchValue);
			$this->agama->PlaceHolder = ew_RemoveHtml($this->agama->FldCaption());

			// noHp
			$this->noHp->EditCustomAttributes = "";
			$this->noHp->EditValue = ew_HtmlEncode($this->noHp->AdvancedSearch->SearchValue);
			$this->noHp->PlaceHolder = ew_RemoveHtml($this->noHp->FldCaption());

			// email
			$this->_email->EditCustomAttributes = "";
			$this->_email->EditValue = ew_HtmlEncode($this->_email->AdvancedSearch->SearchValue);
			$this->_email->PlaceHolder = ew_RemoveHtml($this->_email->FldCaption());
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

	// Validate search
	function ValidateSearch() {
		global $gsSearchError;

		// Initialize
		$gsSearchError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return TRUE;
		if (!ew_CheckInteger($this->guru_id->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->guru_id->FldErrMsg());
		}
		if (!ew_CheckDate($this->tanggalLahir->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->tanggalLahir->FldErrMsg());
		}

		// Return validate result
		$ValidateSearch = ($gsSearchError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateSearch = $ValidateSearch && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsSearchError, $sFormCustomError);
		}
		return $ValidateSearch;
	}

	// Load advanced search
	function LoadAdvancedSearch() {
		$this->guru_id->AdvancedSearch->Load();
		$this->nip->AdvancedSearch->Load();
		$this->nama->AdvancedSearch->Load();
		$this->tempatLahir->AdvancedSearch->Load();
		$this->tanggalLahir->AdvancedSearch->Load();
		$this->alamat->AdvancedSearch->Load();
		$this->agama->AdvancedSearch->Load();
		$this->noHp->AdvancedSearch->Load();
		$this->_email->AdvancedSearch->Load();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("list", $this->TableVar, "gurulist.php", $this->TableVar, TRUE);
		$PageId = "search";
		$Breadcrumb->Add("search", $PageId, ew_CurrentUrl());
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
if (!isset($guru_search)) $guru_search = new cguru_search();

// Page init
$guru_search->Page_Init();

// Page main
$guru_search->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$guru_search->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var guru_search = new ew_Page("guru_search");
guru_search.PageID = "search"; // Page ID
var EW_PAGE_ID = guru_search.PageID; // For backward compatibility

// Form object
var fgurusearch = new ew_Form("fgurusearch");

// Form_CustomValidate event
fgurusearch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fgurusearch.ValidateRequired = true;
<?php } else { ?>
fgurusearch.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search
// Validate function for search

fgurusearch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();
	var infix = "";
	elm = this.GetElements("x" + infix + "_guru_id");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($guru->guru_id->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_tanggalLahir");
	if (elm && !ew_CheckDate(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($guru->tanggalLahir->FldErrMsg()) ?>");

	// Set up row object
	ew_ElementsToRow(fobj);

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $guru_search->ShowPageHeader(); ?>
<?php
$guru_search->ShowMessage();
?>
<form name="fgurusearch" id="fgurusearch" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="guru">
<input type="hidden" name="a_search" id="a_search" value="S">
<table class="ewGrid"><tr><td>
<table id="tbl_gurusearch" class="table table-bordered table-striped">
<?php if ($guru->guru_id->Visible) { // guru_id ?>
	<tr id="r_guru_id">
		<td><span id="elh_guru_guru_id"><?php echo $guru->guru_id->FldCaption() ?></span></td>
		<td><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_guru_id" id="z_guru_id" value="="></span></td>
		<td<?php echo $guru->guru_id->CellAttributes() ?>>
			<div style="white-space: nowrap;">
				<span id="el_guru_guru_id" class="control-group">
<input type="text" data-field="x_guru_id" name="x_guru_id" id="x_guru_id" placeholder="<?php echo ew_HtmlEncode($guru->guru_id->PlaceHolder) ?>" value="<?php echo $guru->guru_id->EditValue ?>"<?php echo $guru->guru_id->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php if ($guru->nip->Visible) { // nip ?>
	<tr id="r_nip">
		<td><span id="elh_guru_nip"><?php echo $guru->nip->FldCaption() ?></span></td>
		<td><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_nip" id="z_nip" value="LIKE"></span></td>
		<td<?php echo $guru->nip->CellAttributes() ?>>
			<div style="white-space: nowrap;">
				<span id="el_guru_nip" class="control-group">
<input type="text" data-field="x_nip" name="x_nip" id="x_nip" size="30" maxlength="8" placeholder="<?php echo ew_HtmlEncode($guru->nip->PlaceHolder) ?>" value="<?php echo $guru->nip->EditValue ?>"<?php echo $guru->nip->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php if ($guru->nama->Visible) { // nama ?>
	<tr id="r_nama">
		<td><span id="elh_guru_nama"><?php echo $guru->nama->FldCaption() ?></span></td>
		<td><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_nama" id="z_nama" value="LIKE"></span></td>
		<td<?php echo $guru->nama->CellAttributes() ?>>
			<div style="white-space: nowrap;">
				<span id="el_guru_nama" class="control-group">
<input type="text" data-field="x_nama" name="x_nama" id="x_nama" size="30" maxlength="200" placeholder="<?php echo ew_HtmlEncode($guru->nama->PlaceHolder) ?>" value="<?php echo $guru->nama->EditValue ?>"<?php echo $guru->nama->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php if ($guru->tempatLahir->Visible) { // tempatLahir ?>
	<tr id="r_tempatLahir">
		<td><span id="elh_guru_tempatLahir"><?php echo $guru->tempatLahir->FldCaption() ?></span></td>
		<td><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_tempatLahir" id="z_tempatLahir" value="LIKE"></span></td>
		<td<?php echo $guru->tempatLahir->CellAttributes() ?>>
			<div style="white-space: nowrap;">
				<span id="el_guru_tempatLahir" class="control-group">
<input type="text" data-field="x_tempatLahir" name="x_tempatLahir" id="x_tempatLahir" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($guru->tempatLahir->PlaceHolder) ?>" value="<?php echo $guru->tempatLahir->EditValue ?>"<?php echo $guru->tempatLahir->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php if ($guru->tanggalLahir->Visible) { // tanggalLahir ?>
	<tr id="r_tanggalLahir">
		<td><span id="elh_guru_tanggalLahir"><?php echo $guru->tanggalLahir->FldCaption() ?></span></td>
		<td><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_tanggalLahir" id="z_tanggalLahir" value="="></span></td>
		<td<?php echo $guru->tanggalLahir->CellAttributes() ?>>
			<div style="white-space: nowrap;">
				<span id="el_guru_tanggalLahir" class="control-group">
<input type="text" data-field="x_tanggalLahir" name="x_tanggalLahir" id="x_tanggalLahir" placeholder="<?php echo ew_HtmlEncode($guru->tanggalLahir->PlaceHolder) ?>" value="<?php echo $guru->tanggalLahir->EditValue ?>"<?php echo $guru->tanggalLahir->EditAttributes() ?>>
<?php if (!$guru->tanggalLahir->ReadOnly && !$guru->tanggalLahir->Disabled && @$guru->tanggalLahir->EditAttrs["readonly"] == "" && @$guru->tanggalLahir->EditAttrs["disabled"] == "") { ?>
<button id="cal_x_tanggalLahir" name="cal_x_tanggalLahir" class="btn" type="button"><img src="phpimages/calendar.png" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("fgurusearch", "x_tanggalLahir", "%Y/%m/%d");
</script>
<?php } ?>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php if ($guru->alamat->Visible) { // alamat ?>
	<tr id="r_alamat">
		<td><span id="elh_guru_alamat"><?php echo $guru->alamat->FldCaption() ?></span></td>
		<td><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_alamat" id="z_alamat" value="LIKE"></span></td>
		<td<?php echo $guru->alamat->CellAttributes() ?>>
			<div style="white-space: nowrap;">
				<span id="el_guru_alamat" class="control-group">
<input type="text" data-field="x_alamat" name="x_alamat" id="x_alamat" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($guru->alamat->PlaceHolder) ?>" value="<?php echo $guru->alamat->EditValue ?>"<?php echo $guru->alamat->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php if ($guru->agama->Visible) { // agama ?>
	<tr id="r_agama">
		<td><span id="elh_guru_agama"><?php echo $guru->agama->FldCaption() ?></span></td>
		<td><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_agama" id="z_agama" value="LIKE"></span></td>
		<td<?php echo $guru->agama->CellAttributes() ?>>
			<div style="white-space: nowrap;">
				<span id="el_guru_agama" class="control-group">
<input type="text" data-field="x_agama" name="x_agama" id="x_agama" size="30" maxlength="15" placeholder="<?php echo ew_HtmlEncode($guru->agama->PlaceHolder) ?>" value="<?php echo $guru->agama->EditValue ?>"<?php echo $guru->agama->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php if ($guru->noHp->Visible) { // noHp ?>
	<tr id="r_noHp">
		<td><span id="elh_guru_noHp"><?php echo $guru->noHp->FldCaption() ?></span></td>
		<td><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_noHp" id="z_noHp" value="LIKE"></span></td>
		<td<?php echo $guru->noHp->CellAttributes() ?>>
			<div style="white-space: nowrap;">
				<span id="el_guru_noHp" class="control-group">
<input type="text" data-field="x_noHp" name="x_noHp" id="x_noHp" size="30" maxlength="15" placeholder="<?php echo ew_HtmlEncode($guru->noHp->PlaceHolder) ?>" value="<?php echo $guru->noHp->EditValue ?>"<?php echo $guru->noHp->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php if ($guru->_email->Visible) { // email ?>
	<tr id="r__email">
		<td><span id="elh_guru__email"><?php echo $guru->_email->FldCaption() ?></span></td>
		<td><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z__email" id="z__email" value="LIKE"></span></td>
		<td<?php echo $guru->_email->CellAttributes() ?>>
			<div style="white-space: nowrap;">
				<span id="el_guru__email" class="control-group">
<input type="text" data-field="x__email" name="x__email" id="x__email" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($guru->_email->PlaceHolder) ?>" value="<?php echo $guru->_email->EditValue ?>"<?php echo $guru->_email->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("Search") ?></button>
<button class="btn ewButton" name="btnReset" id="btnReset" type="button" onclick="ew_ClearForm(this.form);"><?php echo $Language->Phrase("Reset") ?></button>
</form>
<script type="text/javascript">
fgurusearch.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$guru_search->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$guru_search->Page_Terminate();
?>
