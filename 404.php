<?php
/**
 * Страница ошибки 404
 */

// Определение доступа
define('SITE_ACCESS', true);

// Подключение конфигурации и функций
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Устанавливаем HTTP-статус 404
http_response_code(404);

// Содержимое страницы
$content = '
<div class="apple-card p-8 text-center">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 dark:text-gray-500 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    <h1 class="text-2xl font-medium text-gray-100 dark:text-gray-100 mb-4">ошибка 404</h1>
    <p class="text-gray-400 dark:text-gray-400 mb-6">файл не найден или был удален</p>
    <a href="/" class="apple-button py-3 px-6 inline-block text-white font-medium">
        на главную
    </a>
</div>';

// Отображение страницы
displayTemplate('ошибка 404', $content); 