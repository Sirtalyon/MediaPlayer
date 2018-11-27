$(function(){

    $('.delete-modal').on("click", function(event){
        event.preventDefault();

        $("#deleteModal").modal('show');
        $('#deleteModal-on').data('id', $(this).data('id') );

    });

    $('#deleteModal-on').on('click', function () {
        if($(this).data('id') > 0){
            //Chaine vide au milieu car sinon il fait une addition
            document.location.href = $("#deleteModal").data('route') + "" + $(this).data('id');
        }
    });

    //Filtre par catégorie d'idée
    $('#filtre_categorie').on('change', function () {

        var cat = $(this).val();
        document.location.href = $(this).data('route') + "" + cat;
    });

})