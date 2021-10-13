@extends('layouts.admin')
@section('content')		
@section('admin_users_select','active')	
<h5 class="text-left pl-3">Set Permissions</h5>
<div class="admin-head d-flex align-items-center justify-content-end">
    <div class=""><a href="{{url(route('admin-management.index'))}}" class="btn btn-primary">Back</a></div>
</div>
<div class="col-12">
	<form action="{{url('admin/admin-management/Permissions/store',$id)}}" method="post">
		@csrf
		<div class="my-brand-section overflow-hidden">
		    <div class="table-responsive">
		        <table id="dataTableExample" class="table">
		            <thead>
		                <tr>
		                    <th class="">Menu Name</th>
		                    <th class="">Read</th>
		                    <th class="">Write</th>
		                </tr>
		            </thead>
		            <tbody>
		                @if($menuArray ?? '')
		                    @foreach($menuArray ?? '' as $key=>$value) 
		                        <tr>
		                            <td class="rating-list"><span><input type="hidden" name="menu_key[]" value="{{$key}}">{{$value}}</span></td>
		                            <td class="rating-list"><input type="checkbox" name="read[]" value="{{$key}}" id="{{$key}}-read" @if($menu_read ?? '')
		                            						@if(in_array($key,$menu_read))
		                            						Checked 
		                            						@endif
		                            					@endif>
		                            </td>
		                            <td class="rating-list"><input type="checkbox" name="write[]" value="{{$key}}" id="{{$key}}-write" onclick="checkRead('{{$key}}-read','{{$key}}-write')" @if($menu_write ?? '')
			                            						@if(in_array($key,$menu_write))
			                            							Checked 
			                            						@endif
		                            						@endif></td> 
		                        </tr>
		                    @endforeach
		               @endif
		            </tbody>
		        </table>
		    </div>
		</div>
		<div class="text-left mt-2 mb-4">
			<button type="submit" class="btn btn-success">Set Permissions</button>
		</div>
	</form>	
</div>
<script>
function checkRead(read,write){
	let read_permission = document.getElementById(read);
	let write_permission = document.getElementById(write);
	let read_permission_in_start;

	if (read_permission.checked == true){
	    read_permission_in_start = true;
	  }

	if (write_permission.checked == true){
	    read_permission.checked = true;
	  } else {
	    read_permission.checked = false;
	  }

	if (read_permission_in_start == true){
	    read_permission.checked = true;
	  }  

}
</script>
@endsection