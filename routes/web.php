<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
});*/


Route::group(['middleware'=>'admin'], //'prefix'=>'admin',
	function()
	{
		Route::get('/', ['uses'=>'Admin\DashboardController@index', 'as'=>'a.dashboard']);
		Route::get('/home', ['uses'=>'Admin\DashboardController@index', 'as'=>'a.dashboard']);

		Route::get('home/manager', ['uses'=>'Admin\ManagerDashboardController@index', 'as'=>'manager.dashboard']);
		Route::get('home/client', ['uses'=>'Admin\ClientDashboardController@index', 'as'=>'client.dashboard']);
		
		Route::patch('/{id}', 'Admin\DashboardController@userApproved')->name('userApproved');
		Route::delete('/{id}','Admin\DashboardController@destroy')->name('userDelete');
		

		Route::resources(['services' => 'Admin\ServicesController']);

		Route::resources(['clients' => 'Admin\ClientsController']);
		Route::get('clients-draft', ['uses'=>'Admin\ClientsController@draft', 'as'=>'clients.draft']);
		
		Route::get('/clients/{id}/draft/edit', ['uses'=>'Admin\ClientsController@draftedit', 'as'=>'clients.draftedit']);
		Route::post('/clients/{id}/draft/edit', 'Admin\ClientsController@postDraftupdate');

		Route::resources(['contracts' => 'Admin\ContractsController']);

		Route::resources(['uploaded-reports' => 'Admin\ClientUploadedReportsController']);

		Route::resources(['vat-report-submitted' => 'Admin\VatReportSubmittedController']);
		Route::get('vat-report-submitted/create/{id}', ['uses'=>'Admin\VatReportSubmittedController@create', 'as'=>'vat-report-submitted.create']);

		Route::resources(['vat-report-submitted-file' => 'Admin\VatReportSubmittedFileController']);
		Route::get('vat-report-submitted-file/create/{id}', ['uses'=>'Admin\VatReportSubmittedFileController@create', 'as'=>'vat-report-submitted-file.create']);

		//Route::resources(['notifications' => 'Admin\NotificationsController']);
		Route::get('notofications/read', ['uses'=>'Admin\NotificationsController@read', 'as'=>'notofications.read']);

		Route::resources(['notes' => 'Admin\NotesController']);

		Route::resources(['documents' => 'Admin\DocumentsController']);
		Route::get('documents/create/{id?}', ['uses'=>'Admin\DocumentsController@create', 'as'=>'documents.create']);

		Route::resources(['trns' => 'Admin\TRNsController']);

	}
);

