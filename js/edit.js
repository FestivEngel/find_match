$(document).ready(function () {
    profileIndexBindCheckboxHandlers();

    $('#editForm').submit(function () {
        searchEditSendData();
    });

    $('#run').click(function () {
        var pCountryNum = $('#pCountries').val();
        var pCityNum = 0;
        if (pCountryNum != 0) {
            pCityNum = $('#pCities'.concat(pCountryNum)).val();
        }

        $('#pLivingCity').val(pCityNum);
        if ($('#id').val() == '') {
            $('#command').val('runNew');
        } else {
            $('#command').val('runRedefined');
        }

        var msg = $('#editForm').serialize();
        $.ajax({
            type: 'POST',
            async: false,
            dataType: 'json',
            url: '/ajax/search',
            data: msg,
            success: function (data) {
                location.href='/run/'.concat(data.id);
            },
            error: function (xhr, str) {
                // Error: xhr.responseCode
            }
        });
    });

    $('#cancel').click(function () {

    });

    var onlyDualSociotypes = $('#onlyDualSociotypes');

    if (onlyDualSociotypes.is(':checked')) {
        $("#pSociotype").prop('disabled', 'disabled');
    }

    onlyDualSociotypes.on('change', function () {
        if (this.checked) {
            $('#pSociotype').prop('disabled', 'disabled');
        } else {
            $('#pSociotype').removeAttr('disabled');
        }
    });

    var kidsNo = $('#kidsNo');

    if (kidsNo.is(':checked')) {
        $('#pNumberOfKids').prop('disabled', 'disabled');
    }

    kidsNo.on('change', function() {
        if (this.checked) {
            $('#pNumberOfKids').prop('disabled', 'disabled');
        } else {
            $('#pNumberOfKids').removeAttr('disabled');
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

    $('#name').on('input', function(){
        landingIndexRemoveErrorNotification('nameError');
    });
});

function searchEditSendData() {
    var pCountryNum = $('#pCountries').val();
    var pCityNum = 0;
    if (pCountryNum != 0) {
        pCityNum = $('#pCities'.concat(pCountryNum)).val();
    }

    $('#pLivingCity').val(pCityNum);
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
            $('#command').val('update');
            $('#id').val(data.id);
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