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

Route::get('404', ['as' => '404', 'uses' => 'WelcomeController@error404']);


/****** ACCUEIL ******/

	//Page d'accueil
	Route::get('/', ['as' => 'home', 'uses' => 'WelcomeController@index']);

	//Validation des cookies en ajax
	Route::post('cookie', ['as' => '/', 'uses' => 'WelcomeController@cookie']);

	//Envoi d'un mail aux admins en ajax
	Route::post('contactAdmin', ['as' => '/', 'uses' => 'UserController@contactAdmin']);

	//Envoi d'une proposition d'ajout d'oeuvre
	Route::post('proposition_element', ['as' => '/', 'uses' => 'ElementController@submitProposition']);

/****** CATEGORIES ******/

	//Page de toutes les catégories
	//Route::get('categories', ['as' => 'categories', 'uses' => 'CategoryController@index']);

	//Liste des catégories en ajax
	Route::get('listCategory', ['as' => 'listCategory', 'uses' => 'CategoryController@listCategory']);

	//Redirections JS vers la fiche d'une catégorie
	Route::get('category', ['as' => 'list_category', 'uses' => 'ElementController@index']);
	Route::get('category/{id}', ['as' => 'show_category', 'uses' => 'CategoryController@show']);


/****** OEUVRE *******/
	
	//Liste des oeuvres
	Route::get('elements', ['as' => 'elements', 'uses' => 'ElementController@index']);
	Route::get('element/{id}', ['as' => 'show_element', 'uses' => 'ElementController@show']);
	Route::post('category', ['as' => 'bring_elements', 'uses' => 'AjaxController@getElementsBy']);



	
/****** UTILISATEUR ******/

	//Inscription en ajax
	Route::post('register', ['as' => '/', 'uses' => 'UserController@register']);

	//Authentification en ajax
	Route::post('login', ['as' => '/', 'uses' => 'UserController@login']);
	Route::post('forgot', ['as' => '/', 'uses' => 'UserController@forgotPwd']);
	Route::get('logout', ['as' => 'logout', 'uses' => 'UserController@logout']);

	//Création-Renouvellement du mot de passe
	Route::get('checkToken/{token}', ['as' => '/checkToken', 'uses' => 'UserController@checkToken']);
	Route::post('checkToken', ['as' => '/checkToken', 'uses' => 'UserController@newPwd']);

// Route::group(['middleware' => 'App\Http\Middleware\UserMiddleware'], function()
// {
	//Liste des utilisateurs
	Route::get('users', ['as' => 'users', 'uses' => 'UserController@index']);
	Route::post('users', ['as' => 'bring_users', 'uses' => 'AjaxController@getUsersBy']);

	//Affichage d'un utilisateur
	Route::get('user/{id}', ['as' => 'show_user', 'uses' => 'UserController@show']);

	//Contact d'un utilisateur en ajax
	Route::post('contact', ['as' => '/', 'uses' => 'UserController@contact']);

	//Modifications de l'utilisateur
	Route::patch('user/{id}/infos', ['as' => 'update_user', 'uses' => 'UserController@updateInfo']);
	
	//Recherche d'oeuvres pour échange
	Route::post('user/autocompleteExchange', ['as' => '/', 'uses' => 'UserController@autocompleteExchange']);
	Route::post('user/loadExchange', ['as' => '/', 'uses' => 'UserController@loadExchange']);
	Route::post('user/deleteExchange', ['as' => '/', 'uses' => 'UserController@deleteExchange']);
	Route::post('user/add_exchange', ['as' => 'add_exchange', 'uses' => 'UserController@addExchange']);

	//Suppression
	Route::post('deleteAccount', ['as' => 'deleteAccount', 'uses' => 'UserController@deleteAccount']);


/****** SALONS ******/

	/*DON'T TOUCH */Route::get('future_room', ['as' => 'future_room', 'uses' => 'RoomsController@getFutureRoom']);
	Route::get('rooms', ['as' => 'rooms', 'uses' => 'RoomsController@index']);
	Route::get('rooms/futur_rooms', ['as' => 'futur_rooms', 'uses' => 'RoomsController@showFuturRooms']);
	Route::get('rooms/my_rooms', ['as' => 'my_rooms', 'uses' => 'RoomsController@showMyRooms']);
	//Route::get('room', ['as' => 'next_room', 'uses' => 'RoomsController@index']);
	Route::post('room/join/', ['as' => 'join_room', 'uses' => 'RoomsController@joinBis']);

    Route::get('room/{id}', ['as' => 'show_room', 'uses' => 'RoomsController@show'])->middleware('App\Http\Middleware\RoomMiddleware');
	Route::post('room/report', ['as' => 'report_user', 'uses' => 'RoomsController@reportUser']);
    Route::post('room/block', ['as' => 'block_user', 'uses' => 'RoomsController@blockUser']);
	Route::post('room/invite', ['as' => 'invite_user', 'uses' => 'RoomsController@inviteUser']);
    Route::post('room/update_room', ['as' => 'update_room', 'uses' => 'RoomsController@updateRoom']);
    Route::post('room/interrupt_room', ['as' => 'interrupt_room', 'uses' => 'RoomsController@interruptRoom']);
    Route::post('room/autocompleteUser', ['as' => '/', 'uses' => 'RoomsController@autocompleteUser']);
    Route::post('room/addMessage', ['as' => '/', 'uses' => 'RoomsController@addMessage'] );
    Route::post('room/getMessage', ['as' => '/', 'uses' => 'RoomsController@getMessage'] );
	//Route::get('room/dispatch/', ['as' => 'dispatch_user', 'uses' => 'RoomsController@dispatchUser']);
    Route::get('join_room', ['as' => 'join_invitation', 'uses' => 'RoomsController@displayInvitation']);
    Route::post('join_room', ['as' => 'valid_invitation', 'uses' => 'RoomsController@addInvitation']);

/****** ADMINISTRATION ******/

Route::group(['middleware' => 'App\Http\Middleware\AdminMiddleware'], function()
{
	Route::get('admin', ['as' => 'admin', 'uses' => 'AdminController@index']);
	Route::put('admin/edit-home', ['as' => 'edit_home', 'uses' => 'AdminController@editHome']);	
	Route::put('admin/add-top-element', ['as' => 'add_top_element', 'uses' => 'AdminController@addTopElement']);
	Route::put('admin/delete-top-element', ['as' => 'delete_top_element', 'uses' => 'AdminController@deleteTopElement']);
	Route::post('admin/add-category', ['as' => 'add_category', 'uses' => 'AdminController@addCategory']);
	Route::put('admin/delete-category', ['as' => 'delete_category', 'uses' => 'AdminController@deleteCategory']);
	Route::put('admin/edit-category', ['as' => 'edit_category', 'uses' => 'AdminController@editCategory']);
	Route::post('admin/add-element', ['as' => 'add_element', 'uses' => 'AdminController@addElement']);
	Route::put('admin/delete-element', ['as' => 'delete_element', 'uses' => 'AdminController@deleteElement']);
	Route::put('admin/edit-element', ['as' => 'edit_element', 'uses' => 'AdminController@editElement']);
	Route::post('admin/add-user', ['as' => 'add_user', 'uses' => 'AdminController@addUser']);
	Route::put('admin/edit-user', ['as' => 'edit_user', 'uses' => 'AdminController@editUser']);
	Route::put('admin/delete-user', ['as' => 'delete_user', 'uses' => 'AdminController@deleteUser']);
	Route::put('admin/ban-user', ['as' => 'ban_user', 'uses' => 'AdminController@banUser']);
	Route::post('admin/add-footer', ['as' => 'add_footer', 'uses' => 'AdminController@addFooter']);
	Route::put('admin/edit-footer', ['as' => 'edit_footer', 'uses' => 'AdminController@editFooter']);	
	Route::put('admin/delete-footer', ['as' => 'delete_footer', 'uses' => 'AdminController@deleteFooter']);
	Route::post('admin/add-room', ['as' => 'add_room', 'uses' => 'AdminController@addRoom']);
	Route::put('admin/edit-room', ['as' => 'edit_room', 'uses' => 'AdminController@editRoom']);

	// Ajax
	Route::get('admin/sub-categories', ['as' => 'get_sub_categories', 'uses' => 'AjaxController@getSubCategories']);
	Route::get('admin/creators', ['as' => 'get_creators', 'uses' => 'AjaxController@getCreatorForSubCat']);
	Route::get('admin/elements', ['as' => 'get_elements', 'uses' => 'AjaxController@getElementForCreatorAndCategory']);
	Route::get('admin/element', ['as' => 'get_element', 'uses' => 'AjaxController@getElementById']);
	Route::get('admin/user', ['as' => 'get_user', 'uses' => 'AjaxController@getUserById']);
	Route::get('admin/room', ['as' => 'get_room', 'uses' => 'AjaxController@getRoomById']);
	Route::get('admin/elements_by_catagory', ['as' => 'get_elements_by_category', 'uses' => 'AjaxController@getElementForCategory']);
	Route::get('admin/room-users', ['as' => 'get_users_for_room', 'uses' => 'AjaxController@getUsersForRoom']);
	Route::put('admin/ban-user-from-room', ['as' => 'ban_user_from_room', 'uses' => 'AjaxController@banUserFromRoom']);
	Route::put('admin/ban-user-room', ['as' => 'ban_user_room', 'uses' => 'AjaxController@banUserRoom']);
	Route::put('admin/refuse-ban-user-room', ['as' => 'refuse_ban_user_room', 'uses' => 'AjaxController@refuseBanUserRoom']);
	Route::put('admin/valide-element-suggest', ['as' => 'valide_element_suggest', 'uses' => 'AjaxController@valideElementSuggest']);
	Route::put('admin/refuse-element-suggest', ['as' => 'refuse_element_suggest', 'uses' => 'AjaxController@refuseElementSuggest']);

	Route::post('admin/getApi', ['as' => 'get_api', 'uses' => 'AjaxController@getApi']);
});

//Routes classiques directement vers une Vue en particulier
/* 
Route::get('/', function () {
     return view('welcome');
});

Route::get('article/{n}', function($n) { 
    return view('article')->with('numero', $n); 
})->where('n', '[0-9]+');
*/
