<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'paypal-form',
    'enableClientValidation'=>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
    ),
    'htmlOptions'=>array('class'=>'paypalForm'),
)); ?>

    <div class="row">
        <?php echo $form->labelEx($model,'firstName'); ?>
        <?php echo $form->textField($model,'firstName', array('class'=>'txtInput')); ?>
        <?php echo $form->error($model, 'firstName'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'lastName'); ?>
        <?php echo $form->textField($model,'lastName', array('class'=>'txtInput')); ?>
        <?php echo $form->error($model, 'lastName'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'creditCardType'); ?>
        <?php echo $form->dropDownList($model,'creditCardType', PaymentFormModel::cardTypeData(), array('class'=>'dropDownInput')); ?>
        <?php echo $form->error($model, 'creditCardType'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'creditCardNumber'); ?>
        <?php echo $form->textField($model,'creditCardNumber', array('class'=>'txtInput')); ?>
        <?php echo $form->error($model, 'creditCardNumber'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'expDate'); ?>
        <?php echo $form->dropDownList($model,'expDateMonth', PaymentFormModel::expMonthData(), array('class'=>'dropDownInput')); ?>
        <?php echo $form->error($model, 'expDateMonth'); ?>
        <?php echo $form->dropDownList($model,'expDateYear', PaymentFormModel::expYearData(),  array('class'=>'dropDownInput')); ?>
        <?php echo $form->error($model, 'expDateYear'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'cvv2Number'); ?>
        <?php echo $form->textField($model,'cvv2Number', array('class'=>'txtInput', 'maxlength'=>'3')); ?>
        <?php echo $form->error($model, 'cvv2Number'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'address1'); ?>
        <?php echo $form->textField($model,'address1', array('class'=>'txtInput')); ?>
        <?php echo $form->error($model, 'address1'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'address2'); ?>
        <?php echo $form->textField($model,'address2', array('class'=>'txtInput')); ?>
        <?php echo $form->error($model, 'address2'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'city'); ?>
        <?php echo $form->textField($model,'city', array('class'=>'txtInput')); ?>
        <?php echo $form->error($model, 'city'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'zip'); ?>
        <?php echo $form->textField($model,'zip', array('class'=>'txtInput')); ?>
        <?php echo $form->error($model, 'zip'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'state'); ?>
        <?php echo $form->textField($model,'state', array('class'=>'txtInput')); ?>
        <?php echo $form->error($model, 'state'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'country'); ?>
        <?php echo $form->dropDownList($model,'country', PaymentFormModel::countriesData(), array('class'=>'dropDownInput')); ?>
        <?php echo $form->error($model, 'country'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'phone'); ?>
        <?php echo $form->textField($model,'phone', array('class'=>'txtInput')); ?>
        <?php echo $form->error($model, 'phone'); ?>
    </div>

    <div class="row">
        <?php echo CHtml::submitButton('submit', array('class'=>'submit')); ?>
    </div>

<?php $this->endWidget(); ?>