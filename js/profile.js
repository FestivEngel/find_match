$(document).ready(function () {
    profileIndexBindCheckboxHandlers();

    $('#activate').click(function () {
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: '/ajax/profile',
            data: {
                command: 'activate'
            },
            success: function (result) {
                $('#activate').css({'display': 'none'});
                $('#deactivate').css({'display': 'block'});
            },
            error: function (xhr, str) {
                // Error: xhr.responseCode
            }
        });
    });

    $('#deactivate').click(function () {
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: '/ajax/profile',
            data: {
                command: 'deactivate'
            },
            success: function (result) {
                $('#deactivate').css({'display': 'none'});
                $('#activate').css({'display': 'block'});

            },
            error: function (xhr, str) {
                // Error: xhr.responseCode
            }
        });
    });

    $('#matchingOn').click(function () {
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: '/ajax/profile',
            data: {
                command: 'matchingOn'
            },
            success: function (result) {
                $('#matchingOn').css({'display': 'none'});
                $('#matchingOff').css({'display': 'block'});
            },
            error: function (xhr, str) {
                // Error: xhr.responseCode
            }
        });
    });

    $('#matchingOff').click(function () {
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: '/ajax/profile',
            data: {
                command: 'matchingOff'
            },
            success: function (result) {
                $('#matchingOff').css({'display': 'none'});
                $('#matchingOn').css({'display': 'block'});
            },
            error: function (xhr, str) {
                // Error: xhr.responseCode
            }
        });
    });

    $('#delete').click(function () {
        $('#deleteConfirm').css({'display': 'block'});

        return false;
    });

    $('#deleteConfirmCancel').click(function () {
        $('#deleteConfirm').css({'display': 'none'});

        return false;
    });

    $('#deleteConfirmYes').click(function () {
        $.ajax({
            type: 'POST',
            async: false,
            dataType: 'json',
            url: '/ajax/profile',
            data: {
                command: 'delete'
            },
            success: function (result) {
                location.href = '/logout';
            },
            error: function (xhr, str) {
                // Error: xhr.responseCode
            }
        });
    });

    $('#profileForm').submit(function () {
        profileIndexSendData();
    });

    var kids = $('#kids');

    if (kids.val() == 1) { // no
        $("#numberOfKids").prop('disabled', 'disabled');
    }

    kids.on('change', function () {
        if ($(this).val() == 1) {
            $("#numberOfKids").prop('disabled', 'disabled');
        } else {
            $("#numberOfKids").removeAttr('disabled');
        }
    });

    var countries = $('#countries');

    countries.on('change', function () {
        for (var i = 1; i <= 6; i++) {
            if ($(this).val() == i) {
                $('#cities'.concat(i)).css({'display': 'block'});
            } else {
                $('#cities'.concat(i)).css({'display': 'none'});
            }
        }
    });

    var pCountries = $('#pCountries');

    pCountries.on('change', function () {
        for (var i = 1; i <= 6; i++) {
            if ($(this).val() == i) {
                $('#pCities'.concat(i)).css({'display': 'block'});
            } else {
                $('#pCities'.concat(i)).css({'display': 'none'});
            }
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

    $('#upload_form').submit(function () {
        $(this).ajaxSubmit(options); //trigger ajax submit
        return false; //return false to prevent standard browser submit
    });

    var relationsType = $('#relationsType');
    var pRelationsType = $('#pRelationsType');
    $("#slider").slider({
        min: 0,
        max: 15,
        value: pRelationsType.val() - 1,
        slide: function (event, ui) {
            $('#pRelationsType').val(ui.value + 1);
            var type = getRelationsType(ui.value + 1);
            $('#relationsType').html(type);
        }
    });

    relationsType.html(getRelationsType(pRelationsType.val()));
});

function getRelationsType(type) {
    // Du
    if (type == 1) {
        return $('#Du').val();
    }
    // Id
    if (type == 2) {
        return $('#Id').val();
    }
    // Ac
    if (type == 3) {
        return $('#Ac').val();
    }
    // Mr
    if (type == 4) {
        return $('#Mr').val();
    }
    // Sd
    if (type == 5) {
        return $('#Sd').val();
    }
    // Cg
    if (type == 6) {
        return $('#Cg').val();
    }
    // Cf
    if (type == 7) {
        return $('#Cf').val();
    }
    // Se
    if (type == 8) {
        return $('#Se').val();
    }
    // QI
    if (type == 9) {
        return $('#QI').val();
    }
    // Ex
    if (type == 10) {
        return $('#Ex').val();
    }
    // Mg
    if (type == 11) {
        return $('#Mg').val();
    }
    // Cp
    if (type == 12) {
        return $('#Cp').val();
    }
    // Rq+
    if (type == 13) {
        return $('#RqPlus').val();
    }
    // Rq-
    if (type == 14) {
        return $('#RqMinus').val();
    }
    // Sv+
    if (type == 15) {
        return $('#SvPlus').val();
    }
    // Sv-
    return $('#SvMinus').val();
}

//customize values to suit your needs.
var max_file_size = 16 * 1024 * 1024; //maximum allowed file size
var allowed_file_types = ['image/png', 'image/gif', 'image/jpeg', 'image/pjpeg']; //allowed file types
var message_output_el = 'output'; //ID of an element for response output
var loaded_image_el = 'loading-img'; //ID of an loading Image element

//You may edit below this line but not necessarily
var options = {
    dataType: 'json', //expected content type
    target: '#' + message_output_el,   // target element(s) to be updated with server response
    beforeSubmit: before_submit,  // pre-submit callback
    success: after_success,  // post-submit callback
    resetForm: true        // reset the form after successful submit
};

function profileIndexSendData() {
    var countryNum = $('#countries').val();
    var cityNum = $('#cities'.concat(countryNum)).val();
    $('#livingCity').val(cityNum);
    var pCountryNum = $('#pCountries').val();
    var pCityNum = $('#pCities'.concat(pCountryNum)).val();
    $('#pLivingCity').val(pCityNum);
    var msg = $('#profileForm').serialize();
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '/ajax/profile',
        data: msg,
        success: function (data) {
            $('#profileUpdated').css({'display': 'block'});
            setTimeout(function () {
                $('#profileUpdated').css({'display': 'none'});
            }, 5 * 1000);
        },
        error: function (xhr, str) {
            // Error: xhr.responseCode
        }
    });
}

function before_submit(formData, jqForm, options) {
    var proceed = true;
    var error = [];
    /* validation ##iterate though each input field
     if you add extra text or email fields just add "required=true" attribute for validation. */
    $(formData).each(function () {

        // check any empty required file input
        if (this.type == "file" && this.required == true && !$.trim(this.value)) { //check empty text fields if available
            error.push(this.name + " is empty!");
            proceed = false;
        }

        // check any empty required text input
        if (this.type == "text" && this.required == true && !$.trim(this.value)) { //check empty text fields if available
            error.push(this.name + " is empty!");
            proceed = false;
        }

        // check any invalid email field
        var email_reg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        if (this.type == "email" && !email_reg.test($.trim(this.value))) {
            error.push(this.name + " contains invalid email!");
            proceed = false;
        }

        // check invalid file types and maximum size of a file
        if (this.type == "file") {
            if (window.File && window.FileReader && window.FileList && window.Blob) {
                if (this.value !== "") {
                    if (allowed_file_types.indexOf(this.value.type) === -1) {
                        error.push("<b>" + this.value.type + "</b> is unsupported file type!");
                        proceed = false;
                    }

                    //allowed file size. (1 MB = 1048576)
                    if (this.value.size > max_file_size) {
                        error.push("<b>" + bytes_to_size(this.value.size) + "</b> is too big! Allowed size is " + bytes_to_size(max_file_size));
                        proceed = false;
                    }
                }
            } else {
                error.push("Please upgrade your browser, because your current browser lacks some new features we need!");
                proceed = false;
            }
        }

    });

    $(error).each(function (i) { // output any error to element
        $('#' + message_output_el).html('<div class="error">' + error[i] + "</div>");
    });

    if (!proceed) {
        return false;
    }

    $('#' + loaded_image_el).show();
}

function getImageHtml(imageFileName) {
    return '<a href="/photos/' + imageFileName + '" data-lightbox="image-1" data-title="' + $('#nickname').val() + '"><img src="/photos/thumb_' + imageFileName + '">';
}

// Callback function after success
function after_success(data) {
    $('#' + message_output_el).html(getImageHtml(data.imageFileName));
    $('#' + loaded_image_el).hide();
    $('#filename').html('');
}

// Callback function to format bites bit.ly/19yoIPO
function bytes_to_size(bytes) {
    var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    if (bytes == 0) return '0 Bytes';
    var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
    return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
}

function handleBrowseClick() {
    $('#browse').click()
}

function handleChange() {
    $('#filename').html($('#browse').val());
}

