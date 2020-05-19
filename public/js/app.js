$( document ).ready(function() {
    function formAnimation () {
        $('#toggleForm').click( function () {
            $('#addData').slideToggle( "slow");
        });
    }
    function init () {
        formAnimation();
    }
    init();
});
