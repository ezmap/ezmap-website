$('#geocodemodal').on('submit', '#geocodeForm', function (event) {
    event.preventDefault();
    $.ajax({
        type: "POST",
        url: $(this).attr('action'),
        data: $(this).serialize(),
        success: function (msg) {
            console.log(msg);
        }
    });
});