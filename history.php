<?php
	include_once 'html_open.php';
?>

<?php
	if (isset($_GET['error'])) {
		$error = $_GET['error'];
		echo '<p><div id="loginError" class="alert alert-danger" role="alert">' . $error . '</div></p>';
	}
?> 

<div class="container">	
	<H2 class="heading-large">GIVING HISTORY</H2>
	<hr>

	<?php if ($logged_in) : ?>

	<div id="rsErrorDiv" class="alert alert-danger" role="alert" style="display:none">
		Message for immediate attention.
	</div>	

	
	<!-- Hidden fields -->
	<div id="myemail" hidden><?php echo $_SESSION['email'] ?></div>


	<div id="result" class="alert alert-warning" role="alert" style="display:none">
	</div>	
	
	<!-- ---------------------------------------------------------------------- --> 	
	<!--		Downloading PDF file Giving Statement from Raiser's Edge		-->
	<!-- ---------------------------------------------------------------------- --> 	
	<div class="panel panel-primary">
		<div class="panel-heading">
			<H4 class="panel-title">Giving Statement from Raiser's Edge</H4>
		</div>
		<div class="panel-body">
			<form id="statementRE" method="get" action="#" class="form-horizontal">
				<div class="form-group">				
					<label for="cid" class="col-sm-2 control-label">CID</label>
					<div class="col-sm-4">
						<input type="text" class="form-control" id="cidRE" name="cidRE" placeholder="CID" maxlength="20"  >
					</div>
					<label for="status" class="col-sm-2 control-label">Select Year: </label>
					<div class="col-sm-4">
						<select id="yearRE" name="yearRE" class="form-control">
							<option value="2016">2016</option>
							<option value="2015">2015</option>
							<option value="2014">2014</option>
							<option value="2013">2013</option>
							<option value="2012">2012</option>
							<option value="2011">2011</option>
						</select>
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<button type="submit" class="btn btn-primary">GET</button>
						<button type="reset"  class="btn btn-default">Clear</button>
					</div>
				</div>
			</form>
		</div>
	</div>	


	<!-- ---------------------------------------------------------------------- --> 	
	<!--				Giving History from Giving Database						-->
	<!-- ---------------------------------------------------------------------- --> 	
	<div class="panel panel-info table-responsive">
		<div class="panel-heading">
			<H4 class="panel-title">Giving History from InSite (Online Giving System)</H4>
		</div>

		<div class="panel-body">
			<div id="resultInsite" class="alert alert-danger" role="alert" style="display:none">
				Imlementing... Just wait to finish!
			</div>	
			<form id="getHistory" method="get" action="#" class="form-horizontal">
				<div class="form-group">				
					<label for="cid" class="col-sm-2 control-label">CID</label>
					<div class="col-sm-4">
						<input type="text" class="form-control" id="cid" name="cid" placeholder="CID" maxlength="20" required >
					</div>
					<label for="status" class="col-sm-2 control-label">Select Year: </label>
					<div class="col-sm-4">
						<select id="year" name="year" class="form-control">
							<option value="2017">2017</option>
							<option value="2016">2016</option>
							<option value="2015">2015</option>
							<option value="2014">2014</option>
							<option value="2013">2013</option>
							<option value="2012">2012</option>
							<option value="2011">2011</option>
						</select>
					</div>
				</div>

				<div class="form-group" style="display:block">
					<div class="col-sm-offset-2 col-sm-10">
						<button type="submit" class="btn btn-primary">GET</button>
						<button type="reset"  class="btn btn-default">Clear</button>
					</div>
				</div>
			</form>
		
			<!-- Table -->
			<table class="table table-striped">
			<thead>
				<tr>
					<th>Date</th>
					<th>Payment Type</th>
					<th>Account No</th>
					<th>Frequency</th>	
					<th>Distribution</th>
					<th>Amount</th>
				</tr>
			</thead>
			<tbody id="givings">
			</tbody>
			</table>
		
		</div>
	</div>

	<div class="panel panel-success table-responsive">
		<div class="panel-heading">
			<H3 class="panel-title">Total Giving</H3>
		</div>

		<!-- Table -->
		<table class="table table-striped">
			<tbody id="total">
			</tbody>
		</table>
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

var table;					// variable for holding giving history
var givings 	= [];

$(document).ready(function () {
	$('#navbar-history').addClass('active');
	
	// Get current year
	var d 		= new Date();
	var cyear 	= d.getFullYear();


	// Trigger proper method based on the form clicked
	$("form").submit(function(event) {
		event.preventDefault();

		// Hide alert messages
		$(".alert").hide();
		console.log(event.target.id);

		switch(event.target.id) {
			case "statementRE":
				processStatementInRE();
				break;
			case "getHistory":
				processGivingHistory();
				break;
			default:
				break;
		}

	});

});		// End for $(document).ready(


///////////////////////////////////////////////////////////////////////////
//	Sending a request to download PDF Giving Statement at Raiser's Edge
///////////////////////////////////////////////////////////////////////////
function processStatementInRE() 
{
	var cid 	= $("#cidRE").val();
	var year 	= $("#yearRE").val();
	
	console.log("CID: " + cid + ", Year: " + year);
	
	window.location.href="http://finance.northlandcc.net/tcpdf/TCPDF-master/examples/statement.php?cid=" + cid + "&year=" + year;
}


///////////////////////////////////////////////////////////////////////////
//	Sending a request to retrieve giving data from Online Giving Records
///////////////////////////////////////////////////////////////////////////
function processGivingHistory() 
{
	var cid 	= $("#cid").val();
	var year 	= $("#year").val();

	// Get giving history on selected year for a CID
	$.ajax({
		type: "POST",
		url: "ws/webservice.php",
		data: "srv=finance_get_giving_history&format=json&cid="+cid+"&year="+year,
		dataType: "json",
	})
	.done(function(data) {
		parseGetGivingHistory(data);
	})
	.fail(function(jqXHR, textStatus) {
		alert("Request failed: " + textStatus);
	})
	.always(function() { });

}


///////////////////////////////////////////////////////////////////////////
//	Parsing Giving histories and display on the browser
///////////////////////////////////////////////////////////////////////////
function parseGetGivingHistory(data) 
{ 
	var jsonObj 	= $.parseJSON(data);

	var error_len 	= jsonObj.northland_api[1].errors.length;
	var html		= "";
	// Error handling for the request
	if (error_len > 0) 
	{
		var error_arr 		= jsonObj.northland_api[1].errors;
		var error_message 	= '';
		for (var i=0; i<error_len; i++)
			error_message  += error_arr[i].number + ": " + error_arr[i].type + " - " + error_arr[i].message + "\n";
		
		// Display empty in the table
		$("#givings").html(html);
		$("#total").html(html);
		
		alert (error_message);
	} 
	else 
	{
		// Error handling
		if (jsonObj.northland_api[2].response.error != null) 
		{
			var error_arr 		= jsonObj.northland_api[2].response.error;

			var error_message 	= error_arr.message;
			$("#resultInsite").html(error_arr.message);
			$("#resultInsite").show();
			
			// Display empty in the table
			$("#givings").html(html);
			$("#total").html(html);
		} 
		// Display recurring information
		else 
		{
			givings = jsonObj.northland_api[2].response.givings;
			// Display gifts into the table
			displayGivingHistory(givings);
		}

	}
}


///////////////////////////////////////////////////////////////////
// 		Display gifts information into the table
///////////////////////////////////////////////////////////////////
function displayGivingHistory(data)
{
	var genTotal	= parseFloat(parseFloat(0).toFixed(2));
	var misTotal	= parseFloat(parseFloat(0).toFixed(2));
	var escTotal	= parseFloat(parseFloat(0).toFixed(2));
	var genDesc		= "Tithes and Offerings";
	var misDesc		= "Offering/Missions/Service";
	var escDesc		= "Other(Sites/Sharing/etc)";

	// Get giving information in the table
	var html = "";
	
	for(var i=0; i<data.length; i++) 
	{
		var txnid 		= data[i].PK_transaction;
		var date		= data[i].txndate;
		// Make transaction date for display (i.e. MM/DD/YYYY)
		var txndate		= date.substring(5, 7) + "/" + date.substring(8, 10) + "/" + date.substring(0, 4);
		var totalamount	= data[i].amount;
		var source		= (data[i].source).toLowerCase();
		var fundcode	= (data[i].fundcode).toLowerCase();
		var paymentinfo	= "";
		var accountno	= "****";
		
		// Make Payment type and Account number for display
		if (data[i].paymenttype == "creditcard") 
		{
			if (source == 'text') {
				paymentinfo	= 'Text';
			} else {
				// i.e. Visa xxxx
				paymentinfo = returnCardType(data[i].cardtype);
			}
			accountno  += data[i].cardlast4;
		} 
		else if (data[i].paymenttype == "bankaccount")
		{
			// i.e. BoA xxxx
			paymentinfo = "eCheck";
			accountno  += data[i].banklast4;
		}
		else if ((data[i].paymenttype == "BusinessCheck") || (data[i].paymenttype == "PersonalCheck") || (data[i].paymenttype == "Cash"))
		{
			paymentinfo = data[i].paymenttype;
			accountno  	= "";
		}
		else
		{
			paymentinfo	= 'Other';
			accountno	= "";
		}

		// Make Location info
		var location 	= returnLocation(source, fundcode);
		
		// Make Frequency info
		var frequency 	= returnFrequency(data[i].frequency);
		
		// Make Distribution info
		var dists 			= data[i].dists;
		var fundnameHtml 	= "";
		var fundamountHtml 	= "";
		// Display each distribution
		for (var j=0; j<dists.length; j++)
		{
			var fundtype 	= dists[j].fundtype;
			var fundamount	= dists[j].amount;

			if (fundamount <= 0)
				continue;

//			fundnameHtml 	+= dists[j].fundName + "<BR />";
			fundnameHtml 	+= returnFundName(dists[j].fundtype) + "<BR />";
			fundamountHtml 	+= "$ " + fundamount + "<BR />";
		
			// Calculate total amount per fund
			switch(fundtype) {
				case "GEN":
					genTotal += parseFloat(parseFloat(fundamount).toFixed(2));
					break;
				case "MIS":
					misTotal += parseFloat(parseFloat(fundamount).toFixed(2));
					break;
				default:
					escTotal += parseFloat(parseFloat(fundamount).toFixed(2));
					break;
			}
		}
		
		html += "<tr>"
			+ "<td>" + txndate + "</td>"
			+ "<td>" + paymentinfo + "</td>"
			+ "<td>" + accountno + "</td>"
			+ "<td>" + frequency + "</td>"
			+ "<td>" + fundnameHtml + "</td>"
			+ "<td>" + fundamountHtml + "</td>"
			+ "</tr>";

	}	// End of the whole giving loop

	// Display giving information in the table
	$("#givings").html(html);

	// Assemble Summary Giving information
	var total = "<tr>"
			  +	"<td id='genTotal' data-container='body' data-placement='top' data-toggle='tooltip' title='All tithes and offerings go into one fund to resource ongoing ministry efforts, eliminate our building debt and fund church network expansion over a two-year period.'>" 
			  + genDesc + " <span class='glyphicon glyphicon-question-sign' aria-hidden='true'></span>"
			  + "</td>" + "<td> $ " + parseFloat(genTotal).toFixed(2) + "</td>"
//			  +	"<td id='genTotal'>" + genDesc + "</td>" + "<td> $ " + parseFloat(genTotal).toFixed(2) + "</td>"
			  + "</tr>";

	if (misTotal > 0) {
		total += "<tr>"
			  +	"<td>" + misDesc + "</td>" + "<td> $ " + parseFloat(misTotal).toFixed(2) + "</td>"
			  + "</tr>";
	}

	if (escTotal > 0) {
		total += "<tr>"		
			  +	"<td>" + escDesc + "</td>" + "<td> $ " + parseFloat(escTotal).toFixed(2) + "</td>"
			  + "</tr>";
	}


	// Display summary information in the table
	$("#total").html(total);

    $('[data-toggle="tooltip"]').tooltip({placement: 'top'});   

}



</script>

<?php endif; ?>


</body>

</html>
