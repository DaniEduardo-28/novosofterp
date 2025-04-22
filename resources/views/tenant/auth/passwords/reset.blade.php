<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reiniciar Contraseña - {{ strtoupper($company->trade_name) }}</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('/images/novosofterp.jpg');
            background-size: cover;
            background-position: center center;
            background-attachment: fixed;
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .card {
            background: rgba(255, 255, 255, 0.97);
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            padding: 20px;
        }

        .auth__title {
            font-weight: bold;
            color: #333;
        }

        .auth__logo-form {
            max-width: 150px;
        }

        .custom-primary {
            background-color: #fc4f40;
            border-color: #fc4f40;
            color: #fff;
        }

        .custom-primary:hover,
        .custom-primary:focus {
            background-color: #e44536;
            border-color: #e44536;
            color: #fff;
        }

        .form-label {
            text-align: left;
        }

        .form-group {
            position: relative;
        }

        .form-check {
            display: flex;
            align-items: center;
        }

        .form-check input {
            margin-right: 8px;
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="text-center mb-2">
            <img class="auth__logo-form" src="{{ asset('storage/uploads/logos/' . $company->logo) }}" alt="Logo formulario">
            <h2 class="auth__title mt-2">Bienvenido a {{ $company->trade_name }}</h2>
            <p class="text-muted">Reinicia tu contraseña</p>
        </div>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group mb-3">
                <label for="email" class="form-label text-left">Correo electrónico</label>
                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                    name="email" value="{{ $email ?? old('email') }}" required autofocus>

                @if ($errors->has('email'))
                    <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                @endif
            </div>

            <div class="form-group mb-3">
                <label for="password" class="form-label text-left">Contraseña</label>
                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                    name="password" required>

                @if ($errors->has('password'))
                    <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                @endif
            </div>

            <div class="form-group mb-3">
                <label for="password-confirm" class="form-label text-left">Confirmar Contraseña</label>
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
            </div>

            <div class="form-group text-center mt-4">
                <button type="submit" class="btn btn-primary custom-primary w-100">Reiniciar Contraseña</button>
            </div>

            <div class="text-center mt-4">
                <a href="{{ url('login') }}" class="btn btn-link">
                    <i class="fa fa-arrow-left mr-2"></i> Regresar al login
                </a>
            </div>
        </form>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>
