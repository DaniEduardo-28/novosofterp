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


     use Illuminate\Support\Facades\Route;

    $current_hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

    if ($current_hostname) {
        Route::domain($current_hostname->fqdn)->group(function () {
            Route::middleware(['auth', 'locked.tenant'])->group(function () {
                Route::prefix('digemid')->group(function () {
                    Route::get('/list', 'DigemidController@index')->name('tenant.digemid.index');
                    Route::get('/products', 'DigemidController@products')->name('tenant.digemid.products');
                    Route::get('/caja', 'DigemidController@caja')->name('tenant.digemid.caja');
                    Route::get('/pos', 'DigemidController@pos')->name('tenant.digemid.pos');
                    Route::post('/update_exportable/{item?}', 'DigemidController@updateExportableItem');
                });

                Route::get('laboratory', 'LaboratoryController@index')->name('tenant.laboratory.index')->middleware('redirect.level');
                Route::get('laboratory/records', 'LaboratoryController@records');
                Route::get('laboratory/columns', 'LaboratoryController@columns');
                Route::get('laboratory/record/{laboratory}', 'LaboratoryController@record');
                Route::post('laboratory', 'LaboratoryController@store');
                Route::delete('laboratory/{laboratory}', 'LaboratoryController@destroy');

                Route::get('manufacturer', 'ManufacturerController@index')->name('tenant.manufacturer.index')->middleware('redirect.level');
                Route::get('manufacturer/records', 'ManufacturerController@records');
                Route::get('manufacturer/columns', 'ManufacturerController@columns');
                Route::get('manufacturer/record/{manufacturer}', 'ManufacturerController@record');
                Route::post('manufacturer', 'ManufacturerController@store');
                Route::delete('manufacturer/{manufacturer}', 'ManufacturerController@destroy');

                Route::get('importer', 'ImporterController@index')->name('tenant.importer.index')->middleware('redirect.level');
                Route::get('importer/records', 'ImporterController@records');
                Route::get('importer/columns', 'ImporterController@columns');
                Route::get('importer/record/{importer}', 'ImporterController@record');
                Route::post('importer', 'ImporterController@store');
                Route::delete('importer/{importer}', 'ImporterController@destroy');

                Route::get('origin', 'OriginController@index')->name('tenant.origin.index')->middleware('redirect.level');
                Route::get('origin/records', 'OriginController@records');
                Route::get('origin/columns', 'OriginController@columns');
                Route::get('origin/record/{origin}', 'OriginController@record');
                Route::post('origin', 'OriginController@store');
                Route::delete('origin/{origin}', 'OriginController@destroy');

                Route::get('patient', 'PatientsController@index')->name('tenant.patient.index')->middleware('redirect.level');
                Route::get('patient/records', 'PatientsController@records');
                Route::get('patient/tables', 'PatientsController@tables');
                Route::get('patient/columns', 'PatientsController@columns');
                Route::get('patient/record/{patient}', 'PatientsController@record');
                Route::post('patient', 'PatientsController@store');
                Route::delete('patient/{patient}', 'PatientsController@destroy');

                Route::get('active_principles', 'ActivePrinciplesController@index')->name('tenant.active_principles.index')->middleware('redirect.level');
                Route::get('active_principles/records', 'ActivePrinciplesController@records');
                Route::get('active_principles/columns', 'ActivePrinciplesController@columns');
                Route::get('active_principles/record/{active_principles}', 'ActivePrinciplesController@record');
                Route::post('active_principles', 'ActivePrinciplesController@store');
                Route::delete('active_principles/{active_principles}', 'ActivePrinciplesController@destroy');

                Route::get('pharmacological_action', 'PharmacologicalActionController@index')->name('tenant.pharmacological_action.index')->middleware('redirect.level');
                Route::get('pharmacological_action/records', 'PharmacologicalActionController@records');
                Route::get('pharmacological_action/columns', 'PharmacologicalActionController@columns');
                Route::get('pharmacological_action/record/{pharmacological_action}', 'PharmacologicalActionController@record');
                Route::post('pharmacological_action', 'PharmacologicalActionController@store');
                Route::delete('pharmacological_action/{pharmacological_action}', 'PharmacologicalActionController@destroy');

                Route::get('cycles', 'CyclesController@index')->name('tenant.cycles.index')->middleware('redirect.level');
                Route::get('cycles/records', 'CyclesController@records');
                Route::get('cycles/columns', 'CyclesController@columns');
                Route::get('cycles/record/{cycles}', 'CyclesController@record');
                Route::post('cycles', 'CyclesController@store');
                Route::delete('cycles/{cycles}', 'CyclesController@destroy');
            });
        });
    }
