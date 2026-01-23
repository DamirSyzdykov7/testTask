@extends('admin.layouts.master')

@section('title', ' - Клиенты')

@section('content')
    <div class="content-header">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <h1 class="page-title">Управление клиентами</h1>
                <p class="page-subtitle">Просмотр и управление клиентской базой</p>
            </div>
        </div>
    </div>

    <div class="content-body">
        <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 24px;">
            <div style="display: flex; gap: 16px; align-items: center;">
                <div style="flex: 1;">
                    <div style="position: relative;">
                        <input
                            type="text"
                            class="form-control"
                            placeholder="Поиск по имени, email или телефону..."
                            style="width: 100%; padding-left: 40px;"
                        >
                        <span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); font-size: 18px;">
                            🔍
                        </span>
                    </div>
                </div>
                <button class="btn btn-primary">
                    Найти
                </button>
                <button class="btn btn-secondary">
                    Сбросить
                </button>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px;">
            <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
                <div style="font-size: 14px; color: #666; margin-bottom: 8px;">Всего клиентов</div>
                <div style="font-size: 28px; font-weight: 600; color: #1976d2;">{{ count($customers) }}</div>
            </div>
            <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
                <div style="font-size: 14px; color: #666; margin-bottom: 8px;">Активных</div>
                <div style="font-size: 28px; font-weight: 600; color: #4caf50;">{{ count($customers) }}</div>
            </div>
            <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
                <div style="font-size: 14px; color: #666; margin-bottom: 8px;">Новых сегодня</div>
                <div style="font-size: 28px; font-weight: 600; color: #ff9800;">0</div>
            </div>
        </div>

        <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                    <tr style="background: #f8fafc;">
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #1976d2; font-size: 14px; border-bottom: 2px solid #e3f2fd;">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <span>ID</span>
                                <span style="font-size: 12px; color: #666; cursor: pointer;">▼</span>
                            </div>
                        </th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #1976d2; font-size: 14px; border-bottom: 2px solid #e3f2fd;">Клиент</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #1976d2; font-size: 14px; border-bottom: 2px solid #e3f2fd;">Контактная информация</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #1976d2; font-size: 14px; border-bottom: 2px solid #e3f2fd;">Дата регистрации</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #1976d2; font-size: 14px; border-bottom: 2px solid #e3f2fd;">Статус</th>
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #1976d2; font-size: 14px; border-bottom: 2px solid #e3f2fd;">Действия</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($customers as $customer)
                        <tr style="border-bottom: 1px solid #e3f2fd; transition: background-color 0.2s;">
                            <td style="padding: 16px;">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <div style="font-weight: 600; color: #1976d2;">#{{ $customer->id }}</div>
                                </div>
                            </td>
                            <td style="padding: 16px;">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #1976d2, #64b5f6); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 16px;">
                                        {{ strtoupper(substr($customer->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div style="font-weight: 500; color: #333; margin-bottom: 4px;">
                                            {{ $customer->name ?? 'Не указано' }}
                                        </div>
                                        <div style="font-size: 12px; color: #666;">
                                            ID: CUST-{{ str_pad($customer->id, 4, '0', STR_PAD_LEFT) }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 16px;">
                                <div style="display: flex; flex-direction: column; gap: 8px;">
                                    <div>
                                        <div style="font-size: 12px; color: #666; margin-bottom: 2px;">Email</div>
                                        <div style="color: #333; font-weight: 500;">
                                            {{ $customer->email ?? 'Не указан' }}
                                        </div>
                                    </div>
                                    <div>
                                        <div style="font-size: 12px; color: #666; margin-bottom: 2px;">Телефон</div>
                                        <div style="color: #333; font-weight: 500;">
                                            {{ $customer->phone_number ?? 'Не указан' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 16px;">
                                @if($customer->created_at)
                                    <div>
                                        <div style="font-weight: 500; color: #333;">
                                            {{ \Carbon\Carbon::parse($customer->created_at)->format('d.m.Y') }}
                                        </div>
                                        <div style="font-size: 12px; color: #666;">
                                            {{ \Carbon\Carbon::parse($customer->created_at)->format('H:i') }}
                                        </div>
                                    </div>
                                @else
                                    <span style="color: #999; font-style: italic;">Не указана</span>
                                @endif
                            </td>
                            <td style="padding: 16px;">
                                    <span class="status-badge status-resolved" style="background: #e8f5e9; color: #2e7d32; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 500;">
                                        Активен
                                    </span>
                            </td>
                            <td style="padding: 16px;">
                                <div style="display: flex; gap: 8px;">
                                    <button class="btn btn-secondary"
                                            style="padding: 8px 12px; min-width: auto;"
                                            title="Просмотр профиля"
                                            onclick="viewCustomer({{ $customer->id }})">
                                        <span>👁️</span>
                                    </button>
                                    <button class="btn btn-primary"
                                            style="padding: 8px 12px; min-width: auto;"
                                            title="Редактировать"
                                            onclick="editCustomer({{ $customer->id }})">
                                        <span>✏️</span>
                                    </button>
                                    <button class="btn btn-danger"
                                            style="padding: 8px 12px; min-width: auto;"
                                            title="Удалить"
                                            onclick="deleteCustomer({{ $customer->id }})">
                                        <span>🗑️</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding: 40px; text-align: center; color: #666;">
                                <div style="font-size: 18px; margin-bottom: 12px;">😔</div>
                                <div style="font-size: 16px; margin-bottom: 8px;">Клиенты не найдены</div>
                                <div style="color: #999; font-size: 14px;">Добавьте первого клиента или измените условия поиска</div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 24px;">
            <div style="color: #666; font-size: 14px;">
                @if(count($customers) > 0)
                    Показано {{ count($customers) }} клиентов
                @endif
            </div>
        </div>
    </div>

    <style>
        .btn {
            display: inline-flex;
            align-items: center;
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

        .form-control {
            padding: 10px 16px;
            border: 1px solid #e3f2fd;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: #1976d2;
            box-shadow: 0 0 0 3px rgba(25, 118, 210, 0.1);
        }

        tr:hover {
            background-color: #f8fdff !important;
        }
    </style>

    <script>
        function viewCustomer(id) {
            alert('Просмотр клиента #' + id);
        }

        function editCustomer(id) {
            alert('Редактирование клиента #' + id);
        }

        function deleteCustomer(id) {
        }
    </script>
@endsection
