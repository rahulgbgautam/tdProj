@extends('layouts.master-dashboard')
@section('content')
<div class="page-content">
    <!-- My Brands Section HTML -->
    <div class="search-box d-flex align-items-center w-40">
        <label class="mb-0">Search:</label>
        <input type="text" name="search" id="searchText" class="form-control ml-3" placeholder="Enter your search here" value="">
    </div>
    <!-- <div class="quote">Type any text/word/keyword in search box which you want to search.</div> -->
    <br />
    <div class="my-brand-section overflow-hidden">
        <div class="table-responsive">
            <table id="dataTableExample" class="table">
                <thead>
                    <tr>    
                        <th>Search Type</th>
                        <th>Search Description</th>
                        <th>How to Search</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>                        
                    @foreach($webTools as $webToolInfo)
                    <tr>
                        <td>{{$webToolInfo['tootbox']}}</td>
                        <td style="white-space: normal;">{{$webToolInfo['description']}}</td>
                        <td style="white-space: normal;">
                            {!! $webToolInfo['how_to_use'] !!}
                        </td>
                        <td class="blue-link">
                            <input type="hidden" name="" id="toolbox{{$webToolInfo['id']}}" value="{{$webToolInfo['critaria']}}">
                            <a title="Search" alt="Search" href="javascript:void(0)" onclick="showResults('<?php echo 'toolbox'.$webToolInfo['id']; ?>')">
                            <img src="{{asset('img/search.jpeg')}}" alt="View Icon" /></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript">
var popupWindow = null;
function showResults(toolboxid){
    var searchText = $('#searchText').val();
    var toolStr = $('#'+toolboxid).val();
    var finalToolStr = toolStr.replace("target", searchText);  
    url = 'https://www.google.com/search?q=' + finalToolStr;

    var w = 1200;
    var h = 600;
    if(screen.width) {
        w = (screen.width)*8/10;
    }
    if(screen.height) {
        h = (screen.height)*7/10;
    }

    var winName = 'myWindow';
    LeftPosition = (screen.width) ? (screen.width-w)/2 : 0;
    TopPosition = (screen.height) ? (screen.height-h)/2 : 0;
    settings =
    'height='+h+',width='+w+',top='+TopPosition+',left='+LeftPosition+',scrollbars=yes,resizable'

    popupWindow = window.open(url,winName,settings)
}
</script>
@endsection