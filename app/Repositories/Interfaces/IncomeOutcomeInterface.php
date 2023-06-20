<?php 
namespace App\Repositories\Interfaces;

interface IncomeOutcomeInterface 
{
    // get all income_outcome lists
    public function index();
    
    // store income_outcome datas
    public function store($request, $income_outcome);
}