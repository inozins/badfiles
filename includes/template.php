<?php
/**
 * Основной шаблон
 * 
 * Шаблон для всех страниц сайта
 */

// Предотвращение прямого доступа к файлу
if (!defined('SITE_ACCESS')) {
    exit('Прямой доступ к файлу запрещен!');
}
?>
<!DOCTYPE html>
<html lang="ru" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title) ?> - <?= SITE_NAME ?></title>
    
    <!-- Tailwind CSS через CDN с настройкой темного режима -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script>
        // Настройка Tailwind CSS
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['-apple-system', 'BlinkMacSystemFont', 'San Francisco', 'Helvetica Neue', 'Helvetica', 'Arial', 'sans-serif'],
                    },
                    colors: {
                        'apple-dark': '#1c1c1e',
                        'apple-darker': '#2c2c2e',
                        'apple-blue': '#0a84ff',
                        'apple-blue-light': '#64d2ff',
                        'apple-gray': '#8e8e93',
                    },
                    boxShadow: {
                        'apple': '0 0 10px rgba(0, 0, 0, 0.5)',
                    },
                    animation: {
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'fade-in': 'fadeIn 0.5s ease-out forwards',
                        'slide-up': 'slideUp 0.5s ease-out forwards',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(20px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        }
                    }
                }
            }
        }
    </script>
    
    <style>
        /* Дополнительные стили для темного интерфейса в стиле Apple */
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'San Francisco', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            background-color: #000000;
            color: #f5f5f7;
            background-image: radial-gradient(ellipse at top, #1a1a1a 0%, #000000 70%);
            background-attachment: fixed;
        }
        
        .apple-button {
            background-color: #0a84ff;
            border-radius: 12px;
            color: white;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        
        .apple-button:hover {
            background-color: #0071e3;
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(10, 132, 255, 0.2);
        }
        
        .apple-button:active {
            transform: translateY(0);
        }
        
        .apple-input {
            border-radius: 12px;
            border: 1px solid #3a3a3c;
            background-color: rgba(28, 28, 30, 0.8);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        
        .apple-card {
            background-color: rgba(28, 28, 30, 0.8);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .apple-card:hover {
            box-shadow: 0 12px 36px rgba(0, 0, 0, 0.3);
            transform: translateY(-2px);
        }
        
        .progress-bar {
            width: 0%;
            height: 5px;
            background: linear-gradient(90deg, #0a84ff, #64d2ff);
            border-radius: 3px;
            transition: width 0.3s ease;
        }
        
        /* Градиентный текст для заголовков */
        .gradient-text {
            background: linear-gradient(90deg, #0a84ff, #64d2ff);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            display: inline-block;
        }
        
        /* Стили для glassmorphism */
        .glassmorphism {
            background: rgba(28, 28, 30, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 16px;
        }
        
        /* Анимации для элементов */
        .animate-fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }
        
        .animate-slide-up {
            animation: slideUp 0.5s ease-out forwards;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
    </style>
</head>
<body class="bg-black dark:bg-black min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <?php include 'partials/header.php'; ?>
        
        <div class="animate-fade-in opacity-0">
            <?= $content ?>
        </div>
        
        <?php include 'partials/footer.php'; ?>
    </div>
</body>
</html> 