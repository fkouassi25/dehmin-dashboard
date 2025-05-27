<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BeneficiaireController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\BonController;
use App\Http\Controllers\PrestataireController;
use App\Http\Controllers\DonateurController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    //return view('welcome');
    return redirect('/list_benef');
});

Auth::routes();


// Auth routes
Route::get('/sign_up_user', [AuthController::class, 'signUpForm']);
Route::post('/sign_up_user', [AuthController::class, 'signup']);

Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Beneficiaire routes
Route::get('/signup_benef', [BeneficiaireController::class, 'signupForm'])->name('signup_benef');
Route::post('/signup_benef', [BeneficiaireController::class, 'signup']);

Route::get('/update_benef/{code}', [BeneficiaireController::class, 'updateForm'])->name('update_benef');
Route::post('/update_benef', [BeneficiaireController::class, 'update_benef']);

// Beneficiaire routes
Route::get('/list_benef', [BeneficiaireController::class, 'list'])->name('list_benef');
Route::get('/benef/{code}', [BeneficiaireController::class, 'benef'])->name('benef');
Route::get('/bf_carte/{code}', [BeneficiaireController::class, 'print_carte'])->name('print_carte');

Route::get('/list_proposition', [BeneficiaireController::class, 'list_proposition'])->name('list_proposition');
Route::get('/process_proposition/{id}', [BeneficiaireController::class, 'process_proposition_view'])->name('process_proposition_view');
Route::get('/process_proposition/{id}/{choix}', [BeneficiaireController::class, 'process_proposition'])->name('process_proposition');

// Commande routes
Route::get('/list_cmd_attente_regl', [CommandeController::class, 'list_att_regl'])->name('list_cmd_attente_regl');
Route::get('/cmd_att_regl/{code}', [CommandeController::class, 'cmd_att_regl'])->name('cmd_attente_regl');
Route::post('/regl_cmd', [CommandeController::class, 'regler_une_commande']);

Route::get('/list_cmd', [CommandeController::class, 'list_cmd'])->name('list_cmd');
Route::get('/cmd_infos/{code}', [CommandeController::class, 'cmd_infos'])->name('cmd_infos');

// Bon routes
Route::get('/list_bon_non_attribue', [BonController::class, 'list_bon_non_attribue'])->name('list_bon_non_attribue');
Route::get('/list_bon_attribue', [BonController::class, 'list_bon_attribue'])->name('list_bon_attribue');
Route::post('/attribuer', [BonController::class, 'attribuer'])->name('attribuer');
Route::get('/detail_bon/{id}', [BonController::class, 'detail_bon'])->name('detail_bon');

// Prestataire routes
Route::get('/list_presta', [PrestataireController::class, 'list'])->name('list_presta');
Route::get('/presta/{code}', [PrestataireController::class, 'presta'])->name('presta');
Route::post('/presta_approv', [PrestataireController::class, 'approvisionner']);

Route::get('/update_presta/{code}', [PrestataireController::class, 'updateForm'])->name('update_presta');
Route::post('/update_presta', [PrestataireController::class, 'update_presta']);


// Donateur rutes
Route::get('/list_donateur', [DonateurController::class, 'list'])->name('list_donateur');
Route::get('/donateur/{code}', [DonateurController::class, 'donateur'])->name('donateur');

Route::get('/update_donateur/{code}', [DonateurController::class, 'updateForm'])->name('update_donateur');
Route::post('/update_donateur', [DonateurController::class, 'update_donateur']);

// BonController Test routes
// Route::get('/refresh_conso', [BonController::class, 'calcul_conso_benef']);
// Route::get('/test_mail', [BonController::class, 'test_mail_webmaster']);
Route::get('/test_sms', [BonController::class, 'test_sms']);

// BeneficiaireController Test routes
// Route::get('/clean_db', [BeneficiaireController::class, 'clean_db_benef']);
Route::get('/test_api_mtn', [BeneficiaireController::class, 'test_api_mtn']);


