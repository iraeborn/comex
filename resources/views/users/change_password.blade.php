<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name') }}</title>
    <link rel="stylesheet" href="css/styles.css">

<style>
.input-group-text, body {
    background-color: #addc88;
}
div.card.bg-primary {
    background-color: #7ccc6e !important;
    border-color: #7ccc6e !important;
}

.btn.btn-primary.px-4 {
    background-color: #7ccc6e;
    border-color: #7ccc6e;
}

.card {
    background-color: #deedad;
    border-color: #7ccc6e;
}

h1 {
    color: #2d57a6;
}

.form-control, .card {
    background-color: #deedad;
    border-color: #7ccc6e;
}
</style>

</head>
<body class="app flex-row align-items-center  pace-done">

    <div class="pace-progress-inner"></div>
    <div class="pace-activity"></div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card-group">
                    <div class="card p-4">
                        <form class="card-body" method="post" action="">
                            @csrf
                            <input type="hidden" name="user_id" value="{{$user->id}}">
                            <input type="hidden" name="token" value="{{$user->token}}">

                            <div class="input-group mb-3">
                                <input class="form-control" type="password" placeholder="New password" name="new_password" id="new_password" required>
                            </div>
                            <div class="input-group mb-4">
                                <input class="form-control" type="password" placeholder="Confirm Password" name="password_confirmation" id="password_confirmation" required>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <button class="btn btn-primary px-4 pill-right" type="submit">Save new password</button>
                                </div>
                            </div>

                        </form>
                    </div>
                    <div class="card text-white bg-primary py-5 d-md-down-none" style="width:44%">
                        <div class="card-body text-center">
                            <div>
                                <h2>{{ config('app.name') }}</h2>
                                <p>Please, fill in your new password and password confirmation to access.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="js/main.js"></script>
<script>
var password = document.getElementById("new_password")
var confirm_password = document.getElementById("password_confirmation")

function validatePassword(){
  if(password.value != confirm_password.value) {
    confirm_password.setCustomValidity("Passwords Don't Match");
  } else {
    confirm_password.setCustomValidity('');
  }
}

password.onchange = validatePassword;
confirm_password.onkeyup = validatePassword;
</script>
</body>
</html>