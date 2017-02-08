<?php
	include_once 'html_open.php';
?>

<?php
	if (isset($_GET['error'])) {
		$error = $_GET['error'];
		echo '<p><div id="loginError" class="alert alert-danger" role="alert">' . $error . '</div></p>';
	}

?> 

<!-- Email Modal -->
<div class="modal draggable fade" id="emailModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog " role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">New Message Template</h4>
			</div>

			<div class="modal-body">
				<form id="SendEmail" action="#" class="form-horizontal">
					<div class="form-group">
						<label for="recipient-email" class="col-sm-2 control-label">Email</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="recipient-email">
						</div>
					</div>
					
					<div class="form-group">
						<label for="recipient-name" class="col-sm-2 control-label">Recipient</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="recipient-name">
						</div>
					</div>
					
					<div class="form-group">
						<label for="message-text" class="col-sm-2 control-label">Message</label>
						<div class="col-sm-10">
							<textarea class="form-control" id="message-text" rows="6"></textarea>
						</div>
					</div>
					
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							<button type="submit" class="btn btn-primary">Send</button>
						</div>
					</div>
				</form>	
			</div>

			<div class="modal-footer">
			</div>
		</div>
	</div>
</div>

<div class="container">	

	<div id="rsErrorDiv" class="alert alert-danger" role="alert" style="display:none">
		Message for immediate attention.
	</div>	

	<?php if (($logged_in)) : ?>

		<?php if (($role != 'Manager' && $role != 'Admin' && $role != 'Super Admin')) : ?>
			<div class="alert alert-danger" role="alert" >
				<p>You don't have enough role to access this page. <BR />
				Please <a href="./logout.php">Logout</a> and Login with proper role. 
				</p>
			</div>
		<?php exit; ?>
		<?php endif; ?>
	
	<!-- Hidden fields -->
	<div id="myemail" hidden><?php echo $_SESSION['email'] ?></div>
	<div id="myscreenname" hidden><?php echo $_SESSION['screen_name'] ?></div>


	<!-- Panel for Search -->
	<div class="panel panel-primary">
		<div class="panel-heading">
			<H4 class="panel-title">Search for Transactions</H4>
		</div>
		<div class="panel-body">
			<form id="Search" method="get" action="#" class="form-horizontal">

				<div class="form-group">				
					<label for="searchcid" class="col-sm-2 control-label">CID</label>
					<div class="col-sm-2">
						<input type="text" class="form-control" id="searchcid" name="searchcid" placeholder="CID" maxlength="20"  >
					</div>
					<label for="firstname" class="col-sm-2 control-label">First Name</label>
					<div class="col-sm-2">
						<input type="text" class="form-control" id="firstname" name="firstname" placeholder="First Name" maxlength="20"  >
					</div>
					<label for="lastname" class="col-sm-2 control-label">Last Name</label>
					<div class="col-sm-2">
						<input type="text" class="form-control" id="lastname" name="lastname" placeholder="Last Name" maxlength="20"  >
					</div>
				</div>

				<div class="form-group">				
					<label for="startdate" class="col-sm-2 control-label">Start Date</label>
					<div class="col-sm-2">
						<input type="text" id="startdate" class="form-control">
					</div>
					<label for="enddate" class="col-sm-2 control-label">End Date</label>
					<div class="col-sm-2">
						<input type="text" id="enddate" class="form-control">
					</div>
					<label for="searchamount" class="col-sm-2 control-label">Amount</label>
					<div class="col-sm-2">
						<input type="text" class="form-control" id="searchamount" 	name="searchamount" placeholder="0.00" maxlength="10" >
					</div>
				</div>
				<div id="errorsearchamount" 	class="alert alert-warning" role="alert" style="display:none">Please enter valid amount.</div>

				<div class="form-group">				
					<label for="searchgiftid" class="col-sm-2 control-label">Gift ID</label>
					<div class="col-sm-2">
						<input type="text" class="form-control" id="searchgiftid" name="searchgiftid" placeholder="Gift ID" maxlength="20"  >
					</div>
					<label for="searchsource" class="col-sm-2 control-label">Source</label>
					<div class="col-sm-2">
						<!-- This is populated dynamically by Javascript	-->
						<select id="searchsource" name="searchsource" class="form-control"></select>
					</div>
					<label for="searchpayment" class="col-sm-2 control-label">Payment</label>
					<div class="col-sm-2">
						<!-- This is populated dynamically by Javascript	-->
						<select id="searchpayment" name="searchpayment" class="form-control"></select>
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<button type="submit" class="btn btn-primary">Search</button>
						<button type="reset"  class="btn btn-default">Clear</button>
					</div>
				</div>
			</form>
		</div>
	</div>	
	
	
	<!-- Panel for displaying Gifts -->
	<div class="panel panel-primary table-responsive">
		<div class="panel-heading">
			<H3 class="panel-title">Search Results</H3>
		</div>

		<div class="panel-body">
			<p><span class="label label-info">Maximum 100 records can display.</span></p>
			<table id="datatable" class="table table-striped table-hover table-condensed" cellspacing="0" width="100%">
				<thead>
				<tr>
					<th>Order #</th>
					<th>Date</th>
					<th>CID</th>
					<th>Txn. Name</th>
					<th>Amount</th>
					<th>Gift ID</th>
					<th>Source</th>
					<th>Type</th>
				</tr>
				</thead>
				<tbody id="transactions">
				</tbody>
			</table>
		</div>
	</div>	
	

	<!-- Panel for displaying user information  -->
	<div class="panel panel-info">
		<div class="panel-heading">
			<H4 class="panel-title">(Primary) User Details</H3>
		</div>
		<div class="panel-body">
			<form class="form-horizontal">	
				<div class="form-group">
					<label class="col-sm-2 control-label">Name</label>
					<div class="col-sm-4">
						<p class="form-control-static"><span id="name"></span></p>	
					</div>
					<label class="col-sm-2 control-label">Email</label>
					<div class="col-sm-4">
						<p id="email" class="form-control-static"><span id="email"></span></p>
					</div>
				</div>	
				<div class="form-group">
					<label class="col-sm-2 control-label">Street</label>
					<div class="col-sm-4">
						<p class="form-control-static"><span id="address1"></span></p>
					</div>
					<label class="col-sm-2 control-label">City/State</label>
					<div class="col-sm-4">
						<p class="form-control-static"><span id="address2"></span></p>
					</div>
				</div>	
				
				<?php if ($role == 'Manager' || $role == 'Super Admin') : ?>
				<div class="form-group" id="email-button">
					<div class="col-sm-offset-2 col-sm-10">
						<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#emailModal">Launch Email Template</button>
					</div>
				</div>
				<?php endif; ?>
			</form>	
		</div>
	</div>

	<!-- Panel for Payment Information -->
	<div class="panel panel-info">
		<div class="panel-heading">
			<H4 class="panel-title">Payment Details</H4>
		</div>
		<div class="panel-body">
			<form id="UpdatePayment" action="#" class="form-horizontal">

				<div class="form-group">				
					<label for="payment" class="col-sm-2 control-label">Payment Type</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" id="payment" 	name="payment" value="Payment Type (Card/Bank/Cash/Check)" readonly>
					</div>
				</div>
				<div class="form-group" id="payment-nameoncard">				
					<label for="nameoncard" class="col-sm-2 control-label">Name on Card</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" id="nameoncard" name="nameoncard" value="Name on Card" readonly>
					</div>
				</div>
			</form>
		</div>
	</div>	
	
	
	<!-- Panel for editing Transaction (Status, Place of Worship, Comments) -->
	<div class="panel panel-info">
		<div class="panel-heading">
			<H3 class="panel-title">Trasaction Details</H3>
		</div>
		
		<div class="panel-body">
			<form id="UpdateTransaction" method="get" action="#" class="form-horizontal">

				<input type="hidden" 	id="txnid" 		name="txnid" 		value="">

				<div class="form-group">				
					<label for="txncid" class="col-sm-2 control-label">Constituent ID</label>
					<div class="col-sm-10">
						<input type="text" class="form-control required" id="txncid" name="txncid" maxlength="20" required >
					</div>
				</div>
				<div id="errortxncid" class="alert alert-warning" role="alert" style="display:none">Please enter Constituent ID.</div>
				
				<!-- Beginning of Fund Amount Section -->
				<div class="form-group">				
					<label for="fund1amount" class="col-sm-2 control-label">GEN</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" id="fund1amount" 	name="fund1amount" placeholder="0.00" maxlength="10">
						<input type="hidden" 					id="fund1id" 		name="fund1id" 		value="">
						<input type="hidden" 					id="fund1type" 		name="fund1type" 	value="GEN">
					</div>
				</div>
				<div id="errorfund1amount" 	class="alert alert-warning" role="alert" style="display:none">Please enter valid amount.</div>
				<div id="error1fund1amount" class="alert alert-warning" role="alert" style="display:none">Please enter more than $1.00.</div>
			
				<div class="form-group">				
					<label for="fund2amount" class="col-sm-2 control-label">MIS</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" id="fund2amount" name="fund2amount" placeholder="0.00" maxlength="10">
						<input type="hidden" 					id="fund2id" 	name="fund2id" 		value="">
						<input type="hidden" 					id="fund2type" name="fund2type" 	value="MIS">
					</div>
				</div>
				<div id="errorfund2amount" 	class="alert alert-warning" role="alert" style="display:none">Please enter valid amount.</div>
				<div id="error1fund2amount" class="alert alert-warning" role="alert" style="display:none">Please enter more than $1.00.</div>
				
				<div class="form-group">
					<label class="col-sm-2 control-label">Total</label>
					<div class="col-sm-10">
						<p class="form-control-static">$ <span id="totalamount">0.00</span></p>
					</div>
				</div>	
				<!-- End of Fund Amount Section -->

				<div class="form-group">				
					<label for="giftid" class="col-sm-2 control-label">Gift ID</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" id="giftid" name="giftid" maxlength="20" readonly >
					</div>
				</div>

				<div class="form-group">				
					<label for="place" class="col-sm-2 control-label">Place of Worship</label>
					<div class="col-sm-10">
						<select id="place" name="place" class="form-control required" required>
						</select>
					</div>
				</div>
				<div id="errorplace" class="alert alert-warning" role="alert" style="display:none">Please select your place of worship.</div>

				<div class="form-group">				
					<label for="comments" class="col-sm-2 control-label">Comments</label>
					<div class="col-sm-10">
						<textarea id="comments" name="comments" class="form-control" maxlength="200"></textarea>
					</div>
				</div>

				<div id="successtransaction" class="alert alert-success" role="alert" style="display:none">
					Gift Transaction has been successfully updated!
				</div>	

				<div id="dangertransaction" class="alert alert-danger" role="alert" style="display:none">
					Need attention!
				</div>	
				
				<?php if ($role == 'Manager' || $role == 'Super Admin') : ?>
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<button type="submit" class="btn btn-primary">Update</button>
						<button type="button" class="btn btn-success" id='duplicateTransaction'>Duplicate</button>
						<button type="button" class="btn btn-warning" id="deleteTransaction">Delete</button>
						<button type="reset"  class="btn btn-default">Clear</button>
				</div>
				<?php endif; ?>
			</form>
		</div>
	</div>

	<p></p>


	<div id="result" class="alert alert-danger" role="alert" style="display:none">
		<p id="message"></p>
	</div>	
	
	<div id="resultsuccess" class="alert alert-success" role="alert" style="display:none">
		<p id="messagesuccess"></p>
	</div>	
	


	<div class="alert alert-warning" role="alert" style="display:none;">
		Please add an email address <a href='mailto:giving@northlandchurch.net'>giving@northlandchurch.net</a> into your email list to avoid spam. 
	</div>
	

	<?php else : ?>
	<!-- ////////////////////////////////////////////////////// -->
	<!-- 		Section for non-logged in user 					-->
	<!-- ////////////////////////////////////////////////////// -->
	<div class="alert alert-danger" role="alert" >
		<p>You are not authorized to access this page. <BR />
		Please <a href="index.php">Login</a>
		</p>
	</div>
	
	<?php endif; ?>


</div>

<?php
	include_once 'html_footer.php';
?>

<?php if ($logged_in) : ?>
	
<script>
'use strict';

///////////////////////////////////////////////////////////////////////
// 					Global variables
///////////////////////////////////////////////////////////////////////

var payments 	= [];		// Payments

var message		= "";
var table;					// variable for holding giving transactions
var searchUrl	= "";		


$(document).ready(function () {
	$('#navbar-maintenance').addClass('active');
	
	var myemail 		= $("#myemail").text();
	var myscreenname 	= $("#myscreenname").text();

	// Create Giving Source drop-down menu dynamically
	var source 	= populateGivingSource();
	$("#searchsource").html(source);
	
	// Create Payment Type drop-down menu dynamically
	var payment	= populatePaymentType();
	$("#searchpayment").html(payment);
	
	
	// Adding Place of Worship drop-down menu 
	var place = populatePlaceOfWorship();
	$("#place").html(place);


	// Creating a datepicker in order to select a Date Range
	var startdate = $("#startdate").datepicker({
		changeYear: 		true,
		showOtherMonths: 	true,
		selectOtherMonths: 	true,
	}).on( "change", function() {
		enddate.datepicker( "option", "minDate", getDateforCalendar( this ) );
	});
	
	var enddate = $("#enddate").datepicker({
		changeYear: 		true,
		showOtherMonths: 	true,
		selectOtherMonths: 	true,
	}).on( "change", function() {
		startdate.datepicker( "option", "maxDate", getDateforCalendar( this ) );
	});



	// DataTable Initialization
	table = $('#datatable').DataTable({
		ajax: {
			url: "ws/webserviceGET.php?srv=finance_get_transactions&format=json&step=init",
			dataSrc: function(data) {
				var jsonObj = $.parseJSON(data);
				var txns 	= jsonObj.northland_api[2].response.txns;

				return txns;
			}
		},
		stateSave: 		true,
		scrollCollapse:	true,
		scrollY:		"350px",	
		lengthChange: 	false,
		select:		{
			style:	'single',
		},
		columns: [
			{ data: "order" },
			{ data: "txnDate" },
			{ data: "cid" },
			{ data: "txnName"},
			{ data: "amount" },
			{ data: "giftId" },
			{ data: "fundcode" },
			{ data: "payment" },
		],
		buttons: ['csv', 'excel', 'pdf', 'print',
			{
				extend: 'colvis',
				text:	'Colums'
			}
		],
		initComplete: function (){
			table.buttons().container().appendTo($("#datatable_wrapper .col-sm-6:eq(0)"));
		},
	});
	
	
	// Event handler when clicked a row in scheduled givings
	$("#datatable tbody").on('click', 'tr', function () {
		clearResultDiv();
		
		var data = table.row(this).data();
		
		if (data === undefined)
		{
			return false;
		}

//		console.log("A row is selected");
		///////////////////////////////////////////////////////////
		// Fill the values in 'User Details' section - This is Primary User information
		///////////////////////////////////////////////////////////
		$("#name").html(data.name);
		$("#email").html(data.email);
		$("#address1").html(data.address1);
		$("#address2").html(data.address2)

		///////////////////////////////////////////////////////////
		// Fill the values in 'Payment Details' section
		///////////////////////////////////////////////////////////
		var paymenttype = returnPaymentType(data.payment);
		// Credit Card
		if (paymenttype == "Credit Card")
		{
			var msg 	= returnCardType(data.cardtype) + ' ending in ' + data.cardlast4 + ' expiring ' + data.expmonth + '/' + data.expyear;
			$("#payment").val(msg);
			var msg1	= data.nameoncard;
			$("#nameoncard").val(msg1);
			$("#payment-nameoncard").show();
		}
		// Bank Account or Else
		else
		{
			if (paymenttype == "Bank Account")
			{
				var bankname = data.bankname;
				if ((!data.bankname) || data.bankname.trim() == "")
					bankname = "Bank Account";
				var msg 	= bankname + ' ending in ' + data.banklast4;
				$("#payment").val(msg);
			}
			else
			{
				$("#payment").val(paymenttype);
			}
			$("#payment-nameoncard").hide();
		}
		
		///////////////////////////////////////////////////////////
		// Fill the values in 'Transaction Details' section
		///////////////////////////////////////////////////////////
		// Must clear all fields before populating
		clearTransactionInfo();

		// Assign Transaction ID
		$("#txnid").val(data.txnId);

		$("#txncid").val(data.cid);
		
		// Assign each fund information in each distribution
		for (var i=0; i<data.dists.length; i++)
		{
			switch(data.dists[i].fundtype) {
				case "GEN":
					$("#fund1id").val(data.dists[i].PK_dist);
					$("#fund1amount").val(data.dists[i].amount);

					break;
				case "MIS":
					$("#fund2id").val(data.dists[i].PK_dist);
					$("#fund2amount").val(data.dists[i].amount);

					break;
				default:
					break;
			}
		}
		$("#totalamount").html(data.amount);

		$("#giftid").val(data.giftId);
		
		// Assign Place of worship in the drop-down menu
		$("#place").val(returnPlaceOfWorship(data.place));

		$("#comments").val(data.comments);
	});
	
	// Trigger proper method based on the form clicked
	$("form").submit(function(event) {
		event.preventDefault();

		// Hide all DIVs for result messages
		clearResultDiv();

		console.log(event.target.id);

		switch(event.target.id) {
			case "Search":
				processSearch();
				break;
			case "UpdateTransaction":
				processUpdateTransaction();
				break;
			case "SendEmail":
				sendEmail();
				break;
			default:
				break;
		}

	});

	
	$("#duplicateTransaction").click(function() {
		processDuplicateTransaction();
		return false;
	});
	
	
	$("#deleteTransaction").click(function() {
		processDeleteTransaction();
		return false;
	});
	
	// Display email modal when clicked button
	$('#emailModal').on('show.bs.modal', function (event) {
		var email 	= $("#email").html(); 
		var name	= $("#name").html();

		var modal = $(this);

		$('#recipient-email').val(email);
		$('#recipient-name').val(name);

		var msg = "Your donation from bank has been declined. \n"
				+ "Please contact April Guenther, our Director of Finance at april.guenther@northlandchurch.net or 407-949-4000.";
		$('#message-text').val(msg);
	})
	
});		// End for $(document).ready(

//Return the date with 'mm/dd/yyyy' format to display on Datepicker
var dateFormat = "mm/dd/yy";
function getDateforCalendar(element) 
{
	var date;
	try 
	{
		date = $.datepicker.parseDate(dateFormat, element.value);
	} 
	catch( error ) 
	{
		date = null;
	}

	return date;
}

// Return the date with 'yyyy-mm-dd' format for InSite DB
function getMySQLDate(date) 
{
	var seldate		= date.getDate();
	var selmonth	= date.getMonth()+1;
	var selyear		= date.getFullYear();

	return selyear + "-" + selmonth + "-" + seldate;
}



////////////////////////////////////////////////////////////////////////////////////////////////////////
//	Sending a search request and load the response to the table in the Scheduled Givings section
////////////////////////////////////////////////////////////////////////////////////////////////////////
function processSearch()
{
	// Clear all sections
	clearResultDiv();				// Hide all Result related DIV
	clearUserInfo();				// Clear 'User Details' Panel
	clearPaymentInfo();				// Clear 'Payment Details' Panel
	clearTransactionInfo();			// Clear 'Scheduled Giving Details' Panel

	
	var firstname	= $("#firstname").val().trim();
	var lastname	= $("#lastname").val().trim();
	var cid 		= $("#searchcid").val().trim();

	var startdate 	= $("#startdate").val();
	if(startdate != "")
		startdate 	= getMySQLDate(new Date(startdate));

	var enddate 	= $("#enddate").val();
	if(enddate != "")
		enddate 	= getMySQLDate(new Date(enddate));
	
	var amount		= $("#searchamount").val();
	var giftid		= $("#searchgiftid").val().trim();
	var source		= $("#searchsource").val();
	if (source == '')
		source 		= "ALL";
	var payment		= $("#searchpayment").val();
	if (payment == '')
		payment 	= "ALL";

	// Build a search request for PHP web services
	var parameters 	= "&cid=" + cid + "&firstname=" + firstname + "&lastname=" + lastname + "&start=" + startdate + "&end=" + enddate 
					+ "&amount=" + amount + "&giftid=" + giftid + "&source=" + source + "&payment=" + payment;
	searchUrl	= "ws/webserviceGET.php?srv=finance_get_transactions&format=json&step=middle" + parameters;

	// Load a new search
	table.ajax.url(searchUrl).load();
}


//////////////////////////////////////////////////////////////////
//		Processing a gift transaction UPDATE
//////////////////////////////////////////////////////////////////
function processUpdateTransaction()
{
	clearResultDiv();
	
	if (!validateTransaction())
	{
		return false;
	}
	
	var totalamount	= parseFloat($("#totalamount").text()).toFixed(2);

	// Display confirmation pop-up
	var msg = "You select to update a gift of $" + totalamount + " to CID, " + $("#txncid").val() + "."
			+ "\nDo you want to proceed?";
			
	if (confirm(msg) != true) {
		return false;
	}
	
	/////////////////////////////////////////////////////////////////////////////
	// 		Sending a transaction update request and Response handling
	/////////////////////////////////////////////////////////////////////////////
	var txnid		= $("#txnid").val();
	var giftid		= $("#giftid").val().trim();
	var comments	= $("#comments").val().trim();
	var place		= $("#place").val();
	
	// Collects fund information
	var fund1id = $("#fund1id").val();
	var fund2id = $("#fund2id").val();
	var amountGEN	= parseFloat($("#fund1amount").val());
	if (isNaN(amountGEN))
		amountGEN = 0;
	var amountMIS	= parseFloat($("#fund2amount").val());
	if (isNaN(amountMIS))
		amountMIS = 0;


	var send_data = "";
	// Build a request message
	send_data = "srv=finance_update_transaction&format=json&txnid=" + txnid 
			+ "&manager=" + $("#myemail").text() + "&txncid=" + $("#txncid").val()
			+ "&giftid=" + giftid + "&amount=" + totalamount + "&fundcount=2" 
			+ "&fundid0=" + fund1id + "&fundtype0=GEN" + "&fundamount0=" + amountGEN
			+ "&fundid1=" + fund2id + "&fundtype1=MIS" + "&fundamount1=" + amountMIS 
			+ "&worshipplace=" + place + "&comments=" + comments;
			
	// Call a web service to update a Transaction
	$.ajax({
		type: "POST",
		url: "ws/webservice.php",
		data: send_data,
		dataType: "json",
	})
	.done(function(data) {
		parseUpdateTransaction(data);
	})
	.fail(function(jqXHR, textStatus) {
		var msg = "Request failed: " + textStatus + ".<BR />Contact <a href='mailto:giving@northlandchurch.net'>Administrator</a>.";
		$("#dangertransaction").html(msg);
		$("#dangertransaction").show();
	})
	.always(function() { });
}


//////////////////////////////////////////////////////////////////////////////////
//		Parsing a Transaction UPDATE response 
//////////////////////////////////////////////////////////////////////////////////
function parseUpdateTransaction(data) 
{ 
	var jsonObj = $.parseJSON(data);
	
	var error_len = jsonObj.northland_api[1].errors.length;
	// Error handling for the request
	if (error_len > 0) 
	{
		var error_arr = jsonObj.northland_api[1].errors;
		var error_message = '';
		for (var i=0; i<error_len; i++)
			error_message += error_arr[i].number + ": " + error_arr[i].type + " - " + error_arr[i].message + "\n";

		$("#dangertransaction").html(error_message);
		$("#dangertransaction").show();
	} 
	else 
	{

		// Error handling
		if (jsonObj.northland_api[2].response.error != null) 
		{
			var error_arr 		= jsonObj.northland_api[2].response.error;
			var error_message 	= error_arr.number + ": " + error_arr.type + " - " + error_arr.message ;

			$("#dangertransaction").html(error_message);
			$("#dangertransaction").show();
		} 
		// Display trasaction information
		else 
		{
			var data = jsonObj.northland_api[2].response.txns[0];
			$("#successtransaction").html("Gift Transaction has been successfully updated!");
			$("#successtransaction").show();

			// Re-display Primary user information for case CID has changed
			$("#name").html(data.name);
			$("#email").html(data.email);
			$("#address1").html(data.address1);
			$("#address2").html(data.address2);
			
			// MUST update fund information from reponse because a fund of certain gifts has to be created in the system
			for (var i=0; i<data.dists.length; i++)
			{
				switch(data.dists[i].fundtype) {
					case "GEN":
						$("#fund1id").val(data.dists[i].PK_dist);
						$("#fund1amount").val(data.dists[i].amount);
						break;
					case "MIS":
						$("#fund2id").val(data.dists[i].PK_dist);
						$("#fund2amount").val(data.dists[i].amount);
						break;
					default:
						break;
				}
			}

			// Reload previous search in order to renew the search result
			table.ajax.reload(null, false);
		}
	}
}


///////////////////////////////////////////////////////////////////////////
//		Processing a gift transaction DUPLICATE from selected one
///////////////////////////////////////////////////////////////////////////
function processDuplicateTransaction()
{
	clearResultDiv();
	
	if (!validateTransaction())
	{
		return false;
	}
	
	var totalamount	= parseFloat($("#totalamount").text()).toFixed(2);

	// Display confirmation pop-up
	var msg = "You select to duplicate a gift of $" + totalamount + "."
			+ "\nDo you want to proceed?";
			
	if (confirm(msg) != true) {
		return false;
	}
	
	
	/////////////////////////////////////////////////////////////////////////////
	// 		Sending a gift transaction DUPLICATE request and Response handling
	/////////////////////////////////////////////////////////////////////////////
	var txnid		= $("#txnid").val();

	var send_data = "";
	// Build a request message
	send_data = "srv=finance_duplicate_transaction&format=json&txnid=" + txnid + "&manager=" + $("#myemail").text();
			
	// Call a web service to DUPLICATE a gift Transaction
	$.ajax({
		type: "POST",
		url: "ws/webservice.php",
		data: send_data,
		dataType: "json",
	})
	.done(function(data) {
		parseDuplicateTransaction(data);
	})
	.fail(function(jqXHR, textStatus) {
		var msg = "Request failed: " + textStatus + ".<BR />Contact <a href='mailto:giving@northlandchurch.net'>Administrator</a>.";
		$("#dangertransaction").html(msg);
		$("#dangertransaction").show();
	})
	.always(function() { });
}


////////////////////////////////////////////////////////////////////////////////////////
//		Parsing a gift Transaction DUPLICATE response 
////////////////////////////////////////////////////////////////////////////////////////
function parseDuplicateTransaction(data) 
{ 
	var jsonObj = $.parseJSON(data);
	
	var error_len = jsonObj.northland_api[1].errors.length;
	// Error handling for the request
	if (error_len > 0) 
	{
		var error_arr = jsonObj.northland_api[1].errors;
		var error_message = '';
		for (var i=0; i<error_len; i++)
			error_message += error_arr[i].number + ": " + error_arr[i].type + " - " + error_arr[i].message + "\n";

		$("#dangertransaction").html(error_message);
		$("#dangertransaction").show();
	} 
	else 
	{

		// Error handling
		if (jsonObj.northland_api[2].response.error != null) 
		{
			var error_arr 		= jsonObj.northland_api[2].response.error;
			var error_message 	= error_arr.number + ": " + error_arr.type + " - " + error_arr.message ;

			$("#dangertransaction").html(error_message);
			$("#dangertransaction").show();
		} 
		// Display trasaction information
		else 
		{
			var data = jsonObj.northland_api[2].response.txns[0];
			$("#successtransaction").html("A new Gift Transaction has been successfully duplicated!");
			$("#successtransaction").show();
			
			clearTransactionInfo();

			// Fill up a new gift transaction info that just created
			// This gift does not have fund information now.
			$("#txnid").val(data.txnId);
			$("#txncid").val(data.txncid);
			$("#giftid").val(data.giftId);
			$("#place").val(returnPlaceOfWorship(data.place));
			$("#comments").val(data.comments);

			// Reload previous search in order to renew the search result
			table.ajax.reload(null, false);
		}
	}
}


//////////////////////////////////////////////////////////////////
//		Processing a gift transaction DELETE
//////////////////////////////////////////////////////////////////
function processDeleteTransaction()
{
	clearResultDiv();
	
	if (!validateTransaction())
	{
		return false;
	}
	
	var totalamount	= parseFloat($("#totalamount").text()).toFixed(2);

	// Display confirmation pop-up
	var msg = "You select to delete a gift of $" + totalamount + " from CID, " + $("#txncid").val() + "."
			+ "\nDo you want to proceed?";
			
	if (confirm(msg) != true) {
		return false;
	}

	/////////////////////////////////////////////////////////////////////////////
	// 		Sending a gift transaction DELETE request and Response handling
	/////////////////////////////////////////////////////////////////////////////
	var txnid		= $("#txnid").val();
	
	var send_data = "";
	// Build a request message
	send_data = "srv=finance_delete_transaction&format=json&txnid=" + txnid + "&manager=" + $("#myemail").text();
			
	// Call a web service to delete a Transaction
	$.ajax({
		type: "POST",
		url: "ws/webservice.php",
		data: send_data,
		dataType: "json",
	})
	.done(function(data) {
		parseDeleteTransaction(data);
	})
	.fail(function(jqXHR, textStatus) {
		var msg = "Request failed: " + textStatus + ".<BR />Contact <a href='mailto:giving@northlandchurch.net'>Administrator</a>.";
		$("#dangertransaction").html(msg);
		$("#dangertransaction").show();
	})
	.always(function() { });
}


//////////////////////////////////////////////////////////////////////////////
//		Parsing a gift Transaction DELETE response 
//////////////////////////////////////////////////////////////////////////////
function parseDeleteTransaction(data) 
{ 
	var jsonObj = $.parseJSON(data);
	
	var error_len = jsonObj.northland_api[1].errors.length;
	// Error handling for the request
	if (error_len > 0) 
	{
		var error_arr = jsonObj.northland_api[1].errors;
		var error_message = '';
		for (var i=0; i<error_len; i++)
			error_message += error_arr[i].number + ": " + error_arr[i].type + " - " + error_arr[i].message + "\n";

		$("#dangertransaction").html(error_message);
		$("#dangertransaction").show();
	} 
	else 
	{
		// Error handling
		if (jsonObj.northland_api[2].response.error != null) 
		{
			var error_arr 		= jsonObj.northland_api[2].response.error;
			var error_message 	= error_arr.number + ": " + error_arr.type + " - " + error_arr.message ;

			$("#dangertransaction").html(error_message);
			$("#dangertransaction").show();
		} 
		// Display trasaction information
		else 
		{
			var data = jsonObj.northland_api[2].response.txns[0];
			$("#successtransaction").html("Gift Transaction has been successfully deleted!");
			$("#successtransaction").show();

			clearUserInfo();				// Clear 'User Details' Panel
			clearPaymentInfo();				// Clear 'Payment Details' Panel
			clearTransactionInfo();			// Clear 'Scheduled Giving Details' Panel

			// Reload previous search in order to renew the search result
			table.ajax.reload(null, false);
		}
	}
}


///////////////////////////////////////////////////////////////////////
// 		Validation check
///////////////////////////////////////////////////////////////////////
function validateTransaction()
{
	var err_msg 	= "";
	var txnid		= $("#txnid").val();
	var place 		= $("#place").val();

	if (txnid == '') 
	{
		err_msg += "Select a Gift from the list.<BR />";
		$("#dangertransaction").html(err_msg);
		$("#dangertransaction").show();
		return false;
	}

	if (place == null || place == '') 
		err_msg += "Select Place of Worship.<BR />";
	
	if (err_msg != "")
	{
		$("#dangertransaction").html(err_msg);
		$("#dangertransaction").show();
		return false;
	}
	// End of validation check
	return true;
}


function sendEmail()
{
	var email	= $("#recipient-email").val();
	var name	= $("#recipient-name").val();
	var content	= $("#message-text").val();
	
	// Display confirmation pop-up
	var msg = "Do you want to send an email to " + email + "?";

	if (confirm(msg) != true) {
		return false;
	}
	
	var send_data = "";
	// Build a data to send an email
	send_data = "email=" + email + "&name=" + name + "&msg=" + content;
			
	// Call web service to send an email
	$.ajax({
		type: "POST",
		url: "mail/admin_returned_ach.php",
		data: send_data,
	})
	.done(function(data) {
		if (data.includes("Success"))
		{
			alert("Email sent");
		}
		else
		{
			alert(data);
		}
	})
	.fail(function(jqXHR, textStatus) {
		alert("Error: " + textStatus + "\nPlease contact us at giving@northlandchurch.net");
	})
	.always(function() { });
	
}


///////////////////////////////////////////////////////////////////////////
//	Clear all Divs for result display
///////////////////////////////////////////////////////////////////////////
function clearResultDiv()
{
	$(".alert-warning").hide();
	$(".alert-success").hide();
	$(".alert-danger").hide();
}


///////////////////////////////////////////////////////////////////////////
//	Clear values in 'User Details' panel
///////////////////////////////////////////////////////////////////////////
function clearUserInfo()
{
	$("#name").html("");
	$("#email").html("");
	$("#address1").html("");
	$("#address2").html("");
}

///////////////////////////////////////////////////////////////////////////
//	Clear values in 'Payment Details' panel
///////////////////////////////////////////////////////////////////////////
function clearPaymentInfo()
{
	$("#payment").val("Payment Type (Card/Bank/Cash/Check)");
	$("#nameoncard").val("Name on Card");
}

///////////////////////////////////////////////////////////////////////////
//	Clear values in 'Scheduled Giving Details' panel
///////////////////////////////////////////////////////////////////////////
function clearTransactionInfo()
{
	$("#txnid").val("");
	$("#txncid").val("");
	$("#fund1id").val('');
	$("#fund2id").val('');
	$("#fund1amount").val("0.00");
	$("#fund2amount").val("0.00");
	calculateTotalAmount();
	$("#giftid").val("");
	$("#place").val("");
	$("#comments").val("");
}



</script>

<?php endif; ?>


</body>

</html>
