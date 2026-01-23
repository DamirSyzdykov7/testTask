<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Админ-панель @yield('title')</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .admin-header {
            background: linear-gradient(135deg, #1976d2 0%, #2196f3 100%);
            color: white;
            padding: 0 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 64px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-icon {
            width: 36px;
            height: 36px;
            background: white;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #1976d2;
            font-weight: bold;
            font-size: 18px;
        }

        .logo-text {
            font-size: 20px;
            font-weight: 600;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #1976d2;
            font-weight: bold;
        }

        .logout-btn {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            transition: background 0.3s ease;
        }

        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .admin-container {
            display: flex;
            margin-top: 64px;
            min-height: calc(100vh - 64px);
        }

        .admin-sidebar {
            width: 240px;
            background: white;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
            position: fixed;
            top: 64px;
            left: 0;
            bottom: 0;
            z-index: 900;
            padding: 24px 0;
            overflow-y: auto;
        }

        .sidebar-menu {
            list-style: none;
        }

        .menu-item {
            padding: 12px 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            color: #333;
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }

        .menu-item:hover {
            background: #f5f5f5;
            color: #1976d2;
        }

        .menu-item.active {
            background: #e3f2fd;
            color: #1976d2;
            border-left-color: #1976d2;
        }

        .menu-icon {
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .menu-text {
            font-weight: 500;
        }

        .admin-content {
            flex: 1;
            margin-left: 240px;
            padding: 24px;
            background: transparent;
        }

        .content-header {
            background: white;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .page-title {
            font-size: 24px;
            font-weight: 600;
            color: #1976d2;
            margin-bottom: 8px;
        }

        .page-subtitle {
            color: #666;
            font-size: 14px;
        }

        .content-body {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            min-height: 400px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card.new {
            border-top: 4px solid #2196f3;
        }

        .stat-card.in-progress {
            border-top: 4px solid #ff9800;
        }

        .stat-card.resolved {
            border-top: 4px solid #4caf50;
        }

        .stat-card.closed {
            border-top: 4px solid #9e9e9e;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }

        .stat-label {
            color: #666;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .table-container {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th {
            background: #f8fdff;
            color: #1976d2;
            font-weight: 600;
            text-align: left;
            padding: 16px;
            border-bottom: 2px solid #e3f2fd;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 1px;
        }

        .data-table td {
            padding: 16px;
            border-bottom: 1px solid #f0f0f0;
            color: #333;
        }

        .data-table tr:hover {
            background: #f8fdff;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            text-transform: uppercase;
        }

        .status-new {
            background: #e3f2fd;
            color: #1976d2;
        }

        .status-in-progress {
            background: #fff3e0;
            color: #ff9800;
        }

        .status-resolved {
            background: #e8f5e9;
            color: #4caf50;
        }

        .status-closed {
            background: #f5f5f5;
            color: #9e9e9e;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn {
            padding: 8px 16px;
            border-radius: 6px;
            border: none;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 14px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #1976d2 0%, #2196f3 100%);
            color: white;
        }

        .btn-primary:hover {
            box-shadow: 0 4px 12px rgba(33, 150, 243, 0.3);
        }

        .btn-secondary {
            background: #e3f2fd;
            color: #1976d2;
        }

        .btn-secondary:hover {
            background: #bbdefb;
        }

        .btn-danger {
            background: #ffebee;
            color: #f44336;
        }

        .btn-danger:hover {
            background: #ffcdd2;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            color: #1976d2;
            font-weight: 500;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e3f2fd;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #f8fdff;
        }

        .form-control:focus {
            outline: none;
            border-color: #2196f3;
            box-shadow: 0 0 0 3px rgba(33, 150, 243, 0.1);
        }

        @media (max-width: 1024px) {
            .admin-sidebar {
                width: 200px;
            }

            .admin-content {
                margin-left: 200px;
            }
        }

        @media (max-width: 768px) {
            .admin-sidebar {
                width: 64px;
            }

            .menu-text {
                display: none;
            }

            .admin-content {
                margin-left: 64px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }

        .login-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }

        .login-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 119, 255, 0.1);
            width: 100%;
            max-width: 400px;
            padding: 40px;
        }

        .login-logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-title {
            font-size: 24px;
            color: #1976d2;
            margin-bottom: 8px;
        }

        .login-subtitle {
            color: #666;
            font-size: 14px;
        }

        .loading-spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-icon {
            width: 80px;
            height: 80px;
            background: #e3f2fd;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: #1976d2;
            font-size: 36px;
        }

        .empty-title {
            font-size: 18px;
            color: #333;
            margin-bottom: 8px;
        }

        .empty-text {
            color: #666;
            margin-bottom: 20px;
        }

        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 2000;
        }

        .modal-content {
            background: white;
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-header {
            padding: 24px;
            border-bottom: 1px solid #e3f2fd;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title {
            font-size: 18px;
            font-weight: 600;
            color: #1976d2;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #666;
        }

        .modal-body {
            padding: 24px;
        }
    </style>
</head>
<body>
@auth
    <header class="admin-header">
        <div class="logo">
            <div class="logo-icon">A</div>
            <div class="logo-text">Админ-панель</div>
        </div>

        <div class="header-actions">
            <div class="user-info">
                <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
                <div>{{ auth()->user()->name ?? 'Администратор' }}</div>
            </div>
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="logout-btn">Выйти</button>
            </form>
        </div>
    </header>

    <div class="admin-container">
        <nav class="admin-sidebar">
            <ul class="sidebar-menu">
                <li>
                    <a href="{{ route('admin.tickets.index') }}" class="menu-item @if(Route::is('admin.tickets.*')) active @endif">
                        <div class="menu-icon">🎫</div>
                        <div class="menu-text">Заявки</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.customers.index') }}" class="menu-item @if(Route::is('admin.customers.*')) active @endif">
                        <div class="menu-icon">👥</div>
                        <div class="menu-text">Клиенты</div>
                    </a>
                </li>
            </ul>
        </nav>

        <main class="admin-content">
            @yield('content')
        </main>
    </div>
@else
    @yield('content')
@endauth

@stack('scripts')
</body>
</html>
