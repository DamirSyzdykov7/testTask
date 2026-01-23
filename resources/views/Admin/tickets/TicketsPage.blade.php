@extends('admin.layouts.master')

@section('title', ' - Заявки')

@section('content')
    <div class="content-header">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <h1 class="page-title">Управление заявками</h1>
                <p class="page-subtitle">Все заявки от клиентов</p>
            </div>

            <div style="position: relative;">
                <button class="btn btn-secondary" onclick="toggleStatusDropdown()" style="display: flex; align-items: center; gap: 8px;">
                    <span>🎯</span>
                    Статус
                    <span style="font-size: 12px;">▼</span>
                </button>

                <div id="statusDropdown" style="display: none; position: absolute; top: 100%; left: 0; background: white; border: 1px solid #e3f2fd; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); z-index: 1000; min-width: 200px; margin-top: 8px;">
                    <a href="{{ route('admin.tickets.index') }}" class="dropdown-item">
                        Все статусы
                    </a>
                    <a href="{{ route('admin.tickets.index') }}?status=0" class="dropdown-item">
                        Новые
                    </a>
                    <a href="{{ route('admin.tickets.index') }}?status=1" class="dropdown-item">
                        В работе
                    </a>
                    <a href="{{ route('admin.tickets.index') }}?status=2" class="dropdown-item">
                        Решенные
                    </a>
                    <a href="{{ route('admin.tickets.index') }}?status=3" class="dropdown-item">
                        Закрытые
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <div class="table-container">
            <table class="data-table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Клиент</th>
                    <th>Email</th>
                    <th>Тема</th>
                    <th>Дата создания</th>
                    <th>Статус</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody>
                @foreach($tickets as $ticket)
                    @php
                        $statusInfo = match($ticket->status) {
                            0 => ['text' => 'Новая', 'class' => 'status-new'],
                            1 => ['text' => 'В работе', 'class' => 'status-in-progress'],
                            2 => ['text' => 'Решена', 'class' => 'status-resolved'],
                            3 => ['text' => 'Закрыта', 'class' => 'status-closed'],
                            default => ['text' => 'Неизвестно', 'class' => 'status-closed']
                        };
                    @endphp
                    <tr onclick="window.location='{{ route('admin.tickets.show', ['id'=>$ticket->id , 'customer_name' => $ticket->customer_name, 'email' => $ticket->email, 'topic' => $ticket->topic]) }}'" style="cursor: pointer;">
                        <td>#{{ $ticket->id }}</td>
                        <td>{{ $ticket->customer_name ?? 'Неизвестно' }}</td>
                        <td>{{ $ticket->email ?? 'Не указан' }}</td>
                        <td>{{ $ticket->topic ?? 'Без темы' }}</td>
                        <td>{{ $ticket->created_at ??'Не указана' }}</td>
                        <td><span class="status-badge {{ $statusInfo['class'] }}">{{ $statusInfo['text'] }}</span></td>
                        <td>
                            <div class="action-buttons" onclick="event.stopPropagation()">
                                <a href="{{ route('admin.tickets.show', ['id'=>$ticket->id , 'customer_name' => $ticket->customer_name, 'email' => $ticket->email, 'topic' => $ticket->topic]) }}" class="btn btn-secondary" title="Просмотр">
                                    👁️
                                </a>
                                <a href="" class="btn btn-primary" title="Редактировать">
                                    ✏️
                                </a>
                                <form action="" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" title="Удалить" onclick="return confirm('Удалить заявку?')">
                                        🗑️
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function toggleStatusDropdown() {
            const dropdown = document.getElementById('statusDropdown');
            dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
        }

        // Закрыть dropdown при клике вне его
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('statusDropdown');
            const button = document.querySelector('button[onclick="toggleStatusDropdown()"]');

            if (dropdown && !dropdown.contains(event.target) && !button.contains(event.target)) {
                dropdown.style.display = 'none';
            }
        });
    </script>

    <style>
        .dropdown-item {
            display: block;
            padding: 12px 16px;
            text-decoration: none;
            color: #333;
            border-bottom: 1px solid #f5f5f5;
            transition: background-color 0.2s;
        }

        .dropdown-item:hover {
            background-color: #f8fdff;
        }
    </style>
@endsection
