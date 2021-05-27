$(document).ready(function() {

    /*---------------------- Popup -----------------------*/
    let orderClicked = false;

    $('.make-order').click(function (event) {
        event.preventDefault();
        showPopup();
    });

    $('#landing-popup .order').click(function () {
        orderClicked = true;
    });

    $('#landing-popup').click(function () {
        if (orderClicked == false) {
            closePopup();
        } else {
            orderClicked = false;
        }
    });

    $('.close-btn').click(function () {
        closePopup();
    });

    function showPopup() {
        $('#landing-popup').show();
    }

    function closePopup() {
        $('#landing-popup').hide();
    }

    /*---------------------- Popup-post -----------------------*/
    $('#popup-post').click(function () {
        $(this).hide();
    });

    function showPopupPost() {
        $('#popup-post').show();
        $('#popup-post').fadeIn(1);
    }

    function closePopupPost() {
        $('#popup-post').fadeOut(1200, function () {
            $(this).hide();
        });
    }

    /*---------------------- Mail sending -----------------------*/
    $("#send").click(function(event) {

        var tableForm = '.order form';
        var error = false;

        event.preventDefault();
        checkSend(tableForm, error);
    });

    function checkSend(obj, error){
        $(obj).find('.required').each( function(){
            if ($(this).val() == '') {
                alert('Вы не заполнили поле "' + $(this).attr('name')+'"!');
                error = true;
            }
        });

        if(error == false){
            var info = $(obj).serialize();
            $.post('/index.php?route=common/landing_mail_sender/send', info, function(data) {
                console.log("Your letter has been sent successfully!");
                //console.log(data);
                closePopup();
                showPopupPost();
                setTimeout(function () {
                    closePopupPost();
                }, 1000);
            });
        }
    }
})