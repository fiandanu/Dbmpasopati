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
        :root {
            /* Font Families */
            --font-primary: "Poppins", sans-serif;
            --font-secondary: "Ag", sans-serif;

            /* Font Sizes and Line Heights */
            --display-large-size: 64px;
            --display-large-line-height: 72px;
            --display-medium-size: 48px;
            --display-medium-line-height: 56px;
            --display-small-size: 40px;
            --display-small-line-height: 48px;

            --headline-large-size: 32px;
            --headline-large-line-height: 40px;
            --headline-medium-size: 24px;
            --headline-medium-line-height: 36px;
            --headline-small-size: 20px;
            --headline-small-line-height: 32px;

            --title-large-size: 20px;
            --title-large-line-height: 28px;
            --title-large-letter-spacing: 0.4px;
            --title-medium-size: 18px;
            --title-medium-line-height: 24px;
            --title-medium-letter-spacing: 0.16px;
            --title-small-size: 16px;
            --title-small-line-height: 20px;
            --title-small-letter-spacing: 0.12px;

            --label-large-size: 16px;
            --label-large-line-height: 24px;
            --label-large-letter-spacing: 0.2px;
            --label-medium-size: 14px;
            --label-medium-line-height: 20px;
            --label-medium-letter-spacing: 0.4px;
            --label-small-size: 12px;
            --label-small-line-height: 20px;
            --label-small-letter-spacing: 0.6px;

            /* Font Weights */
            --weight-regular: 400;
            --weight-medium: 500;
            --weight-semi-bold: 600;
            --weight-bold: 700;

            --body-small-size: 12px;
            --body-small-line-height: 20px;
            --body-medium-size: 14px;
            --body-medium-line-height: 23px;
            --body-large-size: 16px;
            --body-large-line-height: 26px;

            --Netral-10: #ffffff;
            --Netral-9: #fafafa;
            --Netral-8: #ebebeb;
            --Netral-7: #dedede;
            --Netral-6: #c7c7c7;
            --Netral-5: #ababab;
            --Netral-4: #949494;
            --Netral-3: #757575;
            --Netral-2: #616161;
            --Netral-1: #3d3d3d;
            --Netral-0: #262626;

            --Primary-01: #c7f5d5;
            --Primary-02: #93ebaf;
            --Primary-03: #5be186;
            --Primary-04: #27d35d;
            --Primary-05: #1d9d45;
            --Primary-06: #177d37;
            --Primary-07: #125f2a;
            --Primary-08: #0c411d;
            --Primary-09: #061e0d;
            --Primary-10: #031108;

            --Secondary-00: #f1f6fe;
            --Secondary-01: #e7f0fd;
            --Secondary-02: #c5dafc;
            --Secondary-03: #99bffa;
            --Secondary-04: #639df7;
            --Secondary-05: #3882f5;
            --Secondary-06: #186ce1;
            --Secondary-07: #155dc2;
            --Secondary-08: #124fa6;
            --Secondary-09: #0d3c7c;
            --Secondary-10: #08244a;

            --yellow-00: #fffdf0;
            --yellow-01: #fffad6;
            --yellow-02: #fff7b8;
            --yellow-03: #fff28f;
            --yellow-04: #ffee70;
            --yellow-05: #ffe942;
            --yellow-06: #ffe314;
            --yellow-07: #d6bd00;
            --yellow-08: #ad9900;
            --yellow-09: #756800;
            --yellow-10: #3d3600;

            --danger-00: #fff0f1;
            --danger-01: #ffd6d9;
            --danger-02: #ffb8bf;
            --danger-03: #ff8f9a;
            --danger-04: #ff707e;
            --danger-05: #ff4255;
            --danger-06: #ff142b;
            --danger-07: #d60015;
            --danger-08: #ad0011;
            --danger-09: #75000b;
            --danger-10: #3d0006;

            --purple-00: #eae6f9;
            --purple-01: #d4cdf4;
            --purple-02: #b1a3ea;
            --purple-03: #8d7ae1;
            --purple-04: #6d54d9;
            --purple-05: #5537d2;
            --purple-06: #4429b7;
            --purple-07: #3b249e;
            --purple-08: #2b1a74;
            --purple-09: #1c114b;
            --purple-10: #0c0821;
        }

        .poppins-thin {
            font-family: "Poppins", sans-serif;
            font-weight: 100;
            font-style: normal;
        }

        .poppins-extralight {
            font-family: "Poppins", sans-serif;
            font-weight: 200;
            font-style: normal;
        }

        .poppins-light {
            font-family: "Poppins", sans-serif;
            font-weight: 300;
            font-style: normal;
        }

        .poppins-regular {
            font-family: "Poppins", sans-serif;
            font-weight: 400;
            font-style: normal;
        }

        .poppins-medium {
            font-family: "Poppins", sans-serif;
            font-weight: 500;
            font-style: normal;
        }

        .poppins-semibold {
            font-family: "Poppins", sans-serif;
            font-weight: 600;
            font-style: normal;
        }

        .poppins-bold {
            font-family: "Poppins", sans-serif;
            font-weight: 700;
            font-style: normal;
        }

        .poppins-extrabold {
            font-family: "Poppins", sans-serif;
            font-weight: 800;
            font-style: normal;
        }

        .poppins-black {
            font-family: "Poppins", sans-serif;
            font-weight: 900;
            font-style: normal;
        }

        .poppins-thin-italic {
            font-family: "Poppins", sans-serif;
            font-weight: 100;
            font-style: italic;
        }

        .poppins-extralight-italic {
            font-family: "Poppins", sans-serif;
            font-weight: 200;
            font-style: italic;
        }

        .poppins-light-italic {
            font-family: "Poppins", sans-serif;
            font-weight: 300;
            font-style: italic;
        }

        .poppins-regular-italic {
            font-family: "Poppins", sans-serif;
            font-weight: 400;
            font-style: italic;
        }

        .poppins-medium-italic {
            font-family: "Poppins", sans-serif;
            font-weight: 500;
            font-style: italic;
        }

        .poppins-semibold-italic {
            font-family: "Poppins", sans-serif;
            font-weight: 600;
            font-style: italic;
        }

        .poppins-bold-italic {
            font-family: "Poppins", sans-serif;
            font-weight: 700;
            font-style: italic;
        }

        .poppins-extrabold-italic {
            font-family: "Poppins", sans-serif;
            font-weight: 800;
            font-style: italic;
        }

        .poppins-black-italic {
            font-family: "Poppins", sans-serif;
            font-weight: 900;
            font-style: italic;
        }

        .display-large-64 {
            font-family: var(--font-primary);
            font-weight: var(--weight-regular);
            font-size: var(--display-large-size);
            line-height: var(--display-large-line-height);
        }

        .display-medium-48 {
            font-family: var(--font-primary);
            font-weight: var(--weight-regular);
            font-size: var(--display-medium-size);
            line-height: var(--display-medium-line-height);
        }

        .display-small-40 {
            font-family: var(--font-primary);
            font-weight: var(--weight-regular);
            font-size: var(--display-small-size);
            line-height: var(--display-small-line-height);
        }

        .headline-large-32 {
            font-family: var(--font-primary);
            font-weight: var(--weight-semi-bold);
            font-size: var(--headline-large-size);
            line-height: var(--headline-large-line-height);
        }

        .headline-medium-24 {
            font-family: var(--font-primary);
            font-weight: var(--weight-semi-bold);
            font-size: var(--headline-medium-size);
            line-height: var(--headline-medium-line-height);
        }

        .headline-small-20 {
            font-family: var(--font-primary);
            font-weight: var(--weight-semi-bold);
            font-size: var(--headline-small-size);
            line-height: var(--headline-small-line-height);
        }

        .title-large-20 {
            font-family: var(--font-primary);
            font-weight: var(--weight-medium);
            font-size: var(--title-large-size);
            line-height: var(--title-large-line-height);
            letter-spacing: var(--title-large-letter-spacing);
        }

        .title-medium-18 {
            font-family: var(--font-primary);
            font-weight: var(--weight-bold);
            font-size: var(--title-medium-size);
            line-height: var(--title-medium-line-height);
            letter-spacing: var(--title-medium-letter-spacing);
        }

        .title-small-16 {
            font-family: var(--font-primary);
            font-weight: var(--weight-bold);
            font-size: var(--title-small-size);
            line-height: var(--title-small-line-height);
            letter-spacing: var(--title-small-letter-spacing);
        }

        .label-large-16 {
            font-family: var(--font-primary);
            font-weight: var(--weight-medium);
            font-size: var(--label-large-size);
            line-height: var(--label-large-line-height);
            letter-spacing: var(--label-large-letter-spacing);
        }

        .label-medium-14 {
            font-family: var(--font-primary);
            font-weight: var(--weight-medium);
            font-size: var(--label-medium-size);
            line-height: var(--label-medium-line-height);
            letter-spacing: var(--label-medium-letter-spacing);
        }

        .label-small-12 {
            font-family: var(--font-primary);
            font-weight: var(--weight-medium);
            font-size: var(--label-small-size);
            line-height: var(--label-small-line-height);
            letter-spacing: var(--label-small-letter-spacing);
        }

        .body-small-12 {
            font-family: var(--font-secondary);
            font-weight: var(--weight-regular);
            font-size: var(--body-small-size);
            line-height: var(--body-small-line-height);
        }

        .body-medium-14 {
            font-family: var(--font-secondary);
            font-weight: var(--weight-regular);
            font-size: var(--body-medium-size);
            line-height: var(--body-medium-line-height);
        }

        .body-large-16 {
            font-family: var(--font-secondary);
            font-weight: var(--weight-regular);
            font-size: var(--body-large-size);
            line-height: var(--body-large-line-height);
        }

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
            margin: -40px;
            height: auto;
        }
    </style>
</head>

<body class="flex items-center justify-center min-h-screen">
    <div id="tsparticles"></div>
    <div class="login-box w-full max-w-md p-8">
        <div class="text-center mb-6">
            <div class="flex justify-center">
                <img src="{{ asset('img/logo_pasopati.png')  }}" alt="Logo Pasopati" class="logo-responsive">
            </div>
            {{-- <h1 class="text-3xl font-bold">Admin Login</h1> --}}
            <p>Silahkan Masuk Ke Panel Admin</p>
        </div>
        <form action="/dashboard" method="post">
            <div class="mb-4 input-group">
                <div class="relative">
                    <input type="email" class="w-full" placeholder="Username" required>
                    <span class="absolute inset-y-0 right-0 flex items-center pr-3">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207">
                            </path>
                        </svg>
                    </span>
                </div>
            </div>
            <div class="mb-6 input-group">
                <div class="relative">
                    <input type="password" class="w-full" placeholder="Password" required>
                    <span class="absolute inset-y-0 right-0 flex items-center pr-3">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 11c0 1.104-.896 2-2 2s-2-.896-2-2 2-4 2-4 2 .896 2 2zM6 17h12v4H6v-4z"></path>
                        </svg>
                    </span>
                </div>
            </div>
            <button type="submit" class="btn-purple title-medium-18">Log In</button>
        </form>
    </div>

    <script>
        tsParticles.load("tsparticles", {
            particles: {
                number: {
                    value: 30
                },
                color: {
                    value: "#3B249E"
                },
                /* Partikel dengan warna #3B249E */
                shape: {
                    type: "circle"
                },
                opacity: {
                    value: 0.4,
                    random: true
                },
                size: {
                    value: 3,
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
