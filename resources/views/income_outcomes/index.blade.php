<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Income Outcome Calculation</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/argon-design-system-free@1.2.0/assets/css/argon-design-system.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>
<body>
    <div class="container-fluid mt-5">
        @if (session('success'))
            <div class="col-md-6 alert alert-success mx-auto d-block">{{ session('success') }}</div>
        @endif
        <div class="row text-center">
            {{-- form --}}
            <div class="col-12">
                <div class="card card-body">
                    <form action="{{ route('income_outcome.store') }}" method="POST">
                        @csrf
                        <input type="text" class="btn btn-dark text-white" name="description">
                        <input type="number" class="btn btn-dark text-white" name="amount">
                        <input type="date" class="btn btn-dark text-white" name="date">
                        <select name="type" class="btn btn-dark text-white">
                            <option value="income">ဝင်ငွေ</option>
                            <option value="outcome">ထွက်ငွေ</option>
                        </select>
                        <input type="submit" value="စာရင်းသွင်းမယ်" class="btn btn-success text-white">
                    </form>
                </div>
            </div>
            {{-- lists --}}
            <div class="col-4">
                <ul class="list-group mt-3 overflow-auto">
                   @foreach ($income_outcomes as $list)
                        <li class="list-group-item d-flex justify-content-between">
                            <div>
                               <small>{{ $list->description }}</small> 
                                    <br>
                                <small class="text-muted">{{ $list->date }}</small>
                            </div>
                            @if ($list->type == 'income')
                                <small class="text text-success"> + {{ $list->amount }}ကျပ်</small>
                            @else
                                <small class="text text-danger"> - {{ $list->amount }}ကျပ်</small>
                            @endif                            
                        </li>                     
                   @endforeach            
                </ul>
            </div>
            {{-- bar chart --}}
            <div class="col-4">
                <div class="card card-body mt-3">
                    <div class="d-flex justify-content-between">
                        <h5>Today Chart</h5>
                        <div>
                            <small class="text text-success">
                                ဝင်ငွေ + {{ $today_income }} ကျပ်
                            </small>
                            <small class="text text-danger ml-3">
                                ထွက်ငွေ - {{ $today_outcome }} ကျပ်
                            </small>
                            <input type="date" id="daily-date-picker" class="btn btn-white text-dark mb-2 ml-2" name="date">
                        </div>
                    </div>
                    <hr class="p-0 m-0">
                    <div class="mt-3">
                        <canvas id="in_out"></canvas>
                    </div>
                </div>
            </div>
             {{-- pie chart --}}
             <div class="col-4">
                <div class="card card-body mt-3">
                    <div class="d-flex justify-content-between">
                        <h5>Monthly Chart</h5>
                        <div>
                            <small class="text text-success">
                                ဝင်ငွေ + {{ $monthly_income_amount }} ကျပ်
                            </small>
                            <small class="text text-danger ml-3">
                                ထွက်ငွေ - {{ $monthly_outcome_amount }} ကျပ်
                            </small>
                            <input type="month" id="monthly-date-picker" class="btn btn-white text-dark mb-2" name="month">
                        </div>
                    </div>
                    <hr class="p-0 m-0">
                    <div class="mt-3" style="width:300px; height:300px; margin-left:150px;">
                        <canvas id="pie-chart"></canvas>                    
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript">
        const ctx = document.getElementById('in_out');
        var day_arr = @json($day_arr);
        var daily_income_amount = @json($daily_income_amount);
        var daily_outcome_amount = @json($daily_outcome_amount);

        // for bar chart
        let barChart = null;
        let pieChart = null;
        showBarChart(day_arr, daily_income_amount, daily_outcome_amount);

        // for pie chart
        showPieChart({{ $monthly_income_amount }}, {{ $monthly_outcome_amount }})

        //daily data
        $('#daily-date-picker').on('change', function () {
            var date = $(this).val();

            $.ajax({
                type: "GET",
                url: "/income-outcome/get_daily_data",
                data: {
                    date: date
                },
                success: function(result) {
                    showBarChart(result['day_arr'], result['daily_income_amount'], result['daily_outcome_amount']);
                },
                error: function(result) {
                    console.log(result)
                }
            });
        })

        //monthly data
        $('#monthly-date-picker').on('change', function () {
            var date = $(this).val();

            $.ajax({
                type: "GET",
                url: "/income-outcome/get_monthly_data",
                data: {
                    date: date
                },
                success: function(result) {
                    console.log(result['monthly_income_amount']);
                    showPieChart(result['monthly_income_amount'], result['monthly_outcome_amount'])
                },
                error: function(result) {
                    console.log(result)
                }
            });
        })

        // show bar chart
        function showBarChart(day_arr, daily_income_amount, daily_outcome_amount) {
            if(barChart != null) {
                barChart.destroy();
            }
               barChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: day_arr,
                        datasets: [
                            {
                                label: 'ဝင်ငွေ',
                                data: daily_income_amount,
                                borderWidth: 1,
                                backgroundColor: '#2DCE89'
                            },
                            {
                                label: 'ထွက်ငွေ',
                                data: daily_outcome_amount,
                                borderWidth: 1,
                                backgroundColor: '#F53650'
                            },
                        ]
                    },
                    options: {
                        scales: {
                        y: {
                            beginAtZero: true
                        }
                        }
                    }
            });
        }

        // show pie chart
        function showPieChart(monthly_income_amount, monthly_outcome_amount) {
            if( pieChart != null ) {
                pieChart.destroy();
            }
            pieChart = new Chart(document.getElementById("pie-chart"), {
        	type : 'pie',
        	data : {
        		labels : [ "ဝင်ငွေ", "ထွက်ငွေ"],
        		datasets : [ {
        			backgroundColor : [ "#2DCE89", "#FB3569" ],
        			data : [monthly_income_amount, monthly_outcome_amount]
        		} ]
        	},
        	options : {
        		title : {
        			display : true,
        			text : 'Chart JS Pie Chart Example'
        		}
        	}
        });
        }
        
    </script>
</body>
</html>