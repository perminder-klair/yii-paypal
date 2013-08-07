<?php

Yii::import('ext.paypal.models.PaymentFormModel');

class PaymentForm extends CWidget
{

    /**
     * Initializes the widget.
     */
    public function init()
    {

    }

    /**
     * Runs the widget.
     */
    public function run()
    {
        $model = new PaymentFormModel;

        $this->render('paymentForm', array(
            'model'=>$model,
        ));
    }
}