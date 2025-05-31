<?php
/**
 * Подвал сайта
 * 
 * Содержит копирайт и дополнительную навигацию
 */

// Предотвращение прямого доступа к файлу
if (!defined('SITE_ACCESS')) {
    exit('Прямой доступ к файлу запрещен!');
}
?>

<footer class="mt-12 pt-6 border-t border-gray-700/30 dark:border-gray-700/30 animate-fade-in opacity-0">
    <div class="flex flex-col sm:flex-row items-center justify-between">
        <!-- Копирайт -->
        <div class="mb-4 sm:mb-0">
            <div class="flex items-center">
                <div class="bg-gradient-to-r from-apple-blue to-apple-blue-light p-1.5 rounded-lg mr-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-500">
                    <span class="text-gray-400"><?= SITE_NAME ?></span> &copy; <?= date('Y') ?> • простой файлообменник
                </p>
            </div>
        </div>
        
        <!-- Дополнительная навигация -->
        <nav class="glassmorphism py-1.5 px-4 rounded-full">
            <ul class="flex space-x-5">
                <li>
                    <a href="/" class="text-xs text-gray-400 dark:text-gray-400 hover:text-white dark:hover:text-white transition-all duration-300">
                        главная
                    </a>
                </li>
                <li>
                    <a href="/files" class="text-xs text-gray-400 dark:text-gray-400 hover:text-white dark:hover:text-white transition-all duration-300">
                        файлы
                    </a>
                </li>
                <li>
                    <a href="/info.php" class="text-xs text-gray-400 dark:text-gray-400 hover:text-white dark:hover:text-white transition-all duration-300">
                        информация
                    </a>
                </li>
                <li>
                    <a href="/contact.php" class="text-xs text-gray-400 dark:text-gray-400 hover:text-white dark:hover:text-white transition-all duration-300">
                        контакты
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    
    <div class="text-center mt-6">
        <p class="text-xs text-gray-600 dark:text-gray-600">
            максимальный размер файла: 150 мб • файлы хранятся временно
        </p>
    </div>
</footer>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Анимация появления подвала
        setTimeout(() => {
            const footer = document.querySelector("footer");
            footer.classList.remove("opacity-0");
        }, 300);
    });
</script> 