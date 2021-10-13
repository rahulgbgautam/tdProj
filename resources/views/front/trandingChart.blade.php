<div class="rating-detail text-center">
    <img class="circle-graph" src="{{$chart['speedMeterImage']}}" alt="" />
    <div class="content-grph-list">
        @if($chart['domainRatingMessage'])
            <p>{{$chart['domainRatingMessage']}}</p>
        @endif
        <ul>
            @foreach(getRatings() ?? '' as $key=>$rating)
                <li><span class="badge {{getRatingClass($rating)}}">{{$rating}}</span> {{getRatingValue($rating)}}</li>
            @endforeach
        </ul>
    </div>
    <figure class="highcharts-figure">
        <div id="container{{$chart['type']}}" style="height: 220px"></div>
        <p class="highcharts-description"></p>
    </figure>
</div>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script type="text/javascript">
  Highcharts.chart('container<?php echo $chart['type'] ?>', {
    chart: {
        type: 'line'
    },
    title: {
        text: 'Rating Tracker'
    },
    // subtitle: {
    //     text: 'Source: WorldClimate.com'
    // },
    xAxis: {
        categories: {!! $chart['xAxis'] !!}
    },
    yAxis: {
        title: {
            text: 'Rating (Between A to E)'
        }
    },
    plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            enableMouseTracking: false
        }
    },
    series: [{
        name: '',
        data: {!! $chart['yAxis'] !!}
    }]
});
</script>