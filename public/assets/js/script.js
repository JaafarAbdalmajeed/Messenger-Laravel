$('.form-chat').submit(function (e) {
    e.preventDefault();
    var form = $(this);
    $.post(form.attr('action'), form.serialize(), function(response){
        let msg = form.find('.write-message').val(); // Get the value of the input field
        let message = `
            <div class="message">
                <div class="photo" style="background-image: url(https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1050&q=80);"></div>
                <p class="text">${msg}</p>
                <small class="extra-small text-muted"></small>
            </div>
        `;
        $('.messages-chat').append(message);
    });
});

$(document).ready(function () {
    $('.fa-user').click(function () {
        $('#discussions1').hide();
        $('#discussions2').show();
    });

    $('.fa-commenting').click(function () {
        $('#discussions1').show();
        $('#discussions2').hide();
    });
});

