@extends('templates/template')

@section('css')
     <link rel="stylesheet" type="text/css" href="{{asset('css/rooms.css')}}">    
@endsection

@section('title')
    Liste des salons à venir
@endsection

@section('content')
    <div class="container">
            <h1 class="text-center">Les salons à venir 
                @if(Auth::guest())
                    <small>Pour rejoindre un salon à venir, veuillez vous connecter.</small>
                @endif
            </h1>
            @if(count($rooms))
            <table id="salons" class="table table-hover table-responsive" cellspacing="0">
                <thead>
                    <tr>
                        <th>Nom du salon</th>
                        <th>Titre (Auteur)</th>
                        <th>Dates du salon</th>
                        @if(Auth::check())
                            <th>Information</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                @foreach($rooms as $room)
                    <tr>
                        <td>{{$room->name}}</td>
                        <td>{{$room->element->name}} ({{$room->element->creator}})</td>
                        <td>Du {{date("d/m/Y", strtotime($room->date_start))}} 
                            au {{date("d/m/Y", strtotime($room->date_end))}}
                        </td>
                        @if(Auth::check())
                        <td> 
                            @if(!($user_element->contains('id_element', $room->element->id)))
                                <button type="button"
                                        class="btn btn-success"
                                        data-toggle="modal"
                                        data-target="#join"
                                        data-title="{{$room->element->name}}"
                                        data-autor="{{$room->element->creator}}"
                                        data-id_room="{{$room->id}}"
                                        data-id_element="{{$room->element->id}}"
                                        data-salon="Salon 1">
                                    M'inscrire au salon
                                </button>
                            @else
                                 Salon déjà rejoint !
                            @endif
                        </td>
                        @endif
                    </tr>
                    <div class="modal fade" id="join" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                </div>
                                {{ Form::open(['route' => 'join_room', 'method' => 'post', 'class' => 'col-md-12']) }}
                                <div class="modal-body">
                                    <h1 id="title" class="text-center text-uppercase col-xs-10 col-sm-12"></h1>
                                    <h1 id="autor" class="text-center text-uppercase col-xs-10 col-sm-12 autor"></h1>
                                    <div class="text-center" id="div_note">
                                        <h3>Donnez une note !</h3>
                                        <div class="rating">
                                            <a href="#4" title="Donner 4 étoiles">☆</a>
                                            <a href="#3" title="Donner 3 étoiles">☆</a>
                                            <a href="#2" title="Donner 2 étoiles">☆</a>
                                            <a href="#1" title="Donner 1 étoile">☆</a>
                                        </div>
                                    </div>
                                    <input type="hidden" id="element" name="element"/>
                                    <input type="hidden" id="room" name="room"/>
                                    <input type="hidden" id="note" name="note"/>
                                </div>
                                <div class="modal-footer text-center">
                                    <button type="submit" class="btn btn-success btn-lg">Rejoindre</button>
                                </div>
                                {{Form::close()}}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
            @else
                <p class="text-center">Il n'y a aucun salon à venir. Revenez plus tard !</p>
            @endif
        </div>
@endsection

@section('js')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#salons').dataTable({
                "language": {
                    "infoEmpty": "No entries to show",
                    "info": "Affichage des enregistrements _START_ à _END_ sur _TOTAL_",
                    "paginate": {
                        "previous": "Précédent",
                        "next": "Suivant"
                    },
                    "search": "Recherche : ",
                    "lengthMenu": "Affichage par _MENU_ enregistrements"
                }
            });

            $('#join').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                var title = button.data('title')
                var autor = button.data('autor')
                var salon = button.data('salon')
                var id_room = button.data('id_room')
                var id_element = button.data('id_element')
                var modal = $(this)
                modal.find('.modal-body #title').text(title)
                modal.find('.modal-body #autor').text("(" + autor + ")")
                modal.find('.modal-body #room').val(id_room)
                modal.find('.modal-body #element').val(id_element)
            })

            $('.rating').children('a').each(function(){
                $(this).click(function(){
                    //alert(this.getAttribute("href"));
                    document.getElementById('note').value = this.getAttribute("href").substring(1);
                })
            })
        });
    </script>
@endsection