<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>
        <link rel="icon" href="{{ asset('images/winking_panda.png') }}" type="image/png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Chinese Calligraphy and Serif Fonts for Ink Painting Style -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Ma+Shan+Zheng&family=Zhi+Mang+Xing&family=Liu+Jian+Mao+Cao&family=Noto+Serif+SC:wght@400;500;600;700&family=Song+Myung&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased ink-body"
          style="background-color: #faf8f2; background-image: url('/images/rice-paper-texture.png'); background-size: 400px 400px; background-repeat: repeat; background-attachment: fixed;">
        @inertia
    </body>
    <!-- 备案信息区域 -->
    <footer class="py-3 bg-gray-50 border-t border-gray-200 mt-auto">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-center gap-2 text-gray-600 text-sm">
                <!-- 备案图标 -->
                <img src="{{ asset('images/beian.png') }}"
                     alt="备案图标"
                     class="w-5 h-5 object-contain inline-block align-middle">

                <!-- 备案链接 -->
                <a href="https://beian.miit.gov.cn/" rel="noreferrer" target="_blank"
                   class="hover:text-gray-900 transition-colors duration-200">
                    沪ICP备2025132458号-1
                </a>
                <a href="https://beian.mps.gov.cn/#/query/webSearch?code=31010602009089"
                   rel="noreferrer"
                   target="_blank"
                   class="hover:text-gray-900 transition-colors duration-200">
                    沪公网安备31010602009089号
                </a>
            </div>
        </div>
    </footer>

</html>
