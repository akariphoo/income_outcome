<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Income Outcome Calculation</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/argon-design-system-free@1.2.0/assets/css/argon-design-system.min.css">
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
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#">Home</a></li>
                    <li><a href="#">Menu 1</a></li>
                    <li><a href="#">Menu 2</a></li>
                    <li><a href="#">Menu 3</a></li>
                  </ul>
                <ul class="list-group mt-3">
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
                        <h5>Today Chart</h5>
                        <div>
                            <small class="text text-success">
                                ဝင်ငွေ + 100 ကျပ်
                            </small>
                            <small class="text text-danger ml-3">
                                ထွက်ငွေ - 50 ကျပ်
                            </small>
                        </div>
                    </div>
                    <hr class="p-0 m-0">
                    <div class="mt-3">
                        <canvas id="in_out"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('in_out');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [1,2,3,4,5,6],
                datasets: [
                    {
                        label: 'ဝင်ငွေ',
                        data: [1,2,3,4,5,6],
                        borderWidth: 1,
                        backgroundColor: '#2DCE89'
                    },
                    {
                        label: 'ထွက်ငွေ',
                        data: [1,2,3,4,5,6],
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
    </script>
</body>
</html>