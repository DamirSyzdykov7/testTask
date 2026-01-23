@extends('admin.layouts.master')

@section('title', ' - Заявка #' . $ticket->id)

@section('content')
    <div class="content-header">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <h1 class="page-title">Заявка #{{ $ticket->id }}</h1>
                <p class="page-subtitle">Детальная информация о заявке</p>
            </div>
            <div style="display: flex; gap: 12px;">
                <a href="{{ route('admin.tickets.index') }}" class="btn btn-secondary">
                    ← Назад к списку
                </a>
                <button class="btn btn-primary" onclick="window.print()">
                    🖨️ Печать
                </button>
            </div>
        </div>
    </div>

    <div class="content-body">
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
            <div>
                <div style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 24px;">
                    <h2 style="font-size: 18px; color: #1976d2; margin-bottom: 20px;">Основная информация</h2>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div>
                            <label style="display: block; margin-bottom: 6px; font-size: 12px; color: #1976d2; font-weight: 500;">Клиент</label>
                            <div style="font-size: 16px; font-weight: 500;">
                                {{ $data['customer_name'] ?? 'не указан' }}
                            </div>
                        </div>

                        <div>
                            <label style="display: block; margin-bottom: 6px; font-size: 12px; color: #1976d2; font-weight: 500;">Email</label>
                            <div style="font-size: 16px;">
                                {{ $data['email'] ?? 'Не указан' }}
                            </div>
                        </div>

                        <div>
                            <label style="display: block; margin-bottom: 6px; font-size: 12px; color: #1976d2; font-weight: 500;">ID клиента</label>
                            <div style="font-size: 16px;">
                                {{ $data['id'] ?? 'Не указан' }}
                            </div>
                        </div>

                        <div>
                            <label style="display: block; margin-bottom: 6px; font-size: 12px; color: #1976d2; font-weight: 500;">Тема</label>
                            <div style="font-size: 16px;">
                                {{ $ticket->topic ?? 'Без темы' }}
                            </div>
                        </div>
                    </div>
                </div>

                <div style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
                    <h2 style="font-size: 18px; color: #1976d2; margin-bottom: 16px;">Описание</h2>
                    <div style="background: #f8fdff; padding: 20px; border-radius: 8px; border: 1px solid #e3f2fd; min-height: 150px; line-height: 1.6;">
                        {!! nl2br(e($ticket->description)) !!}
                    </div>
                </div>

                @if($ticket->media && $ticket->media->count() > 0)
                    <div style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-top: 24px;">
                        <h2 style="font-size: 18px; color: #1976d2; margin-bottom: 20px;">Прикрепленные файлы ({{ $ticket->media->count() }})</h2>
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 16px;">
                            @foreach($ticket->media as $file)
                                <div style="background: #f8fdff; border: 1px solid #e3f2fd; border-radius: 8px; padding: 16px;">
                                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                                        @php
                                            $extension = pathinfo($file->file_name, PATHINFO_EXTENSION);
                                            $icon = match($extension) {
                                                'pdf' => '📕',
                                                'doc', 'docx' => '📘',
                                                'jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp' => '🖼️',
                                                'xls', 'xlsx' => '📊',
                                                'zip', 'rar', '7z' => '📦',
                                                default => '📄'
                                            };
                                            // Проверяем, является ли файл изображением
                                            $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp']);

                                            // Получаем правильный URL через Storage
                                            $fileUrl = Storage::url($file->id . '/' . $file->file_name);
                                        @endphp
                                        <div style="font-size: 24px;">{{ $icon }}</div>
                                        <div>
                                            <div style="font-weight: 500; font-size: 14px; margin-bottom: 4px; word-break: break-all;">
                                                {{ $file->name }}
                                            </div>
                                            <div style="font-size: 12px; color: #666;">
                                                {{ round($file->size / 1024) }} KB • .{{ $extension }}
                                            </div>
                                        </div>
                                    </div>
                                    <div style="display: flex; gap: 8px;">
                                        <!-- Используем прямой URL через Storage -->
                                        <a href="{{ asset($fileUrl) }}"
                                           class="btn btn-primary"
                                           style="flex: 1; padding: 8px; font-size: 14px; text-decoration: none; text-align: center;"
                                           target="_blank"
                                           download="{{ $file->name }}">
                                            📥 Скачать
                                        </a>
                                        @if($isImage)
                                            <button onclick="previewFile('{{ asset($fileUrl) }}')"
                                                    class="btn btn-secondary"
                                                    style="padding: 8px 12px; font-size: 14px;"
                                                    title="Просмотр">
                                                👁️
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div>
                <div style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 24px;">
                    <h2 style="font-size: 18px; color: #1976d2; margin-bottom: 20px;">Статус</h2>

                    @php
                        $statusInfo = match($ticket->status) {
                            0 => ['text' => 'Новая', 'class' => 'status-new', 'color' => '#1976d2'],
                            1 => ['text' => 'В работе', 'class' => 'status-in-progress', 'color' => '#ff9800'],
                            2 => ['text' => 'Решена', 'class' => 'status-resolved', 'color' => '#4caf50'],
                            3 => ['text' => 'Закрыта', 'class' => 'status-closed', 'color' => '#9e9e9e'],
                            default => ['text' => 'Неизвестно', 'class' => 'status-closed', 'color' => '#9e9e9e']
                        };
                    @endphp

                    <div style="text-align: center; margin-bottom: 24px;">
                    <span class="status-badge {{ $statusInfo['class'] }}" style="font-size: 16px; padding: 10px 20px;">
                        {{ $statusInfo['text'] }}
                    </span>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 16px;">
                        <div>
                            <label style="display: block; margin-bottom: 6px; font-size: 12px; color: #1976d2; font-weight: 500;">Дата создания</label>
                            <div style="font-size: 16px; display: flex; align-items: center; gap: 8px;">
                                📅 {{ $ticket->created_at ? $ticket->created_at->format('d.m.Y H:i') : 'Не указана' }}
                            </div>
                        </div>

                        <div>
                            <label style="display: block; margin-bottom: 6px; font-size: 12px; color: #1976d2; font-weight: 500;">Дата ответа</label>
                            <div style="font-size: 16px; display: flex; align-items: center; gap: 8px;">
                                📅 {{ $ticket->response_date ? \Carbon\Carbon::parse($ticket->response_date)->format('d.m.Y') : 'Не указана' }}
                            </div>
                        </div>
                    </div>
                </div>

                <div style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
                    <h2 style="font-size: 18px; color: #1976d2; margin-bottom: 20px;">Действия</h2>

                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        <button class="btn btn-primary" onclick="changeStatus(1)" @if($ticket->status == 1) disabled @endif>
                            🚀 Взять в работу
                        </button>

                        <button class="btn btn-primary" onclick="changeStatus(2)" @if($ticket->status == 2) disabled @endif>
                            ✅ Отметить как решённую
                        </button>

                        <button class="btn btn-secondary" onclick="changeStatus(3)" @if($ticket->status == 3) disabled @endif>
                            🔒 Закрыть заявку
                        </button>

                        <button class="btn btn-danger" onclick="deleteTicket()">
                            🗑️ Удалить заявку
                        </button>
                    </div>
                </div>

                <div style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-top: 24px;">
                    <h2 style="font-size: 18px; color: #1976d2; margin-bottom: 20px;">История изменений</h2>

                    <div style="color: #666; font-style: italic; text-align: center; padding: 20px;">
                        История изменений будет доступна в следующих версиях
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Модальное окно для предпросмотра изображений -->
    <div id="imagePreviewModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 10000; align-items: center; justify-content: center;">
        <div style="position: relative; max-width: 90%; max-height: 90%;">
            <img id="previewImage" src="" alt="Preview" style="max-width: 100%; max-height: 90vh; border-radius: 8px;">
            <button onclick="closePreview()" style="position: absolute; top: -40px; right: 0; background: #ff5252; color: white; border: none; padding: 10px; border-radius: 50%; cursor: pointer;">✕</button>
        </div>
    </div>

    <script>
        function changeStatus(newStatus) {
            if (confirm('Изменить статус заявки?')) {
                const ticketData = {
                    status: newStatus,
                    id: "{{ $ticket->id }}",
                };

                fetch('{{ route("admin.tickets.update", $ticket) }}', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(ticketData)
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            alert('Статус обновлён!');
                            location.reload();
                        } else {
                            alert('Ошибка: ' + (data.message || 'Неизвестная ошибка'));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Ошибка сети: ' + error.message);
                    });
            }
        }

        // Функция для предпросмотра изображений
        function previewFile(imageUrl) {
            // Проверяем, что URL действительный
            if (!imageUrl || imageUrl === '#') {
                alert('Не удалось загрузить изображение');
                return;
            }

            // Показываем модальное окно с изображением
            document.getElementById('previewImage').src = imageUrl;
            document.getElementById('imagePreviewModal').style.display = 'flex';
        }

        // Закрыть предпросмотр
        function closePreview() {
            document.getElementById('imagePreviewModal').style.display = 'none';
            document.getElementById('previewImage').src = '';
        }

        // Закрыть по клику вне изображения
        document.getElementById('imagePreviewModal').addEventListener('click', function(e) {
            if (e.target.id === 'imagePreviewModal') {
                closePreview();
            }
        });
    </script>

    <style>
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 8px;
            border: none;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 14px;
        }

        .btn-primary {
            background: #1976d2;
            color: white;
            text-decoration: none;
        }

        .btn-primary:hover {
            background: #1565c0;
        }

        .btn-secondary {
            background: #f5f7fa;
            color: #333;
            border: 1px solid #e3f2fd;
        }

        .btn-secondary:hover {
            background: #e3f2fd;
        }

        .btn-danger {
            background: #ff5252;
            color: white;
        }

        .btn-danger:hover {
            background: #ff3838;
        }
    </style>
@endsection
