$('#addNewIconForm').on('submit', function (event) {
    event.preventDefault();
    $.ajax({
        type: "POST",
        url: $(this).attr('action'),
        data: $(this).serialize(),
        success: function (msg) {
            event.target.reset();
            var formarker = $('.markericon').first().data('for-marker');
            $('.icon-uploads').append('<div class="col-xs-2 text-center" id="youricon' + msg.icon.id + '">' +
                    '<img class="img img-thumbnail markericon" src="' + msg.icon.url + '" alt="' + msg.icon.name + '" title="' + msg.icon.name + '" onclick="mainVue.setMarkerIcon(event)" data-for-marker="' + formarker + '" data-dismiss="modal">' +
                    '<form action="/deleteIcon" method="POST" class="removeIconForm">' +
                    '<input type="hidden" name="icon-id" value="' + msg.icon.id + '">' +
                    '<div class="form-group"><button class="form-control btn btn-danger" type="submit" value="Delete"><i class="fa fa-trash fa-fw"></i></button></div>' +
                    '</form></div>');
        }
    });
});

$('.icon-uploads').on('submit', '.removeIconForm', function (event) {
    event.preventDefault();
    $.ajax({
        type: "POST",
        url: $(this).attr('action'),
        data: $(this).serialize(),
        success: function (msg) {
            console.log(msg);
            $('#youricon' + msg.icon.id).remove();
        }

    });

});