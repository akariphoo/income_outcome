<?php

namespace App\Http\Controllers;

use App\Http\Requests\IncomeOutcome\StoreRequest;
use App\Models\IncomeOutcome;
use App\Repositories\Interfaces\aa;
use App\Repositories\Interfaces\IncomeOutcomeInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IncomeOutcomeController extends Controller
{
    /**
     * get all income_outcome lists
     */
    public function index(
        IncomeOutcomeInterface $incomeOutcomeInterface
    )
    {
        return $incomeOutcomeInterface->index();
    }

    /**
     * store income_outcome data
     * @param Request $request
     * @param IncomeOutcome $income_outcome
     */
    public function store(
        IncomeOutcomeInterface $incomeOutcomeInterface,
        StoreRequest $request,
        IncomeOutcome $income_outcome = null
    )
    {
       return $incomeOutcomeInterface->store($request, $income_outcome);
    }

    /**
     * get daily data
     */
    public function getDailyData(
        IncomeOutcomeInterface $incomeOutcomeInterface,
        Request $request
    )
    {
        return $incomeOutcomeInterface->getDailyData($request);
    }

    /**
     * get monthly data
     */
    public function getMonthlyData(
        IncomeOutcomeInterface $incomeOutcomeInterface,
        Request $request
    )
    {
        return $incomeOutcomeInterface->getMonthlyData($request);
    }

}
