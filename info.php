<?php
/**
 * Страница с информацией о сервисе
 */

// Определение доступа
define('SITE_ACCESS', true);

// Подключение конфигурации и функций
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Содержимое страницы
$content = '
<div class="text-center mb-8">
    <h1 class="text-4xl font-medium gradient-text">информация о сервисе</h1>
    <p class="text-gray-400 dark:text-gray-400 mt-2">как работает файлообменник и правила использования</p>
</div>

<div class="space-y-8">
    <div class="glassmorphism p-6 rounded-xl shadow-xl animate-fade-in">
        <h2 class="text-xl font-medium gradient-text mb-4">как это работает</h2>
        <div class="text-gray-300 dark:text-gray-300 space-y-3">
            <p>наш файлообменник создан для быстрого обмена файлами без лишних сложностей:</p>
            <ol class="list-decimal pl-6 space-y-2">
                <li>выберите файлы для загрузки (можно несколько)</li>
                <li>файлы загрузятся автоматически</li>
                <li>получите уникальные короткие ссылки</li>
                <li>поделитесь ссылками с другими</li>
            </ol>
        </div>
    </div>
    
    <div class="glassmorphism p-6 rounded-xl shadow-xl animate-fade-in" style="animation-delay: 0.1s;">
        <h2 class="text-xl font-medium gradient-text mb-4">ограничения</h2>
        <div class="text-gray-300 dark:text-gray-300 space-y-3">
            <ul class="list-disc pl-6 space-y-2">
                <li>максимальный размер файла: <span class="text-apple-blue dark:text-apple-blue">150 мб</span></li>
                <li>поддерживаются любые типы файлов</li>
                <li>файлы хранятся ограниченное время</li>
                <li>автоматический предпросмотр для медиафайлов</li>
                <li>возможность просмотра списка всех загруженных файлов</li>
            </ul>
        </div>
    </div>
    
    <div class="glassmorphism p-6 rounded-xl shadow-xl animate-fade-in" style="animation-delay: 0.2s;">
        <h2 class="text-xl font-medium gradient-text mb-4">правила использования</h2>
        <div class="text-gray-300 dark:text-gray-300 space-y-3">
            <p>при использовании сервиса вы соглашаетесь соблюдать следующие правила:</p>
            <ul class="list-disc pl-6 space-y-2">
                <li>не загружать нелегальный контент</li>
                <li>не нарушать авторские права</li>
                <li>не размещать вредоносные файлы</li>
                <li>не использовать сервис для спама</li>
                <li>не злоупотреблять ресурсами сервиса</li>
            </ul>
        </div>
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
</script>';

// Отображение страницы
displayTemplate('информация', $content); 