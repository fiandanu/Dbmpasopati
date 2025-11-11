<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <!-- Google Font: Inter -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <!-- tsparticles -->
    <script src="https://cdn.jsdelivr.net/npm/tsparticles@2.12.0/tsparticles.bundle.min.js"></script>
    <style>
        .btn-purple {
            padding: 0.5rem;
            width: 100%;
            height: 49px;
            background-color: var(--purple-07);
            border-radius: 12px;
            color: var(--Netral-9);
            font-family: var(--font-primary);
            font-weight: var(--weight-medium);
            font-size: var(--label-medium-size);
            line-height: var(--label-medium-line-height);
            letter-spacing: var(--label-medium-letter-spacing);
        }


        input:-webkit-autofill {
            -webkit-box-shadow: 0 0 0px 1000px #FFFFFF inset;
            /* ganti #FFFFFF dengan warna yang diinginkan */
            -webkit-text-fill-color: #1F2937;
            /* ganti dengan warna teks yang diinginkan */
            transition: background-color 5000s ease-in-out 0s;
            /* mencegah flash warna */
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #FFFFFF;
            /* Latar belakang putih dominan */
            overflow: hidden;
        }

        .login-box {
            /* background: rgba(255, 255, 255, 0.1); */
            background-color: #FFFFFF;
            border-radius: 2rem;
            /* border: 1px solid rgba(59, 36, 158, 0.3); */
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .login-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
        }

        .login-box h1 {
            color: #1F2937;
            /* Teks gelap untuk kontras pada latar putih */
        }

        .login-box p {
            color: #4B5563;
            /* Abu-abu gelap untuk teks sekunder */
        }

        .input-group input {
            background: #FFFFFF;
            /* Latar input putih */
            border: 1px solid #E5E7EB;
            /* Border abu-abu netral */
            border-radius: 0.5rem;
            padding: 0.75rem;
            color: #1F2937;
        }

        .input-group input:focus {
            outline: none;
            border-color: #3B249E;
            /* Border fokus dengan #3B249E */
            box-shadow: 0 0 0 3px rgba(59, 36, 158, 0.2);
        }


        #tsparticles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            opacity: 0;
            transition: opacity 1s ease;
        }

        #tsparticles.active {
            opacity: 1;
        }

        @supports not (backdrop-filter: blur(12px)) {
            .login-box {
                background: rgba(255, 255, 255, 0.3);
                /* Fallback untuk browser lama */
            }
        }

        .logo-responsive {
            width: 250px;
            margin: 1px;
            height: auto;
        }
    </style>
</head>

<body class="flex items-center justify-center min-h-screen">
    <div id="tsparticles"></div>
    <div class="login-box w-full max-w-md p-8">
        <div class="text-center mb-6">
            <div class="flex justify-center">
                <img src="{{ asset('img/logo_pasopati.webp') }}" alt="Logo Pasopati" class="logo-responsive">
            </div>
            <p>Silahkan Masuk Ke Panel Admin</p>
        </div>
        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div class="mb-4 input-group">
                <div class="relative">
                    <input type="text" class="w-full" name="username" id="username" placeholder="Username" required>
                    <span class="absolute inset-y-0 right-0 flex items-center pr-3">
                    </span>
                </div>
            </div>
            <div class="mb-6 input-group">
                <div class="relative">
                    <input type="password" class="w-full" name="password" id="password" placeholder="Password"
                        required>
                    <span class="absolute inset-y-0 right-0 flex items-center pr-3">
                    </span>
                </div>
            </div>
            <button type="submit" class="btn-purple title-medium-18">
                Log In
            </button>
        </form>
        @if ($errors->any())
            <div class="mt-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
                <p>{{ $errors->first() }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="mt-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
                <p>{{ session('error') }}</p>
            </div>
        @endif
    </div>

    <script>
        // Particles script tetap sama
        tsParticles.load("tsparticles", {
            particles: {
                number: {
                    value: 30
                },
                color: {
                    value: "#3B249E"
                },
                shape: {
                    type: "circle"
                },
                opacity: {
                    value: 0.4,
                    random: true
                },
                size: {
                    value: 10,
                    random: {
                        enable: true,
                        minimumValue: 1
                    }
                },
                move: {
                    enable: true,
                    speed: 1,
                    direction: "none",
                    random: true,
                    outModes: {
                        default: "out"
                    }
                }
            },
            interactivity: {
                events: {
                    onHover: {
                        enable: false
                    },
                    onClick: {
                        enable: false
                    }
                }
            }
        });

        let inactivityTimer;
        const particlesContainer = document.getElementById("tsparticles");

        function startInactivityTimer() {
            clearTimeout(inactivityTimer);
            inactivityTimer = setTimeout(() => {
                particlesContainer.classList.add("active");
            }, 5000);
        }

        function resetInactivityTimer() {
            particlesContainer.classList.remove("active");
            clearTimeout(inactivityTimer);
            startInactivityTimer();
        }

        startInactivityTimer();
        document.addEventListener("mousemove", resetInactivityTimer);
    </script>
</body>

</html>
