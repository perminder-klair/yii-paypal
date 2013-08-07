<?php

require_once('PPBootStrap.php');

class Paypal {

    public function __construct()
    {

    }

    public function paymentDetails($data=null)
    {
        $returnArray=array();

        if($data===null)
            return false;

        /**
         * Get required parameters from the web form for the request
         */
        $firstName = $data->firstName;
        $lastName = $data->lastName;

        /*
         * shipping address
        */
        $address = new AddressType();
        $address->Name = "$firstName $lastName";
        $address->Street1 = $data->address1;
        $address->Street2 = $data->address2;
        $address->CityName = $data->city;
        $address->StateOrProvince = $data->state;
        $address->PostalCode = $data->zip;
        $address->Country = $data->country;
        $address->Phone = $data->phone;

        $paymentDetails = new PaymentDetailsType();
        $paymentDetails->ShipToAddress = $address;

        /*
         *  Total cost of the transaction to the buyer. If shipping cost and tax
        charges are known, include them in this value. If not, this value
        should be the current sub-total of the order.

        If the transaction includes one or more one-time purchases, this field must be equal to
        the sum of the purchases. Set this field to 0 if the transaction does
        not include a one-time purchase such as when you set up a billing
        agreement for a recurring payment that is not immediately charged.
        When the field is set to 0, purchase-specific fields are ignored.

        * `Currency Code` - You must set the currencyID attribute to one of the
        3-character currency codes for any of the supported PayPal
        currencies.
        * `Amount`
        */
        $paymentDetails->OrderTotal = new BasicAmountType('USD', $data->amount);

        /*
         * Your URL for receiving Instant Payment Notification (IPN) about this transaction.
         * If you do not specify this value in the request, the notification URL from your Merchant Profile is used, if one exists.
         */
        if(isset($_REQUEST['notifyURL']))
        {
            $paymentDetails->NotifyURL = $_REQUEST['notifyURL'];
        }

        $returnArray['paymentDetails']=$paymentDetails;

        //Card Data Starts here
        if(isset($data->creditCardNumber)) {
            $personName = new PersonNameType();
            $personName->FirstName = $firstName;
            $personName->LastName = $lastName;

            //information about the payer
            $payer = new PayerInfoType();
            $payer->PayerName = $personName;
            $payer->Address = $address;
            $payer->PayerCountry = $data->country;

            $cardDetails = new CreditCardDetailsType();
            $cardDetails->CreditCardNumber = $data->creditCardNumber;

            /*
             *
            Type of credit card. For UK, only Maestro, MasterCard, Discover, and
            Visa are allowable. For Canada, only MasterCard and Visa are
            allowable and Interac debit cards are not supported. It is one of the
            following values:

            * Visa
            * MasterCard
            * Discover
            * Amex
            * Solo
            * Switch
            * Maestro: See note.
            `Note:
            If the credit card type is Maestro, you must set currencyId to GBP.
            In addition, you must specify either StartMonth and StartYear or
            IssueNumber.`
            */
            $cardDetails->CreditCardType = $data->creditCardType;
            $cardDetails->ExpMonth = $data->expDateMonth;
            $cardDetails->ExpYear = $data->expDateYear;
            $cardDetails->CVV2 = $data->cvv2Number;
            $cardDetails->CardOwner = $payer;

            $returnArray['cardDetails']=$cardDetails;
        }

        return $returnArray;
    }

    /**
     * @param $model
     * @return DoDirectPaymentResponseType
     * Submits a credit card transaction to PayPal using a
        DoDirectPayment request.

        The code collects transaction parameters from the form
        displayed by DoDirectPayment.php then constructs and sends
        the DoDirectPayment request string to the PayPal server.
        The paymentType variable becomes the PAYMENTACTION parameter
        of the request string.

        After the PayPal server returns the response, the code
        displays the API request and response in the browser.
        If the response from PayPal was a success, it displays the
        response parameters. If the response was an error, it
        displays the errors.
     */
    public function DoDirectPayment($model)
    {
        if($paymentData = $this->paymentDetails($model))
        {
            $ddReqDetails = new DoDirectPaymentRequestDetailsType();
            $ddReqDetails->CreditCard = $paymentData['cardDetails'];
            $ddReqDetails->PaymentDetails = $paymentData['paymentDetails'];

            $doDirectPaymentReq = new DoDirectPaymentReq();
            $doDirectPaymentReq->DoDirectPaymentRequest = new DoDirectPaymentRequestType($ddReqDetails);

            /*
             * 		 ## Creating service wrapper object
            Creating service wrapper object to make API call and loading
            configuration file for your credentials and endpoint
            */
            $paypalService = new PayPalAPIInterfaceServiceService();
            try {
                /* wrap API method calls on the service object with a try catch */
                $doDirectPaymentResponse = $paypalService->DoDirectPayment($doDirectPaymentReq);
            } catch (Exception $ex) {
                include_once("Error.php");
                exit;
            }

            if(isset($doDirectPaymentResponse)) {
                //print_r($doDirectPaymentResponse);
                //$doDirectPaymentResponse->Ack=='Success';
                return $doDirectPaymentResponse;
            }
        }
    }

    /**
     *  # DoReferenceTransaction API
    The DoReferenceTransaction API operation processes a payment from a
    buyer's account, which is identified by a previous transaction.
    This sample code uses Merchant PHP SDK to make API call
    */

    /*
     *  The total cost of the transaction to the buyer. If shipping cost and
    tax charges are known, include them in this value. If not, this value
    should be the current subtotal of the order.

    If the transaction includes one or more one-time purchases, this field must be equal to
    the sum of the purchases. Set this field to 0 if the transaction does
    not include a one-time purchase such as when you set up a billing
    agreement for a recurring payment that is not immediately charged.
    When the field is set to 0, purchase-specific fields are ignored

    * `Currency ID` - You must set the currencyID attribute to one of the
    3-character currency codes for any of the supported PayPal
    currencies.
    * `Amount`
    */
    public function DoReferenceTransaction($model, $transactionData)
    {
        if($paymentData = $this->paymentDetails($model))
        {
            /*
             * 	 `DoReferenceTransactionRequestDetails` takes mandatory params:

            * `Reference Id` - A transaction ID from a previous purchase, such as a
            credit card charge using the DoDirectPayment API, or a billing
            agreement ID.
            * `Payment Action Code` - How you want to obtain payment. It is one of
            the following values:
            * Authorization
            * Sale
            * Order
            * None
            * `Payment Details`
            */
            $RTRequestDetails = new DoReferenceTransactionRequestDetailsType();

            $RTRequestDetails->PaymentDetails = $paymentData['paymentDetails'];
            $RTRequestDetails->ReferenceID = $transactionData['txn_id'];
            $RTRequestDetails->PaymentAction = $transactionData['paymentAction'];
            $RTRequestDetails->PaymentType = $transactionData['paymentType'];

            $RTRequest = new DoReferenceTransactionRequestType();
            $RTRequest->DoReferenceTransactionRequestDetails  = $RTRequestDetails;

            $RTReq = new DoReferenceTransactionReq();
            $RTReq->DoReferenceTransactionRequest = $RTRequest;

            /*
            ## Creating service wrapper object
            Creating service wrapper object to make API call and loading
            configuration file for your credentials and endpoint
            */
            $paypalService = new PayPalAPIInterfaceServiceService();
            try {
                /* wrap API method calls on the service object with a try catch */
                $RTResponse = $paypalService->DoReferenceTransaction($RTReq);
            } catch (Exception $ex) {
                include_once("../Error.php");
                exit;
            }
            if(isset($RTResponse)) {
                //print_r($RTResponse);
                //$RTResponse->Ack=='Success';
                return $RTResponse;
            }
        }
    }
}