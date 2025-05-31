<?php
/**
 * Страница со списком всех загруженных файлов
 */

// Определение доступа
define('SITE_ACCESS', true);

// Подключение конфигурации и функций
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Получение списка всех файлов
$filesList = [];
$filesDir = scandir(UPLOAD_DIR);

foreach ($filesDir as $file) {
    // Пропускаем служебные файлы и директории
    if ($file === '.' || $file === '..' || strpos($file, '.info') !== false) {
        continue;
    }
    
    // Путь к информационному файлу
    $infoFile = UPLOAD_DIR . $file . '.info';
    
    if (file_exists($infoFile)) {
        // Чтение информации о файле
        $fileInfo = json_decode(file_get_contents($infoFile), true);
        
        if ($fileInfo) {
            // Добавление ID файла к информации
            $fileInfo['id'] = $file;
            
            // Форматирование размера файла
            $fileInfo['formatted_size'] = formatFileSize($fileInfo['size']);
            
            // Форматирование даты загрузки
            $fileInfo['formatted_date'] = date('d.m.Y H:i', $fileInfo['uploaded_at']);
            
            // Определение типа файла для иконки
            $fileInfo['type_icon'] = getFileTypeIcon($fileInfo['mime_type'], $fileInfo['extension']);
            
            // Добавление в массив файлов
            $filesList[] = $fileInfo;
        }
    }
}

// Сортировка файлов по дате загрузки (по умолчанию)
usort($filesList, function($a, $b) {
    return $b['uploaded_at'] - $a['uploaded_at'];
});

// Содержимое страницы
$content = '
<!-- Скрипт для сортировки и фильтрации -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const filesContainer = document.getElementById("filesContainer");
    const sortSelect = document.getElementById("sortSelect");
    const searchInput = document.getElementById("searchInput");
    let filesList = ' . json_encode($filesList) . ';
    
    // Обработчик изменения сортировки
    sortSelect.addEventListener("change", function() {
        updateFilesList();
    });
    
    // Обработчик поиска
    searchInput.addEventListener("input", function() {
        updateFilesList();
    });
    
    // Обновление списка файлов с учетом сортировки и фильтрации
    function updateFilesList() {
        const sortValue = sortSelect.value;
        const searchValue = searchInput.value.toLowerCase();
        
        // Фильтрация по поисковому запросу
        let filteredFiles = filesList.filter(file => {
            return file.original_name.toLowerCase().includes(searchValue);
        });
        
        // Сортировка
        filteredFiles.sort((a, b) => {
            if (sortValue === "date-desc") {
                return b.uploaded_at - a.uploaded_at;
            } else if (sortValue === "date-asc") {
                return a.uploaded_at - b.uploaded_at;
            } else if (sortValue === "name-asc") {
                return a.original_name.localeCompare(b.original_name);
            } else if (sortValue === "name-desc") {
                return b.original_name.localeCompare(a.original_name);
            } else if (sortValue === "size-asc") {
                return a.size - b.size;
            } else if (sortValue === "size-desc") {
                return b.size - a.size;
            }
            return 0;
        });
        
        // Рендеринг списка файлов
        renderFiles(filteredFiles);
    }
    
    // Отрисовка списка файлов
    function renderFiles(files) {
        filesContainer.innerHTML = "";
        
        if (files.length === 0) {
            filesContainer.innerHTML = `
                <div class="text-center p-8">
                    <p class="text-gray-400">Нет файлов для отображения</p>
                </div>
            `;
            return;
        }
        
        files.forEach(file => {
            const fileItem = document.createElement("div");
            fileItem.className = "glassmorphism p-4 rounded-xl flex flex-col md:flex-row items-start md:items-center justify-between transition-all duration-300 hover:transform hover:scale-[1.01] hover:shadow-lg animate-fade-in";
            
            // Создание левой части с иконкой и информацией
            const fileInfo = document.createElement("div");
            fileInfo.className = "flex items-center mb-3 md:mb-0";
            
            const fileIcon = document.createElement("div");
            fileIcon.className = "text-3xl mr-4";
            fileIcon.innerHTML = file.type_icon;
            
            const fileDetails = document.createElement("div");
            fileDetails.className = "flex-1";
            
            const fileName = document.createElement("div");
            fileName.className = "text-white font-medium truncate max-w-xs";
            fileName.textContent = file.original_name;
            
            const fileMetadata = document.createElement("div");
            fileMetadata.className = "text-sm text-gray-400 flex flex-wrap";
            fileMetadata.innerHTML = `
                <span class="mr-3">${file.formatted_size}</span>
                <span>${file.formatted_date}</span>
            `;
            
            fileDetails.appendChild(fileName);
            fileDetails.appendChild(fileMetadata);
            
            fileInfo.appendChild(fileIcon);
            fileInfo.appendChild(fileDetails);
            
            // Создание правой части с кнопками
            const fileActions = document.createElement("div");
            fileActions.className = "flex items-center space-x-2 w-full md:w-auto justify-end mt-3 md:mt-0";
            
            const downloadButton = document.createElement("a");
            downloadButton.href = "/${file.id}";
            downloadButton.className = "apple-button py-2 px-4 rounded-xl text-sm font-medium inline-flex items-center";
            downloadButton.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
                Скачать
            `;
            
            const copyButton = document.createElement("button");
            copyButton.className = "bg-gray-700 hover:bg-gray-600 py-2 px-4 rounded-xl text-sm font-medium inline-flex items-center transition-all duration-300";
            copyButton.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M8 2a1 1 0 000 2h2a1 1 0 100-2H8z" />
                    <path d="M3 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v6h-4.586l1.293-1.293a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L10.414 13H15v3a2 2 0 01-2 2H5a2 2 0 01-2-2V5zM15 11h2a1 1 0 110 2h-2v-2z" />
                </svg>
                Копировать ссылку
            `;
            
            copyButton.addEventListener("click", function() {
                const url = window.location.origin + "/" + file.id;
                navigator.clipboard.writeText(url).then(() => {
                    copyButton.textContent = "Скопировано!";
                    copyButton.style.backgroundColor = "#10b981";
                    
                    setTimeout(() => {
                        copyButton.innerHTML = `
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M8 2a1 1 0 000 2h2a1 1 0 100-2H8z" />
                                <path d="M3 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v6h-4.586l1.293-1.293a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L10.414 13H15v3a2 2 0 01-2 2H5a2 2 0 01-2-2V5zM15 11h2a1 1 0 110 2h-2v-2z" />
                            </svg>
                            Копировать ссылку
                        `;
                        copyButton.style.backgroundColor = "";
                    }, 1000);
                });
            });
            
            fileActions.appendChild(downloadButton);
            fileActions.appendChild(copyButton);
            
            fileItem.appendChild(fileInfo);
            fileItem.appendChild(fileActions);
            
            filesContainer.appendChild(fileItem);
        });
    }
    
    // Первоначальная отрисовка
    renderFiles(filesList);
});
</script>

<div class="text-center mb-8">
    <h1 class="text-4xl font-medium gradient-text">все файлы</h1>
    <p class="text-gray-400 dark:text-gray-400 mt-2">
        список всех загруженных файлов
    </p>
</div>

<!-- Фильтры и поиск в стиле iOS -->
<div class="glassmorphism p-4 rounded-xl mb-6">
    <div class="flex flex-col md:flex-row space-y-3 md:space-y-0 md:space-x-4">
        <div class="flex-1">
            <div class="relative">
                <input id="searchInput" type="text" placeholder="Поиск файлов..." class="apple-input py-2 pl-10 pr-4 w-full text-white rounded-xl">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 absolute top-2.5 left-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>
        <div>
            <select id="sortSelect" class="apple-input py-2 px-4 text-white rounded-xl appearance-none bg-[#1c1c1e] pr-8 relative">
                <option value="date-desc">Новые сначала</option>
                <option value="date-asc">Старые сначала</option>
                <option value="name-asc">По имени (А-Я)</option>
                <option value="name-desc">По имени (Я-А)</option>
                <option value="size-desc">По размеру (макс-мин)</option>
                <option value="size-asc">По размеру (мин-макс)</option>
            </select>
        </div>
    </div>
</div>

<!-- Контейнер для списка файлов -->
<div id="filesContainer" class="space-y-4 mb-8">
    <!-- Файлы будут добавлены через JavaScript -->
</div>

<div class="text-center">
    <a href="/" class="apple-button inline-block py-3 px-6 rounded-xl font-medium transition-all duration-300">
        Загрузить новые файлы
    </a>
</div>
';

/**
 * Возвращает HTML-код иконки в зависимости от типа файла
 * 
 * @param string $mimeType MIME-тип файла
 * @param string $extension Расширение файла
 * @return string HTML-код иконки
 */
function getFileTypeIcon($mimeType, $extension) {
    // По умолчанию - иконка документа
    $icon = '<span class="text-gray-400">📄</span>';
    
    // Определение типа файла
    if (strpos($mimeType, 'image/') === 0) {
        $icon = '<span class="text-green-400">🖼️</span>';
    } else if (strpos($mimeType, 'video/') === 0) {
        $icon = '<span class="text-purple-400">🎬</span>';
    } else if (strpos($mimeType, 'audio/') === 0) {
        $icon = '<span class="text-pink-400">🎵</span>';
    } else if (strpos($mimeType, 'text/') === 0) {
        $icon = '<span class="text-blue-400">📝</span>';
    } else if (strpos($mimeType, 'application/pdf') === 0) {
        $icon = '<span class="text-red-400">📕</span>';
    } else if (strpos($mimeType, 'application/zip') === 0 || $extension === 'zip' || $extension === 'rar' || $extension === '7z') {
        $icon = '<span class="text-yellow-400">🗜️</span>';
    } else if (strpos($mimeType, 'application/msword') === 0 || strpos($mimeType, 'application/vnd.openxmlformats-officedocument.wordprocessingml') === 0) {
        $icon = '<span class="text-blue-400">📘</span>';
    } else if (strpos($mimeType, 'application/vnd.ms-excel') === 0 || strpos($mimeType, 'application/vnd.openxmlformats-officedocument.spreadsheetml') === 0) {
        $icon = '<span class="text-green-400">📗</span>';
    } else if (strpos($mimeType, 'application/vnd.ms-powerpoint') === 0 || strpos($mimeType, 'application/vnd.openxmlformats-officedocument.presentationml') === 0) {
        $icon = '<span class="text-orange-400">📙</span>';
    }
    
    return $icon;
}

// Отображение страницы
displayTemplate('все файлы', $content); 