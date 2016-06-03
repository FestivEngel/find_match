// Common
$('#lang').change(function () {
    var msg = {
        lang: $('#lang').val()
    };

    $.ajax({
        type: 'POST',
        async: false,
        dataType: 'json',
        url: '/ajax/changelang',
        data: msg
    });

    location.reload();
});

function removeErrorNotification(name) {
    $('#'.concat(name)).css({'display': 'none'});
}

function checkEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if (!re.test(email)) { // Error: Email is not valid!
        return false;
    }

    return true;
}

// About

// Ajax

// Articles

// Auth

// Landing
function landingIndexRemoveErrorNotification(name) {
    if (name == 'passwordError') {
        for (var i = 1; i <= 6; i++) {
            $('#'.concat(name.concat(i))).css({'display': 'none'});
        }
    } else {
        $('#'.concat(name)).css({'display': 'none'});
    }
}

var landingIndexEmailExists = false;

function profileIndexSetEmailExists(flag) {
    landingIndexEmailExists = flag;
}

function landingIndexCheckForm() {
    var nickname = $('#nickname');
    if (nickname.val() == "") { // Error: Username cannot be blank!
        $('#nicknameError').css({'display': 'block'});
        nickname.focus();

        return false;
    }

    var email = $('#email');
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if (!checkEmail(email.val())) { // Error: Email is not valid!
        $('#emailError').css({'display': 'block'});
        email.focus();

        return false;
    }

    var msg = $('#registerForm').serialize();
    $.ajax({
        type: 'POST',
        async: false,
        dataType: 'json',
        url: '/ajax/checklogin',
        data: msg,
        success: function (result) {
            profileIndexSetEmailExists(result.status);
        },
        error: function (xhr, str) {
            // Error: xhr.responseCode
        }
    });

    if (landingIndexEmailExists) {
        $('#emailExistsError').css({'display': 'block'});
        email.focus();

        return false;
    }

    var password = $('#password');
    var password2 = $('#password2');
    if (password.val() != "" && password.val() == password2.val()) {
        var error = false;
        var re1 = /[0-9]/;
        var re2 = /[a-z]/;
        var re3 = /[A-Z]/;

        if (password.val().length < 3) { // Error: password must contain at least 3 characters!
            $('#passwordError1').css({'display': 'block'});
            error = true;
        } else if (password.val() == nickname.val()) { // Error: password must be different from username!
            $('#passwordError2').css({'display': 'block'});
            error = true;
        }

        if (error) {
            password.focus();

            return false;
        }
    } else { // Error: please check that you've entered and confirmed your password!
        $('#passwordError6').css({'display': 'block'});
        password.focus();

        return false;
    }

    // You entered a valid data
    return true;
}

// Messages
var messagesSendRemoveErrorNotification = removeErrorNotification;

function messagesSendSendData() {
    if (messagesSendCheckForm()) {
        var msg = $('#sendMessageForm').serialize();
        var subject = $('#subject');
        var body = $('#body');

        $.ajax({
            type: 'POST',
            async: false,
            url: '/ajax/msgs',
            data: msg,
            success: function (data) {
                var row = getRow($('#senderName').val(), $('#receiverName').val(), subject.val(), body.val());
                $('#messagesTable').append(row);
                subject.val('');
                body.val('');
            },
            error: function (xhr, str) {
                // Error: xhr.responseCode
            }
        });

        return true;
    }

    return false;
}

function messagesSendCheckForm() {
    var subject = $('#subject');
    var body = $('#body');
    if (subject.val() == '') {
        $('#subjectError').css({'display': 'block'});
        subject.focus();

        return false;
    }

    if (body.val() == '') {
        $('#bodyError').css({'display': 'block'});
        body.focus();

        return false;
    }

    return true;
}

// Profile
var profileInviteRemoveErrorNotification = removeErrorNotification;
var profileLoginRemoveErrorNotification = removeErrorNotification;

var profileLoginUserNotFoundError;

// Profile / Login
function profileLoginSetUserNotFoundError(flag) {
    profileLoginUserNotFoundError = flag;
}

function profileLoginSetPasswordSent() {
    $('#passwordReminder').css({'display': 'none'});
    $('#passwordSent').css({'display': 'block'});
}

function profileLoginCheckForm() {
    profileLoginUserNotFoundError = true;
    var email = $('#email');
    if (!checkEmail(email.val())) { // Error: Email is not valid!
        $('#emailError').css({'display': 'block'});
        email.focus();

        return false;
    }

    var password = $('#password');
    if (password.val().length < 3) {
        $('#passwordError').css({'display': 'block'});

        return false;
    }

    var msg = $('#loginForm').serialize();
    $.ajax({
        type: 'POST',
        async: false,
        dataType: 'json',
        url: '/ajax/checkloginpwd',
        data: msg,
        success: function (result) {
            profileLoginSetUserNotFoundError(!result.status)
        },
        error: function (xhr, str) {
            // Error: xhr.responseCode
        }
    });

    if (profileLoginUserNotFoundError) {
        $('#userNotFoundError').css({'display': 'block'});
        password.focus();
        password.val('');

        return false;
    }

    return true;
}

function profileLoginCheckForm2() {
    var email2 = $('#email2');
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if (!checkEmail(email2.val())) { // Error: Email is not valid!
        $('#email2Error').css({'display': 'block'});
        email2.focus();

        return false;
    }

    return true;
}

function profileLoginSendData() {
    profileLoginUserNotFoundError = true;
    if (profileLoginCheckForm2()) {
        var msg = $('#passwordReminderForm').serialize();
        $.ajax({
            type: 'POST',
            async: false,
            dataType: 'json',
            url: '/ajax/remindpwd',
            data: msg,
            success: function (result) {
                if (result.status) {
                    profileLoginSetPasswordSent();
                } else {
                    profileLoginSetUserNotFoundError(!result.status);
                }
            },
            error: function (xhr, str) {
                // Error: xhr.responseCode
            }
        });

        if (profileLoginUserNotFoundError) {
            $('#userNotFound2Error').css({'display': 'block'});
            $('#email2').focus();
        }

        return true;
    }

    return false;
}

// Profile / Index
var profileIndexDeactivateProfile = function () {
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
};

var profileIndexActivateProfile = function () {
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
};

function profileIndexBindCheckboxHandlers() {
    $("input[name='pHairColor[]']").change(function () {
        if ($(this).val() == 0) {
            $.each($("input[name='pHairColor[]']:checked"), function () {
                if ($(this).val() != 0) {
                    $(this).prop('checked', false);
                }
            })
        } else {
            $.each($("input[name='pHairColor[]']:checked"), function () {
                if ($(this).val() == 0) {
                    $(this).prop('checked', false);
                }
            })
        }
    });

    $("input[name='pHairLength[]']").change(function () {
        if ($(this).val() == 0) {
            $.each($("input[name='pHairLength[]']:checked"), function () {
                if ($(this).val() != 0) {
                    $(this).prop('checked', false);
                }
            })
        } else {
            $.each($("input[name='pHairLength[]']:checked"), function () {
                if ($(this).val() == 0) {
                    $(this).prop('checked', false);
                }
            })
        }
    });

    $("input[name='pHairType[]']").change(function () {
        if ($(this).val() == 0) {
            $.each($("input[name='pHairType[]']:checked"), function () {
                if ($(this).val() != 0) {
                    $(this).prop('checked', false);
                }
            })
        } else {
            $.each($("input[name='pHairType[]']:checked"), function () {
                if ($(this).val() == 0) {
                    $(this).prop('checked', false);
                }
            })
        }
    });

    $("input[name='pEyeColor[]']").change(function () {
        if ($(this).val() == 0) {
            $.each($("input[name='pEyeColor[]']:checked"), function () {
                if ($(this).val() != 0) {
                    $(this).prop('checked', false);
                }
            })
        } else {
            $.each($("input[name='pEyeColor[]']:checked"), function () {
                if ($(this).val() == 0) {
                    $(this).prop('checked', false);
                }
            })
        }
    });

    $("input[name='pEyeWear[]']").change(function () {
        if ($(this).val() == 0) {
            $.each($("input[name='pEyeWear[]']:checked"), function () {
                if ($(this).val() != 0) {
                    $(this).prop('checked', false);
                }
            })
        } else {
            $.each($("input[name='pEyeWear[]']:checked"), function () {
                if ($(this).val() == 0) {
                    $(this).prop('checked', false);
                }
            })
        }
    });

    $("input[name='pBodyType[]']").change(function () {
        if ($(this).val() == 0) {
            $.each($("input[name='pBodyType[]']:checked"), function () {
                if ($(this).val() != 0) {
                    $(this).prop('checked', false);
                }
            })
        } else {
            $.each($("input[name='pBodyType[]']:checked"), function () {
                if ($(this).val() == 0) {
                    $(this).prop('checked', false);
                }
            })
        }
    });

    $("input[name='pEthnicity[]']").change(function () {
        if ($(this).val() == 0) {
            $.each($("input[name='pEthnicity[]']:checked"), function () {
                if ($(this).val() != 0) {
                    $(this).prop('checked', false);
                }
            })
        } else {
            $.each($("input[name='pEthnicity[]']:checked"), function () {
                if ($(this).val() == 0) {
                    $(this).prop('checked', false);
                }
            })
        }
    });

    $("input[name='pBodyArt[]']").change(function () {
        if ($(this).val() == 0) {
            $.each($("input[name='pBodyArt[]']:checked"), function () {
                if ($(this).val() != 0) {
                    $(this).prop('checked', false);
                }
            })
        } else {
            $.each($("input[name='pBodyArt[]']:checked"), function () {
                if ($(this).val() == 0) {
                    $(this).prop('checked', false);
                }
            })
        }
    });

    $("input[name='pDrinking[]']").change(function () {
        if ($(this).val() == 0) {
            $.each($("input[name='pDrinking[]']:checked"), function () {
                if ($(this).val() != 0) {
                    $(this).prop('checked', false);
                }
            })
        } else {
            $.each($("input[name='pDrinking[]']:checked"), function () {
                if ($(this).val() == 0) {
                    $(this).prop('checked', false);
                }
            })
        }
    });

    $("input[name='pSmoking[]']").change(function () {
        if ($(this).val() == 0) {
            $.each($("input[name='pSmoking[]']:checked"), function () {
                if ($(this).val() != 0) {
                    $(this).prop('checked', false);
                }
            })
        } else {
            $.each($("input[name='pSmoking[]']:checked"), function () {
                if ($(this).val() == 0) {
                    $(this).prop('checked', false);
                }
            })
        }
    });

    $("input[name='pMaritalStatus[]']").change(function () {
        if ($(this).val() == 0) {
            $.each($("input[name='pMaritalStatus[]']:checked"), function () {
                if ($(this).val() != 0) {
                    $(this).prop('checked', false);
                }
            })
        } else {
            $.each($("input[name='pMaritalStatus[]']:checked"), function () {
                if ($(this).val() == 0) {
                    $(this).prop('checked', false);
                }
            })
        }
    });

    $("input[name='pKids[]']").change(function () {
        if ($(this).val() == 0) {
            $.each($("input[name='pKids[]']:checked"), function () {
                if ($(this).val() != 0) {
                    $(this).prop('checked', false);
                }
            })
        } else {
            $.each($("input[name='pKids[]']:checked"), function () {
                if ($(this).val() == 0) {
                    $(this).prop('checked', false);
                }
            })
        }
    });

    $("input[name='pWantMoreKids[]']").change(function () {
        if ($(this).val() == 0) {
            $.each($("input[name='pWantMoreKids[]']:checked"), function () {
                if ($(this).val() != 0) {
                    $(this).prop('checked', false);
                }
            })
        } else {
            $.each($("input[name='pWantMoreKids[]']:checked"), function () {
                if ($(this).val() == 0) {
                    $(this).prop('checked', false);
                }
            })
        }
    });

    $("input[name='pEmploymentStatus[]']").change(function () {
        if ($(this).val() == 0) {
            $.each($("input[name='pEmploymentStatus[]']:checked"), function () {
                if ($(this).val() != 0) {
                    $(this).prop('checked', false);
                }
            })
        } else {
            $.each($("input[name='pEmploymentStatus[]']:checked"), function () {
                if ($(this).val() == 0) {
                    $(this).prop('checked', false);
                }
            })
        }
    });

    $("input[name='pField[]']").change(function () {
        if ($(this).val() == 0) {
            $.each($("input[name='pField[]']:checked"), function () {
                if ($(this).val() != 0) {
                    $(this).prop('checked', false);
                }
            })
        } else {
            $.each($("input[name='pField[]']:checked"), function () {
                if ($(this).val() == 0) {
                    $(this).prop('checked', false);
                }
            })
        }
    });

    $("input[name='pAnnualIncome[]']").change(function () {
        if ($(this).val() == 0) {
            $.each($("input[name='pAnnualIncome[]']:checked"), function () {
                if ($(this).val() != 0) {
                    $(this).prop('checked', false);
                }
            })
        } else {
            $.each($("input[name='pAnnualIncome[]']:checked"), function () {
                if ($(this).val() == 0) {
                    $(this).prop('checked', false);
                }
            })
        }
    });

    $("input[name='pResidence[]']").change(function () {
        if ($(this).val() == 0) {
            $.each($("input[name='pResidence[]']:checked"), function () {
                if ($(this).val() != 0) {
                    $(this).prop('checked', false);
                }
            })
        } else {
            $.each($("input[name='pResidence[]']:checked"), function () {
                if ($(this).val() == 0) {
                    $(this).prop('checked', false);
                }
            })
        }
    });

    $("input[name='pReligion[]']").change(function () {
        if ($(this).val() == 0) {
            $.each($("input[name='pReligion[]']:checked"), function () {
                if ($(this).val() != 0) {
                    $(this).prop('checked', false);
                }
            })
        } else {
            $.each($("input[name='pReligion[]']:checked"), function () {
                if ($(this).val() == 0) {
                    $(this).prop('checked', false);
                }
            })
        }
    });

    $("input[name='pWilling2Relocate[]']").change(function () {
        if ($(this).val() == 0) {
            $.each($("input[name='pWilling2Relocate[]']:checked"), function () {
                if ($(this).val() != 0) {
                    $(this).prop('checked', false);
                }
            })
        } else {
            $.each($("input[name='pWilling2Relocate[]']:checked"), function () {
                if ($(this).val() == 0) {
                    $(this).prop('checked', false);
                }
            })
        }
    });
}

// Profile / Invite
function profileInviteCheckForm() {
    var email = $('#email');
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if (!re.test(email.val())) { // Error: Email is not valid!
        $('#emailError').css({'display': 'block'});
        email.focus();

        return false;
    }

    var msg = $('#inviteForm').serialize();
    $.ajax({
        type: 'POST',
        async: false,
        dataType: 'json',
        url: '/ajax/checklogin',
        data: msg,
        success: function (result) {
            profileIndexSetEmailExists(result.status);
        },
        error: function (xhr, str) {
            // Error: xhr.responseCode
        }
    });

    if (landingIndexEmailExists) {
        $('#emailExistsError').css({'display': 'block'});
        email.focus();

        return false;
    }

    var message = $('#message');
    if (message.val() == '') { // Error: Empty message!
        $('#messageError').css({'display': 'block'});
        message.focus();

        return false;
    }

    // You entered a valid data
    return true;
}

function profileInviteSendData() {
    if (profileInviteCheckForm()) {
        var msg = $('#inviteForm').serialize();
        $.ajax({
            type: 'POST',
            url: '/ajax/profile',
            data: msg,
            success: function (data) {
                $('#resultsEmail').html(data.email);
                $('#results').css({'display': 'block'});
                $(':input', '#inviteForm')
                    .not(':button, :submit, :reset, :hidden')
                    .val('')
                    .removeAttr('checked')
                    .removeAttr('selected');
            },
            error: function (xhr, str) {
                // Error: xhr.responseCode
            }
        });

        return true;
    }

    return false;
}

// Profile / Restore
function profileRestoreSendData() {
    var email = $('#email');
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if (!checkEmail(email.val())) { // Error: Email is not valid!
        $('#emailError').css({'display': 'block'});
        email.focus();

        return false;
    }

    var remindPwdForm = $('#remindPwdForm');

    var msg = remindPwdForm.serialize();
    $.ajax({
        type: 'POST',
        async: false,
        dataType: 'json',
        url: '/ajax/checklogin',
        data: msg,
        success: function (result) {
            profileIndexSetEmailExists(result.status);
        },
        error: function (xhr, str) {
            // Error: xhr.responseCode
        }
    });

    if (!landingIndexEmailExists) {
        $('#userNotFoundError').css({'display': 'block'});
        email.focus();

        return false;
    }

    var password = $('#password');
    var password2 = $('#password2');
    if (password.val() != "" && password.val() == password2.val()) {
        var error = false;
        var re1 = /[0-9]/;
        var re2 = /[a-z]/;
        var re3 = /[A-Z]/;

        if (password.val().length < 3) { // Error: password must contain at least 3 characters!
            $('#passwordError1').css({'display': 'block'});
            error = true;
        }

        if (error) {
            password.focus();

            return false;
        }
    } else { // Error: please check that you've entered and confirmed your password!
        $('#passwordError6').css({'display': 'block'});
        password.focus();

        return false;
    }

    $.ajax({
        type: 'POST',
        async: false,
        url: '/ajax/remindpwd',
        data: msg,
        success: function (data) {
            if (data.status) {
                $('#results').css({'display': 'block'});
                remindPwdForm.css({'display': 'none'});
            } else {
                $('#tokenError').css({'display': 'block'});
                email.val('');
                password.val('');
                password2.val('');
                email.focus();
            }
        },
        error: function (xhr, str) {
            // Error: xhr.responseCode
        }
    });

    return true;
}

// Profile / Email
function profileEmailSendData() {
    if (profileEmailCheckForm()) {
        var msg = $('#emailForm').serialize();
        $.ajax({
            type: 'POST',
            url: '/ajax/profile',
            data: msg,
            success: function (data) {
                location.href='/profile';
            },
            error: function (xhr, str) {
                // Error: xhr.responseCode
            }
        });

        return true;
    }

    return false;
}

function profileEmailCheckForm() {
    var email = $('#email');
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if (!re.test(email.val())) { // Error: Email is not valid!
        $('#emailError').css({'display': 'block'});
        email.focus();

        return false;
    }

    var msg = $('#emailForm').serialize();
    $.ajax({
        type: 'POST',
        async: false,
        dataType: 'json',
        url: '/ajax/checklogin',
        data: msg,
        success: function (result) {
            profileIndexSetEmailExists(result.status);
        },
        error: function (xhr, str) {
            // Error: xhr.responseCode
        }
    });

    if (landingIndexEmailExists) {
        $('#emailExistsError').css({'display': 'block'});
        email.focus();

        return false;
    }

    // You entered a valid data
    return true;
}

// Relations

// Report
function reportIndexSendData() {
    var message = $('#message');
    if (message.val() == '') {
        $('#messageError').css({'display': 'block'});
        message.focus();

        return false;
    }

    var msg = $('#reportForm').serialize();
    $.ajax({
        type: 'POST',
        url: '/ajax/report',
        data: msg,
        success: function (data) {
            $('#results').css({'display': 'block'});
            $('#reportForm').css({'display': 'none'});
        },
        error: function (xhr, str) {
            // Error: xhr.responseCode
        }
    });

    return true;
}

// Search
function searchSavedDeleteSearch(id) {
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '/ajax/search',
        data: {
            command: 'delete',
            id: id
        },
        success: function (result) {
            $('#'.concat(id)).remove();
        },
        error: function (xhr, str) {
            // Error: xhr.responseCode
        }
    });

    return false;
}

function searchSavedDeleteFavorite(id) {
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '/ajax/search',
        data: {
            command: 'deleteFavorite',
            id: id
        },
        success: function (result) {
            $('#'.concat(id)).remove();
        },
        error: function (xhr, str) {
            // Error: xhr.responseCode
        }
    });

    return false;
}

function searchRunAdd2Favorites(id) {
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '/ajax/search',
        data: {
            command: 'add2Favorites',
            id: id
        },
        success: function (result) {
            //$('#'.concat(id)).remove();
        },
        error: function (xhr, str) {
            // Error: xhr.responseCode
        }
    });

    return false;
}

function searchRunMarkAsViewed(id) {
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '/ajax/search',
        data: {
            command: 'markAsViewed',
            id: id
        },
        success: function (result) {
            //$('#'.concat(id)).remove();
        },
        error: function (xhr, str) {
            // Error: xhr.responseCode
        }
    });

    return false;
}

function searchRunMarkAsUnviewed(id) {
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '/ajax/search',
        data: {
            command: 'markAsUnviewed',
            id: id
        },
        success: function (result) {
            //$('#'.concat(id)).remove();
        },
        error: function (xhr, str) {
            // Error: xhr.responseCode
        }
    });

    return false;
}

// Test