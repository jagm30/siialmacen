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
Route::get('entradas/edicion/{id_entrada}/{proveedor}/{fecha}/{nfactura}/{referencia}/{categoria}/{observaciones}',[App\Http\Controllers\UsuarioController::class,'edicion'])->name('edicionEntrada');
Route::resource('/entradas', EntradaController::class);

Route::get('entradaproductos/delete/{id}', [App\Http\Controllers\EntradaProductoController::class,'destroy'])->name('destroyentpro');
Route::get('entradaproductos/listarxentrada/{id}',[App\Http\Controllers\EntradaProductoController::class,'listarxentrada'])->name('listarxentrada');
Route::resource('/entradaproductos', EntradaProductoController::class);
Route::resource('/almacenes', CatAlmacenController::class);
Route::resource('/proveedores', ProveedorController::class);

Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

