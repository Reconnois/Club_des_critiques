$(document).ready(function() {

    $('#elementManual').show();
    $('#elementAutomatic').hide();

    initializedDataTable('elementTable');
    initializedDataTable('elementTopTable');
    initializedDataTable('categoryTable');
    initializedDataTable('roomTable');
    initializedDataTable('userTable');

    $( "#date_start" ).datepicker();
    $( "#date_end" ).datepicker();


// HOME 

	var selectCat = $('#top_category');
	var selectSubCat = $('#top_sub_category');
	var selectCreator = $('#top_creator');
	var selectElement = $('#top_element');

	// Cache le bouton "ajouter un element"
	$('#btnShowAddTopElement').click(function() {
		$('#btnShowAddTopElement').hide();
	});
	// Affiche le bouton "ajouter un element"
	$('#btnHideAddTopElement').click(function() {
		$('#btnShowAddTopElement').show();
		// $('#collapseAddElementTop').hide();
	});
    	

	// Sélection de la catégorie => affichage des sous catégories
	$("#top_category").change(function() {
        var categoryId = this.value;

        if (categoryId.length) {
            // Call Ajax Request to get sub categories for the category 
            $.ajax({
                data : { categoryId : categoryId },
                url: "{{ route('get_sub_categories') }}",
                type: 'get',
                success: function(data) {
                    if (data.length) {
                        selectSubCat.attr('disabled',false);
                        selectSubCat.html('<option value="" disabled selected>Choisissez une sous-catégorie</option>');
                        jQuery.each(data, function() {
                            selectSubCat.append(new Option(this.name, this.id));
                        });
                        selectSubCat.selectpicker('refresh');
                    } else {
                        selectSubCat.html('<option value="" disabled selected>Pas de sous-catégorie disponible</option>');
                        selectSubCat.selectpicker('refresh');
                    }
                }
            });
        }
    });

    // Sélection de la sous catégorie => affichage des auteurs
	selectSubCat.change(function() {
        var subCatId = this.value;

        if (subCatId.length) {
            // Call Ajax Request to get creator for the sub category
            $.ajax({
                data : { subCatId : subCatId },
                url: "{{ route('get_creators') }}",
                type: 'get',
                success: function(data) {
                    if (data.length) {
                        selectCreator.attr('disabled',false);
                        selectCreator.html('<option value="" disabled selected>Choisissez un auteur / réalisateur </option>');
                        jQuery.each(data, function() {
                            selectCreator.append(new Option(this.creator, this.creator));
                        });
                        selectCreator.selectpicker('refresh');
                    } else {
                        selectCreator.html('<option value="" disabled selected>Pas d\'auteur / réalisateur disopnible pour cette catégorie</option>');
                        selectCreator.selectpicker('refresh');
                    }
                }
            });
        }
    });

	// Sélection de l'auteur => affichage des oeuvres
	selectCreator.change(function() {
        var subCatId = selectSubCat.val();
        var creator = this.value;

        if (subCatId.length && creator.length) {
            // Call Ajax Request to get element for the creator
            $.ajax({
                data : { subCatId : subCatId, creator : creator },
                url: "{{ route('get_elements') }}",
                type: 'get',
                success: function(data) {
                    if (data.length) {
                        selectElement.attr('disabled',false);
                        selectElement.html('<option value="" disabled selected>Choisissez une oeuvre </option>');
                        jQuery.each(data, function() {
                            selectElement.append(new Option(this.name, this.id));
                        });
                        selectElement.selectpicker('refresh');
                    } else {
                        selectElement.html('<option value="" disabled selected>Pas d\'oeuvre disopnible pour cet  auteur / réalisateur</option>');
                        selectElement.selectpicker('refresh');
                    }
                }
            });
        }
    });

	// Supprime un élément à la une
    $('.delete-top-element').click(function(){
    	if (confirm("Voulez vous vraiement supprimer cet oeuvre?")) {
	    	elementId = this.id;
	    	// Call Ajax Request to delete top element
            $.ajax({
                data : { elementId : elementId },
                url: "{{ route('delete_top_element') }}",
                type: 'put',
                success: function(data) {
                	// Modifier le tableau
                },
                error : function() {
                	// gestion d'erreur
                }
            });
            tab = $( this ).parent().parent().remove();
	    }	
    });


// CATEGORY

		// Cache le bouton "ajouter une categorie"
		$('#btnShowAddCategory').click(function() {
			$('#btnShowAddCategory').hide();
		});
		// Affiche le bouton "ajouter une categorie"
		$('#btnHideAddCategory').click(function() {
			$('#btnShowAddCategory').show();
		});

// ELEMENT

		// Cache le bouton "ajouter un element"
		$('#btnShowAddElement').click(function() {
			$('#btnShowAddElement').hide();
		});
		// Affiche le bouton "ajouter un element"
		$('#btnHideAddElement').click(function() {
			$('#btnShowAddElement').show();
		});

		// Affiche le bon formulaire d'ajout d'oeuvre
    	$('input[type=radio][name=radioElement]').change(function() {
	        if (this.value == 'manual') {
	            $('#elementManual').show();
    			$('#elementAutomatic').hide();
	        }
	        else if (this.value == 'automatic') {
	            $('#elementAutomatic').show();
    			$('#elementManual').hide();
	        }
	    });

    	// Affiche les bonnes dates pour le formulaire d'une oeuvre
    	$('#parent_cat').change(function() {
	    	if( this.value == 1 || this.value == 2){
	    		$('#datePublication').show();
	    		$('#dateStart').hide();
	    		$('#dateEnd').hide();	
	    	} else {
	    		$('#datePublication').hide();
	    		$('#dateStart').show();
	    		$('#dateEnd').show();
	    	}
	    });
	    $('#parent_cat_edit').change(function() {
	    	if( this.value == 1 || this.value == 2){
	    		$('#datePublicationEdit').show();
	    		$('#dateStartEdit').hide();
	    		$('#dateEndEdit').hide();	
	    	} else {
	    		$('#datePublicationEdit').hide();
	    		$('#dateStartEdit').show();
	    		$('#dateEndEdit').show();
	    	}
	    });

// ROOM

		// Cache le bouton "ajouter un salon"
		$('#btnShowAddRoom').click(function() {
			$('#btnShowAddRoom').hide();
		});
		
		// Affiche le bouton "ajouter un salon"
		$('#btnHideAddRoom').click(function() {
			$('#btnShowAddRoom').show();
		});


// USER

		// Cache le bouton "ajouter un utilisateur"
		$('#btnShowAddUser').click(function() {
			$('#btnShowAddUser').hide();
		});
		
		// Affiche le bouton "ajouter un utilisateur"
		$('#btnHideAddUser').click(function() {
			$('#btnShowAddUser').show();
		});

// FUNCTIONS

    function initializedDataTable(id) {
            $('#' + id).DataTable({
                "language": {
                    "sProcessing":     "Traitement en cours...",
                    "sSearch":         "Rechercher&nbsp;:",
                    "sLengthMenu":     "Afficher _MENU_ éléments",
                    "sInfo":           "Affichage de l'élément _START_ à _END_ sur _TOTAL_ éléments",
                    "sInfoEmpty":      "Affichage de l'élément 0 à 0 sur 0 élément",
                    "sInfoFiltered":   "(filtré de _MAX_ éléments au total)",
                    "sInfoPostFix":    "",
                    "sLoadingRecords": "Chargement en cours...",
                    "sZeroRecords":    "Aucun élément à afficher",
                    "sEmptyTable":     "Aucune donnée disponible dans le tableau",
                    "oPaginate": {
                        "sFirst":      "Premier",
                        "sPrevious":   "Précédent",
                        "sNext":       "Suivant",
                        "sLast":       "Dernier"
                    },
                    "oAria": {
                        "sSortAscending":  ": activer pour trier la colonne par ordre croissant",
                    "   sSortDescending": ": activer pour trier la colonne par ordre décroissant"
                    }
                } 
            });
        };

});