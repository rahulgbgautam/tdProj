@extends('layouts.master-dashboard')
@section('content')
<div class="page-content">
    <!-- Buy Credit Section HTML -->
    <div class="buy-credit-section overflow-hidden">
        <form method="post" action="{{route('subscription-domains-store')}}">
        @csrf
        <input type="hidden" name="qty" value="{{$qty}}">
        <input type="hidden" name="ptype" value="{{$ptype}}">
        <div class="table-responsive">
            <table id="dataTableExample" class="table">
                <thead>
                    <tr>
                        <th class="">Domain to Monitor</th>
                        <th class="">Type</th>
                        <th class="">Industry</th>
                        <th class="">Expiry Date</th>
                    </tr>
                </thead>
                <tbody>
                    @for($i=0; $i<$qty; $i++)
                        <tr>
                            <td class="name"><span><img src="{{asset('/img/grey-company-icon.png')}}" class="Company Icon"></span>
                                <input class="form-control" value="{{old('domain_name.'.$i, $domain_name)}}" name="domain_name[]" type="text" placeholder="www.Domain.com">
                                @error('domain_name.'.$i)
                                    <br>
                                    <span class="text-danger">The domain is required.</span>
                                @enderror
                                @error('invalid_domain.'.$i)
                                    <br>
                                    <span class="text-danger">{{$message}}</span>
                                @enderror
                            </td>

                            <td>
                                <div class="custom-dropdown">
                                    <select class="form-control" name="domain_type[]">
                                        <option value="">Select Type</option>
                                        @foreach($types as $key=> $val)
                                        <option value="{{$key}}" @if(old('domain_type.'.$i, $domain_type)== $key) selected="selected" @endif>{{$val}}</option>
                                        @endforeach
                                    </select>
                                    @error('domain_type.'.$i)
                                        <br>
                                        <span class="text-danger">The type is required.</span>
                                    @enderror
                                </div>
                            </td>
                            <td>
                                <div class="custom-dropdown">
                                    <select class="form-control" name="industry_name[]">
                                        <option value="">Select Industry</option>
                                        @foreach($industry as $value)
                                        <option value="{{$value->id}}" @if(old('industry_name.'.$i, $industry_name)== $value->id) selected="selected" @endif>{{$value->industry_name}}</option>
                                        @endforeach
                                    </select>
                                    @error('industry_name.'.$i)
                                        <br>
                                        <span class="text-danger">The industry type is required.</span>
                                    @enderror 
                                </div>
                            </td>
                            <td>
                                <input type="hidden" name="expiry_date[]" value="{{$expiry_date}}">{{showDate($expiry_date)}}
                            </td>
                        </tr>
                        <?php
                        $domain_name = '';
                        $industry_name = '';
                        $domain_type = '';
                        ?>
                    @endfor
                </tbody>
            </table>
        </div>
        <div class="text-right pt-3">
            <button type="submit" class="btn btn-outline-primary">Proceed</button>
        </div>
    </div>
</form>
    <!-- /.Buy Credit Section HTML -->
</div>
@endsection