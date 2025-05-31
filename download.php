<?php
/**
 * Скачивание и предпросмотр файла
 */

// Определение доступа
define('SITE_ACCESS', true);

// Подключение конфигурации и функций
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Проверка наличия идентификатора файла
if (!isset($_GET['id']) || empty($_GET['id'])) {
    // Перенаправление на страницу с ошибкой 404
    header('Location: 404.php');
    exit;
}

// Получение идентификатора файла
$fileId = $_GET['id'];

// Проверка формата ID файла
if (!preg_match('/^[a-z]{' . LINK_LENGTH . '}$/', $fileId)) {
    // Перенаправление на страницу с ошибкой 404
    header('Location: 404.php');
    exit;
}

// Определение путей к файлу
$filePath = UPLOAD_DIR . $fileId;
$fileInfoPath = UPLOAD_DIR . $fileId . '.info';

// Проверка существования файла
if (!file_exists($filePath) || !file_exists($fileInfoPath)) {
    // Перенаправление на страницу с ошибкой 404
    header('Location: 404.php');
    exit;
}

// Чтение информации о файле
$fileInfo = json_decode(file_get_contents($fileInfoPath), true);

// Если не удалось прочитать информацию о файле или информация повреждена
if ($fileInfo === null || !isset($fileInfo['original_name']) || !isset($fileInfo['mime_type'])) {
    $fileInfo = [
        'original_name' => 'файл',
        'mime_type' => 'application/octet-stream',
        'size' => filesize($filePath)
    ];
}

// Проверка физического наличия файла и его размера
if (!is_readable($filePath) || filesize($filePath) === 0) {
    // Перенаправление на страницу с ошибкой 404
    header('Location: 404.php');
    exit;
}

// Определение типа контента для предпросмотра
$contentType = $fileInfo['mime_type'];
$isImage = strpos($contentType, 'image/') === 0;
$isVideo = strpos($contentType, 'video/') === 0;
$isAudio = strpos($contentType, 'audio/') === 0;
$canPreview = $isImage || $isVideo || $isAudio;

// Форматирование размера файла
$formattedSize = formatFileSize($fileInfo['size']);

// Если запрошено прямое скачивание
if (isset($_GET['download'])) {
    // Устанавливаем заголовки для скачивания файла
    header('Content-Description: File Transfer');
    header('Content-Type: ' . $fileInfo['mime_type']);
    header('Content-Disposition: attachment; filename="' . $fileInfo['original_name'] . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($filePath));
    
    // Отправка файла клиенту
    readfile($filePath);
    exit;
}

// Если файл можно предварительно просмотреть
$previewUrl = $canPreview ? '/' . UPLOAD_DIR . $fileId : '';

// Подготовка контента для страницы просмотра файла
$content = '
<div class="text-center mb-8">
    <h1 class="text-4xl font-medium gradient-text">просмотр файла</h1>
    <p class="text-gray-400 dark:text-gray-400 mt-2">информация и скачивание</p>
</div>

<div class="glassmorphism p-6 rounded-xl shadow-xl animate-fade-in">
    <div class="mb-6 space-y-4">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            <h2 class="text-xl font-medium text-white dark:text-white break-all pr-4">' . e($fileInfo['original_name']) . '</h2>
            <a href="?id=' . $fileId . '&download=1" class="apple-button py-2.5 px-5 text-sm font-medium flex items-center justify-center md:justify-start transition-all duration-300 hover:shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
                скачать файл
            </a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div class="glassmorphism bg-opacity-30 p-3 rounded-lg">
                <p class="text-gray-400 dark:text-gray-400 mb-1">тип файла:</p>
                <p class="text-white dark:text-white font-medium">' . e($fileInfo['mime_type']) . '</p>
            </div>
            <div class="glassmorphism bg-opacity-30 p-3 rounded-lg">
                <p class="text-gray-400 dark:text-gray-400 mb-1">размер:</p>
                <p class="text-white dark:text-white font-medium">' . e($formattedSize) . '</p>
            </div>
        </div>
    </div>
    
    ' . ($canPreview ? '<hr class="border-gray-700 dark:border-gray-700 opacity-30 my-6">' : '') . '
    
    ' . ($canPreview ? '<div class="preview-container animate-fade-in opacity-0" style="animation-delay: 0.3s;">' : '') . '
    
    ' . ($isImage ? '
        <div class="flex justify-center">
            <img src="' . $previewUrl . '" alt="' . e($fileInfo['original_name']) . '" class="max-w-full rounded-lg shadow-lg transform transition-all duration-500 hover:scale-[1.02]">
        </div>
    ' : '') . '
    
    ' . ($isVideo ? '
        <div class="flex justify-center">
            <video controls class="max-w-full rounded-lg shadow-lg">
                <source src="' . $previewUrl . '" type="' . e($fileInfo['mime_type']) . '">
                ваш браузер не поддерживает этот видеоформат
            </video>
        </div>
    ' : '') . '
    
    ' . ($isAudio ? '
        <div class="flex justify-center">
            <audio controls class="w-full bg-[#2c2c2e] p-3 rounded-lg">
                <source src="' . $previewUrl . '" type="' . e($fileInfo['mime_type']) . '">
                ваш браузер не поддерживает этот аудиоформат
            </audio>
        </div>
    ' : '') . '
    
    ' . ($canPreview ? '</div>' : '') . '
</div>

' . (!$canPreview ? '
<div class="mt-8 text-center animate-fade-in opacity-0" style="animation-delay: 0.2s;">
    <div class="glassmorphism p-6 rounded-xl mb-6">
        <div class="text-4xl mb-4">📦</div>
        <p class="text-gray-400 dark:text-gray-400 mb-6">для этого типа файлов предпросмотр недоступен</p>
        <a href="?id=' . $fileId . '&download=1" class="apple-button py-3 px-6 text-white font-medium inline-flex items-center transition-all duration-300 hover:shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
            скачать файл
        </a>
    </div>
</div>
' : '') . '

<div class="mt-8 text-center">
    <a href="/files" class="inline-block px-5 py-2.5 bg-opacity-20 hover:bg-opacity-30 bg-apple-blue rounded-lg text-sm text-apple-blue transition-all duration-300">
        вернуться к списку файлов
    </a>
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
displayTemplate('просмотр файла', $content); 