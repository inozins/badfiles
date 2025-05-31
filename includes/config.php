<?php
/**
 * Файл конфигурации
 * 
 * Содержит основные настройки файлообменника
 */

// Предотвращение прямого доступа к файлу
if (!defined('SITE_ACCESS')) {
    exit('Прямой доступ к файлу запрещен!');
}

// Основные настройки сайта
define('SITE_NAME', 'badfiles');
define('UPLOAD_DIR', 'uploads/');
define('MAX_FILE_SIZE', 4096 * 1024 * 1024); // 150 МБ
define('LINK_LENGTH', 5);

// Создание директории для загрузок, если она не существует
if (!is_dir(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0755, true);
}

// Настройки для сессий
ini_set('session.cookie_httponly', 1);
session_start(); 