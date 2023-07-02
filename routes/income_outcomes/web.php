<?php 
use Illuminate\Support\Facades\Route;

Route::post('/{income_outcome?}', 'IncomeOutcomeController@store')->name('income_outcome.store');

Route::get('/get_daily_data', 'IncomeOutcomeController@getDailyData')->name('get-daily-data');
Route::get('/get_monthly_data', 'IncomeOutcomeController@getMonthlyData')->name('get-monthly-data');