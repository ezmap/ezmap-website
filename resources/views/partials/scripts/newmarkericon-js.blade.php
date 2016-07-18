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
                    '<div class="form-group"><button class="ui-button ui-button-normal color-danger"> <div class="ui-button-content"> <i class="ui-icon material-icons ui-button-icon delete" aria-hidden="true">delete</i> <div class="ui-button-text"> {{ EzTrans::translate('deleteIcon') }} </div>  </div> <div class="ui-progress-circular ui-button-spinner v-transition" style="display: none; width: 18px; height: 18px;"> <svg class="ui-progress-circular-indeterminate" viewBox="25 25 50 50" role="progressbar" aria-valuemin="0" aria-valuemax="100"> <circle class="ui-progress-circular-indeterminate-path white" cx="50" cy="50" r="20" fill="none" stroke-miterlimit="10" stroke-width="4.5"> </circle></svg>  </div> <div class="ui-ripple-ink"></div>   </button></div>' +
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