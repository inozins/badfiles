<?php
/**
 * Страница с контактами и формой обратной связи
 */

// Определение доступа
define('SITE_ACCESS', true);

// Подключение конфигурации и функций
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Содержимое страницы
$content = '
<div class="text-center mb-8">
    <h1 class="text-4xl font-medium gradient-text">контакты</h1>
    <p class="text-gray-400 dark:text-gray-400 mt-2">свяжитесь с нами или оставьте сообщение</p>
</div>

<!-- Временное сообщение -->
<div class="glassmorphism p-8 mb-8 text-center animate-fade-in opacity-0">
    <div class="text-7xl mb-4">👋</div>
    <p class="text-2xl text-apple-blue dark:text-apple-blue font-medium mb-2">скоро появятся...</p>
    <p class="text-white dark:text-white">а пока: окак</p>
    
    <div class="mt-8">
        <a href="/" class="apple-button inline-block py-3 px-6 rounded-xl font-medium transition-all duration-300">
            вернуться на главную
        </a>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Анимация появления контента
        setTimeout(() => {
            const fadeElements = document.querySelectorAll(".animate-fade-in");
            fadeElements.forEach(el => el.classList.remove("opacity-0"));
        }, 100);
    });
</script>
';

// Отображение страницы
displayTemplate('контакты', $content); 