$(document).ready(function () {
    $('#nickname').on('input', function () {
        landingIndexRemoveErrorNotification('nicknameError');
    });

    $('#email').on('input', function () {
        landingIndexRemoveErrorNotification('emailError');
        landingIndexRemoveErrorNotification('emailExistsError');
    });

    $('#password').on('input', function () {
        landingIndexRemoveErrorNotification('passwordError');
    });

    $('#password2').on('input', function () {
        landingIndexRemoveErrorNotification('passwordError');
    });

    $("#password").on("keypress keyup keydown", function() {
        var pass = $(this).val();
        $("#strength_human").text(checkPassStrength(pass));
        $("#strength_score").text(scorePassword(pass));
    });
});

function checkPassStrength(pass) {
    var score = scorePassword(pass);
    if (score >= 90)
        return $('#veryStrongPassword').val();
    if (score >= 80)
        return $('#strongPassword').val();
    if (score >= 70)
        return $('#goodPassword').val();
    if (score >= 50)
        return $('#averagePassword').val();
    if (score >= 25)
        return $('#weakPassword').val();

    return $('#veryWeakPassword').val();
}

function scorePassword(pass) {
    var score = 0;
    if (!pass)
        return score;

    // award every unique letter until 5 repetitions
    var letters = new Object();
    for (var i = 0; i < pass.length; i++) {
        letters[pass[i]] = (letters[pass[i]] || 0) + 1;
        score += 5.0 / letters[pass[i]];
    }

    // bonus points for mixing it up
    var variations = {
        digits: /\d/.test(pass),
        lower: /[a-z]/.test(pass),
        upper: /[A-Z]/.test(pass),
        nonWords: /\W/.test(pass)
    };

    variationCount = 0;
    for (var check in variations) {
        variationCount += (variations[check] == true) ? 1 : 0;
    }
    score += (variationCount - 1) * 10;

    return parseInt(score);
}