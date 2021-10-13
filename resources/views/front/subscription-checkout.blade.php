@extends('layouts.master-dashboard')
@section('content')
<div class="page-content">
    @if(session()->has('error'))
        <span class="text-danger" role="alert">
            {{ session('error') }}
        </span>
    @endif 
    @if(session()->has('success'))
        <span class="text-success" role="alert">
            {{ session('success') }}
        </span>
    @endif 
    <!-- Buy Subscription Section HTML -->
    <div class="buy-subscription-section overflow-hidden">
        <div class="mb-3">
            <h4 class="content-head">Payment Summary</h4>
        </div>
        <form method="post" action="{{route('subscription-payment')}}" autocomplete="off">
            @csrf
            <input type="hidden" name="ptype" value="{{$ptype}}" autocomplete="off">
            <input type="hidden" name="qty" value="{{$qty}}" autocomplete="off">
            <input type="hidden" name="checkpaymntuser" value="{{$checkpaymntuser}}" autocomplete="off">
            <input type="hidden" name="amount" id="totalAmountHide" value="{{$amount}}" autocomplete="off">
            <input type="hidden" name="promo_code" id="promoCodeHide" value="" autocomplete="off">
            <div class="payment-detail">
                <ul>
                    <li><strong>Subscription Type:</strong> <span>{{$subscriptionType}}</span></li>
                    <li><strong>Validity:</strong> <span>{{$validity}}</span></li>
                    <li><strong>Amount:</strong> <span>{{'$'.number_format($amount, 2)}}</span></li>
                    <li>
                        <strong>Promo Discount:</strong> 
                        <span>$<span id="discountAmount">{{number_format(0, 2)}}</span></span>
                    </li>
                    <li>
                        <strong>Payable Amount:</strong> 
                        <span>$<span id="payableAmount">{{number_format($amount, 2)}}</span></span>
                    </li>
                    <li>
                        <strong>Auto Payment:</strong> 
                        <span><input type="checkbox" value="Yes" name="auto_payment" id="auto_payment" {{old('auto_payment', 'Yes') == 'Yes'?'checked="checked"':''}} /></span>
                    </li>
                </ul>
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="form-group">
                            <div>
                                <label for="inputHolder">Promo Code</label>
                                <input type="input" class="form-control" id="promo_code" value="" placeholder="Enter Promo Code">
                                <input type="button" name="" class="btn btn-primary mt-3" autocomplete="off" id="applyPromo" value="Apply">

                            </div>
                            @error('promo_code')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <span class="text-success" id="messageSuccess"></span>
                            <span class="text-danger" id="messageError"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="payment-form">
                <div class="mb-3">
                    <h4 class="content-head">Card Information</h4>
                </div>
                <div class="row">
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <label for="inputNumber">Card Number <span class="important">*</span></label>
                            <input id="inputNumber" type="text" maxlength="16" class="form-control card-number" name="card_number" value="{{old('card_number')}}" autofocus placeholder="Enter Card Number">
                            @error('card_number')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <label for="inputHolder">Card Holder Name <span class="important">*</span></label>
                            <input id="inputHolder" type="text" class="form-control" name="card_holder_name" value="{{old('card_holder_name')}}" autofocus placeholder="Enter Card Holder Name">
                            @error('card_holder_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <label for="inputCvv">CVV <span class="important">*</span></label>
                            <input id="inputCvv" type="text" maxlength="3" class="form-control card-number" name="cvv" value="{{old('cvv')}}" autofocus placeholder="Enter CVV" autocomplete="off">
                            @error('cvv')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <label for="inputExpiry">Card Expiry <span class="important">*</span></label>
                            <input id="inputExpiry" type="text" maxlength="7" class="form-control card-number" name="card_expiry" value="{{old('card_expiry')}}" autofocus placeholder="MM/YYYY" autocomplete="off">
                            @error('card_expiry')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Payment</button>
            </div>
        </form>
    </div>
</div>
<script src="https://code.jquery.com/jquery-1.9.1.js"></script>
<script type="text/javascript">
    // function to load next page records
    $("#applyPromo").click(function() {
        $('#messageSuccess').html('');
        $('#messageError').html('');
        $('#promoCodeHide').val('');

        //code to get old value
        totalAmount = $('#totalAmountHide').val();
        $('#discountAmount').html('0.00');
        $('#payableAmount').html(totalAmount);

        promo_code = $('#promo_code').val();
        if(promo_code == ''){
            $('#messageError').html('Please enter Promo Code.');
        }
        else {
            validatePromo();
        }
    });

    // function to get records
    function validatePromo(){
        promo_code = $('#promo_code').val();
        totalAmount = $('#totalAmountHide').val();
        $.ajax({
            type: 'post',
            url: '{{ URL("validatePromoCodeAjax") }}',
            data: {
                '_token': '{{ csrf_token() }}',
                'promo_code': promo_code,
                'totalAmount': totalAmount,
            },
            success: function(data) {
                console.log(data);
                if (data.status == 'success') {
                    $('#messageSuccess').html(data.message);
                    $('#discountAmount').html(data.discountAmount);
                    $('#payableAmount').html(data.payableAmount);
                    $('#promoCodeHide').val(data.promo_code);
                }
                if (data.status == 'error') {
                    $('#messageError').html(data.message);
                }
            },
        });
    }
</script>
@endsection