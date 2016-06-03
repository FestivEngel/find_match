$(document).ready(function () {
    $('#likesAlerts').change(function () {
        if (this.checked) {
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: '/ajax/profile',
                data: {
                    command: 'likesAlertsOn'
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
                url: '/ajax/profile',
                data: {
                    command: 'likesAlertsOff'
                },
                success: function (result) {
                },
                error: function (xhr, str) {
                    // Error: xhr.responseCode
                }
            });
        }
    });

    $('#viewsAlerts').change(function () {
        if (this.checked) {
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: '/ajax/profile',
                data: {
                    command: 'viewsAlertsOn'
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
                url: '/ajax/profile',
                data: {
                    command: 'viewsAlertsOff'
                },
                success: function (result) {
                },
                error: function (xhr, str) {
                    // Error: xhr.responseCode
                }
            });
        }
    });
});
