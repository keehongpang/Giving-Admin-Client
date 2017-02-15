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
			<H4 class="panel-title">Search for Recurrings</H4>
		</div>
		<div class="panel-body">
			<form id="Search" method="get" action="#" class="form-horizontal">
				<div id="successprofilename" class="alert alert-success" role="alert" style="display:none">
					Your name successfully updated!
				</div>	

				<div class="form-group">				
					<label for="firstname" class="col-sm-2 control-label">First Name</label>
					<div class="col-sm-4">
						<input type="text" class="form-control" id="firstname" name="firstname" placeholder="First Name" maxlength="20"  >
					</div>
					<label for="lastname" class="col-sm-2 control-label">Last Name</label>
					<div class="col-sm-4">
						<input type="text" class="form-control" id="lastname" name="lastname" placeholder="Last Name" maxlength="20"  >
					</div>
				</div>

				<div class="form-group">				
					<label for="cid" class="col-sm-2 control-label">CID</label>
					<div class="col-sm-4">
						<input type="text" class="form-control" id="cid" name="cid" placeholder="CID" maxlength="20"  >
					</div>
					<label for="status" class="col-sm-2 control-label">Status</label>
					<div class="col-sm-4">
						<select id="status" name="status" class="form-control"></select>
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
	
	
	<!-- Panel for displaying Scheduled Givings -->
	<div class="panel panel-primary table-responsive">
		<div class="panel-heading">
			<H3 class="panel-title">Search Results</H3>
		</div>

		<div class="panel-body">
			<p><span class="label label-info">Maximum 500 records can display.</span></p>
			<table id="datatable" class="table table-striped table-hover table-condensed" cellspacing="0" width="100%">
				<thead>
				<tr>
					<th>CID</th>
					<th>Name</th>
					<th>Last Run</th>
					<th>Next Run</th>
					<th>Amount</th>
					<th>Frequency</th>
					<th>Payment Type</th>
					<th>Status</th>
				</tr>
				</thead>
				<tbody id="recurrings">
				</tbody>
			</table>
		</div>
	</div>	
	

	<!-- Panel for displaying user information  -->
	<div class="panel panel-info">
		<div class="panel-heading">
			<H4 class="panel-title">User Details</H3>
		</div>
		<div class="panel-body">
			<form class="form-horizontal">	
				<input type="hidden" 	id="giftcid" 		name="giftcid" 		value="">
				<div class="form-group">
					<label class="col-sm-2 control-label">Name</label>
					<div class="col-sm-10">
						<p class="form-control-static"><span id="name"></span></p>	
					</div>
				</div>	
				<div class="form-group">
					<label class="col-sm-2 control-label">Email</label>
					<div class="col-sm-10">
						<p id="email" class="form-control-static"><span id="email"></span></p>
					</div>
				</div>	
				<div class="form-group">
					<label class="col-sm-2 control-label">Address</label>
					<div class="col-sm-10">
						<p class="form-control-static"><span id="address1"></span></p>
					</div>
				</div>	
				<div class="form-group">
					<label class="col-sm-2 control-label">City/State</label>
					<div class="col-sm-10">
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

	<!-- Panel for editing payment information (Card expiration date) -->
	<div class="panel panel-info">
		<div class="panel-heading">
			<H4 class="panel-title">Payment Details</H4>
		</div>
		<div class="panel-body">
			<form id="UpdatePayment" action="#" class="form-horizontal">
				<div id="successpayment" class="alert alert-success" role="alert" style="display:none">
					Payment info has been successfully updated!
				</div>	

				<div id="dangerpayment" class="alert alert-danger" role="alert" style="display:none">
					Need attention!
				</div>	

				<input type="hidden" 	id="paymentid" 		name="paymentid" 		value="">
				
				<div class="form-group">				
					<label for="payment" class="col-sm-2 control-label">Payment Type</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" id="payment" 	name="payment" value="Card or Bank name" readonly>
					</div>
				</div>

				<div class="form-group" id="payment-expiration">				
					<label for="cardexpiration" class="col-sm-2 control-label">Expiration Date</label>
					<div class="col-sm-5">
						<select id="cardexpmonth" name="cardexpmonth" class="form-control required" >
							<option value="">Month</option>
							<option value="01">1</option>
							<option value="02">2</option>
							<option value="03">3</option>
							<option value="04">4</option>
							<option value="05">5</option>
							<option value="06">6</option>
							<option value="07">7</option>
							<option value="08">8</option>
							<option value="09">9</option>
							<option value="10">10</option>
							<option value="11">11</option>
							<option value="12">12</option>
						</select>
					</div>
					<div class="col-sm-5">
						<select id="cardexpyear" name="cardexpyear" class="form-control required" ></select> 
					</div>
				</div>
				
				<?php if ($role == 'Manager' || $role == 'Super Admin') : ?>
				<div class="form-group" id="payment-button">
					<div class="col-sm-offset-2 col-sm-10">
						<button type="submit" class="btn btn-primary">Update</button>
					</div>
				</div>
				<?php endif; ?>
			</form>
		</div>
	</div>	
	
	
	<!-- Panel for editing scheduled giving (Status, Place of Worship, Comments) -->
	<div class="panel panel-info">
		<div class="panel-heading">
			<H3 class="panel-title">Scheduled Giving Details</H3>
		</div>
		
		<div class="panel-body">
			<form id="UpdateRecurring" method="get" action="#" class="form-horizontal">

				<input type="hidden" 	id="recurringid" 		name="recurringid" 		value="">
				
				<div class="form-group">				
					<label for="status1" class="col-sm-2 control-label">Status</label>
					<div class="col-sm-10">
						<select id="status1" name="status1" class="form-control"></select>
					</div>
				</div>

				<!-- Beginning of Fund Amount Section -->
				<div class="form-group">				
					<label for="fund1amount" class="col-sm-2 control-label">GEN</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" id="fund1amount" 	name="fund1amount" placeholder="0.00" maxlength="10" disabled >
						<input type="hidden" 					id="fund1id" 		name="fund1id" 		value="">
						<input type="hidden" 					id="fund1type" 		name="fund1type" 	value="GEN">
					</div>
				</div>
			
				<div class="form-group">				
					<label for="fund2amount" class="col-sm-2 control-label">MIS</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" id="fund2amount" name="fund2amount" placeholder="0.00" maxlength="10" disabled >
						<input type="hidden" 					id="fund2id" 	name="fund2id" 		value="">
						<input type="hidden" 					id="fund2type" name="fund2type" 	value="MIS">
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-2 control-label">Total</label>
					<div class="col-sm-10">
						<p class="form-control-static">$ <span id="totalamount">0.00</span></p>
					</div>
				</div>	
				<!-- End of Fund Amount Section -->

				<div class="form-group">				
					<label for="frequency" class="col-sm-2 control-label">Frequency</label>
					<div class="col-sm-10">
						<select id="frequency" name="frequency" class="form-control">
							<option value="WEEKLY">Weekly</option>
							<option value="BIWEEKLY">Bi-Weekly</option>
							<option value="MONTHLY">Monthly</option>
						</select>
					</div>
				</div>

				<div class="form-group" id="scheduler" style="display:block">				
					<label for="startdate" class="col-sm-2 control-label">Start Date?</label>
					<div class="col-sm-10">
						<input type="text" id="datepicker" class="form-control">
					</div>
				</div>
				<div id="errordatepicker" class="alert alert-warning" role="alert" style="display:none">Please choose your start date of your recurring.</div>
				
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

				<div id="successrecurring" class="alert alert-success" role="alert" style="display:none">
					Scheduled Giving Info has been successfully updated!
				</div>	

				<div id="dangerrecurring" class="alert alert-danger" role="alert" style="display:none">
					Need attention!
				</div>	
				
				<?php if ($role == 'Manager' || $role == 'Super Admin') : ?>
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<button type="submit" class="btn btn-primary">Update</button>
					</div>
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
var recurrings 	= [];		// Recurring Gifts
var expYearMenu = "";		// Expiration Year Drop-down menu

var message		= "";
var table;					// variable for holding scheduled givings
var searchUrl	= "";		


$(document).ready(function () {
	$('#navbar-recurring').addClass('active');
	
	var myemail 		= $("#myemail").text();
	var myscreenname 	= $("#myscreenname").text();

	// Adding Recurring status drop-down menu dynamically
	var status 	= populateRecurringStatus();
	$("#status").html(status);
	$("#status1").html(status);

	// Adding Year drop-down menu dynamically
	expYearMenu = populateExpirationYearMenu();
	$("#cardexpyear").html(expYearMenu);

	// Adding Place of Worship drop-down menu 
	var place = populatePlaceOfWorship();
	$("#place").html(place);

	// Initialize Date Picker
	$("#datepicker").datepicker({
		changeYear: 		true,
		showOtherMonths: 	true,
		selectOtherMonths: 	true,
	});

	// DataTable Initialization
	table = $('#datatable').DataTable({
		ajax: {
			url: "ws/webserviceGET.php?srv=finance_get_recurring_giving&format=json&step=init&firstname=&lastname=&cid=&status=All",
			dataSrc: function(data) {
				var jsonObj = $.parseJSON(data);
				recurrings 	= jsonObj.northland_api[2].response.recurrings;

				return recurrings;
			}
		},
		stateSave: 		true,
		scrollCollapse:	true,
		scrollY:		"350px",	
		lengthChange: 	false,
		select:		{
			style:	'single',
		},
		language: 	{
			lengthMenu:		"Show _MENU_ records per page",
			infoFiltered:	"(filtered from _MAX_ total records)",
//			zeroRecords:	"",
		},
		columns: [
			{ data: "cid" },
			{ data: "name"},
			{ data: "lastrun" },
			{ data: "nextrun" },
			{ data: "amount" },
			{ data: "frequency" },
			{ data: "paymenttype" },
			{ data: "status" },
//			{ data: "place" },
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
		
		console.log('You clicked a recurring id ' + data.recurringId + ' with payment id ' + data.paymentId);
		
		///////////////////////////////////////////////////////////
		// Fill the values in 'User Information' section
		///////////////////////////////////////////////////////////
		$("#giftcid").val(data.cid);
		$("#name").html(data.name);
		$("#email").html(data.email);
		$("#address1").html(data.address1);
		$("#address2").html(data.address2)

		///////////////////////////////////////////////////////////
		// Fill the values in 'Payment Details' section
		///////////////////////////////////////////////////////////
		// Assign Payment ID
		$("#paymentid").val(data.paymentId);
		
		// Credit Card
		if (data.paymenttype == "creditcard")
		{
			var now			= new Date();
			var nowmonth	= now.getMonth()+1;
			var nowyear		= now.getFullYear();
			
			$('#cardexpyear option[value=""]').prop('selected', true);
			
			var msg = data.cardtype + ' ending in ' + data.cardlast4 + ' expiring ' + data.expmonth + '/' + data.expyear;
			$("#payment").val(msg);
			
			$("#payment-expiration").show();
			$("#payment-button").show();

			// Comparing expiration date 
			// Case: if expiration year is expired
			if (nowyear > data.expyear)
			{
				$("#dangerpayment").html("Credit Card expired. Please update it.");
				$("#dangerpayment").show();
				// Assign expiration date 
				$('#cardexpmonth option[value='+data.expmonth+']').prop('selected', true);
			}
			// Case: if expiration month is expired and year is current year
			else if (nowyear == data.expyear)
			{
				var nowMonthFloat	= parseFloat(nowmonth).toFixed(2);
				var expMonthFloat	= parseFloat(data.expmonth).toFixed(2);
				if (nowMonthFloat > expMonthFloat)
				{
					$("#dangerpayment").html("Credit Card expired. Please update it.");
					$("#dangerpayment").show();
				}
				// Assign expiration date 
				$('#cardexpmonth option[value='+data.expmonth+']').prop('selected', true);
				$('#cardexpyear option[value='+data.expyear+']').prop('selected', true);
			}
			// Case: Normal
			else
			{
				// Assign expiration date 
				$('#cardexpmonth option[value='+data.expmonth+']').prop('selected', true);
				$('#cardexpyear option[value='+data.expyear+']').prop('selected', true);
			}
			
		}
		// Bank Account
		else
		{
			var bankname = data.bankname;
			if ((!data.bankname) || data.bankname.trim() == "")
				bankname = "Bank Account";
			var msg = bankname + ' ending in ' + data.banklast4;
			$("#payment").val(msg);
			
			$("#payment-expiration").hide();
			$("#payment-button").hide();
			
		}
		
		
		///////////////////////////////////////////////////////////
		// Fill the values in 'Scheduled Giving Details' section
		///////////////////////////////////////////////////////////
		// Must clear all fields before populating
		clearScheduleInfo();

		// Assign Recurring ID
		$("#recurringid").val(data.recurringId);
		
		// Assign scheduled giving status
		$('#status1 option[value='+data.status+']').prop('selected', true);

		// Assign scheduled giving frequency
		$('#frequency option[value='+data.frequency+']').prop('selected', true);

		// Assign the next giving date 
		var date		= data.nextrun;
		// Convert next run date for display (i.e. MM/DD/YYYY)
		var nextdate	= date.substring(5, 7) + "/" + date.substring(8, 10) + "/" + date.substring(0, 4);
		$("#datepicker").datepicker('setDate', nextdate);

		// Assign Place of worship in the drop-down menu
		$("#place").val(data.place);

		$("#comments").val(data.comments);

		// Initialize each fund section
		$("#fund1id").val('');
		$("#fund1amount").val("0.00");
		$("#fund2id").val('');
		$("#fund2amount").val("0.00");
		
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
//		calculateTotalAmount();
		
	});
	
	// Trigger proper method based on the form clicked
	$("form").submit(function(event) {
		event.preventDefault();

		// Hide result messages
		$("#result").hide();
		$("#resultsuccess").hide();

		console.log(event.target.id);

		switch(event.target.id) {
			case "Search":
				$("#result").hide();
				processSearch();
				break;
			case "UpdatePayment":
				processPayment();
				break;
			case "UpdateRecurring":
				processRecurring();
				break;
			case "SendEmail":
				sendEmail();
				break;
			default:
				break;
		}

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


////////////////////////////////////////////////////////////////////////////////////////////////////////
//	Sending a search request and load the response to the table in the Scheduled Givings section
////////////////////////////////////////////////////////////////////////////////////////////////////////
function processSearch()
{
	// Clear all sections
	clearResultDiv();				// Hide all Result related DIV
	clearUserInfo();				// Clear 'User Details' Panel
	clearPaymentInfo();				// Clear 'Payment Details' Panel
	clearScheduleInfo();			// Clear 'Scheduled Giving Details' Panel

	
	var firstname	= $("#firstname").val().trim();
	var lastname	= $("#lastname").val().trim();
	var cid 		= $("#cid").val().trim();
	var status		= $("#status").val();

	searchUrl	= "ws/webserviceGET.php?srv=finance_get_recurring_giving&format=json&step=middle&firstname=" + firstname + "&lastname=" + lastname 
				+ "&cid=" + cid + "&status=" + status;

	// Load a new search
	table.ajax.url(searchUrl).load();
}


////////////////////////////////////////////////////////////////////////////////////////
//		Processing a credit card expiration date update 
////////////////////////////////////////////////////////////////////////////////////////
function processPayment()
{
	$("#dangerpayment").hide();
	$("#successpayment").hide();

	var err_msg = "";
	var cardexpmonth 	= $("#cardexpmonth").val();
	var cardexpyear 	= $("#cardexpyear").val();

	////////////////////////////////////////
	// 		Validation check
	////////////////////////////////////////
	if (cardexpmonth == null || cardexpmonth == '') {
		err_msg += "Select Credit Card Expiration Month.<BR />";
	}
	if (cardexpyear == null || cardexpyear == '') {
		err_msg += "Select Credit Card Expiration Year.<BR />";
	}

	if (err_msg != "")
	{
		$("#dangerpayment").html(err_msg);
		$("#dangerpayment").show();
		return false;
	}
	// End of validation check

	/////////////////////////////////////////////////////////////////////////////
	// 		Sending credit card expiration date update and Response handling
	/////////////////////////////////////////////////////////////////////////////
	// Build a request message
	var send_data = "srv=finance_update_cc_expiration_log&format=json&paymentid=" + $('#paymentid').val()
			+ "&cardexpmonth=" + $('#cardexpmonth').val() + "&cardexpyear=" + $('#cardexpyear').val();
	
	// Call a web service to Update Credit Card Expiration date
	$.ajax({
		type: "POST",
		url: "ws/webservice.php",
		data: send_data,
		dataType: "json",
	})
	.done(function(data) {
		parseUpdatePayment(data);
	})
	.fail(function(jqXHR, textStatus) {
		var msg = "Request failed: " + textStatus + ".<BR />Contact <a href='mailto:giving@northlandchurch.net'>Administrator</a>.";
		$("#dangerpayment").html(msg);
		$("#dangerpayment").show();
	})
	.always(function() { });
}


////////////////////////////////////////////////////////////////////////////////////////
//	Parsing response after updating payment in the system
// 	and Reload search result to the table in the Scheduled Givings section
////////////////////////////////////////////////////////////////////////////////////////
function parseUpdatePayment(data) 
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
		
		$("#dangerpayment").html(error_message);
		$("#dangerpayment").show();
	} 
	else 
	{
		var temp_payments = [];
		temp_payments = jsonObj.northland_api[2].response.payments;

		// Error handling
		if (jsonObj.northland_api[2].response.error != null) 
		{
			var error_arr = jsonObj.northland_api[2].response.error;
			var error_message = error_arr.number + ": " + error_arr.type + " - " + error_arr.message ;

			$("#dangerpayment").html(error_message);
			$("#dangerpayment").show();
		} 
		// Display result
		else 
		{
			$("#successpayment").show();


			// Reload previous search in order to renew the search result
			table.ajax.reload(null, false);
		}
	}		

}


//////////////////////////////////////////////////////////////////
//		Processing a scheduled giving update
//////////////////////////////////////////////////////////////////
function processRecurring()
{
	$("#dangerrecurring").hide();
	$("#successrecurring").hide();

	var err_msg 	= "";
	var recurringid	= $("#recurringid").val();
	var place 		= $("#place").val();
	var nextdate 	= $("#datepicker").val();
	var status		= $("#status1").val();
	
	var cid			= $("#giftcid").val();
	var name		= $("#name").html();

	////////////////////////////////////////
	// 		Validation check
	////////////////////////////////////////
	if (recurringid == '') 
	{
		err_msg += "Select a Scheduled Giving from the list.<BR />";
		$("#dangerrecurring").html(err_msg);
		$("#dangerrecurring").show();
		return false;
	}

	if (status == 'All') 
		err_msg += "Select Proper Recurring Status.<BR />";

	if (place == null || place == '') 
		err_msg += "Select Place of Worship.<BR />";

	if (nextdate == null || nextdate == '')
		err_msg += "Select Start Date of the Scheduled Giving.<BR />";
	
	if (err_msg != "")
	{
		$("#dangerrecurring").html(err_msg);
		$("#dangerrecurring").show();
		return false;
	}
	// End of validation check
	

	/////////////////////////////////////////////////////////////////////////////
	// 		Sending a scheduled giving update and Response handling
	/////////////////////////////////////////////////////////////////////////////
	var frequency	= $("#frequency").val();
	var comments	= $("#comments").val().trim();
	var totalamount	= parseFloat($("#totalamount").text()).toFixed(2);

	var now			= new Date();
	var nowday		= now.getDay()+1;						// Date.getDay(): 		Returns the day of the week (from 0:Sun-6:Sat)
	var nowdate		= now.getDate();						// Date.getDate():		Returns the day of the month (from 1-31)
	var nowmonth	= now.getMonth()+1;						// Date.getMonth(): 	Returns the month (from 0-11)
	var nowyear		= now.getFullYear();					// Date.getFullYear:	Returns the year (four digits)

	var selected 	= new Date($("#datepicker").val());
	var selday		= selected.getDay()+1;					// Add 1 in order to comply with InSite (from 1:Sun-7:Sat)
	var seldate		= selected.getDate();
	var selmonth	= selected.getMonth()+1;
	var selyear		= selected.getFullYear();

		
	// Display confirmation pop-up
	var msg = "You selected to schedule a gift $" + totalamount + " " + returnFrequency(frequency) + "."
			+ "\nThis gift will start on " + selmonth + "/" + seldate + "/" + selyear + "."
			+ "\nDo you want to proceed?";
			
	if (nowdate == seldate && nowmonth == selmonth && nowyear == selyear)
		msg += "\nIf the gift starts today, it will be processed tonight.";

	if (confirm(msg) != true) {
		return false;
	}
	
	
	// Collects fund information
	var fund1id = $("#fund1id").val();
	var fund2id = $("#fund2id").val();
	var amountGEN	= parseFloat($("#fund1amount").val());
	if (isNaN(amountGEN))
		amountGEN = 0;
	var amountMIS	= parseFloat($("#fund2amount").val());
	if (isNaN(amountMIS))
		amountMIS = 0;

	// Convert selected date to send in Web service
	var nextexecdate= selected.toJSON(); 

	var send_data = "";
	// Build a request message
	send_data = "srv=finance_update_recurring_log&format=json&recurringid=" + recurringid + "&cid=" + cid + "&name=" + name
			+ "&status=" + status + "&amount=" + totalamount + "&fundcount=2" 
			+ "&fundid0=" + fund1id + "&fundtype0=GEN" + "&fundamount0=" + amountGEN
			+ "&fundid1=" + fund2id + "&fundtype1=MIS" + "&fundamount1=" + amountMIS 
			+ "&frequency=" + frequency + "&nextexecdate=" + nextexecdate + "&dayofmonth=" + seldate + "&dayofweek=" + selday 
			+ "&worshipplace=" + $("#place").val() + "&comments=" + comments;
			
	// Call a web service to update a Recurring giving
	$.ajax({
		type: "POST",
		url: "ws/webservice.php",
		data: send_data,
		dataType: "json",
	})
	.done(function(data) {
		parseUpdateRecurring(data);
	})
	.fail(function(jqXHR, textStatus) {
		var msg = "Request failed: " + textStatus + ".<BR />Contact <a href='mailto:giving@northlandchurch.net'>Administrator</a>.";
		$("#dangerrecurring").html(msg);
		$("#dangerrecurring").show();
	})
	.always(function() { });


}


////////////////////////////////////////////////////////////////////////////////////////
//	Parsing Recurring response after updating a recurring giving in the system
////////////////////////////////////////////////////////////////////////////////////////
function parseUpdateRecurring(data) 
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

		$("#dangerrecurring").html(error_message);
		$("#dangerrecurring").show();
	} 
	else 
	{
		var data = jsonObj.northland_api[2].response.recurrings;

		// Error handling
		if (jsonObj.northland_api[2].response.error != null) 
		{
			var error_arr 		= jsonObj.northland_api[2].response.error;
			var error_message 	= error_arr.number + ": " + error_arr.type + " - " + error_arr.message ;

			$("#dangerrecurring").html(error_message);
			$("#dangerrecurring").show();
		} 
		// Display recurring information
		else 
		{
			$("#successrecurring").show();
			
			// Reload previous search in order to renew the search result
			table.ajax.reload(null, false);
		}
	}
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
//	Clear all Div for result display
///////////////////////////////////////////////////////////////////////////
function clearResultDiv()
{
	$("#dangerpayment").hide();
	$("#successpayment").hide();
	
	$("#dangerrecurring").hide();
	$("#successrecurring").hide();
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
	$("#payment").val("Card or Bank name");
	$("#cardexpmonth").val("");
	$("#cardexpyear").val("");
	$("#payment-expiration").show();
}

///////////////////////////////////////////////////////////////////////////
//	Clear values in 'Scheduled Giving Details' panel
///////////////////////////////////////////////////////////////////////////
function clearScheduleInfo()
{
	$("#recurringid").val("");
	$("#status1").val("All");
	$("#fund1id").val('');
	$("#fund2id").val('');
	$("#fund1amount").val("0.00");
	$("#fund2amount").val("0.00");
	calculateTotalAmount();
	$("#frequency").val("WEEKLY");
	$("#datepicker").datepicker('setDate', null);
	$("#place").val("");
	$("#comments").val("");
}



</script>

<?php endif; ?>


</body>

</html>
