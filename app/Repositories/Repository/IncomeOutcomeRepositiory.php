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
        $income_amount = [];
        $outcome_amount = [];
        
        $income_outcomes = IncomeOutcome::orderBy('date', 'desc')->get();
        $today_income = IncomeOutcome::where('date', date('Y-m-d'))->where('type', 'income')->sum('amount');
        $today_outcome = IncomeOutcome::where('date', date('Y-m-d'))->where('type', 'outcome')->sum('amount');

        $day_arr = [date('Y-m-d')];
        $new_date_arr = [
            [
                'year' => date('Y'),
                'month' => date('m'),
                'day' => date('d'),
            ]
        ];

        for($i=1; $i<6; $i++) {
            $day_arr[] = date('Y-m-d', strtotime("-$i day"));
            $new_date_arr[] = [
                'year' => date('Y', strtotime("-$i day")),
                'month' => date('m', strtotime("-$i day")),
                'day' => date('d', strtotime("-$i day")),
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
            'income_outcomes' => $income_outcomes,
            'today_income' => $today_income,
            'today_outcome' => $today_outcome,
            'day_arr' => $day_arr,
            'daily_income_amount' => $income_amount,
            'daily_outcome_amount' => $outcome_amount,
            'monthly_income_amount' => $monthly_income_amount,
            'monthly_outcome_amount' => $monthly_outcome_amount
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
}