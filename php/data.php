<?php

$host = "127.0.0.1";
$user = "root";
$password = "password";
$dbname = "BA";
mb_internal_encoding("UTF-8");
mb_http_output("UTF-8");
$con = mysql_connect($host, $user, $password);
if (!$con) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db($dbname, $con);
mysql_query("SET NAMES 'utf8'", $con);

if (isset($_REQUEST['is_ajax'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    validateUser($username, $password);
}
$value = '';
if (isset($_GET['value']))
    $value = $_GET['value'];
if (isset($_POST['value']))
    $value = $_POST['value'];

if (isset($_GET['id']))
    $id = $_GET['id'];

if (isset($_GET['data']))
    $data = $_GET['data'];
if (isset($_POST['data']))
    $data = $_POST['data'];


switch ($value) {
    case 'allData' :
        getAllData();
        break;
    case 'subcatData':
        getSubCategoryData($id);
        break;
    case 'allMerchantData':
        getMerchantData();
        break;
    case 'statusData':
        getStatusCodes();
        break;
    case 'contractData':
        getContracts();
        break;
    case 'allSalesData':
        getSalesData();
        break;
    case 'saveMerchantData':
        saveMerchantData($data);
        break;
    case 'logout':
        logout();
        break;
    case 'saveData':
        saveData($data);
        break;
    case 'signedBy':
        getSigningAuth();
        break;
    case 'getAllDealData':
        getAllDealRecord();
        break;
    case 'saveAsCSV':
        saveAsCSV();
        break;
    case 'validateContract':
        validateContract($data);
        break;
    case 'validateSalesNames':
        validateSalesNames($data);
        break;
    default :
        break;
}

function getAllData() {
    $sql = "SELECT categoryid, categoryname FROM CATEGORY";
    $result = mysql_query($sql);
    $rows = array();
    while ($row = mysql_fetch_array($result)) {
        array_push($rows, $row);
    }
    echo json_encode($rows);
    mysql_close();
}

function getSubCategoryData($id) {
    $query = 'SELECT subcategoryid, subcategoryname FROM SUBCATEGORY WHERE categoryid = ' . $id . ' AND subcategoryname IS NOT NULL';
    $result = mysql_query($query);
    $rows = array();
    while ($row = mysql_fetch_array($result)) {
        array_push($rows, $row);
    }
    echo json_encode($rows);
    mysql_close();
}

function getMerchantData() {
    $sql = 'SELECT merchantid, merchantname FROM merchant';
    $result = mysql_query($sql);
    $rows = array();
    while ($row = mysql_fetch_array($result)) {
        array_push($rows, $row);
    }
    echo json_encode($rows);
    mysql_close();
}

function getStatusCodes() {
    $query = "SELECT statuscodeid, statuscode FROM statuscode";
    $result = mysql_query($query);
    $rows = array();
    while ($row = mysql_fetch_array($result)) {
        array_push($rows, $row);
    }
    echo json_encode($rows);
    mysql_close();
}

function getContracts() {
    $query = "SELECT sfcontract FROM contract";
    $result = mysql_query($query);
    $rows = array();
    while ($row = mysql_fetch_array($result)) {
        array_push($rows, $row);
    }
    echo json_encode($rows);
    mysql_close();
}

function getSalesData() {
    $query = "SELECT salesid, CONCAT(firstname, ' ', lastname) AS name FROM sales";
    $result = mysql_query($query);
    $rows = array();
    while ($row = mysql_fetch_array($result)) {
        array_push($rows, $row);
    }
    echo json_encode($rows);
    mysql_close();
}

function saveMerchantData($data) {
    $values = explode('æ', $data);
    $query = "INSERT INTO merchant (merchantname, address, updeatedate, updateby, createdate, createby) VALUES ('$values[0]','$values[1]', SYSDATE(), 1, SYSDATE(), 1)";
    $result = mysql_query($query);
    if (mysql_affected_rows() <= 0 || !$result) {
        header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
        echo 'Could not save data, please contact your Sys-admin';
    }
}

function validateUser($username, $password) {
    $query = "SELECT 1 FROM user WHERE username='$username' AND password='$password'";
    $result = mysql_query($query);
    if (mysql_num_rows($result) == 1 && $result) {
        session_start();
        $_SESSION['username'] = $username;
        $_SESSION['userid'] = getUserId($username);
        echo('success');
    } else {
        header('location: /BA_Automation/login.php');
        echo('Bad');
    }
}

function getUserId($username) {
    $query = "SELECT userid FROM user WHERE username='" . $username . "'";
    $result = mysql_query($query);
    while ($row = mysql_fetch_array($result)) {
        return $row['userid'];
    }
}

function logout() {
    session_start();
    session_unset();
    session_destroy();
    header('location: /BA_Automation/login.php');
    exit();
}

function getSigningAuth() {
    $query = "SELECT signedid, signedname FROM signed";
    $result = mysql_query($query);
    $rows = array();
    while ($row = mysql_fetch_array($result)) {
        array_push($rows, $row);
    }
    echo json_encode($rows);
    mysql_close();
}

function saveData($data) {
    $description = $data['description'];
    $inputDate = $data['inputDate'];
    $status = $data['status'];
    $categories = $data['categories'];
    $subCategories = $data['subCategories'];
    $DC = $data['DC'];
    $TMC = $data['TMC'];
    $QA = $data['QA'];
    $merchants = $data['merchants'];
    $comm = $data['comm'];
    $newMerchant = $data['newMerchant'];
    $days = $data['days'];
    $signedBy = $data['signedBy'];
    $fPrice = $data['fPrice'];
    $fQty = $data['fQty'];
    $fGp = $data['fGP'];
    $fPt = $data['fPt'];
    $fPTDC = $data['fPTDC'];
    $AGp = $data['AGp'];
    $APt = $data['APt'];
    $tentativeDate = $data['tentativeDate'];
    $featureDate = $data['featureDate'];
    $sfContract = $data['sfContract'];
    $remarks = $data['remarks'];
    $remarks2 = $data['remarks2'];
    $remarks3 = $data['remarks3'];
    $sales = $data['sales'];
    $salesArray = [];
    //correcting sales data
    if (substr($sales, -2) == ', ') {
        $sales = substr($sales, 0, -2);
        $salesArray = explode(", ", $sales);
    } else {
        $salesArray = explode(", ", $sales);
    }

    $query = "INSERT INTO dealbank (description, inputdate, statuscodeid, DC, TMC, 
                categoryid, subcategoryid, signedid, comm, 
                newdeal, forecastedprice, forecastedqty, forecastedgp, 
                forecastpt, accuratept, accurategp, featuredate, 
                sfcontract, remarks, locatedealcommitmentid, requestdate, 
                tenativedate, QA, schedulesdate, postfollowup, merchantid, 
                remarksDC, addremarks, createdate, updatedate, oldmarchant) VALUES ('$description', str_to_date('$inputDate','%m/%d/%Y') , $status, 
                '$DC', '$TMC', '$categories', '$subCategories', '$signedBy', '$comm', '$newMerchant', '$fPrice', '$fQty', 
                '$fGp', '$fPt', '$APt', '$AGp', str_to_date('$featureDate','%m/%d/%Y'), '$sfContract', '$fPTDC', '', SYSDATE(), 
                str_to_date('$tentativeDate','%m/%d/%Y'), '$QA', SYSDATE(), '$days', '$merchants', '$remarks', '$remarks2/$remarks3', SYSDATE(), SYSDATE(), 'N')";

    $result = mysql_query($query);
    if (mysql_affected_rows() <= 0 || !$result) {
        header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
        ob_clean();
        echo mysql_error() . '';
    } else {
        populateSalesTable(mysql_insert_id(), $salesArray);
    }
}

function populateSalesTable($dealId, $salesNameArray) {
    $salesId = getSalesIds($salesNameArray);
    $query = "INSERT INTO salerecord (dealid, saleid, startdate, enddate) VALUES ";
    for ($i = 0; $i < count($salesId); $i++) {
        $query .= "($dealId, $salesId[$i], '', ''), ";
    }
    $query = substr($query, 0, -2);
    $result = mysql_query($query);
    if (mysql_affected_rows() <= 0 || !$result) {
        header($_SERVER['SERVER_PROTOCOL'] . '500 Internal server error', true, 500);
        ob_clean();
        echo mysql_error() . '';
        exit();
    }
}

function getSalesIds($salesNameArray) {
    $query = "select salesid from sales where concat(firstname, ' ', lastname) in (";
    for ($index = 0; $index < count($salesNameArray); $index++) {
        $query.="'" . $salesNameArray[$index] . "',";
    }
    $query = substr($query, 0, -1);
    $query .= ")";
    $result = mysql_query($query);
    $salesId = array();
    while ($row = mysql_fetch_array($result)) {
        array_push($salesId, $row[0]);
    }
    return $salesId;
}

function validateContract($data) {
    $query = 'SELECT 1 FROM contract WHERE sfcontract = "'.$data.'"';
    $result = mysql_query($query);
    $row = mysql_fetch_row($result);
    echo $row[0];
}

function validateSalesNames($sales) {
    $salesArray = [];
    if (substr($sales, -2) == ', ') {
        $sales = substr($sales, 0, -2);
        $salesArray = explode(", ", $sales);
    } else {
        $salesArray = explode(", ", $sales);
    }
    $salesId = getSalesIds($salesArray);
    if(count($salesId) != count($salesArray))
        echo '-1';
}

function getAllDealRecord() {
    $page = 1; // The current page
    $sortname = 'inputdate';  // Sort column
    $sortorder = 'asc';  // Sort order
    $qtype = '';  // Search column
    
    // Get posted data
    if (isset($_POST['page'])) {
        $page = mysql_real_escape_string($_POST['page']);
    }
    if (isset($_POST['sortname'])) {
        $sortname = mysql_real_escape_string($_POST['sortname']);
    }
    if (isset($_POST['sortorder'])) {
        $sortorder = mysql_real_escape_string($_POST['sortorder']);
    }
    if (isset($_POST['qtype'])) {
        $qtype = mysql_real_escape_string($_POST['qtype']);
    }
    if (isset($_POST['query'])) {
        $query = mysql_real_escape_string($_POST['query']);
    }
    if (isset($_POST['rp'])) {
        $rp = mysql_real_escape_string($_POST['rp']);
    }
    // Setup sort and search SQL using posted data
    $sortSql = "order by $sortname $sortorder";
    $searchSql = ($qtype != '' && $query != '') ? "where $qtype = '$query'" : '';
    
    // Setup paging SQL
    $pageStart = ($page-1)*$rp;
    $limitSql = "limit $pageStart, $rp";

    $countQuery = "SELECT count(*) FROM dealbank $searchSql";
    $countResult = mysql_query($countQuery);
    $row1 = mysql_fetch_array($countResult);
    $total = $row1[0];
    
    
    $query = "SELECT distinct dl.dealid, dl.description, dl.inputdate, st.statuscode, dl.dc, dl.tmc, ct.categoryname, sct.subcategoryname, sg.signedname, dl.comm, 
            dl.newdeal, dl.forecastedprice, dl.forecastedgp, dl.forecastpt, dl.accuratept, dl.accurategp, dl.accurategp, dl.featuredate, dl.sfcontract, dl.remarks, 
            dl.requestdate, dl.tenativedate, dl.QA, dl.schedulesdate, dl.postfollowup, mr.merchantname, dl.remarksdc, dl.addremarks, dl.oldmarchant from dealbank dl 
            left join statuscode st on dl.statuscodeid = st.statuscodeid  
            left join category ct on dl.categoryid = ct.categoryid 
            left join subcategory sct on dl.subcategoryid = sct.subcategoryid 
            left join signed sg on dl.signedid = sg.signedid 
            left join merchant mr on dl.merchantid = mr.merchantid $searchSql $sortSql $limitSql";

    $result = mysql_query($query);
    $rows = array();
    $rows['page'] = $page;
    $rows['total'] = $total;
    $rows['rows'] = array();
    setlocale(LC_MONETARY, "zh_HK");
    while ($row = mysql_fetch_assoc($result)) {
        $rows['rows'][] = array(
            'dealid' => $row['dealid'],
            'cell' => array(
                'dealid'            => $row['dealid'],
                'description'       => $row['description'],
                'inputdate'         => $row['inputdate'],
                'statuscode'        => $row['statuscode'],
                'dc'                => $row['dc'],
                'tmc'               => $row['tmc'],
                'categoryname'      => $row['categoryname'],
                'subcategoryname'   => $row['subcategoryname'],
                'signedname'        => $row['signedname'],
                'comm'              => $row['comm'].'%',
                'newdeal'           => $row['newdeal'],
                'forecastedprice'   => money_format("%.2n", floatval($row['forecastedprice'])),
                'forecastedgp'      => money_format("%.2n", floatval($row['forecastedgp'])),
                'forecastpt'        => $row['forecastpt'],
                'accuratept'        => $row['accuratept'],
                'accurategp'        => money_format("%.2n", floatval($row['accurategp'])),
                'featuredate'       => $row['featuredate'],
                'sfcontract'        => $row['sfcontract'],
                'remarks'           => $row['remarks'],
                'requestdate'       => $row['requestdate'],
                'tenativedate'      => $row['tenativedate'],
                'QA'                => $row['QA'],
                'schedulesdate'     => $row['schedulesdate'],
                'postfollowup'      => $row['postfollowup'],
                'merchantname'      => $row['merchantname'],
                'remarksdc'         => $row['remarksdc'],
                'addremarks'        => $row['addremarks'],
                'oldmarchant'       => $row['oldmarchant'],
                )
        );
    }
    echo json_encode($rows);
    mysql_close();
}

function saveAsCSV() {
    $headerQuery = "select column_name from information_schema.columns where table_name = 'dealbank' order by ordinal_position";
    $headerString = "";
    $headerResult = mysql_query($headerQuery);
    while ($row = mysql_fetch_array($headerResult)) {
        $headerString .= '"'.$row[0].'",';
    }
    $csv = substr($headerString, 0, -1)."\n";
    
    $query = "SELECT distinct dl.dealid, dl.description, dl.inputdate, st.statuscode, dl.dc, dl.tmc, ct.categoryname, sct.subcategoryname, sg.signedname, dl.comm, 
            dl.newdeal, dl.forecastedprice, dl.forecastedgp, dl.forecastpt, dl.accuratept, dl.accurategp, dl.accurategp, dl.featuredate, dl.sfcontract, dl.remarks, 
            dl.requestdate, dl.tenativedate, dl.QA, dl.schedulesdate, dl.postfollowup, mr.merchantname, dl.remarksdc, dl.addremarks, dl.oldmarchant from dealbank dl 
            left join statuscode st on dl.statuscodeid = st.statuscodeid  
            left join category ct on dl.categoryid = ct.categoryid 
            left join subcategory sct on dl.subcategoryid = sct.subcategoryid 
            left join signed sg on dl.signedid = sg.signedid 
            left join merchant mr on dl.merchantid = mr.merchantid";
    setlocale(LC_MONETARY, "zh_HK");
    $result = mysql_query($query);
    while ($row1 = mysql_fetch_assoc($result)) {
        $csv .= '"' . str_replace('"', '""', $row1['dealid']) . '",';
        $csv .= '"' . str_replace('"', '""', $row1['description']) . '",';
        $csv .= '"' . str_replace('"', '""', $row1['inputdate']) . '",';
        $csv .= '"' . str_replace('"', '""', $row1['statuscode']) . '",';
        $csv .= '"' . str_replace('"', '""', $row1['dc']) . '",';
        $csv .= '"' . str_replace('"', '""', $row1['tmc']) . '",';
        $csv .= '"' . str_replace('"', '""', $row1['categoryname']) . '",';
        $csv .= '"' . str_replace('"', '""', $row1['subcategoryname']) . '",';
        $csv .= '"' . str_replace('"', '""', $row1['signedname']) . '",';
        $csv .= '"' . str_replace('"', '""', $row1['comm'].'%') . '",';
        $csv .= '"' . str_replace('"', '""', $row1['newdeal']) . '",';
        $csv .= '"' . str_replace('"', '""', money_format("%.2n", floatval($row1['forecastedprice']))) . '",';
        $csv .= '"' . str_replace('"', '""', money_format("%.2n", floatval($row1['forecastedgp']))) . '",';
        $csv .= '"' . str_replace('"', '""', $row1['forecastpt']) . '",';
        $csv .= '"' . str_replace('"', '""', $row1['accuratept']) . '",';
        $csv .= '"' . str_replace('"', '""', money_format("%.2n", floatval($row1['accurategp']))) . '",';
        $csv .= '"' . str_replace('"', '""', $row1['featuredate']) . '",';
        $csv .= '"' . str_replace('"', '""', $row1['sfcontract']) . '",';
        $csv .= '"' . str_replace('"', '""', $row1['remarks']) . '",';
        $csv .= '"' . str_replace('"', '""', $row1['requestdate']) . '",';
        $csv .= '"' . str_replace('"', '""', $row1['tenativedate']) . '",';
        $csv .= '"' . str_replace('"', '""', $row1['QA']) . '",';
        $csv .= '"' . str_replace('"', '""', $row1['schedulesdate']) . '",';
        $csv .= '"' . str_replace('"', '""', $row1['postfollowup']) . '",';
        $csv .= '"' . str_replace('"', '""', $row1['merchantname']) . '",';
        $csv .= '"' . str_replace('"', '""', $row1['remarksdc']) . '",';
        $csv .= '"' . str_replace('"', '""', $row1['addremarks']) . '",';
        $csv .= '"' . str_replace('"', '""', $row1['oldmarchant']) . '"' . "\n";
    }
    mysql_close();
    header('Content-Encoding: UTF-8');
    header("Content-type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=Deal_Bank.csv");
    echo "\xEF\xBB\xBF";
    echo $csv;
}

?>