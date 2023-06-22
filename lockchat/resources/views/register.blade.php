<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <!-- Font Awesome-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.3.0/mdb.min.css" rel= "stylesheet" />
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mbd-ui-kit/3.3.0/mbd.min.js"></script>
</head>
<body class="bg-info">
    <div class="container w-25 mt-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <h3> Register </h3>
                <form action="{{ route('register') }}" method="POST" autocomplete="off">
                    @csrf
                    <div class="input-group">
                        <input type="text" class="name" name="name" placeholder="name">
                        <input type="text" class="password" name="password" placeholder="password">
                        <input type="text" class="email" name="email" placeholder="email">      
                    </div>
                    <br>
                    <button class="button login__submit">
                        <span class="button__text">Register </span>
                        <i class="button__icon fas fa-chevron-right"></i>
                    </button>
                </form>
    </div>
</body>
</html>