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
        
        $data = [
            'income_outcomes' => $income_outcomes,
            'today_income' => $today_income,
            'today_outcome' => $today_outcome
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