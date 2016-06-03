$(document).ready(function () {
    $('#like').click(function () {
        var msg = $('#likeForm').serialize();
        $.ajax({
            type: 'POST',
            dataType: 'json',
            async: false,
            url: '/ajax/profile',
            data: msg,
            success: function (result) {
                $('#like').prop('disabled', true);
            },
            error: function (xhr, str) {
                // Error: xhr.responseCode
            }
        });
    });

    $('#sendAMessage').click(function() {
        var id = $(this).data('id');
        document.location.href = '/messages/send/'.concat(id);
    });
});
