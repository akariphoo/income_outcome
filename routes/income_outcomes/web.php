<?php 
use Illuminate\Support\Facades\Route;

Route::post('/{income_outcome?}', 'IncomeOutcomeController@store')->name('income_outcome.store');