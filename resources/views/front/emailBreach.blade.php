@extends('layouts.master-dashboard')
@section('content')
<div class="page-content">
    <!-- My Brands Section HTML -->
    @if(session()->has('error'))
        <span class="text-danger" role="alert">
            <strong> {{ session('error') }} </strong>
        </span>
    @endif 
    @if(session()->has('success'))
        <span class="text-success" role="alert">
            <strong> {{ session('success') }} </strong>
        </span>
    @endif 
    <div class="admin-head d-flex align-items-center justify-content-between mb-3">
        <h4 class="content-head">Email Breach</h4>
    </div>
    <div class="row">
        <div class="col-md-12">
            <form id="email" action="{{url('email-breach')}}" method="post">
                @csrf
                @method('post')
                <div class="form-group">
                    <label>My Domain </label>
                    <select class="form-control" name="domain_name" id="domain_name">
                            <option value="">Select Domain</option>
                                @if($domain_data)
                                @foreach($domain_data as $key => $value)
                                <option <?php echo $value->domain_name == $domain_name ? 'selected': '';?>  value="{{$value->domain_name}}" >{{$value->domain_name}}
                                </option>
                                @endforeach
                            @endif 
                    </select>
                     @error('domain_name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <!-- <input type="text" name="domain_name" class="form-control" value="{{old('domain_name', $domain_name)}}" placeholder="Please enter domain name">
                    @error('domain_name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror -->
                </div> 
                <div class="text-left">
                    <button type="submit" class="btn btn-success"> Submit </button>
                </div>
            </form>         
        </div>
    </div>
    @if($result)
        <div>
            <br/><br/>
            <strong>
                {{'TOTAL '. $dehashed_total . ' ' . $domain_name}} emails found
            </strong>
            <br/><br/>
        </div>
        <div class="my-brand-section overflow-hidden">
            <div class="table-responsive">
                <table class="table email-listing">
                    <tbody> 
                        @if(count($dehashed_entire_db) > 0) 
                            @foreach($dehashed_entire_db as $database_name=>$count)
                                <tr>
                                    <td>Users {{$count}} = {{$database_name}} </td>
                                </tr>   
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection