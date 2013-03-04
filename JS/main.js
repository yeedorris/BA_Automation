/******************************************************************************
 *Below this are global function calls
 ******************************************************************************/

$(function() {
    $("#tabs").tabs();
});

$(function() {
    $('#categories').change(function(){
        getSubCategoriesData(this.value);
    })
});

$(function(){
    $('.date').datepicker({
        minDate: 0
    });
    var todaysDate = getTodaysDate();
    $('.date').val(todaysDate);
});

$(function() {
    $( "input[type=button], button" )
      .button()
      .click(function( event ) {
        event.preventDefault();
      });
});

$(function(){
    $('#dataForm').submit(function(event) {
        event.preventDefault();
        saveDealData();
    })
})

var salesCount = 0;
var is_chrome = navigator.userAgent.indexOf('Chrome') > -1;
var is_explorer = navigator.userAgent.indexOf('MSIE') > -1;
var is_firefox = navigator.userAgent.indexOf('Firefox') > -1;
var is_safari = navigator.userAgent.indexOf("Safari") > -1;
var is_Opera = navigator.userAgent.indexOf("Presto") > -1;
/******************************************************************************
 *Below this are AJAX calls
 ******************************************************************************/


function getAllData() {
    getAllCategories();
    getAllMerchants();
    getAllStatusCodes();
    getAllSalesPeople();
    getAllContracts();
    getSigningAuths();
}

function getAllCategories() {
   $.ajax ({
        url: 'php/data.php',
        dataType: 'json',
        data: {
            value: 'allData'
        },
        type: 'get',
        beforeSend: function() {
            $('#categories').empty();  
        },
        success: function(data){
            $('#categories').empty();
            $('#categories').append("<option value=''>--Select--</option>");
            for (var i=0 ; i<data.length ; i++) {
                $('#categories').append("<option value='"+data[i].categoryid+"'>"+data[i].categoryname+"</option>");
            }
        }
    });
}

function getAllMerchants() {
    $.ajax({
        url: 'php/data.php',
        dataType: 'json',
        data: {
            value: 'allMerchantData'
        },
        type: 'get',
        beforeSend: function() {
            $('#merchants').empty();
        },
        success: function(data) {
            $('#merchants').empty();
            $('#merchants').append("<option value=''>--Select--</option>");
            for (var i=0 ; i<data.length ; i++) {
                $('#merchants').append("<option value='"+data[i].merchantid+"'>"+data[i].merchantname+"</option>");
            }
            $('#merchants').combobox(); //HAHA.. figure this one out.. 
            $('.ui-combobox-input').attr("required", "required");
        }
    });
}

function getAllStatusCodes() {
    $.ajax({
        url: 'php/data.php',
        dataType: 'json',
        data: {
            value: "statusData"
        },
        type: 'get',
        success: function(data) {
            for (var i=0 ; i<data.length ; i++) {
                $('#status').append("<option value='"+data[i].statuscodeid+"'>"+data[i].statuscode+"</option>");
            }
        }
    });
}

function getAllContracts() {
    var contracts = [];
    $.ajax({
        url: 'php/data.php',
        dataType: 'json',
        data: {
            value: 'contractData'
        },
        type: 'get',
        success: function (data) {
            for (var i=0 ; i<data.length ; i++) {
                contracts[i] = data[i].sfcontract;
            }
            $('#sfContract').autocomplete({
                source: contracts
            });
        }
    });
}

function getAllSalesPeople() {
    var sales = [];
    var salesId = [];
    var salesArray;
    $.ajax({
        url: 'php/data.php',
        dataType: 'json',
        data: {
            value: 'allSalesData'
        },
        type: 'get',
        success: function (data) {
            salesArray = Create2DArray(data.length);
            for (var i=0 ; i<data.length ; i++) {
                sales[i] = data[i].name;
                salesId[i] = data[i].salesid;
                salesArray[i][0] = data[i].salesid;
                salesArray[i][1] = data[i].name;
            }
            $('#sales').bind( "keydown", function( event ) {
                if ( event.keyCode === $.ui.keyCode.TAB &&
                    $( this ).data( "autocomplete" ).menu.active ) {
                    event.preventDefault();
                }
            }).autocomplete({
                minLength: 0,
                source: function( request, response ) {
                    // delegate back to autocomplete, but extract the last term
                    response( $.ui.autocomplete.filter(
                        sales, extractLast( request.term ) ) );
                },
                focus: function() {
                    // prevent value inserted on focus
                    return false;
                },
                select: function( event, ui ) {
                    var terms = split( this.value );
                    // remove the current input
                    terms.pop();
                    // add the selected item
                    terms.push( ui.item.value );
                    // add placeholder to get the comma-and-space at the end
                    terms.push( "" );
                    this.value = terms.join( ", " );
                    return false;
                }
            });
        }
    });
}

function getSigningAuths() {
    $.ajax({
        url: 'php/data.php',
        dataType: 'json',
        data: {
            value: 'signedBy'
        },
        type: 'get',
        success: function(data) {
            $('#signedBy').empty();
            $('#signedBy').append("<option value=''>--Select--</option>");
            for (var i=0 ; i<data.length ; i++) {
                $('#signedBy').append("<option value='"+data[i].signedid+"'>"+data[i].signedname+"</option>");
            }
        }
    });
}

function getSubCategoriesData(value) {
    $.ajax({
        url: 'php/data.php',
        dataType: 'json',
        data: {
            value: 'subcatData',
            id: value
        },
        type: 'get',
        beforeSend: function(){
            $('#subCategories').empty();
        },
        success: function(data) {
            $('#subCategories').empty();
            $('#subCategories').append("<option value=''>--Select--</option>");
            for (var i=0 ; i<data.length ; i++) {
                $('#subCategories').append("<option value='"+data[i].subcategoryid+"'>"+data[i].subcategoryname+"</option>");
            }
            $('#subCategories').combobox();
            $('.ui-combobox-input').attr("required", "required");
        }
    });
}

function saveMerchantData() {
    $.ajax({
       url: 'php/data.php',
       dataType: 'json',
       data: {
           value: 'saveMerchantData',
           data: $('#addMerchantName').val()+'æ'+$('#addMerchantAddress').val()
       },
       type: 'get',
       success: function() {
           getAllMerchants();
           $('#addMerchantDialog').dialog('close');
       }
    });
}


function logout() {
   window.location='/BA_AUtomation/login.php';
   $.ajax({
      url: 'php/data.php',
      dataType: 'json',
      data: {
          value: 'logout'
      },
      type: 'get'
   });
}

function saveDealData() {
    //if(confirm("Please confirm to save data")) {
        var dealData = getDealData();
        $.ajax ({
            url: 'php/data.php',
            dataType: 'json',
            data: {
                value: 'saveData',
                data: dealData
            },
            type: 'post',
            success: function() {
                var todaysDate = getTodaysDate();
                $('#dataForm')[0].reset();
                $('.date').val(todaysDate);
                $('#subCategories').combobox('destroy');
                $('#subCategories').empty();
                $('#DCMultiplier').addClass("invisible");
            }
        }); 
    //}
}

function getAllDealsData() {
    $("#dealData").flexigrid({
	url: 'php/data.php?value=getAllDealData',
	dataType: 'json',
	colModel : [
		{display: 'ID', name : 'dealid', width : 20, sortable : true, align: 'center', hide: true},
		{display: 'Description', name : 'description', width : 180, sortable : true, align: 'left'},
		{display: 'Input Date', name : 'inputdate', width : 60, sortable : true, align: 'left'},
		{display: 'Status', name : 'statuscode', width : 40, sortable : true, align: 'left'},
		{display: 'DC', name : 'dc', width : 15, sortable : true, align: 'left'},
                {display: 'TMC', name : 'tmc', width : 20, sortable : true, align: 'left'},
                {display: 'Category', name : 'categoryname', width : 40, sortable : true, align: 'left'},
                {display: 'Sub-Category', name : 'subcategoryname', width : 60, sortable : true, align: 'left'},
                {display: 'Signed By', name : 'signedname', width : 40, sortable : true, align: 'left'},
                {display: 'Commission', name : 'comm', width : 40, sortable : true, align: 'left'},
                {display: 'New', name : 'newdeal', width : 20, sortable : true, align: 'left'},
                {display: 'fPrice', name : 'forecastedprice', width : 40, sortable : true, align: 'left'},
                {display: 'fGP', name : 'forecastedgp', width : 30, sortable : true, align: 'left'},
                {display: 'fPT', name : 'forecastpt', width : 30, sortable : true, align: 'left'},
                {display: 'aPT', name : 'accuratept', width : 30, sortable : true, align: 'left'},
                {display: 'aGP', name : 'accurategp', width : 50, sortable : true, align: 'left'},
                {display: 'Featured Date', name : 'featuredate', width : 60, sortable : true, align: 'left'},
                {display: 'Contract', name : 'sfcontract', width : 70, sortable : true, align: 'left'},
                {display: 'Remarks', name : 'remarks', width : 70, sortable : true, align: 'left'},
                {display: 'Request Date', name : 'requestdate', width : 60, sortable : true, align: 'left'},
                {display: 'Tentative Date', name : 'tenativedate', width : 60, sortable : true, align: 'left'},
                {display: 'QA', name : 'QA', width : 10, sortable : true, align: 'left'},
                {display: 'Scheduled Date', name : 'schedulesdate', width : 60, sortable : true, align: 'left'},
                {display: 'Follow Up', name : 'postfollowup', width : 20, sortable : true, align: 'left'},
                {display: 'Merchant', name : 'merchantname', width : 60, sortable : true, align: 'left'},
                {display: 'Remarks DC', name : 'remarksdc', width : 60, sortable : true, align: 'left'},
                {display: 'Add Remarks', name : 'addremarks', width : 60, sortable : true, align: 'left'},
                {display: 'Old Merchant', name : 'oldmarchant', width : 60, sortable : true, align: 'left'},
		],
        buttons : [
                {name: 'Export', bclass:'export', onpress: saveAsCSV}
        ],
	searchitems : [
		{display: 'Category', name : 'categoryname', isdefault: true},
		{display: 'Status', name : 'statuscode'},
                {display: 'Sub Category', name: 'subcategoryname'},
                {display: 'Merchant', name: 'merchantname'}
		],
	sortname: "inputdate",
	sortorder: "asc",
	usepager: true,
	title: 'Deal Bank',
	useRp: true,
	rp: 30,
	showTableToggleBtn: false,
	width: 1180,
	height: 720
    });   
}

function saveAsCSV() {
    location.href = "php/data.php?value=saveAsCSV";
}

function validateSfContract(SfContract) {
    var data = document.getElementById(SfContract).value;
    if (!isEmpty(SfContract)) {
        $.ajax({
            url: 'php/data.php',
            dataType: 'text',
            data: {
                value: 'validateContract',
                data: data
            },
            type: 'get',
            success: function(data) {
                if (data != '1') {
                    alert('Invalid sales contract');
                    document.getElementById(SfContract).value = "";
                    document.getElementById(SfContract).focus();
                }
            }
        });
    }
}

function validateSalesNames(salesName) {
    var data = document.getElementById(salesName).value;
    if (!isEmpty(salesName)) {
        $.ajax({
            url: 'php/data.php',
            dataType: 'text',
            data: {
                value: 'validateSalesNames',
                data: data
            },
            type: 'get',
            success: function (data) {
                if (data == -1) {
                    alert('Sales rep name not found');
                    document.getElementById(salesName).value = "";
                    document.getElementById(salesName).focus();
                }  
            }
        });
    }
}
/******************************************************************************
 *Below this are Dialog popups
 ******************************************************************************/

function test() {
    alert('Hello World!');
}

function showAddMerchantDialog() {
    $('#addMerchantDialog').dialog({
        resizable: false,
        modal: true,
        buttons: {
            "Save": function() {
                saveMerchantData();
            },
            "Cancel": function() {
                $(this).dialog('close');
            }
        }
    })
}
/******************************************************************************
 *Below this are caluclating functions
 ******************************************************************************/
function calculateFGP() {
    var fPrice = document.getElementById('fPrice').value;
    var fQty = document.getElementById('fQty').value;
    var comm = document.getElementById('comm').value;
    
    if (isEmpty('fPrice')) {
        alert("Helloooo! you missed Forecasted Price");
        return false;
    }
    else {
        fPrice = accounting.unformat(fPrice);
    }
    
    if (isEmpty('fQty')) {
        alert("Sorry mate.. need forcasted quantity!");
        return false;
    } else { 
        fQty = accounting.unformat(fQty);
    }
    
    if (isEmpty('comm')) {
        alert("Duh uh! you missed commission");
        return false;
    } else {
        comm = accounting.unformat(comm);
    }
    var calculatedGP = (fPrice*fQty)*(comm/100);
    document.getElementById("fGP").value = accounting.formatMoney(calculatedGP, "HK$");
    document.getElementById("fPt").value = getForeCastedPoints(accounting.unformat(document.getElementById("fGP").value));
    setForeCastedPointsWithDealCommitment();
    setRemarks();
}

function getForeCastedPoints(value) {
    var fGP = value;
    if (fGP <10000)
        return 0;
    else if (fGP >= 10000 && fGP < 20000)
        return 1;
    else if (fGP >= 20000 && fGP < 30000)
        return 2;
    else if (fGP >= 30000 && fGP < 40000)
        return 3;
    else if (fGP >= 40000 && fGP < 50000)
        return 4;
    else if (fGP >= 50000 && fGP < 60000)
        return 5;
    else if (fGP >= 60000 && fGP < 70000)
        return 6;
    else if (fGP >= 70000 && fGP < 80000)
        return 7;
    else if (fGP >= 80000 && fGP < 90000)
        return 8;
    else if (fGP >= 90000 && fGP < 100000)
        return 9;
    else if (fGP > 100000)
        return 10;
}

function setForeCastedPointsWithDealCommitment() {
    if($('#DC').is(':checked')) {
        var DCMultiplier = document.getElementById('DCMultiplier').value;
        if (isEmpty('DCMultiplier')) {
            DCMultiplier = 2.0;
        }
        document.getElementById("fPTDC").value = parseInt(document.getElementById("fPt").value)*DCMultiplier;
    }
    else
        document.getElementById("fPTDC").value = "";

//    $('#isAgeSelected').click(function () {
//    $("#txtAge").toggle(this.checked);
//    });
}

function setRemarks() {
    var date = getTodaysDate();
    var fPt = document.getElementById('fPt').value;
    var remarkString = date+'-DC('+fPt+'*2) = '+ eval(fPt*2);
    $('#remarks').val(remarkString);
}

function addSalesRep() {
    var tempSalesCount = salesCount++;
    var delButton = $("<button></button>");
    delButton.html("-");
    delButton.attr("id", "delSales"+tempSalesCount);
    delButton.click(function(event){
        event.preventDefault();
        $('#sales'+tempSalesCount).remove();
        $('#delSales'+tempSalesCount).remove();
    }).button();
    var enclosingDiv = $('<div></div>').css("clear", "both");
    $('#salesColumn').append(enclosingDiv).append($('#sales').clone().attr('id', 'sales'+tempSalesCount))
    .append(delButton);
}

function toggleDCMultiplier() {
    $('#DCMultiplier').toggleClass("invisible");
}

function calculateAGp(value) {
    document.getElementById('APt').value = getForeCastedPoints(value);
}

function setValidationRules() {
   var statusCode = $('#status').val();
   if (statusCode == 2) {
       $('#signedBy').removeAttr('required');
       $('#fPrice').removeAttr('required');
       $('#fQty').removeAttr('required');
   } else {
       $('#signedBy').attr('required', 'required');
       $('#fPrice').attr('required', 'required');
       $('#fQty').attr('required', 'required');
   }
}

function getDealData() {
    var data = {
        description: $('#description').val(),
        inputDate: $('#inputDate').val(),
        status: $('#status').val(),
        categories: $('#categories').val(),
        subCategories: $('#subCategories').val(),
        DC: $('#DC').val(),
        TMC: $('#TMC').val(),
        QA: $('#QA').val(),
        merchants: $('#merchants').val(),
        comm: accounting.unformat($('#comm').val()),
        newMerchant: $('input:radio[name=merchant]:checked').val(),
        days: $('#days').val(),
        signedBy: $('#signedBy').val(),
        fPrice: accounting.unformat($('#fPrice').val()),
        sales: $('#sales').val(),
        fQty: $('#fQty').val(),
        fGP: accounting.unformat($('#fGP').val()),
        fPt: $('#fPt').val(),
        fPTDC: $('#fPTDC').val(),
        AGp: accounting.unformat($('#AGp').val()),
        APt: $('#APt').val(),
        tentativeDate: $('#tentativeDate').val(),
        featureDate: $('#featureDate').val(),
        sfContract: $('#sfContract').val(),
        remarks: $('#remarks').val(),
        remarks2: $('#remarks2').val(),
        remarks3: $('#remarks3').val()
    };
    return data;
}

/******************************************************************************
Below this are helper functions
******************************************************************************/

/*
 * Formats the values to the specified type currently supports
 * currency: HK$ 12,345,678.00
 * percentage: 34.00%
 * @param type (type of formatting required)
 * @param elementId id of field to be formatted
 */

function formatValue(type, elementId) {
    var value = document.getElementById(elementId).value;
    switch (type) {
        case 'currency':
            if(validate('float', elementId)) {
                document.getElementById(elementId).value = accounting.formatMoney(value, "HK$");
            }
            break;
        case 'percentage':
            if(value > 100) {
                alert("Woah! you managed to get commission more than 100%, 教教我呀，師傅!");
                document.getElementById(elementId).value = "";
                return false;
            }
            if(validate('float', elementId)) {
                document.getElementById(elementId).value = accounting.formatMoney(value, {
                    symbol: "%",  
                    format: "%v %s"
                });
            }
            break;
        default:
            break;
    }
}

/*
 * Validates if a value is of certain type or not, but blank values are okay
 * Currently supported types (integer, float, email)
 * @param type type of value to be validated (String)
 * @param elementId id of element whose value to be validates
 */
function validate(type, elementId) {
    var isValid = true;
    var value = document.getElementById(elementId).value;
    switch (type) {
        case 'integer':
            if (isNaN(parseInt(value)))
                isValid = false;
            break;
        case 'float':
            if(isNaN(parseFloat(value)))
                isValid = false;
        case 'email':
            if (is_safari || is_explorer) { //skipping FIrefox and Chrome because they support HTML5 email validation
                if (!isValidEmail(value))
                    isValid = false;
            }
        default:
            break;
    }
    if (isEmpty(elementId))
        isValid = true;
    if (!isValid) {
        alert('Please provide a valid value');
        document.getElementById(elementId).value = "";
        return false;
    } else if (isValid) {
        return true;
    }
}

/*
 * Checks if an element is empty
 * @param elementId
 */

function isEmpty(elementId) {
    var value = document.getElementById(elementId).value.replace(/\s+/g, ' ');
    if (value == "")
        return true;
}
/*
 * This function returns todays date in mm/dd/yyyy format 
 */
function getTodaysDate() {
    var d = new Date();
    var month = d.getMonth() + 1;
    var day = d.getDate();
    var year = d.getFullYear();
    var date = ((''+month).length<2?'0':'')+month+'/'+((''+day).length<2?'0':'')+day+"/"+year;
    return date;
}

/*
 * Checks if a email is valid
 * @param email (String)
 */

function isValidEmail(email) { 
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

function split( val ) {
    return val.split( /,\s*/ );
}

function extractLast( term ) {
    return split( term ).pop();
}

/*
 * Creates a 2D JS array
 * @param rows number of rows in array
 */

function Create2DArray(rows) {
    var arr = [];
    for (var i=0;i<rows;i++) {
        arr[i] = [];
    }
    return arr;
}