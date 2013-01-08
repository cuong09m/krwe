<?php
/*
 * Template Name: Payment 1
 */
get_header ();
?>

<script language="JavaScript">
	function generateCC(){
		var cc_number = new Array(16);
		var cc_len = 16;
		var start = 0;
		var rand_number = Math.random();

		switch(document.DoDirectPaymentForm.creditCardType.value)
        {
			case "Visa":
				cc_number[start++] = 4;
				break;
			case "Discover":
				cc_number[start++] = 6;
				cc_number[start++] = 0;
				cc_number[start++] = 1;
				cc_number[start++] = 1;
				break;
			case "MasterCard":
				cc_number[start++] = 5;
				cc_number[start++] = Math.floor(Math.random() * 5) + 1;
				break;
			case "Amex":
				cc_number[start++] = 3;
				cc_number[start++] = Math.round(Math.random()) ? 7 : 4 ;
				cc_len = 15;
				break;
        }

        for (var i = start; i < (cc_len - 1); i++) {
			cc_number[i] = Math.floor(Math.random() * 10);
        }

		var sum = 0;
		for (var j = 0; j < (cc_len - 1); j++) {
			var digit = cc_number[j];
			if ((j & 1) == (cc_len & 1)) digit *= 2;
			if (digit > 9) digit -= 9;
			sum += digit;
		}

		var check_digit = new Array(0, 9, 8, 7, 6, 5, 4, 3, 2, 1);
		cc_number[cc_len - 1] = check_digit[sum % 10];

		document.DoDirectPaymentForm.creditCardNumber.value = "";
		for (var k = 0; k < cc_len; k++) {
			document.DoDirectPaymentForm.creditCardNumber.value += cc_number[k];
		}
	}
</script>

<!-- Row for main content area -->
<div id="content" class="eight columns" role="main">

	<div class="post-box">
	<?php
	/**
	 * *********************************************************
	 * DoDirectPayment.php
	 *
	 * Submits a credit card transaction to PayPal using a
	 * DoDirectPayment request.
	 *
	 * The code collects transaction parameters from the form
	 * displayed by DoDirectPayment.php then constructs and sends
	 * the DoDirectPayment request string to the PayPal server.
	 * The paymentType variable becomes the PAYMENTACTION parameter
	 * of the request string.
	 *
	 * After the PayPal server returns the response, the code
	 * displays the API request and response in the browser.
	 * If the response from PayPal was a success, it displays the
	 * response parameters. If the response was an error, it
	 * displays the errors.
	 *
	 * Called by DoDirectPayment.html.php.
	 *
	 * *********************************************************
	 */
	if ($_POST ['DoDirectPaymentBtn']) {
		$path = 'c:\Zend\Apache2\htdocs\keesings\paypal/lib';
		
		set_include_path ( get_include_path () . PATH_SEPARATOR . $path );
		require_once ('services/PayPalAPIInterfaceService/PayPalAPIInterfaceServiceService.php');
		require_once ('PPLoggingManager.php');
		/**
		 * Get required parameters from the web form for the request
		 */
		$logger = new PPLoggingManager ( 'DoDirectPayment' );
		
		$firstName = $_POST ['firstName'];
		$lastName = $_POST ['lastName'];
		
		$address = new AddressType ();
		$address->Name = "$firstName $lastName";
		$address->Street1 = $_POST ['address1'];
		$address->Street2 = $_POST ['address2'];
		$address->CityName = $_POST ['city'];
		$address->StateOrProvince = $_POST ['state'];
		$address->PostalCode = $_POST ['zip'];
		$address->Country = $_POST ['country'];
		$address->Phone = $_POST ['phone'];
		
		$paymentDetails = new PaymentDetailsType ();
		$paymentDetails->ShipToAddress = $address;
		$paymentDetails->OrderTotal = new BasicAmountType ( 'USD', $_POST ['amount'] );
		$paymentDetails->PaymentAction = $_POST ['paymentType'];
		
		$personName = new PersonNameType ();
		$personName->FirstName = $firstName;
		$personName->LastName = $lastName;
		
		$payer = new PayerInfoType ();
		$payer->PayerName = $personName;
		$payer->Address = $address;
		$payer->PayerCountry = $_POST ['country'];
		
		$cardDetails = new CreditCardDetailsType ();
		$cardDetails->CreditCardNumber = $_POST ['creditCardNumber'];
		$cardDetails->CreditCardType = $_POST ['creditCardType'];
		$cardDetails->ExpMonth = $_POST ['expDateMonth'];
		$cardDetails->ExpYear = $_POST ['expDateYear'];
		$cardDetails->CVV2 = $_POST ['cvv2Number'];
		$cardDetails->CardOwner = $payer;
		
		$ddReqDetails = new DoDirectPaymentRequestDetailsType ();
		$ddReqDetails->CreditCard = $cardDetails;
		$ddReqDetails->PaymentDetails = $paymentDetails;
		
		$doDirectPaymentReq = new DoDirectPaymentReq ();
		$doDirectPaymentReq->DoDirectPaymentRequest = new DoDirectPaymentRequestType ( $ddReqDetails );
		$logger->info ( "created doDirectPaymentReq Object" );
		$paypalService = new PayPalAPIInterfaceServiceService ();
		try {
			/*
			 * wrap API method calls on the service object with a try catch
			 */
			$doDirectPaymentResponse = $paypalService->DoDirectPayment ( $doDirectPaymentReq );
		} catch ( Exception $ex ) {
			include_once ("c:\Zend\Apache2\htdocs\keesings\paypal/samples/Error.php");
			exit ();
		}
		if (isset ( $doDirectPaymentResponse )) {
			echo "<table>";
			echo "<tr><td>Ack :</td><td><div id='Ack'>$doDirectPaymentResponse->Ack</div> </td></tr>";
			echo "<tr><td>TransactionID :</td><td><div id='TransactionID'>$doDirectPaymentResponse->TransactionID</div> </td></tr>";
			echo "</table>";
			echo "<pre>";
			print_r ( $doDirectPaymentResponse );
			echo "</pre>";
			require_once 'c:\Zend\Apache2\htdocs\keesings\paypal/samples/Response.php';
		}
	} else {
		?>
		<form method="POST" action="" name="DoDirectPaymentForm">
			<div class="params">
				<div class="param_name">Payment type</div>
				<div class="param_value">
					<select name="paymentType">
						<option value="Sale" selected="selected">Sale</option>
						<option value="Authorisation">Authorisation</option>
					</select>
				</div>
			</div>
			<div class="params">
				<div class="param_name">First name</div>
				<div class="param_value">
					<input type="text" name="firstName" value="John" />
				</div>
			</div>
			<div class="params">
				<div class="param_name">Last name</div>
				<div class="param_value">
					<input type="text" name="lastName" value="Doe" />
				</div>
			</div>
			<div class="params">
				<div class="param_name">Card type</div>
				<div class="param_value">
					<select name="creditCardType"
						onChange="javascript:generateCC(); return false;">
						<option value="Visa" selected="selected">Visa</option>
						<option value="MasterCard">MasterCard</option>
						<option value="Discover">Discover</option>
						<option value="Amex">American Express</option>
					</select>
				</div>
			</div>
			<div class="params">
				<div class="param_name">Card number</div>
				<div class="param_value">
					<input type="text" size="19" maxlength="19" name="creditCardNumber">
				</div>
			</div>
			<div class="params">
				<div class="param_name">Expiry date</div>
				<div class="param_value">
					<select name="expDateMonth">
						<option value="01">01</option>
						<option value="02">02</option>
						<option value="03">03</option>
						<option value="04">04</option>
						<option value="05">05</option>
						<option value="06">06</option>
						<option value="07">07</option>
						<option value="08">08</option>
						<option value="09">09</option>
						<option value="10">10</option>
						<option value="11">11</option>
						<option value="12">12</option>
					</select> <select name="expDateYear">
						<option value="2013">2013</option>
						<option value="2014" selected>2014</option>
						<option value="2015">2015</option>
						<option value="2016">2016</option>
						<option value="2017">2017</option>
						<option value="2018">2018</option>
						<option value="2019">2019</option>
						<option value="2020">2020</option>
					</select>
				</div>
			</div>
			<div class="params">
				<div class="param_name">CVV</div>
				<div class="param_value">
					<input type="text" size="3" maxlength="4" name="cvv2Number"
						value="962">
				</div>
			</div>
			<div class="params">
				<div class="param_name">Amount</div>
				<div class="param_value">
					<input type="text" size="5" maxlength="7" name="amount"
						value="1.00"> USD
				</div>
			</div>
			<div class="section_header">Billing address</div>
			<div class="params">
				<div class="param_name">Address 1</div>
				<div class="param_value">
					<input type="text" size="25" maxlength="100" name="address1"
						value="1 Main St">
				</div>
			</div>
			<div class="params">
				<div class="param_name">Address 2 (optional)</div>
				<div class="param_value">
					<input type="text" size="25" maxlength="100" name="address2"
						value="">
				</div>
			</div>
			<div class="params">
				<div class="param_name">City</div>
				<div class="param_value">
					<input type="text" size="25" maxlength="40" name="city"
						value="San Jose">
				</div>
			</div>
			<div class="params">
				<div class="param_name">State</div>
				<div class="param_value">
					<select id=state name="state">
						<option value=""></option>
						<option value="AK">AK</option>
						<option value="AL">AL</option>
						<option value="AR">AR</option>
						<option value="AZ">AZ</option>
						<option value="CA" selected>CA</option>
						<option value="CO">CO</option>
						<option value="CT">CT</option>
						<option value="DC">DC</option>
						<option value="DE">DE</option>
						<option value="FL">FL</option>
						<option value="GA">GA</option>
						<option value="HI">HI</option>
						<option value="IA">IA</option>
						<option value="ID">ID</option>
						<option value="IL">IL</option>
						<option value="IN">IN</option>
						<option value="KS">KS</option>
						<option value="KY">KY</option>
						<option value="LA">LA</option>
						<option value="MA">MA</option>
						<option value="MD">MD</option>
						<option value="ME">ME</option>
						<option value="MI">MI</option>
						<option value="MN">MN</option>
						<option value="MO">MO</option>
						<option value="MS">MS</option>
						<option value="MT">MT</option>
						<option value="NC">NC</option>
						<option value="ND">ND</option>
						<option value="NE">NE</option>
						<option value="NH">NH</option>
						<option value="NJ">NJ</option>
						<option value="NM">NM</option>
						<option value="NV">NV</option>
						<option value="NY">NY</option>
						<option value="OH">OH</option>
						<option value="OK">OK</option>
						<option value="OR">OR</option>
						<option value="PA">PA</option>
						<option value="RI">RI</option>
						<option value="SC">SC</option>
						<option value="SD">SD</option>
						<option value="TN">TN</option>
						<option value="TX">TX</option>
						<option value="UT">UT</option>
						<option value="VA">VA</option>
						<option value="VT">VT</option>
						<option value="WA">WA</option>
						<option value="WI">WI</option>
						<option value="WV">WV</option>
						<option value="WY">WY</option>
						<option value="AA">AA</option>
						<option value="AE">AE</option>
						<option value="AP">AP</option>
						<option value="AS">AS</option>
						<option value="FM">FM</option>
						<option value="GU">GU</option>
						<option value="MH">MH</option>
						<option value="MP">MP</option>
						<option value="PR">PR</option>
						<option value="PW">PW</option>
						<option value="VI">VI</option>
					</select>
				</div>
			</div>
			<div class="params">
				<div class="param_name">Zip code</div>
				<div class="param_value">
					<input type="text" size="10" maxlength="10" name="zip"
						value="95131"> (5 or 9 digits)
				</div>
			</div>
			<div class="params">
				<div class="param_name">Country</div>
				<div class="param_value">
					<input type="text" size="10" maxlength="10" name="country"
						value="US">
				</div>
			</div>
			<div class="params">
				<div class="param_name">Phone</div>
				<div class="param_value">
					<input type="text" size="10" maxlength="10" name="phone" value="">
				</div>
			</div>

			<div class="params">
				<div class="param_name"></div>
				<div class="param_value"></div>
			</div>
			<div class="submit">
				<input type="submit" name="DoDirectPaymentBtn"
					value="DoDirectPayment" />
			</div>
		</form>
		<?php
	}
	?>
	</div>

</div>
<script language="javascript">
		generateCC();
	</script>
<!-- End Content row -->

<?php get_sidebar(); ?>
		
<?php get_footer(); ?>