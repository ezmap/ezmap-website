Vue.use(Keen);
Vue.filter('nl2br', function (value) {
    return value.replace(/\n/g, '<br>');
});
Vue.filter('jsonShrink', function (value) {
    return value.replace(/\n/g, '');
});
feedbackVue = new Vue({
    el: '#app',
    data: {
        feedback: {
            name: "{{ Auth::user()->name ?? '' }}",
            email: "{{ Auth::user()->email ?? '' }}",
            feedback: "",
        },
        loading: {
            submitButton: false,
        },
        buttonText: "Submit bug report.",
        subject: "Bug Report",
        icon: "bug_report",
        label: "What happened?",
        placeholder: "I was doing ... and expected ... but instead got ...",
    },
    computed: {},
});

$('#feedbackform .ui-textbox-input, #feedbackform .ui-textbox-textarea').on('keyup', function () {
    if ($('.ui-textbox').hasClass('invalid')) {
        $('#feedbackform button').addClass('hidden');
    } else {
        $('#feedbackform button').removeClass('hidden');
    }
});
$('#feedbackform .ui-textbox-input').on('keydown', function (event) {
    if (event.keyCode == 13) {
        event.preventDefault();
    }
});
$('#feedbackform').on('submit', function (event) {
    event.preventDefault();
    feedbackVue.loading.submitButton = true;
    $.ajax({
        type: "POST",
        url: $(this).attr('action'),
        data: $(this).serialize(),
        success: function (msg) {
            event.target.reset();
            $('#feedbackform button').addClass('hidden');
            feedbackVue.loading.submitButton = false;
            swal({
                title: msg.title,
                text: msg.text,
                type: msg.type,
                timer: 2500,
                showConfirmButton: false
            });
        },
        statusCode: {
            422: function (error) {
                var text = '';
                for (var key in error.responseJSON) {
                    if (error.responseJSON.hasOwnProperty(key)) {
                        text += '\n' + error.responseJSON[key];
                    }
                }
                swal({
                    title: "Oh Noes!",
                    text: text,
                    type: "error",
                    timer: 2500,
                    showConfirmButton: true
                });
            },
        },
        error: function (error) {
            feedbackVue.loading.submitButton = false;

            swal({
                title: "Oh Noes!",
                text: "There was a problem submitting your form, please try again.",
                type: "error",
                timer: 2500,
                showConfirmButton: false
            });
        }
    });
});