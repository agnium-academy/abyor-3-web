<?php

// Global variable for table object
$d_nilai = NULL;

//
// Table class for d_nilai
//
class cd_nilai extends cTable {
	var $detail_nilai_id;
	var $id_mapel;
	var $nilai_afektif;
	var $nilai_komulatif;
	var $nilai_psikomotorik;
	var $nilai_rata2Drata;
	var $nilai_id;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'd_nilai';
		$this->TableName = 'd_nilai';
		$this->TableType = 'TABLE';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// detail_nilai_id
		$this->detail_nilai_id = new cField('d_nilai', 'd_nilai', 'x_detail_nilai_id', 'detail_nilai_id', '`detail_nilai_id`', '`detail_nilai_id`', 3, -1, FALSE, '`detail_nilai_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->detail_nilai_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['detail_nilai_id'] = &$this->detail_nilai_id;

		// id_mapel
		$this->id_mapel = new cField('d_nilai', 'd_nilai', 'x_id_mapel', 'id_mapel', '`id_mapel`', '`id_mapel`', 3, -1, FALSE, '`id_mapel`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id_mapel->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_mapel'] = &$this->id_mapel;

		// nilai_afektif
		$this->nilai_afektif = new cField('d_nilai', 'd_nilai', 'x_nilai_afektif', 'nilai_afektif', '`nilai_afektif`', '`nilai_afektif`', 3, -1, FALSE, '`nilai_afektif`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nilai_afektif->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nilai_afektif'] = &$this->nilai_afektif;

		// nilai_komulatif
		$this->nilai_komulatif = new cField('d_nilai', 'd_nilai', 'x_nilai_komulatif', 'nilai_komulatif', '`nilai_komulatif`', '`nilai_komulatif`', 3, -1, FALSE, '`nilai_komulatif`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nilai_komulatif->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nilai_komulatif'] = &$this->nilai_komulatif;

		// nilai_psikomotorik
		$this->nilai_psikomotorik = new cField('d_nilai', 'd_nilai', 'x_nilai_psikomotorik', 'nilai_psikomotorik', '`nilai_psikomotorik`', '`nilai_psikomotorik`', 3, -1, FALSE, '`nilai_psikomotorik`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nilai_psikomotorik->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nilai_psikomotorik'] = &$this->nilai_psikomotorik;

		// nilai_rata-rata
		$this->nilai_rata2Drata = new cField('d_nilai', 'd_nilai', 'x_nilai_rata2Drata', 'nilai_rata-rata', '`nilai_rata-rata`', '`nilai_rata-rata`', 3, -1, FALSE, '`nilai_rata-rata`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nilai_rata2Drata->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nilai_rata-rata'] = &$this->nilai_rata2Drata;

		// nilai_id
		$this->nilai_id = new cField('d_nilai', 'd_nilai', 'x_nilai_id', 'nilai_id', '`nilai_id`', '`nilai_id`', 3, -1, FALSE, '`nilai_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->nilai_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nilai_id'] = &$this->nilai_id;
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Current master table name
	function getCurrentMasterTable() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_MASTER_TABLE];
	}

	function setCurrentMasterTable($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_MASTER_TABLE] = $v;
	}

	// Session master WHERE clause
	function GetMasterFilter() {

		// Master filter
		$sMasterFilter = "";
		if ($this->getCurrentMasterTable() == "nilai") {
			if ($this->nilai_id->getSessionValue() <> "")
				$sMasterFilter .= "`nilai_id`=" . ew_QuotedValue($this->nilai_id->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		return $sMasterFilter;
	}

	// Session detail WHERE clause
	function GetDetailFilter() {

		// Detail filter
		$sDetailFilter = "";
		if ($this->getCurrentMasterTable() == "nilai") {
			if ($this->nilai_id->getSessionValue() <> "")
				$sDetailFilter .= "`nilai_id`=" . ew_QuotedValue($this->nilai_id->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		return $sDetailFilter;
	}

	// Master filter
	function SqlMasterFilter_nilai() {
		return "`nilai_id`=@nilai_id@";
	}

	// Detail filter
	function SqlDetailFilter_nilai() {
		return "`nilai_id`=@nilai_id@";
	}

	// Table level SQL
	function SqlFrom() { // From
		return "`d_nilai`";
	}

	function SqlSelect() { // Select
		return "SELECT * FROM " . $this->SqlFrom();
	}

	function SqlWhere() { // Where
		$sWhere = "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlGroupBy() { // Group By
		return "";
	}

	function SqlHaving() { // Having
		return "";
	}

	function SqlOrderBy() { // Order By
		return "";
	}

	// Check if Anonymous User is allowed
	function AllowAnonymousUser() {
		switch (@$this->PageID) {
			case "add":
			case "register":
			case "addopt":
				return FALSE;
			case "edit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return FALSE;
			case "delete":
				return FALSE;
			case "view":
				return FALSE;
			case "search":
				return FALSE;
			default:
				return FALSE;
		}
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(),
			$this->SqlGroupBy(), $this->SqlHaving(), $this->SqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(),
			$this->SqlGroupBy(), $this->SqlHaving(), $this->SqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(), $this->SqlGroupBy(),
			$this->SqlHaving(), $this->SqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->SqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		global $conn;
		$cnt = -1;
		if ($this->TableType == 'TABLE' || $this->TableType == 'VIEW') {
			$sSql = "SELECT COUNT(*) FROM" . preg_replace('/^SELECT\s([\s\S]+)?\*\sFROM/i', "", $sSql);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function SelectRecordCount() {
		global $conn;
		$origFilter = $this->CurrentFilter;
		$this->Recordset_Selecting($this->CurrentFilter);
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Update Table
	var $UpdateTable = "`d_nilai`";

	// INSERT statement
	function InsertSQL(&$rs) {
		global $conn;
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]))
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		global $conn;
		return $conn->Execute($this->InsertSQL($rs));
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "") {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]))
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = $this->CurrentFilter;
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL) {
		global $conn;
		return $conn->Execute($this->UpdateSQL($rs, $where));
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "") {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if ($rs) {
			if (array_key_exists('detail_nilai_id', $rs))
				ew_AddFilter($where, ew_QuotedName('detail_nilai_id') . '=' . ew_QuotedValue($rs['detail_nilai_id'], $this->detail_nilai_id->FldDataType));
		}
		$filter = $this->CurrentFilter;
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "") {
		global $conn;
		return $conn->Execute($this->DeleteSQL($rs, $where));
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`detail_nilai_id` = @detail_nilai_id@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->detail_nilai_id->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@detail_nilai_id@", ew_AdjustSql($this->detail_nilai_id->CurrentValue), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "d_nilailist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "d_nilailist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("d_nilaiview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("d_nilaiview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "d_nilaiadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("d_nilaiedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("d_nilaiadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("d_nilaidelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->detail_nilai_id->CurrentValue)) {
			$sUrl .= "detail_nilai_id=" . urlencode($this->detail_nilai_id->CurrentValue);
		} else {
			return "javascript:alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return ew_CurrentPage() . "?" . $sUrlParm;
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = ew_StripSlashes($_POST["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET)) {
			$arKeys[] = @$_GET["detail_nilai_id"]; // detail_nilai_id

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		foreach ($arKeys as $key) {
			if (!is_numeric($key))
				continue;
			$ar[] = $key;
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->detail_nilai_id->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {
		global $conn;

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->detail_nilai_id->setDbValue($rs->fields('detail_nilai_id'));
		$this->id_mapel->setDbValue($rs->fields('id_mapel'));
		$this->nilai_afektif->setDbValue($rs->fields('nilai_afektif'));
		$this->nilai_komulatif->setDbValue($rs->fields('nilai_komulatif'));
		$this->nilai_psikomotorik->setDbValue($rs->fields('nilai_psikomotorik'));
		$this->nilai_rata2Drata->setDbValue($rs->fields('nilai_rata-rata'));
		$this->nilai_id->setDbValue($rs->fields('nilai_id'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security, $gsLanguage;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// detail_nilai_id
		// id_mapel
		// nilai_afektif
		// nilai_komulatif
		// nilai_psikomotorik
		// nilai_rata-rata
		// nilai_id
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

		// nilai_id
		$this->nilai_id->LinkCustomAttributes = "";
		$this->nilai_id->HrefValue = "";
		$this->nilai_id->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {
	}

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;

		// Write header
		$Doc->ExportTableHeader();
		if ($Doc->Horizontal) { // Horizontal format, write header
			$Doc->BeginExportRow();
			if ($ExportPageType == "view") {
				if ($this->detail_nilai_id->Exportable) $Doc->ExportCaption($this->detail_nilai_id);
				if ($this->id_mapel->Exportable) $Doc->ExportCaption($this->id_mapel);
				if ($this->nilai_afektif->Exportable) $Doc->ExportCaption($this->nilai_afektif);
				if ($this->nilai_komulatif->Exportable) $Doc->ExportCaption($this->nilai_komulatif);
				if ($this->nilai_psikomotorik->Exportable) $Doc->ExportCaption($this->nilai_psikomotorik);
				if ($this->nilai_rata2Drata->Exportable) $Doc->ExportCaption($this->nilai_rata2Drata);
			} else {
				if ($this->detail_nilai_id->Exportable) $Doc->ExportCaption($this->detail_nilai_id);
				if ($this->id_mapel->Exportable) $Doc->ExportCaption($this->id_mapel);
				if ($this->nilai_afektif->Exportable) $Doc->ExportCaption($this->nilai_afektif);
				if ($this->nilai_komulatif->Exportable) $Doc->ExportCaption($this->nilai_komulatif);
				if ($this->nilai_psikomotorik->Exportable) $Doc->ExportCaption($this->nilai_psikomotorik);
				if ($this->nilai_rata2Drata->Exportable) $Doc->ExportCaption($this->nilai_rata2Drata);
				if ($this->nilai_id->Exportable) $Doc->ExportCaption($this->nilai_id);
			}
			$Doc->EndExportRow();
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
				if ($ExportPageType == "view") {
					if ($this->detail_nilai_id->Exportable) $Doc->ExportField($this->detail_nilai_id);
					if ($this->id_mapel->Exportable) $Doc->ExportField($this->id_mapel);
					if ($this->nilai_afektif->Exportable) $Doc->ExportField($this->nilai_afektif);
					if ($this->nilai_komulatif->Exportable) $Doc->ExportField($this->nilai_komulatif);
					if ($this->nilai_psikomotorik->Exportable) $Doc->ExportField($this->nilai_psikomotorik);
					if ($this->nilai_rata2Drata->Exportable) $Doc->ExportField($this->nilai_rata2Drata);
				} else {
					if ($this->detail_nilai_id->Exportable) $Doc->ExportField($this->detail_nilai_id);
					if ($this->id_mapel->Exportable) $Doc->ExportField($this->id_mapel);
					if ($this->nilai_afektif->Exportable) $Doc->ExportField($this->nilai_afektif);
					if ($this->nilai_komulatif->Exportable) $Doc->ExportField($this->nilai_komulatif);
					if ($this->nilai_psikomotorik->Exportable) $Doc->ExportField($this->nilai_psikomotorik);
					if ($this->nilai_rata2Drata->Exportable) $Doc->ExportField($this->nilai_rata2Drata);
					if ($this->nilai_id->Exportable) $Doc->ExportField($this->nilai_id);
				}
				$Doc->EndExportRow();
			}
			$Recordset->MoveNext();
		}
		$Doc->ExportTableFooter();
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here	
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here	
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here	
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		// Enter your code here
	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>); 

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
