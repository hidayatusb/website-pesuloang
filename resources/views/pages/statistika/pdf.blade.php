<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Statistik {{ $identity->name }}</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 11px;
            color: #1f2937;
            margin: 24px 28px;
        }
        .header {
            border-bottom: 2px solid #16a34a;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0 0 4px;
            font-size: 18px;
            color: #111827;
        }
        .header p {
            margin: 0;
            color: #6b7280;
            font-size: 10px;
        }
        .category {
            margin-bottom: 24px;
            page-break-inside: avoid;
        }
        .category h2 {
            font-size: 13px;
            margin: 0 0 2px;
            color: #166534;
        }
        .category .description {
            margin: 0 0 8px;
            color: #6b7280;
            font-size: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #d1d5db;
            padding: 6px 8px;
            text-align: left;
        }
        th {
            background-color: #f0fdf4;
            font-weight: bold;
            color: #14532d;
        }
        tr:nth-child(even) td {
            background-color: #f9fafb;
        }
        .footer {
            margin-top: 24px;
            padding-top: 8px;
            border-top: 1px solid #e5e7eb;
            font-size: 9px;
            color: #9ca3af;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Data Statistik {{ $identity->name }}</h1>
        <p>Diunduh pada {{ $generatedAt->translatedFormat('l, d F Y H:i') }} WITA</p>
    </div>

    @foreach ($categories as $category)
        @php $columns = $category->columnDefinitions(); @endphp
        <div class="category">
            <h2>{{ $category->name }}</h2>
            @if ($category->description)
                <p class="description">{{ $category->description }}</p>
            @endif
            <table>
                <thead>
                    <tr>
                        @foreach ($columns as $column)
                            <th>{{ $column['label'] }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse ($category->activeItems as $item)
                        <tr>
                            @foreach ($columns as $column)
                                <td>{{ $item->valueFor($column['key']) ?: '-' }}</td>
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($columns) }}">Belum ada data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endforeach

    <div class="footer">
        Dokumen ini dibuat otomatis dari situs resmi {{ $identity->name }}.
    </div>
</body>
</html>
