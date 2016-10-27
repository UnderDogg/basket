<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
    <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="headingFinancial">
            <h4 class="panel-title">
                Financial <small>(Optional)</small> <span class="financial-status"></span>
                <p class="pull-right">
                    <a role="button" class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseFinancial" class="collapsed">
                        <span class="if-collapsed"><span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></span>
                        <span class="if-not-collapsed"><span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span></span>
                    </a>
                </p>
            </h4>
        </div>
        <div id="collapseFinancial" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFinancial">
            <div class="panel-body">
                <div class="col-sm-12">
                    <form class="form-horizontal" id="financial" method="POST" data-fv-framework="bootstrap" data-fv-icon-valid="glyphicon glyphicon-ok" data-fv-icon-invalid="glyphicon glyphicon-remove" data-fv-icon-validating="glyphicon glyphicon-refresh">
                        {!! Form::hidden('user', isset($user) ? $user : null) !!}
                        <div class="form-group">
                            {!! Form::label('monthly_income', 'Monthly Income', ['class' => 'col-sm-2 control-label text-right']) !!}
                            <div class="col-sm-8">
                                {!! Form::input('number', 'monthly_income', isset($monthly_income) ? $monthly_income : null, ['class' => 'form-control col-xs-12', 'min' => '0', 'max' => '99999', 'placeholder' => 'Amount in whole &pound;', 'data-fv-integer' => 'true', 'data-fv-integer-message' => 'Your income can only be numeric']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('monthly_outgoings', 'Monthly Outgoings', ['class' => 'col-sm-2 control-label text-right']) !!}
                            <div class="col-sm-8">
                                {!! Form::input('number', 'monthly_outgoings', isset($monthly_outgoings) ? $monthly_outgoings : null, ['class' => 'form-control col-xs-12', 'min' => 0, 'max' => '99999', 'placeholder' => 'Amount in whole &pound;', 'data-fv-integer' => 'true', 'data-fv-integer-message' => 'Monthly debt repayments can only be numeric']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('bank_sort_code', 'Bank Sort Code', ['class' => 'col-sm-2 control-label text-right']) !!}
                            <div class="col-sm-8">
                                {!! Form::text('bank_sort_code', isset($bank_sort_code) ? $bank_sort_code : null, ['class' => 'form-control col-xs-12',
                                 'maxlength' => 8, 'data-fv-regexp' => 'true',
                                 'data-fv-regexp-regexp' => '^[0-9][0-9]\-[0-9][0-9]\-[0-9][0-9]$', 'data-fv-regexp-message' => 'The sort code must be in format 00-00-00']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('bank_account', 'Bank Account Number', ['class' => 'col-sm-2 control-label text-right']) !!}
                            <div class="col-sm-8">
                                {!! Form::input('number', 'bank_account', isset($bank_account) ? $bank_account : null, ['class' => 'form-control col-xs-12',
                                 'maxlength' => 8, 'data-fv-regexp' => 'true',
                                 'data-fv-regexp-regexp' => '^[0-9]{8}$', 'data-fv-regexp-message' => 'The bank account number must be 8 digits, if your account number is only 7 digits add 0 at the start']) !!}
                            </div>
                        </div>
                        {!! Form::token() !!}
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-8">
                                <a class="btn btn-info" data-target="save" data-source="ajax">Save Financial</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@if(isset($validation) && $validation == true)
    <script>
        $(document).ready(function() {
            var validators = {
                callback: {
                    message: 'You must enter both the sort code and the account number, or neither',
                    callback: function (value, validator) {
                        var notEmpty = false;
                        var atleastOne = false;
                        var sortCode = validator.getFieldElements('bank_sort_code');
                        if (sortCode.eq(0).val() !== '') {
                            atleastOne = true;
                        }
                        var accountNumber = validator.getFieldElements('bank_account');
                        if (accountNumber.eq(0).val() !== '') {
                            if (atleastOne == true) {
                                notEmpty = true;
                            }
                            atleastOne = true;
                        }

                        if (atleastOne == true && notEmpty == true) {
                            validator.updateStatus('bank_sort_code', validator.STATUS_VALID, 'callback');
                            validator.updateStatus('bank_account', validator.STATUS_VALID, 'callback');
                            return true;
                        }
                        return false;
                    }
                }
            };

            $('#financial').formValidation({
                framework: 'bootstrap',
                icon: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields: {
                    bank_sort_code: {validators: validators},
                    bank_account: {validators: validators}
                }
            });
        });
    </script>
@endif
