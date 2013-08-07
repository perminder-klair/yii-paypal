<?php
class PaymentFormModel extends CFormModel
{
    public $paymentType;

	public $creditCardNumber;
	public $creditCardType;
    public $expDate;
	public $expDateMonth;
    public $expDateYear;
	public $cvv2Number;
	
	public $firstName;
	public $lastName;
	public $address1;
    public $address2;
	public $zip;
	public $city;
    public $state;
	public $country;
	public $phone;

    public $amount;
    public $currency;
    public $notifyURL;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('paymentType, creditCardNumber, creditCardType, expDateMonth, expDateYear, cvv2Number, firstName, lastName, address1,
			zip, city, country, amount, currency', 'required'),
            array('cvv2Number', 'numerical', 'integerOnly'=>true),
            array('firstName, lastName, zip, city, state, country, phone', 'length', 'max'=>50),
            array('address1, address2', 'length', 'max'=>255),
            array('notifyURL', 'url'),
            array('paymentType, creditCardNumber, creditCardType, expDateMonth, expDateYear, cvv2Number, firstName, lastName, address1,
			address1, zip, city, state, country, phone, amount, currency, notifyURL', 'safe'),
		);
	}

	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
            'paymentType'=>'Payment Type',
			'creditCardNumber'=>'Credit Card No',
			'creditCardType'=>'Card Type',
            'expDate'=>'Expiration Date',
			'expDateMonth'=>'Expiration Month',
            'expDateYear'=>'Expiration Year',
			'cvv2Number'=>'CW / CSC',
			'firstName'=>'First Name',
			'lastName'=>'Last Name',
			'address1'=>'Address Line 1',
            'address2'=>'Address Line 2',
			'zip'=>'Postcode',
			'city'=>'City',
            'state'=>'Region',
			'country'=>'Country',
			'phone'=>'Phone',
            'amount'=>'Amount',
            'currency'=>'Currency',
		);
	}

    /**
     * returns an array of months list with names
     * @return array
     */
    public static function expMonthData()
    {
        $months = array();
        for ($i = 1; $i < 13; $i++) {
            $key = date('m', mktime(0, 0, 0, $i, 1));
            $value = date('F', mktime(0, 0, 0, $i, 1));

            $months[$key] = $value;
        }

        return $months;
    }

    /**
     * returns an array of next 10 years
     * @return array
     */
    public static function expYearData()
    {
        $currentYear = date('Y');
        $endYear = date('Y',strtotime('+10 years', strtotime("01-01-$currentYear")));

        $years = array();
        for ($i = $currentYear; $i < $endYear; $i++) {
            $years[$i] = "$i";
        }

        return $years;
    }

    /**
     * returns an array of card type data
     * @return array
     */
    public static function cardTypeData()
    {
        return array(
            'Visa'=>'Visa',
            'MasterCard'=>'MasterCard',
            'Discover'=>'Discover',
            'Amex'=>'American Express',
        );
    }

    /**
     * returns an array of countries list
     * @return array
     */
    public static function countriesData()
    {
        return array(
            'GB'=>'Great Britain',
        );
    }
}