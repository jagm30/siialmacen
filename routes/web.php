<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\CategoriaproductoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\EntradaController;
use App\Http\Controllers\EntradaProductoController;
use App\Http\Controllers\CatAlmacenController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\SalidaController;
use App\Http\Controllers\SalidaproductoController;
use App\Http\Controllers\KardexController;
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

Route::get('/', function () {
    return view('welcome');
});
Route::get('productos/edicion/{id_producto}/{nombre}/{descripcion}/{categoria}/{precio}/{precioPromocion}',[App\Http\Controllers\ProductoController::class,'edicion'])->name('edicion');
Route::get('productos/delete/{id}', [App\Http\Controllers\ProductoController::class,'destroy'])->name('destroy');
Route::resource('/productos', ProductoController::class);
Route::resource('/medicos', MedicoController::class);
Route::resource('/categoriaproductos', CategoriaproductoController::class);
Route::get('usuarios/delete/{id}', [App\Http\Controllers\UsuarioController::class,'destroy'])->name('eliminauser');
Route::get('usuarios/edicion/{id_usuario}/{nombre}/{email}/{password}/{tipo_usuario}',[App\Http\Controllers\UsuarioController::class,'edicion'])->name('edicionUser');
Route::resource('/usuarios', UsuarioController::class);
Route::get('entradas/edicion/{id_entrada}/{proveedor}/{fecha}/{nfactura}/{referencia}/{categoria}/{observaciones}',[App\Http\Controllers\EntradaController::class,'edicion'])->name('edicionEntrada');
Route::get('entradas/delete/{id}', [App\Http\Controllers\EntradaController::class,'destroy'])->name('eliminaent');
Route::get('entradas/finalizarentrada/{id}', [App\Http\Controllers\EntradaController::class,'finalizarentrada'])->name('finalizarentrada');
Route::get('entradas/reportepdf/{id}', [App\Http\Controllers\EntradaController::class,'reportepdf'])->name('reportepdf');
Route::get('entradas/filtrofecha/{fecha1}/{fecha2}', [App\Http\Controllers\EntradaController::class,'filtrofecha'])->name('filtrofecha');
Route::resource('/entradas', EntradaController::class);

Route::get('entradaproductos/delete/{id}', [App\Http\Controllers\EntradaProductoController::class,'destroy'])->name('destroyentpro');
Route::get('entradaproductos/listarxentrada/{id}',[App\Http\Controllers\EntradaProductoController::class,'listarxentrada'])->name('listarxentrada');
Route::resource('/entradaproductos', EntradaProductoController::class);
Route::resource('/almacenes', CatAlmacenController::class);
Route::resource('/proveedores', ProveedorController::class);
Route::resource('/inventario', InventarioController::class);
Route::resource('/kardex', KardexController::class);

Route::get('salidas/finalizarsalida/{id}', [App\Http\Controllers\SalidaController::class,'finalizarsalida'])->name('finalizarsalida');
Route::get('salidas/delete/{id}', [App\Http\Controllers\SalidaController::class,'destroy'])->name('eliminasal');
Route::get('salidas/reportepdf/{id}', [App\Http\Controllers\SalidaController::class,'reportepdf'])->name('reportepdfsalida');
Route::get('salidas/ventauniforme/', [App\Http\Controllers\SalidaController::class,'ventauniforme'])->name('ventauniforme');
Route::get('salidas/showventauniforme/{id}', [App\Http\Controllers\SalidaController::class,'showventauniforme'])->name('showventauniforme');
Route::get('salidas/ventaxalmacen/{almacen}', [App\Http\Controllers\SalidaController::class,'ventaxalmacen'])->name('ventaxalmacen');
Route::get('salidas/edicion/{id_salida}/{folioreq}/{solicitante}/{fecha}/{almacen}/{cajapago}/{nnotaventa}/{fventa}/{status}/{observaciones}/{id_usuario}',[App\Http\Controllers\SalidaController::class,'edicionsalida'])->name('edicionsalida');
Route::get('salidas/filtrofecha/{fecha1}/{fecha2}', [App\Http\Controllers\SalidaController::class,'filtrofecha'])->name('filtrofechasalida');
Route::resource('/salidas', SalidaController::class);
Route::get('salidaproductos/delete/{id}', [App\Http\Controllers\SalidaproductoController::class,'destroy'])->name('destroysalprod');
Route::get('salidaproductos/listarxsalida/{id}',[App\Http\Controllers\SalidaproductoController::class,'listarxsalida'])->name('listarxsalida');
Route::resource('/salidaproductos', SalidaproductoController::class);

Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

