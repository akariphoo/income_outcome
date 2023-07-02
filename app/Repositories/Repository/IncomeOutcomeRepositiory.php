<?php 
namespace App\Repositories\Repository;

use App\Models\IncomeOutcome;
use App\Repositories\Interfaces\IncomeOutcomeInterface;

class IncomeOutcomeRepositiory implements IncomeOutcomeInterface
{
    private $basePath = "income_outcomes.";

    /**
     * get all income_outcome lists
     */
    public function index()
    {
        
        $income_outcomes = IncomeOutcome::orderBy('date', 'desc')->get();
        $today_income = IncomeOutcome::where('date', date('Y-m-d'))->where('type', 'income')->sum('amount');
        $today_outcome = IncomeOutcome::where('date', date('Y-m-d'))->where('type', 'outcome')->sum('amount');

        $getData = $this->getData(date('Y-m-d'));
        // $day_arr = [date('Y-m-d')];
        // $new_date_arr = [
        //     [
        //         'year' => date('Y'),
        //         'month' => date('m'),
        //         'day' => date('d'),
        //     ]
        // ];

        // for($i=1; $i<6; $i++) {
        //     $day_arr[] = date('Y-m-d', strtotime("-$i day"));
        //     $new_date_arr[] = [
        //         'year' => date('Y', strtotime("-$i day")),
        //         'month' => date('m', strtotime("-$i day")),
        //         'day' => date('d', strtotime("-$i day")),
        //     ];
        // }
       
        // foreach($new_date_arr as $date) {
        //     $income_amount []= IncomeOutcome::whereYear('date', $date['year'])
        //                                     ->whereMonth('date', $date['month'])
        //                                     ->whereDay('date', $date['day'])
        //                                     ->where('type', 'income')
        //                                     ->sum('amount');

        //     $outcome_amount []= IncomeOutcome::whereYear('date', $date['year'])
        //                                     ->whereMonth('date', $date['month'])
        //                                     ->whereDay('date', $date['day'])
        //                                     ->where('type', 'outcome')
        //                                     ->sum('amount');
        // }

        // $monthly_income_amount = IncomeOutcome::whereYear('date', date('Y'))->whereMonth('date', date('m'))->where('type', 'income')->sum('amount');
        // $monthly_outcome_amount = IncomeOutcome::whereYear('date', date('Y'))->whereMonth('date', date('m'))->where('type', 'outcome')->sum('amount');

        $data = [
            'income_outcomes' => $income_outcomes,
            'today_income' => $today_income,
            'today_outcome' => $today_outcome,
            'day_arr' => $getData['day_arr'],
            'daily_income_amount' => $getData['daily_income_amount'],
            'daily_outcome_amount' => $getData['daily_outcome_amount'],
            'monthly_income_amount' => $getData['monthly_income_amount'],
            'monthly_outcome_amount' => $getData['monthly_outcome_amount']
        ];

        return viewPath($this->basePath.'index', $data);
    }

    /**
     * store income_outcome data
     * @param IncomeOutcome $income_outcome
     * @param Request $request
     */
    public function store($request, $income_outcome)
    {
        $refineData = $request->except('_token');

        if($income_outcome) {
            $income_outcome->update($refineData);
        } else {
           $income_outcome = IncomeOutcome::create($refineData);
        }

        return redirect()->back()->with('success', 'Data Stored Successfully!');
    }

    /**
     * get daily data
     * @param date
     */
    public function getDailyData($request) 
    {
        return $this->getData($request->date);
    }

    /**
     * get monthly data
     * @param date with only month and year
     */
    public function getMonthlyData($request)
    {
        $date = $request->date;
        $monthly_income_amount = IncomeOutcome::whereYear('date', date('Y', strtotime($date)))->whereMonth('date', date('m', strtotime($date)))->where('type', 'income')->sum('amount');
        $monthly_outcome_amount = IncomeOutcome::whereYear('date', date('Y', strtotime($date)))->whereMonth('date', date('m', strtotime($date)))->where('type', 'outcome')->sum('amount');
        $data = [
            'monthly_income_amount' => $monthly_income_amount,
            'monthly_outcome_amount' => $monthly_outcome_amount
        ];

        return $data;
    }

    public function getData($date)
    {
        $income_amount = [];
        $outcome_amount = [];
        $day_arr = [ ymd($date) ];
        $new_date_arr = [
            [
                'year' => date('Y', strtotime($date)),
                'month' => date('m', strtotime($date)),
                'day' => date('d', strtotime($date)),
            ]
        ];

        for($i=1; $i<6; $i++) {
            $day_arr[] = date('Y-m-d', strtotime("-$i day", strtotime($date)));
            $new_date_arr[] = [
                'year' => date('Y', strtotime("-$i day", strtotime($date))),
                'month' => date('m', strtotime("-$i day", strtotime($date))),
                'day' => date('d', strtotime("-$i day", strtotime($date))),
            ];
        }
       
        foreach($new_date_arr as $date) {
            $income_amount []= IncomeOutcome::whereYear('date', $date['year'])
                                            ->whereMonth('date', $date['month'])
                                            ->whereDay('date', $date['day'])
                                            ->where('type', 'income')
                                            ->sum('amount');

            $outcome_amount []= IncomeOutcome::whereYear('date', $date['year'])
                                            ->whereMonth('date', $date['month'])
                                            ->whereDay('date', $date['day'])
                                            ->where('type', 'outcome')
                                            ->sum('amount');
        }

        $monthly_income_amount = IncomeOutcome::whereYear('date', date('Y'))->whereMonth('date', date('m'))->where('type', 'income')->sum('amount');
        $monthly_outcome_amount = IncomeOutcome::whereYear('date', date('Y'))->whereMonth('date', date('m'))->where('type', 'outcome')->sum('amount');
        $data = [
            'day_arr' => $day_arr,
            'daily_income_amount' => $income_amount,
            'daily_outcome_amount' => $outcome_amount,
            'monthly_income_amount' => $monthly_income_amount,
            'monthly_outcome_amount' => $monthly_outcome_amount
        ];

        return $data;
    }

}