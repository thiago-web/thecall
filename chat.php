<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors','On');

require 'constants.inc.php';
require 'DbConnPDO.class.php';
?>
<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <title>Chat público</title>
  <meta name="description" content="Chat público" />
  <meta name="author" content="Sandro Marques">
  <meta name="editor" content="Thiago Henrique">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="styles.css" rel="stylesheet" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
  <!-- Link Bootstrap Css -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>

<body>
<div id="sidebar">
    conversas
</div>
<div id="primary">
    <div id="log">
        <span class="long-content">&    ;</span>
    </div>
    <div id="composer">
        <form name="form_message" id="form_message" method="post" action="set_message.ajax.php" 
        class="form-control">
          <input name="nickname" type="hidden" id="nickname" value="<?php echo $_SESSION['nickname']; ?>">
          <input name="message" type="text" autofocus required class="textbox_message" id="message">
          <button class="btn btn-primary" id="btn_send">Enviar</button>
        </form>
    </div>
</div>

<!-- Link JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<!-- Pacote Completo JS -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

</body>
<script>
'use strict';
$(document).ready(function() {
    getMessages();

    function getMessages() {
        $('.long-content').empty();
        var msg_error = 'Ocorreu um erro...';
        var msg_timeout = 'O servidor não está a responder';
        var message = '';
        $.ajax({
            url: 'get_messages.ajax.php',
            dataType: "json",
            error: function(xhr, status, error) {
                if (status==="timeout") {
                    message = msg_timeout;
                    alert(message);
                } else {
                    message = msg_error;
                    alert(message + ': ' + error);
                }
            },
            success: function(response) {
                $.each(response, function(i, item) {
                    $('.long-content').prepend('<p><b>'+item.FromNickname + '</b>: ' + item.Message+'</p>');
                });
                setTimeout(getMessages, 2000);
            },
            timeout: 7000
        });
    }   

    // submeter formulário pela função sendForm()
    $('#form_message').on('submit', function(e) {
        e.preventDefault();
        sendForm();
    });
    
    function sendForm() {
        var msg_error = 'Ocorreu um erro..';
        var msg_timeout = 'O servidor não está a responder';
        var message = '';
        var form = $('#form_message');
        $.ajax({
            data: form.serialize(),
            url: form.attr('action'),
            type: form.attr('method')
        })
        $('#message').val('');
    }

});
</script>

</html>