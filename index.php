<?php
/**
 * Главная страница файлообменника
 */

// Определение доступа
define('SITE_ACCESS', true);

// Подключение конфигурации и функций
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Проверка результатов загрузки из сессии
$uploadResult = null;
if (isset($_SESSION['upload_result'])) {
    $uploadResult = $_SESSION['upload_result'];
    unset($_SESSION['upload_result']);
}

// Построение содержимого страницы
$content = '
<!-- Анимация появления контента -->
<div class="transform transition-all duration-500" id="mainContent">

    <div class="text-center mb-8">
        <h1 class="text-4xl font-medium gradient-text">' . SITE_NAME . '</h1>
        <p class="text-gray-400 dark:text-gray-400 mt-2">
            простой файлообменник без регистрации. просто загрузи — получи ссылку.
        </p>
    </div>

    <!-- Плавающая карточка с эффектом glassmorphism -->
    <div class="apple-card p-8 shadow-xl glassmorphism">
        <div id="uploadContainer" class="flex flex-col items-center justify-center">
            <!-- Область для загрузки файла с улучшенным hover-эффектом -->
            <div id="dropArea" 
                 class="w-full border-2 border-dashed border-gray-600 dark:border-gray-600 rounded-xl p-10 mb-6
                        transition-all duration-300 transform hover:scale-[1.02]
                        hover:border-apple-blue dark:hover:border-apple-blue
                        hover:bg-[#0a84ff15] dark:hover:bg-[#0a84ff15] cursor-pointer">
                
                <!-- Современная иконка облака для загрузки -->
                <div class="text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4 text-gray-400 dark:text-gray-400 
                            transition-all duration-500 group-hover:text-apple-blue" 
                         viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                              d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    
                    <label for="fileInput" class="cursor-pointer block">
                        <span class="block text-white dark:text-white font-medium mb-2 text-lg">
                            выберите файлы для загрузки
                        </span>
                        <span class="text-gray-400 dark:text-gray-400">
                            перетащите файлы сюда или выберите с устройства
                        </span>
                        <p class="mt-3 text-sm text-gray-500">
                            (можно выбрать несколько файлов)
                        </p>
                    </label>
                    <input type="file" id="fileInput" name="files[]" class="hidden" multiple>
                </div>
            </div>
            
            <!-- Информация о файлах с улучшенным дизайном -->
            <div id="filesInfo" class="w-full mb-6 hidden">
                <div id="filesContainer" class="space-y-4"></div>
            </div>
            
            <!-- Индикатор загрузки с пульсацией -->
            <div id="uploadingText" class="text-center text-gray-400 dark:text-gray-400 hidden">
                <div class="flex items-center justify-center">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-apple-blue dark:text-apple-blue" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p>загрузка файлов</p>
                </div>
            </div>
        </div>
        
        <!-- Сообщение об успешной загрузке с улучшенным дизайном -->
        <div id="successMessage" class="' . ($uploadResult && $uploadResult['success'] ? '' : 'hidden') . ' mt-2 text-center">
            <div class="glassmorphism bg-green-900/30 dark:bg-green-900/30 rounded-xl p-4 mb-4 animate-fade-in">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-400 dark:text-green-400 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <p class="text-green-400 dark:text-green-400">файл успешно загружен!</p>
            </div>
            
            <p class="text-gray-300 dark:text-gray-300 mb-3">поделитесь этой ссылкой:</p>
            <div class="flex">
                <input id="shareLink" type="text" class="apple-input py-3 px-4 w-full mr-2 text-white dark:text-white rounded-xl" value="' . ($uploadResult && $uploadResult['success'] ? $uploadResult['link'] : '') . '" readonly>
                <button id="copyButton" class="apple-button py-3 px-4 rounded-xl font-medium flex items-center" onclick="copyToClipboard()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M8 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" />
                        <path d="M6 3a2 2 0 00-2 2v11a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2 3 3 0 01-3 3H9a3 3 0 01-3-3z" />
                    </svg>
                    копировать
                </button>
            </div>
            
            <div class="mt-6">
                <button id="uploadAnother" class="apple-button py-2.5 px-6 rounded-xl text-sm font-medium transition-all duration-300 hover:bg-opacity-90">
                    загрузить другие файлы
                </button>
            </div>
        </div>
        
        <!-- Контейнер для отображения результатов множественной загрузки -->
        <div id="multiUploadResults" class="hidden mt-6 glassmorphism p-4 rounded-xl space-y-4">
            <h3 class="text-xl font-medium text-center gradient-text mb-2">Загруженные файлы</h3>
            <div id="uploadedFilesList" class="space-y-3"></div>
        </div>
        
        <!-- Сообщение об ошибке с улучшенным дизайном -->
        <div id="errorMessage" class="' . ($uploadResult && !$uploadResult['success'] && $uploadResult['message'] ? '' : 'hidden') . ' mt-6 glassmorphism bg-red-900/30 backdrop-blur-sm text-red-400 dark:text-red-400 p-4 rounded-xl text-center">
            ' . ($uploadResult && !$uploadResult['success'] ? e($uploadResult['message']) : 'ошибка') . '
            
            <div class="mt-4">
                <button id="tryAgain" class="text-apple-blue dark:text-apple-blue underline text-sm">
                    попробовать снова
                </button>
            </div>
        </div>
    </div>

    <div class="mt-8 text-center text-sm text-gray-500 dark:text-gray-500">
        <p>файлы хранятся временно и могут быть удалены после периода неактивности</p>
        <p class="mt-1 text-xs text-gray-600 dark:text-gray-600">максимальный размер файла: 150 мб</p>
        <a href="/files" class="inline-block mt-3 px-4 py-2 bg-opacity-20 hover:bg-opacity-30 bg-apple-blue rounded-lg text-sm text-apple-blue transition-all duration-300">
            просмотреть все файлы
        </a>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const dropArea = document.getElementById("dropArea");
        const fileInput = document.getElementById("fileInput");
        const filesInfo = document.getElementById("filesInfo");
        const filesContainer = document.getElementById("filesContainer");
        const uploadContainer = document.getElementById("uploadContainer");
        const uploadingText = document.getElementById("uploadingText");
        const successMessage = document.getElementById("successMessage");
        const errorMessage = document.getElementById("errorMessage");
        const shareLink = document.getElementById("shareLink");
        const uploadAnother = document.getElementById("uploadAnother");
        const tryAgain = document.getElementById("tryAgain");
        const mainContent = document.getElementById("mainContent");
        const multiUploadResults = document.getElementById("multiUploadResults");
        const uploadedFilesList = document.getElementById("uploadedFilesList");
        
        // Анимация появления контента
        setTimeout(() => {
            mainContent.classList.add("animate-fade-in");
        }, 100);
        
        // Обработка выбора файлов - автоматически начинает загрузку
        fileInput.addEventListener("change", function() {
            if (fileInput.files.length > 0) {
                handleFilesSelect();
            }
        });
        
        // Обработка "загрузить другие файлы"
        uploadAnother.addEventListener("click", function() {
            successMessage.classList.add("hidden");
            multiUploadResults.classList.add("hidden");
            uploadContainer.classList.remove("hidden");
            filesInfo.classList.add("hidden");
            fileInput.value = "";
            filesContainer.innerHTML = "";
        });
        
        // Обработка "попробовать снова"
        if (tryAgain) {
            tryAgain.addEventListener("click", function() {
                errorMessage.classList.add("hidden");
                uploadContainer.classList.remove("hidden");
                filesInfo.classList.add("hidden");
                fileInput.value = "";
                filesContainer.innerHTML = "";
            });
        }
        
        // Обработка перетаскивания файлов
        ["dragenter", "dragover", "dragleave", "drop"].forEach(eventName => {
            dropArea.addEventListener(eventName, preventDefaults, false);
        });
        
        ["dragenter", "dragover"].forEach(eventName => {
            dropArea.addEventListener(eventName, highlight, false);
        });
        
        ["dragleave", "drop"].forEach(eventName => {
            dropArea.addEventListener(eventName, unhighlight, false);
        });
        
        dropArea.addEventListener("drop", handleDrop, false);
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        function highlight() {
            dropArea.classList.add("border-apple-blue", "bg-[#0a84ff15]");
            dropArea.classList.add("scale-[1.02]");
        }
        
        function unhighlight() {
            dropArea.classList.remove("border-apple-blue", "bg-[#0a84ff15]");
            dropArea.classList.remove("scale-[1.02]");
        }
        
        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            if (files.length > 0) {
                fileInput.files = files;
                handleFilesSelect();
            }
        }
        
        function handleFilesSelect() {
            const files = fileInput.files;
            
            // Проверка общего размера файлов
            let totalSize = 0;
            for (let i = 0; i < files.length; i++) {
                totalSize += files[i].size;
            }
            
            // Если общий размер превышает лимит
            if (totalSize > ' . MAX_FILE_SIZE . ') {
                showError("Общий размер файлов превышает допустимый лимит (150 МБ)");
                return;
            }
            
            // Отображение информации о файлах
            filesContainer.innerHTML = "";
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const fileItem = document.createElement("div");
                fileItem.className = "flex items-center justify-between p-3 glassmorphism rounded-lg animate-slide-up";
                fileItem.style.animationDelay = (i * 0.1) + "s";
                
                const fileInfo = document.createElement("div");
                fileInfo.className = "flex-1 mr-4";
                
                const fileName = document.createElement("div");
                fileName.className = "text-white dark:text-white truncate";
                fileName.textContent = file.name;
                
                const fileSizeElement = document.createElement("div");
                fileSizeElement.className = "text-sm text-gray-400 dark:text-gray-400";
                fileSizeElement.textContent = formatFileSize(file.size);
                
                fileInfo.appendChild(fileName);
                fileInfo.appendChild(fileSizeElement);
                
                const progressContainer = document.createElement("div");
                progressContainer.className = "w-20 h-1.5 bg-gray-700 dark:bg-gray-700 rounded-full overflow-hidden";
                
                const progressBar = document.createElement("div");
                progressBar.className = "progress-bar rounded-full";
                progressBar.id = "progress-" + i;
                
                progressContainer.appendChild(progressBar);
                
                fileItem.appendChild(fileInfo);
                fileItem.appendChild(progressContainer);
                
                filesContainer.appendChild(fileItem);
            }
            
            filesInfo.classList.remove("hidden");
            
            // Начать загрузку файлов
            uploadFiles();
        }
        
        function uploadFiles() {
            const files = fileInput.files;
            const formData = new FormData();
            
            for (let i = 0; i < files.length; i++) {
                formData.append("files[]", files[i]);
            }
            
            // Отображение индикатора загрузки
            uploadingText.classList.remove("hidden");
            
            // AJAX-запрос для загрузки файлов
            const xhr = new XMLHttpRequest();
            
            // Отслеживание прогресса загрузки
            xhr.upload.addEventListener("progress", function(e) {
                if (e.lengthComputable) {
                    const percentComplete = (e.loaded / e.total) * 100;
                    
                    // Обновление прогресс-баров для всех файлов
                    for (let i = 0; i < files.length; i++) {
                        const progressBar = document.getElementById("progress-" + i);
                        if (progressBar) {
                            progressBar.style.width = percentComplete + "%";
                        }
                    }
                }
            });
            
            xhr.open("POST", "upload.php", true);
            
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    uploadingText.classList.add("hidden");
                    
                    if (xhr.status === 200) {
                        try {
                            const response = JSON.parse(xhr.responseText);
                            
                            if (response.success) {
                                // Если загружен один файл
                                if (response.files && response.files.length === 1) {
                                    shareLink.value = response.files[0].link;
                                    successMessage.classList.remove("hidden");
                                    uploadContainer.classList.add("hidden");
                                } 
                                // Если загружено несколько файлов
                                else if (response.files && response.files.length > 1) {
                                    displayMultipleUploads(response.files);
                                }
                            } else {
                                showError(response.message || "Произошла ошибка при загрузке файлов");
                            }
                        } catch (e) {
                            showError("Ошибка при обработке ответа сервера");
                        }
                    } else {
                        showError("Ошибка сервера: " + xhr.status);
                    }
                }
            };
            
            xhr.send(formData);
        }
        
        function displayMultipleUploads(files) {
            uploadContainer.classList.add("hidden");
            uploadedFilesList.innerHTML = "";
            
            files.forEach((file, index) => {
                const fileItem = document.createElement("div");
                fileItem.className = "flex items-center justify-between p-3 glassmorphism rounded-lg animate-slide-up";
                fileItem.style.animationDelay = (index * 0.1) + "s";
                
                const fileInfo = document.createElement("div");
                fileInfo.className = "flex-1 mr-4";
                
                const fileName = document.createElement("div");
                fileName.className = "text-white dark:text-white truncate";
                fileName.textContent = file.original_name;
                
                const fileSizeElement = document.createElement("div");
                fileSizeElement.className = "text-sm text-gray-400 dark:text-gray-400";
                fileSizeElement.textContent = file.size;
                
                fileInfo.appendChild(fileName);
                fileInfo.appendChild(fileSizeElement);
                
                const linkContainer = document.createElement("div");
                linkContainer.className = "flex";
                
                const linkInput = document.createElement("input");
                linkInput.type = "text";
                linkInput.className = "apple-input py-2 px-3 w-60 text-sm text-white rounded-l-xl";
                linkInput.value = file.link;
                linkInput.readOnly = true;
                
                const copyBtn = document.createElement("button");
                copyBtn.className = "apple-button py-2 px-3 rounded-r-xl text-sm font-medium flex items-center";
                copyBtn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M8 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" />
                    <path d="M6 3a2 2 0 00-2 2v11a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2 3 3 0 01-3 3H9a3 3 0 01-3-3z" />
                </svg>`;
                
                copyBtn.addEventListener("click", function() {
                    linkInput.select();
                    document.execCommand("copy");
                    
                    // Анимация копирования
                    const originalBg = copyBtn.style.backgroundColor;
                    copyBtn.style.backgroundColor = "#10b981";
                    setTimeout(() => {
                        copyBtn.style.backgroundColor = originalBg;
                    }, 500);
                });
                
                linkContainer.appendChild(linkInput);
                linkContainer.appendChild(copyBtn);
                
                fileItem.appendChild(fileInfo);
                fileItem.appendChild(linkContainer);
                
                uploadedFilesList.appendChild(fileItem);
            });
            
            multiUploadResults.classList.remove("hidden");
        }
        
        function showError(message) {
            const errorMessageText = errorMessage.querySelector("div:not(.mt-4)") || errorMessage;
            errorMessageText.textContent = message;
            
            errorMessage.classList.remove("hidden");
            uploadContainer.classList.add("hidden");
        }
        
        function formatFileSize(bytes) {
            if (bytes === 0) return "0 байт";
            
            const sizes = ["байт", "КБ", "МБ", "ГБ"];
            const i = Math.floor(Math.log(bytes) / Math.log(1024));
            
            return parseFloat((bytes / Math.pow(1024, i)).toFixed(2)) + " " + sizes[i];
        }
        
        function copyToClipboard() {
            shareLink.select();
            document.execCommand("copy");
            
            // Анимация копирования
            const copyButton = document.getElementById("copyButton");
            const originalBg = copyButton.style.backgroundColor;
            copyButton.style.backgroundColor = "#10b981";
            copyButton.textContent = "скопировано!";
            
            setTimeout(() => {
                copyButton.style.backgroundColor = originalBg;
                copyButton.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M8 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" />
                    <path d="M6 3a2 2 0 00-2 2v11a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2 3 3 0 01-3 3H9a3 3 0 01-3-3z" />
                </svg>копировать`;
            }, 1000);
        }
    });
</script>';

// Отображение страницы
displayTemplate('загрузка файла', $content); 