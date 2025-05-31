<?php
/**
 * Обработка загрузки файлов
 */

// Определение доступа
define('SITE_ACCESS', true);

// Подключение конфигурации и функций
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Проверка метода запроса
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// Инициализация результата
$result = [
    'success' => false,
    'message' => '',
    'files' => []
];

// Проверка наличия файлов для загрузки
if (!isset($_FILES['files'])) {
    $result['message'] = 'файлы не выбраны';
} else {
    $files = reArrayFiles($_FILES['files']);
    
    // Проверка наличия файлов
    if (count($files) === 0) {
        $result['message'] = 'файлы не выбраны';
    } else {
        // Проверка общего размера файлов
        $totalSize = 0;
        foreach ($files as $file) {
            $totalSize += $file['size'];
        }
        
        if ($totalSize > MAX_FILE_SIZE) {
            $result['message'] = 'общий размер файлов слишком большой. максимальный размер: ' . (MAX_FILE_SIZE / 1024 / 1024) . ' мб';
        } else {
            $uploadedFiles = 0;
            $errors = [];
            
            foreach ($files as $file) {
                // Проверка ошибок загрузки
                if ($file['error'] !== UPLOAD_ERR_OK) {
                    $errors[] = 'ошибка загрузки файла "' . $file['name'] . '" (код: ' . $file['error'] . ')';
                    continue;
                }
                
                // Генерация уникального идентификатора
                $fileId = generateUniqueId();
                
                // Получение расширения файла
                $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                
                // Перемещение загруженного файла в хранилище
                $storagePath = UPLOAD_DIR . $fileId;
                if (move_uploaded_file($file['tmp_name'], $storagePath)) {
                    
                    // Сохранение метаданных файла
                    $fileInfo = [
                        'original_name' => $file['name'],
                        'mime_type' => $file['type'],
                        'size' => $file['size'],
                        'uploaded_at' => time(),
                        'extension' => $extension
                    ];
                    
                    // Сохранение информации о файле
                    file_put_contents(UPLOAD_DIR . $fileId . '.info', json_encode($fileInfo));
                    
                    $uploadedFiles++;
                    
                    // Формирование информации о загруженном файле
                    $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
                    $fileUrl = $protocol . '://' . $_SERVER['HTTP_HOST'] . '/' . $fileId;
                    
                    $result['files'][] = [
                        'id' => $fileId,
                        'original_name' => $file['name'],
                        'size' => formatFileSize($file['size']),
                        'link' => $fileUrl
                    ];
                } else {
                    $errors[] = 'не удалось сохранить файл "' . $file['name'] . '"';
                }
            }
            
            if ($uploadedFiles > 0) {
                $result['success'] = true;
                $result['message'] = $uploadedFiles . ' ' . declOfNum($uploadedFiles, ['файл', 'файла', 'файлов']) . ' успешно загружено!';
            } else {
                $result['message'] = 'ни один файл не был загружен';
                if (!empty($errors)) {
                    $result['message'] .= ': ' . implode(', ', $errors);
                }
            }
        }
    }
}

/**
 * Преобразует массив $_FILES для множественной загрузки
 *
 * @param array $filePost Массив $_FILES
 * @return array Преобразованный массив файлов
 */
function reArrayFiles($filePost) {
    $fileArray = [];
    $fileCount = count($filePost['name']);
    $fileKeys = array_keys($filePost);
    
    for ($i = 0; $i < $fileCount; $i++) {
        foreach ($fileKeys as $key) {
            $fileArray[$i][$key] = $filePost[$key][$i];
        }
    }
    
    return $fileArray;
}

/**
 * Склонение существительного в зависимости от числа
 *
 * @param int $number Число
 * @param array $words Массив слов [один, два, пять]
 * @return string Слово в нужном склонении
 */
function declOfNum($number, $words) {
    $cases = [2, 0, 1, 1, 1, 2];
    return $words[($number % 100 > 4 && $number % 100 < 20) ? 2 : $cases[min($number % 10, 5)]];
}

// Возвращаем JSON-ответ для AJAX-запросов
header('Content-Type: application/json');
echo json_encode($result);
exit; 