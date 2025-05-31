<?php
/**
 * Шапка сайта
 * 
 * Содержит логотип и навигационное меню
 */

// Предотвращение прямого доступа к файлу
if (!defined('SITE_ACCESS')) {
    exit('Прямой доступ к файлу запрещен!');
}

// Получение текущего пути для подсветки активного пункта меню
$currentPage = basename($_SERVER['PHP_SELF']);

// Специальная обработка для страницы файлов (из-за rewrite в .htaccess)
$filesActive = false;
if (strpos($_SERVER['REQUEST_URI'], '/files') !== false) {
    $filesActive = true;
}
?>

<header class="mb-8 animate-fade-in opacity-0">
    <div class="flex flex-col sm:flex-row items-center justify-between py-4 border-b border-gray-700/30 dark:border-gray-700/30 mb-2">
        <!-- Логотип -->
        <div class="flex items-center mb-4 sm:mb-0">
            <a href="/" class="flex items-center group transition-all duration-300">
                <div class="bg-gradient-to-r from-apple-blue to-apple-blue-light p-2 rounded-xl mr-3 transition-all duration-300 group-hover:shadow-lg group-hover:shadow-apple-blue/20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </div>
                <span class="text-xl font-medium gradient-text"><?= SITE_NAME ?></span>
            </a>
        </div>
        
        <!-- Навигация -->
        <nav class="glassmorphism py-2 px-4 rounded-full">
            <ul class="flex space-x-6">
                <li>
                    <a href="/" class="text-sm transition-all duration-300 <?= $currentPage === 'index.php' ? 'gradient-text font-medium' : 'text-gray-300 dark:text-gray-300 hover:text-white dark:hover:text-white' ?>">
                        главная
                    </a>
                </li>
                <li>
                    <a href="/files" class="text-sm transition-all duration-300 <?= $filesActive ? 'gradient-text font-medium' : 'text-gray-300 dark:text-gray-300 hover:text-white dark:hover:text-white' ?>">
                        файлы
                    </a>
                </li>
                <li>
                    <a href="/info.php" class="text-sm transition-all duration-300 <?= $currentPage === 'info.php' ? 'gradient-text font-medium' : 'text-gray-300 dark:text-gray-300 hover:text-white dark:hover:text-white' ?>">
                        информация
                    </a>
                </li>
                <li>
                    <a href="/contact.php" class="text-sm transition-all duration-300 <?= $currentPage === 'contact.php' ? 'gradient-text font-medium' : 'text-gray-300 dark:text-gray-300 hover:text-white dark:hover:text-white' ?>">
                        контакты
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</header>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Анимация появления шапки
        setTimeout(() => {
            const header = document.querySelector("header");
            header.classList.remove("opacity-0");
        }, 50);
    });
</script> 