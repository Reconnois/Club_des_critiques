<?php

//Pour créer un controlleur, executer cette commande dans le dossier racine du projet
// php artisan make:controller NomController


namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
	{

		/**
		 * get concept
		 * get oeuvres à la une
		 * get contact info
		 */
		return view('welcome');
	}
}
