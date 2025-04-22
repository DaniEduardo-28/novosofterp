<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña - {{ strtoupper($company->trade_name) }}</title>
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
            font-size: 0.9rem;
        }

        .form-group {
            position: relative;
        }

        a.text-primary {
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }

        a.text-primary:hover {
            color: #e44536;
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="text-center mb-2">
            @if ($useLoginGlobal)
                @if ($login->logo ?? false)
                    @if ($login->show_logo_in_form)
                        <img class="auth__logo-form" src="{{ $login->logo }}" alt="Logo formulario">
                    @endif
                @endif
            @else
                @if ($login->show_logo_in_form)
                    @if ($company->logo)
                        <img class="auth__logo-form" src="{{ asset('storage/uploads/logos/' . $company->logo) }}"
                            alt="Logo formulario">
                    @else
                        <img class="auth__logo-form" src="{{ asset('logo/tulogo.png') }}" alt="Logo formulario">
                    @endif
                @endif
            @endif
            <h2 class="auth__title mt-2">Restablecer Contraseña</h2>
            <p class="text-muted">{{ $company->trade_name }}</p>
        </div>
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="form-group mb-3" style="text-align: left;">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input type="email" name="email" id="email"
                    class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" value="{{ old('email') }}"
                    autofocus placeholder="Ingresa tu correo electrónico">
                @if ($errors->has('email'))
                    <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                @endif
            </div>

            <div class="form-group text-center mb-3">
                <button class="btn custom-primary w-100" type="submit">ENVIAR LINK</button>
            </div>

            <div class="form-group mb-3">
                <a href="{{ url('login') }}" class="text-decoration-none text-primary">
                    <i class="fa fa-arrow-left mr-2"></i> Regresar al login
                </a>
            </div>
        </form>

        <div class="text-center mt-4">
            <p class="small">&copy; {{ date('Y') }} NovoSoft ERP <br> Todos los derechos reservados.</p>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>
