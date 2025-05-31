<?php
/**
 * Вспомогательные функции
 * 
 * Общие функции для работы файлообменника
 */

// Предотвращение прямого доступа к файлу
if (!defined('SITE_ACCESS')) {
    exit('Прямой доступ к файлу запрещен!');
}

/**
 * Генерация уникального идентификатора для файла
 *
 * @param int $length Длина идентификатора
 * @return string Уникальный идентификатор
 */
function generateUniqueId($length = LINK_LENGTH) {
    $characters = 'abcdefghijklmnopqrstuvwxyz';
    $id = '';
    
    // Генерация случайной строки
    for ($i = 0; $i < $length; $i++) {
        $id .= $characters[rand(0, strlen($characters) - 1)];
    }
    
    // Проверка существования идентификатора
    if (file_exists(UPLOAD_DIR . $id . '.info')) {
        return generateUniqueId($length); // Рекурсивный вызов для повторной генерации
    }
    
    return $id;
}

/**
 * Форматирование размера файла в читаемом виде
 *
 * @param int $bytes Размер в байтах
 * @return string Отформатированный размер
 */
function formatFileSize($bytes) {
    if ($bytes === 0) {
        return '0 байт';
    }
    
    $units = ['байт', 'кб', 'мб', 'гб'];
    $factor = floor((strlen($bytes) - 1) / 3);
    
    return sprintf("%.2f %s", $bytes / pow(1024, $factor), $units[$factor]);
}

/**
 * Получение информации о файле по идентификатору
 *
 * @param string $fileId Идентификатор файла
 * @return array|false Информация о файле или false если файл не найден
 */
function getFileInfo($fileId) {
    $filePath = UPLOAD_DIR . $fileId;
    $fileInfoPath = UPLOAD_DIR . $fileId . '.info';
    
    // Проверка существования файла
    if (file_exists($filePath) && file_exists($fileInfoPath)) {
        $fileInfo = json_decode(file_get_contents($fileInfoPath), true);
        
        // Если не удалось прочитать информацию о файле
        if ($fileInfo === null) {
            $fileInfo = [
                'original_name' => 'файл',
                'mime_type' => 'application/octet-stream'
            ];
        }
        
        return $fileInfo;
    }
    
    return false;
}

/**
 * Безопасный вывод с экранированием спецсимволов
 *
 * @param string $str Строка для вывода
 * @return string Экранированная строка
 */
function e($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

/**
 * Отображение шаблона страницы
 *
 * @param string $title Заголовок страницы
 * @param string $content HTML-содержимое страницы
 */
function displayTemplate($title, $content) {
    include 'template.php';
} 