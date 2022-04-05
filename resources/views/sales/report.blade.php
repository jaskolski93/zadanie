@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <form method="POST" action="{{route('sales.report')}}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <div class="col-md-8 col-md-offset-2">
                <div class="form-row">
                    <div class="col-md-5">
                        <label for="staticEmail" class="col-sm-12 col-form-label">Data zamówienia od</label>
                        <input value="{{$request->dateFrom}}" name="dateFrom" type="date" class="form-control" placeholder="Data od">
                    </div>
                    <div class="col-md-6">
                        <label for="staticEmail" class="col-sm-12 col-form-label">Data zamówienia do</label>
                        <input value="{{$request->dateTo}}" name="dateTo" type="date" class="form-control" placeholder="Data do">
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary">Generuj</button>
                    </div>
                </div>
            </div>
        </form>
    <div class="row">
        <div class="col-md-6" style="margin-top:15px">
            <table class="table data-table" id="table" >
                <thead>
                    <tr> 
                        <th>Grupa</th>
                        <th>Dzień</th>
                        <th>Kwota netto [zł]</th>
                        <th>Kwota brutto [zł]</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data AS $row)
                        <tr>
                            <td>{{$row->nazwa}}</td>
                            <td>{{$row->data}}</td>
                            <td>{{round($row->cena_netto,2)}}</td>
                            <td>{{round($row->cena_brutto,2)}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-md-6" style="margin-top:15px">
            <div id="chart_div">
                       @foreach($chartData AS $category => $categoryArr)
        @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
   <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
   <script type="text/javascript">
    google.charts.load('current', {'packages':['bar']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        ['Grupa', 'Suma netto', 'Suma brutto'],
        @foreach($chartData AS $category => $categoryArr)
            ['{{$category}}',{{$categoryArr['nettoVal']}},{{$categoryArr['bruttoVal']}}] @if(!$loop->last), @endif
        @endforeach
      ]);
      var options = {
        chart: {
        },
        bars: 'vertical' // Required for Material Bar Charts.
      };

      var chart = new google.charts.Bar(document.getElementById('chart_div'));

      chart.draw(data, google.charts.Bar.convertOptions(options));
    }
</script>