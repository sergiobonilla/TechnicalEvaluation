$( document ).ready(function() {
    function formAnimation () {
        $('#toggleForm').click( function () {
            $('#addData').slideToggle( "slow");
        });
    }
    function pushData () {
        $('#sendData').click( function () {
            $('#addData').hide();
            $('#loadingFrame').removeClass('d-none');
            $('#loadingFrame').addClass('d-flex');

            $.ajax({
                url: route,
                type: 'POST',
                dataType: 'json',
                data: {'data': $('#json').val()},
                async: true,
                success: function (data) {
                    $('#loadingFrame').addClass('d-none');
                    $('#loadingFrame').removeClass('d-flex');

                    $('#success').find('span').text(data['message']);
                    $('#success').show();

                    setTimeout(function(){
                        $('#success').hide();
                    }, 5000);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $('#loadingFrame').addClass('d-none');
                    $('#loadingFrame').removeClass('d-flex');

                    $('#danger').find('span').text(jqXHR['responseJSON']['message']);
                    $('#danger').show();

                    setTimeout(function(){
                        $('#danger').hide();
                    }, 5000);
                }
            });
        });
    }
    function init () {
        formAnimation();
        pushData();
    }
    init();
});
