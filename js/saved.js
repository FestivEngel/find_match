$(document).ready(function () {
    $('#delete').onclick(function () {
        alert($(this).attr('href'));

        return false;
    });
});