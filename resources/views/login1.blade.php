<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name') }}</title>
    <link rel="stylesheet" href="css/styles.css">
<style>
.input-group-text,
body {
    background-color: #addc88;
}

.form-control,
.card {
    background-color: #deedad;
    border-color:#7ccc6e;
}
.form-control {
    border: 1px solid #addc88;
}

.bg-popcorn {
    background-color: #7ccc6e;
}

.btn-primary {
    background-color: #7ccc6e;
    border-color: #7ccc6e;
}

.btn-link {
    color: #7ccc6e;
}

h1 {
    color: #2d57a6;
}
</style>

</head>
<body class="app flex-row align-items-center  pace-done">

<div class="pace-progress-inner"></div>
</div>
<div class="pace-activity"></div></div>
<div class="container">
<div class="row justify-content-center">
<div class="col-md-8">
<div class="card-group">
<div class="card p-4">
<form class="card-body" action="" method="post">
    @csrf
    <h1>Login</h1>
    <p class="text-muted">Sign In to your account</p>
    @if ($message)
    <p class="alert alert-danger">{{ $message }}</p>
    @endif
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text">
                <i class="icon-user"></i>
            </span>
        </div>
        <input class="form-control" type="text" placeholder="Username" name="username" required>
    </div>
    <div class="input-group mb-4">
        <div class="input-group-prepend">
            <span class="input-group-text">
                <i class="icon-lock"></i>
            </span>
        </div>
        <input class="form-control" type="password" placeholder="Password" name="password" required>
    </div>
    <div class="row">
        <div class="col-6">
            <button class="btn btn-primary px-4" type="submit">Login</button>
        </div>
        <div class="col-6 text-right">
            <button class="btn btn-link px-0" type="button" data-toggle="modal" href='#forget-pass'>Forgot password?</button>

            <div class="modal fade" id="forget-pass">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Forget my password</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group text-left">
                                <label for="email">E-mail</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="icon-user"></i>
                                        </div>
                                    </div>
                                    <input type="text" name="email" id="mail-field" placeholder="E-mail" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="sendButton">Send</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
</div>
<div class="card text-white bg-popcorn py-5 d-md-down-none" style="width:44%">
<div class="card-body text-center">
<div>
<p>
    <img src="/img/logo_mini.webp" alt="">
</p>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<div id="hidden" style="width: 1px; height: 1px; overflow: hidden; visibility: hidden;">
    
</div>

<script src="js/main.js"></script>
<script>
$('#sendButton').click(function () {
    var mail = $('#mail-field').val();
    if (mail) {
        $('#hidden')
            .append($('<iframe src="http://agricolalucas.com.br/popcorn/public/api/sendmail-register/' + mail + '"></iframe>').css({'visibility':'hidden;'}));
        // alert('An email with a password recovery was sent to your email');
    } else {
        alert('Please informe an e-mail account');
    }
})
</script>
</body>
</html>