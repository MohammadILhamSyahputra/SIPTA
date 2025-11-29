<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Aplikasi Laravel - Login</title>

    <link href="{{ asset('template/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <link href="{{ asset('template/css/sb-admin-2.min.css') }}" rel="stylesheet">

    <style>
        /* ====================================
           I. Body & Card (Background & Shadow)
           ==================================== */
        body.bg-gradient-primary {
            /* Warna latar belakang hijau yang konsisten dengan register */
            background: linear-gradient(135deg, #004d40 0%, #00796b 100%);
            display: flex;
            justify-content: center;
            align-items: center; 
            min-height: 100vh;
            margin: 0;
            overflow: auto; 
        }

        .card.o-hidden {
            border-radius: 1.5rem; 
            box-shadow: 0 25px 50px rgba(0,0,0,0.3);
            max-width: 1500px; /* Lebar yang sama dengan form register sebelumnya */
            width: 95%; 
            overflow: hidden;
            transition: transform 0.3s ease;
            margin-top: 2rem !important; 
            margin-bottom: 2rem !important;
        }
        
        /* ====================================
           II. Area Gambar (Logo - col-lg-3)
           ==================================== */
        .bg-login-image {
            background-color: #f0f4f7;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem;
            border-radius: 1.5rem 0 0 1.5rem; 
            min-height: 350px; 
        }
        
        .bg-login-image img {
            max-width: 100%;
            height: auto;
            border-radius: 50%; /* Membuat gambar logo bulat */
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            object-fit: contain;
            /* Penyesuaian agar logo turun sedikit */
            margin-top: 15px; 
            margin-bottom: -15px; 
        }

        /* ====================================
           III. Area Form (col-lg-9)
           ==================================== */
        .col-lg-9 {
            /* Padding yang sama dengan register untuk konsistensi */
            padding: 3.5rem !important; 
            display: flex;
            flex-direction: column;
            justify-content: center; 
        }
        
        .text-center h1 {
            color: #212529; 
            font-weight: 800; 
            font-size: 2.25rem;
            letter-spacing: -0.5px; 
            margin-bottom: 2.5rem; /* Jarak untuk login */
        }

        /* Input Fields */
        .form-control-user {
            border-radius: 0.75rem; 
            padding: 1rem 1.25rem;
            font-size: 1rem;
            border: 1px solid #dee2e6;
            background-color: #f8f9fa;
            transition: all 0.3s;
        }
        
        .form-control-user:focus {
            border-color: #00796b;
            box-shadow: 0 0 0 0.2rem rgba(0, 121, 107, 0.25);
            background-color: #ffffff;
        }

        .form-group {
            margin-bottom: 1.5rem; /* Jarak antar input */
        }

        /* Button */
        .btn-primary {
            background-color: #00796b; 
            border-color: #00796b;
        }
        
        .btn-user {
            border-radius: 0.75rem;
            padding: 1rem 1.5rem;
            font-size: 1.1rem;
            font-weight: 700;
            transition: all 0.3s ease;
        }

        .btn-user:hover {
            background-color: #004d40;
            border-color: #004d40;
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(0, 121, 107, 0.3);
        }

        /* Divider & Link */
        hr {
            margin-top: 2rem; 
            margin-bottom: 1rem; 
            border-top: 1px solid rgba(0, 0, 0, 0.05);
        }

        .small {
            color: #6c757d;
            font-size: 0.95rem;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .small:hover {
            color: #00796b;
            text-decoration: underline;
        }

        /* ====================================
           IV. Responsiveness
           ==================================== */
        @media (max-width: 992px) {
            .card.o-hidden {
                border-radius: 1rem;
                max-width: 100%;
                margin-top: 1rem !important;
                margin-bottom: 1rem !important; 
                min-height: 100vh;
            }
            .col-lg-3.d-none.d-lg-block {
                display: none !important;
            }
            .col-lg-9 {
                padding: 3rem !important;
                border-radius: 1rem;
            }
        }
    </style>
</head>
<body class="bg-gradient-primary">
    <div class="container">
        <div class="card o-hidden border-0 shadow-lg">
            <div class="card-body p-0">
                <div class="row">
                    <div class="col-lg-3 d-none d-lg-block bg-login-image">
                        <img src="{{ asset('template/img/LogoSipta.jpg') }}" alt="Logo Sipta">
                    </div>
                    <div class="col-lg-9">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Login Account!</h1>
                            </div>
                            
                            {{-- Flash Message (Jika ada) --}}
                            @if (session('status'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('status') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                            
                            <form action="{{ route('login') }}" method="POST" class="user">
                                @csrf

                                <div class="form-group">
                                    <input type="email" name="email"
                                        class="form-control form-control-user @error('email') is-invalid @enderror"
                                        id="exampleInputEmail" placeholder="Email Address" value="{{ old('email') }}" required autofocus>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group mb-4">
                                    <input type="password" name="password"
                                        class="form-control form-control-user @error('password') is-invalid @enderror"
                                        id="exampleInputPassword" placeholder="Password" required>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary btn-user btn-block">
                                    Login
                                </button>
                            </form>
                            <hr>

                            <div class="text-center">
                                <a class="small" href="{{ route('register')}}">Belum Punya Akun? Registrasi!</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('template/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('template/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('template/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('template/js/sb-admin-2.min.js') }}"></script>
</body>
</html>