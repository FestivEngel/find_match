$(document).ready(function () {
    $('#runForm').submit(function() {
        return false;
    });

    $('.sendAMessage').click(function() {
        var id = $(this).data('id');
        document.location.href = '/messages/send/'.concat(id);
    });

    $('.reportAbuse').click(function() {
        var id = $(this).data('id');
        document.location.href = '/report/'.concat(id);
    });

    $('.add2Favorites').click(function() {
        var id = $(this).data('id');
        searchRunAdd2Favorites(id);
        $(this).prop('disabled', 'disabled');
    });

    $('.markAsViewed').click(function() {
        var id = $(this).data('id');
        searchRunMarkAsViewed(id);
        $('#v'.concat(id)).css({'display': 'none'});
        $('#uv'.concat(id)).css({'display': 'block'});
    });

    $('.markAsUnviewed').click(function() {
        var id = $(this).data('id');
        searchRunMarkAsUnviewed(id);
        $('#v'.concat(id)).css({'display': 'block'});
        $('#uv'.concat(id)).css({'display': 'none'});
    });

    $('#saveSearchLink').on('click', function() {
        $('#saveSearch').css({'display': 'block'});

        return false;
    });

    $('#editForm').submit(function() {
        searchRunSendData();

        return false;
    });
});

function searchRunSendData() {
    var name = $('#name');
    if (name.val() == '') {
        $('#nameError').css({'display': 'block'});
        name.focus();

        return false;
    }

    var msg = $('#editForm').serialize();
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '/ajax/search',
        data: msg,
        success: function (data) {
            $('#saveSearch').css({'display': 'none'});
            $('#saveRedefineSearch').css({'display': 'none'});
            $('#searchUpdated').css({'display': 'block'});
            setTimeout(function () {
                $('#searchUpdated').css({'display': 'none'});
            }, 5 * 1000);
        },
        error: function (xhr, str) {
            // Error: xhr.responseCode
        }
    });
}