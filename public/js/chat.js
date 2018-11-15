
$(document).ready(function() {
    $('input[type=text]').focus();

    setInterval(function() {
        $.get(chat_messages_url, function(data) {
            $('.chat').html(data);
        });
    }, 300);
});