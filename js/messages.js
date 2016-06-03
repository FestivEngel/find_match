$(document).ready(function () {
    $('#newMsgsAlerts').change(function () {
        if (this.checked) {
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: '/ajax/msgs',
                data: {
                    command: 'newMsgsAlertsOn'
                },
                success: function (result) {
                },
                error: function (xhr, str) {
                    // Error: xhr.responseCode
                }
            });
        } else {
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: '/ajax/msgs',
                data: {
                    command: 'newMsgsAlertsOff'
                },
                success: function (result) {
                },
                error: function (xhr, str) {
                    // Error: xhr.responseCode
                }
            });
        }
    });

    setInterval(function () {
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: '/ajax/msgs',
            data: {
                command: 'getNewCount'
            },
            success: function (result) {
                $('#newMessagesCount').html(result.newCount);
            },
            error: function (xhr, str) {
                // Error: xhr.responseCode
            }
        });
    }, 10 * 1000);
});