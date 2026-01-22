<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Обратная связь</title>
    <link rel="stylesheet" href="{{ asset('css/CustomSite/CustomSite.css') }}">
</head>
<body>
<div class="widget-container">
    <div class="feedback-widget" id="feedbackWidget">
        <div class="widget-header">
            <h2>Обратная связь</h2>
            <p>Мы ответим вам в ближайшее время</p>
        </div>

        <div class="widget-body">
            <form id="feedbackForm">
                <div class="form-group">
                    <label class="form-label" for="name">
                        Ваше имя
                        <span class="required">*</span>
                    </label>
                    <input type="text"
                           id="name"
                           name="name"
                           class="form-control"
                           placeholder="Иван Иванов"
                           required>
                    <div class="error-text" id="nameError"></div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="email">
                        Email
                        <span class="required">*</span>
                    </label>
                    <input type="email"
                           id="email"
                           name="email"
                           class="form-control"
                           placeholder="example@mail.ru"
                           required>
                    <div class="error-text" id="emailError"></div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="phone_number">
                        Телефон
                        <span class="required">*</span>
                    </label>
                    <input type="tel"
                           id="phone_number"
                           name="phone_number"
                           class="form-control"
                           placeholder="+7 (999) 999-99-99"
                           required>
                    <div class="error-text" id="phoneError"></div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="message">
                        Сообщение
                        <span class="required">*</span>
                    </label>
                    <textarea id="message"
                              name="message"
                              class="form-control"
                              placeholder="Опишите ваш вопрос или проблему..."
                              required></textarea>
                    <div class="error-text" id="messageError"></div>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Прикрепленные файлы
                    </label>
                    <div class="file-upload-area" id="fileUploadArea">
                        <div class="file-upload-icon">
                            <i>📎</i>
                        </div>
                        <div class="file-upload-text">
                            Перетащите файлы сюда или нажмите для выбора
                        </div>
                        <div class="file-upload-hint">
                            Максимум 5 файлов, не более 10MB каждый
                            <br>Допустимые форматы: .pdf, .doc, .docx, .jpg, .jpeg, .png
                        </div>
                        <div class="file-upload-btn">
                            Выбрать файлы
                        </div>
                        <input type="file"
                               id="fileInput"
                               class="file-input"
                               multiple
                               accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                    </div>
                    <div class="file-error" id="fileError"></div>

                    <div class="file-preview-container" id="filePreviewContainer">
                        <ul class="file-preview-list" id="filePreviewList">
                        </ul>
                    </div>
                </div>

                <div class="progress-container" id="progressContainer">
                    <div class="progress-bar">
                        <div class="progress-fill" id="progressFill"></div>
                    </div>
                    <div class="progress-text" id="progressText">Загрузка: 0%</div>
                </div>

                <button type="submit" class="btn-submit" id="submitBtn">
                    <span id="btnText">Отправить сообщение</span>
                    <div class="loading-spinner" id="loadingSpinner"></div>
                </button>

                <div class="message-container success-message" id="successMessage">
                    <div id="successMessageContent">
                        ✓ Сообщение успешно отправлено! Мы свяжемся с вами в ближайшее время.
                    </div>
                    <div class="ticket-details" id="ticketDetails" style="display: none;">
                        <h4>Детали заявки:</h4>
                        <p><strong>Номер заявки:</strong> <span id="ticketId" class="ticket-id"></span></p>
                    </div>
                </div>

                <div class="message-container error-message" id="errorMessage">
                    ✗ Произошла ошибка при отправке. Пожалуйста, попробуйте еще раз.
                </div>

                <div class="form-footer">
                    <p>Нажимая кнопку, вы соглашаетесь с обработкой персональных данных</p>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('feedbackForm');
        const submitBtn = document.getElementById('submitBtn');
        const btnText = document.getElementById('btnText');
        const loadingSpinner = document.getElementById('loadingSpinner');
        const successMessage = document.getElementById('successMessage');
        const successMessageContent = document.getElementById('successMessageContent');
        const errorMessage = document.getElementById('errorMessage');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const fileInput = document.getElementById('fileInput');
        const fileUploadArea = document.getElementById('fileUploadArea');
        const filePreviewContainer = document.getElementById('filePreviewContainer');
        const filePreviewList = document.getElementById('filePreviewList');
        const fileError = document.getElementById('fileError');
        const progressContainer = document.getElementById('progressContainer');
        const progressFill = document.getElementById('progressFill');
        const progressText = document.getElementById('progressText');

        const ticketDetails = document.getElementById('ticketDetails');
        const ticketId = document.getElementById('ticketId');

        const nameError = document.getElementById('nameError');
        const emailError = document.getElementById('emailError');
        const phoneError = document.getElementById('phoneError');
        const messageError = document.getElementById('messageError');

        let files = [];
        const MAX_FILES = 5;
        const MAX_FILE_SIZE = 10 * 1024 * 1024;
        const ALLOWED_TYPES = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'image/jpeg',
            'image/jpg',
            'image/png'
        ];

        const API_URL = '/api/tickets';

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        function getFileIcon(filename) {
            const ext = filename.split('.').pop().toLowerCase();
            const icons = {
                pdf: '📕',
                doc: '📘',
                docx: '📘',
                jpg: '🖼️',
                jpeg: '🖼️',
                png: '🖼️'
            };
            return icons[ext] || '📄';
        }

        function validateFile(file) {
            if (files.length >= MAX_FILES) {
                return `Максимум ${MAX_FILES} файлов`;
            }

            if (file.size > MAX_FILE_SIZE) {
                return `Файл слишком большой (максимум ${formatFileSize(MAX_FILE_SIZE)})`;
            }

            if (!ALLOWED_TYPES.includes(file.type)) {
                return 'Недопустимый формат файла';
            }

            return null;
        }

        function addFile(file) {
            const error = validateFile(file);
            if (error) {
                showFileError(error);
                return false;
            }

            files.push(file);
            updateFilePreview();
            return true;
        }

        function removeFile(index) {
            files.splice(index, 1);
            updateFilePreview();
        }

        function updateFilePreview() {
            if (files.length === 0) {
                filePreviewContainer.style.display = 'none';
                return;
            }

            filePreviewContainer.style.display = 'block';
            filePreviewList.innerHTML = '';

            files.forEach((file, index) => {
                const li = document.createElement('li');
                li.className = 'file-preview-item';
                li.innerHTML = `
                    <div class="file-info">
                        <div class="file-icon">${getFileIcon(file.name)}</div>
                        <div class="file-details">
                            <div class="file-name" title="${file.name}">${file.name}</div>
                            <div class="file-size">${formatFileSize(file.size)}</div>
                        </div>
                    </div>
                    <button type="button" class="file-remove" data-index="${index}">×</button>
                `;
                filePreviewList.appendChild(li);
            });

            filePreviewList.querySelectorAll('.file-remove').forEach(button => {
                button.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    removeFile(index);
                });
            });
        }

        function showFileError(message) {
            fileError.textContent = message;
            fileError.style.display = 'block';
            setTimeout(() => {
                fileError.style.display = 'none';
            }, 5000);
        }

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            fileUploadArea.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            fileUploadArea.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            fileUploadArea.addEventListener(eventName, unhighlight, false);
        });

        function highlight() {
            fileUploadArea.classList.add('drag-over');
        }

        function unhighlight() {
            fileUploadArea.classList.remove('drag-over');
        }

        fileUploadArea.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const droppedFiles = dt.files;

            handleFiles(droppedFiles);
        }

        function handleFiles(fileList) {
            for (let file of fileList) {
                addFile(file);
            }
        }

        fileUploadArea.addEventListener('click', () => {
            fileInput.click();
        });

        fileInput.addEventListener('change', () => {
            handleFiles(fileInput.files);
            fileInput.value = '';
        });

        function showError(element, message) {
            element.textContent = message;
            element.style.display = 'block';
            element.previousElementSibling.classList.add('error');
        }

        function clearErrors() {
            [nameError, emailError, phoneError, messageError].forEach(error => {
                error.style.display = 'none';
                error.textContent = '';
            });

            document.querySelectorAll('.form-control.error').forEach(input => {
                input.classList.remove('error');
            });
        }

        function showLoading() {
            btnText.style.display = 'none';
            loadingSpinner.style.display = 'block';
            submitBtn.disabled = true;
        }

        function hideLoading() {
            btnText.style.display = 'block';
            loadingSpinner.style.display = 'none';
            submitBtn.disabled = false;
        }

        function showSuccess(ticketData = null) {
            successMessage.style.display = 'block';
            errorMessage.style.display = 'none';

            if (ticketData) {
                successMessageContent.innerHTML = '✓ Сообщение успешно отправлено! Мы свяжемся с вами в ближайшее время.';
                ticketDetails.style.display = 'block';
                ticketId.textContent = '#' + ticketData.id;
            } else {
                ticketDetails.style.display = 'none';
            }

            setTimeout(() => {
                form.reset();
                files = [];
                updateFilePreview();
                successMessage.style.display = 'none';
                ticketDetails.style.display = 'none';
            }, 5000);
        }

        function showErrorMsg(message) {
            errorMessage.textContent = message || 'Произошла ошибка при отправке. Пожалуйста, попробуйте еще раз.';
            errorMessage.style.display = 'block';
            successMessage.style.display = 'none';
        }

        function updateProgress(percentage) {
            progressFill.style.width = `${percentage}%`;
            progressText.textContent = `Загрузка: ${Math.round(percentage)}%`;
        }

        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            clearErrors();
            showLoading();

            const formData = {
                name: document.getElementById('name').value.trim(),
                email: document.getElementById('email').value.trim(),
                phone_number: document.getElementById('phone_number').value.trim(),
                description: document.getElementById('message').value.trim()
            };

            let hasError = false;

            if (!formData.name) {
                showError(nameError, 'Пожалуйста, введите ваше имя');
                hasError = true;
            }

            if (!formData.email) {
                showError(emailError, 'Пожалуйста, введите email');
                hasError = true;
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(formData.email)) {
                showError(emailError, 'Пожалуйста, введите корректный email');
                hasError = true;
            }

            if (!formData.phone_number) {
                showError(phoneError, 'Пожалуйста, введите телефон');
                hasError = true;
            }

            if (!formData.description) {
                showError(messageError, 'Пожалуйста, введите сообщение');
                hasError = true;
            }

            if (hasError) {
                hideLoading();
                return;
            }

            try {
                progressContainer.style.display = 'block';
                updateProgress(0);

                const formDataObj = new FormData();
                formDataObj.append('name', formData.name);
                formDataObj.append('email', formData.email);
                formDataObj.append('phone_number', formData.phone_number);
                formDataObj.append('description', formData.description);

                files.forEach((file, index) => {
                    formDataObj.append(`files[${index}]`, file);
                });

                const xhr = new XMLHttpRequest();

                xhr.open('POST', API_URL);
                xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
                xhr.setRequestHeader('Accept', 'application/json');

                xhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                        const percentage = (e.loaded / e.total) * 100;
                        updateProgress(percentage);
                    }
                });

                xhr.onload = function() {
                    progressContainer.style.display = 'none';

                    try {
                        const responseText = xhr.responseText.trim();

                        if (!responseText) {
                            showErrorMsg('Пустой ответ от сервера');
                            return;
                        }

                        const data = JSON.parse(responseText);

                        if (xhr.status === 201) {
                            if (Array.isArray(data) && data.length > 0) {
                                const ticket = data[0];
                                showSuccess({
                                    id: ticket.id || 'не указан'
                                });
                            } else {
                                showSuccess();
                            }
                        }
                        else if (xhr.status === 200) {
                            if (data.errors) {
                                Object.keys(data.errors).forEach(key => {
                                    if (key === 'name') showError(nameError, data.errors[key][0]);
                                    if (key === 'email') showError(emailError, data.errors[key][0]);
                                    if (key === 'phone_number') showError(phoneError, data.errors[key][0]);
                                    if (key === 'description') showError(messageError, data.errors[key][0]);
                                    if (key.startsWith('files')) showFileError(data.errors[key][0]);
                                });
                                showErrorMsg(data.description || 'Пожалуйста, исправьте ошибки в форме');
                            } else {
                                showErrorMsg(data.description || 'Неизвестная ошибка');
                            }
                        }
                        else {
                            showErrorMsg(data.description || data.error || 'Ошибка сервера');
                        }
                    } catch (error) {
                        console.error('Error parsing response:', error, xhr.responseText);
                        showErrorMsg('Ошибка обработки ответа от сервера');
                    }
                };

                xhr.onerror = function() {
                    progressContainer.style.display = 'none';
                    showErrorMsg('Ошибка соединения с сервером');
                };

                xhr.onabort = function() {
                    progressContainer.style.display = 'none';
                    showErrorMsg('Запрос был отменен');
                };

                xhr.send(formDataObj);

            } catch (error) {
                console.error('Error:', error);
                progressContainer.style.display = 'none';
                showErrorMsg('Ошибка при отправке формы');
            } finally {
                hideLoading();
            }
        });

        form.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('input', function() {
                this.classList.remove('error');
                const errorElement = this.nextElementSibling;
                if (errorElement && errorElement.classList.contains('error-text')) {
                    errorElement.style.display = 'none';
                }
            });
        });
    });
</script>
</body>
</html>
