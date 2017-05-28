<?php

namespace App\Http\Controllers;

use App\Category;
use App\Chatbox;
use App\Element;
use App\Room;
use App\User;
use App\UserElement;
use App\UserRoom;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RoomsController extends Controller
{

	/**
     * Affiche la liste de tous les salons
     *
     * @return view
     */
    public function index()
    {
        $rooms = Room::all()->sortByDesc("date_start");;
        return view('rooms.all_rooms')
            ->with(compact('rooms'));
	}

	/**
     * Affiche la liste des salons à vanir
     *
     * @param  int  $id
     * @return view
     */
	public function showFuturRooms()
	{
        $rooms = Room::where('date_start', '>', date("Y-m-d H:i:s"))->get();
		return view('rooms.index')
            ->with(compact('rooms'));
	}

	/**
     * Affichage des salons auxquelles participe un utilisateur
     *
     * @param  int  $id
     * @return view
     */
	public function showMyRooms()
	{
		return view('rooms.my_rooms');
	}

    /**
     * Récupère le prochain salon
     *
     * @param  int  $id
     * @return view
     */
    public function getFutureRoom()
    {   $now = new \DateTime();
        $nextRoom = DB::table('room')->join('element','room.id_element', '=', 'element.id')
                                    ->select(   'room.id', 
                                                'room.name as nameRoom', 
                                                'element.name as nameElement', 
                                                'room.status',
                                                'room.date_start',
                                                'room.date_end',
                                                'room.date_created')
                                        ->where('room.date_start', '>=', $now)
                                        ->orderBy('room.date_start', 'asc')
                                        ->offset(0)
                                        ->limit(1)
                                        ->get();

        return json_encode($nextRoom[0]);
    }

	/**
     * Affichage de la page d'un salon
     *
     * @param  int  $id
     * @return view
     */
	public function show($id)
	{
        $header = Room::findOrFail($id);
        $element = Element::findOrFail($header->id_element);
        $cat = Category::findOrFail($element->id_category);
        $mark = UserElement::where('id_element', $element->id)->where('id_user', $element->id)->first();
        $global_mark = UserElement::where('id_element', $element->id)->get();
        $user_room = UserRoom::where('id_room', $header->id)->get();
        foreach ($user_room as $u){
            $users[] = User::where('id', $u->id_user)->get();
        }
        $chatbox = Chatbox::where('id_room', $header->id)->get();
		return view('rooms.show')
            ->with(compact('header'))
            ->with(compact('element'))
            ->with(compact('mark'))
		    ->with(compact('global_mark'))
            ->with(compact('user_room'))
            ->with(compact('users'))
            ->with(compact('chatbox'))
            ->with(compact('cat'));
	}

	/**
     * Rejoindre un salon
     *
     * @param  int  $id
     * @return view
     */
	public function join($id_room)
	{
        DB::table('user_room')->insert(
            [
                'id_user' => Auth::id(),
                'id_room' => $id_room,
                'status_user' => 1
            ]
        );
		return view('rooms.index');
	}

	/////// ADMINISTRATION //////

	/**
     * Création d'un salon
     *
     * @return view
     */
	public function add()
	{
		# code...
	}

	/**
     * Modification d'un salon
     *
     * @param  int  $id
     * @return view
     */
	public function edit($id)
	{
		# code...
	}

	/**
     * Suppression d'un salon
     *
     * @param  int  $id
     * @return view
     */
	public function delete($id)
	{
		# code...
	}

	public function addMessage()
    {
        DB::table('chatbox')->insert(
            [
                'id_user_sender' => $_POST['id_user_sender'],
                'id_room' => $_POST['id_room'],
                'date_post' => date("Y-m-d H:i:s"),
                'message' => $_POST['message'],
                'status' => 1,
                'is_deleted' => 0
            ]
        );

        $user = User::where('id', $_POST['id_user_sender']);
        $data = array(
            "user" => $user
        );
        echo json_encode($data);
    }

    public function autocompleteUser()
    {
        $term = $_GET['term'];
        $users =  User::where('first_name', 'like', $term);
        $array = array();

        while($name = $users->fetch())
        {
            array_push($array, $name['first_name']);
        }
        echo json_encode($array);
    }
}