# PayPal Yii Extenstion
=======================

## To init, add to import in main.php config
--------------------------------------------
```php
'application.extensions.paypal.Paypal',
'application.extensions.paypal.models.*',
```

# Paypal Direct Payment Pro
===========================

## Example of Payment Method in your controller
-----------------------------------------------
```php
public function actionPayment()
{
    $model = new PaymentFormModel;

    if(isset($_POST['PaymentFormModel']))
    {
        $model->attributes=$_POST['PaymentFormModel'];
        $model->paymentType='Sale'; //Sale or Authorization
        $model->amount='2.00'; //Total Cost
        $model->currency='GBP';

        if($model->validate())
        {
            $paypal = new Paypal;
            if($result = $paypal->DoDirectPayment($model)) {

                if($result->Ack=='Success') {
                    //Order Success
                    var_dump($result);
                } else {
                    //Order Failed
                    var_dump($result);
                }

                exit;
            }
        }
    }

    $this->render('payment');
}
```

## Example of Payment View
--------------------------
```php
$this->widget('ext.paypal.widgets.PaymentForm');
```

# Paypal Reference Transaction
==============================
## Example of Payment Method in your controller
-----------------------------------------------
```php
public function reoccurOrder()
{
    $model = new PaymentFormModel;

    if(isset($_POST['PaymentFormModel']))
    {
        $transactionData['paymentAction'] = 'Sale'; //Sale or Authorization
        $transactionData['paymentType'] = 'Any'; //Any or InstantOnly or EcheckOnly
        $transactionData['txn_id'] = 'TRANSACTION ID HERE';

        $paypal = new Paypal;
        $result = $paypal->doReferenceTransaction($model, $transactionData);

        if($result->Ack=='Success')
            return true; //Success
        else
            return false; //Failed
    }
}
```


## Example of Payment View
--------------------------
```php
$this->widget('ext.paypal.widgets.PaymentForm');
```