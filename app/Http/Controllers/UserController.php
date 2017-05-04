<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Input;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Mail\Register;
use App\Mail\BackUp;
use App\User;

class UserController extends Controller
{
     /**
     * Affiche la liste de tous les utilisateurs
     *
     * @return view
     */
     public function index()
     {
          // Collection de tous les users
          // $users = User::all();
          $array = User::latest('date_created')->get();
          $array->redirect = 'show_user';

		return view('user.index')
                    ->with(compact('array'));
	}

	/**
     * Affiche le profil d'un utilisateur
     *
     * @param  int  $id
     * @return view
     */
	public function show($id)
	{
          // Récupère l'utilisateur
          $infos = User::findOrFail($id);
          $infos->editAccount  = true;
          //Récupère les oeuvres que l'utilisateur soihaite échanger
          /*$exchange = [1, 2, 3, 4];
          $popUp = 'element.show';
          $array->items = [
                ['id' => 1, 'name' => 'Harry Potter', 'subname' => 'Livre', 'url_img' => '/images/oeuvre.jpg', 'description' => "COUCOU"],
                ['id' => 2, 'name' => 'Interstellar', 'subname' => 'Film', 'url_img' => '/images/oeuvre1.jpg', 'description' => "COUCOU"],
                ['id' => 3, 'name' => 'Paris Games Week', 'subname' => 'Exposition', 'url_img' => '/images/oeuvre2.jpg', 'description' => "COUCOU"],
          ];*/

		return view('user.show')
                    ->with(compact('infos'));/*
                    ->with(compact('exchange'))
                    ->with(compact('array'))
                    ->with(compact('popUp'));*/
	}


	/**
     * Modification des info personnelles d'un utilisateur
     *
     * @param int  $id
     * @param Illuminate\Http\Request $request
     * @return view
     */
	public function updateInfo(Request $request, $id)
	{
          $user = User::findOrFail($id);
          $oldPicture = $user->picture;

          $this->validate($request, [
               'first_name' => 'required',
               'last_name' => 'required',
          ]);
          $input = $request->all();
          $user->fill($input);
          $user->is_contactable = Input::get('is_contactable');

          //Enregistrement de la nouvelle image uploadée
          if (Input::file('picture')!==NULL && Input::file('picture')->isValid()){
               $destinationPath = 'uploads/';
               $extension = Input::file('picture')->getClientOriginalExtension(); // getting image extension
               $fileName = rand(11111,99999).'.'.$extension; // renameing image
               Input::file('picture')->move($destinationPath, $fileName); // uploading file to given path
               $user->picture = "/uploads/".$fileName;
          }
          else 
               $user->picture = $oldPicture;               

          $user->save();
		return $this->show($id);
	}

	/**
     * Modification du mot de passe de l'utilisateur
     *
     * @param  int  $id
     * @param Illuminate\Http\Request $request
     * @return view
     */
	public function updatePassword(Request $request, $id)
	{
		return view('user.show');
	}

	/**
     * Contacter un utilisateur
     *
     * @param  int  $id
     * @param Illuminate\Http\Request $request
     * @return view
     */
	public function contact(Request $request, $id)
	{
		//
	}

     /**
     * Inscription d'un utilisateur
     *
     */
     public function register(Request $request){
          $this->validate($request, [
               'email' => 'required',
          ]);
          $input = $request->all();

          //Création de l'utilisateur
          try{
               $user = User::create($input);
          }
          catch(\Exception $e){
               //var_dump($e->getMessage());
               return 2;
          }

          //Envoi du mail
          try{
               $token = str_random(60);
               $user->fill(['token' => $token])->save();
               Mail::to($user->email)->send(new Register($token));
          }
          catch(\Exception $e){
               var_dump($e->getMessage());
               return 3;
          }

          return 1;
     }

     /**
     * Authentification
     *
     */
     public function login(Request $request){
          //
     }

     /**
     * Mot de passe oublié
     *
     */
     public function forgotPwd(Request $request){
          $this->validate($request, [
               'email' => 'required',
          ]);
          $input = $request->all();

          //Récupération de l'utilisateur
          try{
               $user = User::where('email', Input::get('email'))->first();
          }
          catch(\Exception $e){
               //var_dump($e->getMessage());
               return 2;
          }

          //Envoi du mail
          try{
               $token = str_random(60);
               $user->token = $token;
               $user->save();
               Mail::to($user->email)->send(new BackUp($token));
          }
          catch(\Exception $e){
               //var_dump($e->getMessage());
               return 3;
          }

          return 1;
     }

     /**
     * Vérification du token
     *
     */
     public function checkToken($token){
          //Vérification de la validité
          try{
               $user = User::where('token', $token)->first();
          }
          catch(\Exception $e){
               var_dump($e->getMessage());
               die;
          }
          return view('user.backUp')
                    ->with('token',$token);
     }

     /**
     * Remplacement du mot de passe
     *
     */
     public function newPwd(Request $request){
          $this->validate($request, [
               'new_pwd' => 'required',
               'new_pwd_confirm' => 'required',
               'token' => 'required',
          ]);
          $input = $request->all();

          //Récupération de l'utilisateur
          try{
               $user = User::where('token', Input::get('token'))->first();
               $user->password = Input::get('new_pwd');
               $user->save();
          }
          catch(\Exception $e){
               var_dump($e->getMessage());
               die;
          }
          echo "OK";
          die;
     }

	////// ADMINISTRATION ///////

	/**
     * Ajout d'un nouvel utilisateur
     *
     * @return view
     */
	public function add()
	{
		# code...
	}

	/**
     * Modification d'un utilisateur
     *
     * @param  int  $id
     * @return view
     */
	public function edit($id)
	{
		$user = User::findOrFail($id);
          $this->show($id);
	}

	/**
     * Suppression d'un utilisateur
     *
     * @param  int  $id
     * @return view
     */
	public function delete($id)
	{
		# code...
	}

	/**
     * Ban d'un utilisateur
     *
     * @param  int  $id
     * @return view
     */
	public function ban($id)
	{
		# code...
	}
}