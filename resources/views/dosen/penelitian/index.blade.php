<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Dosen - LPPM Mahardika</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
        
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Riwayat Pengajuan Proposal</h2>
            <!-- Tombol yang mengarah ke form pengajuan yang sudah kita buat -->
            <a href="{{ url('/penelitian/pengajuan') }}" class="bg-blue-600 text-white font-bold py-2 px-4 rounded-md hover:bg-blue-700 transition duration-300">
                + Ajukan Proposal Baru
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full border-collapse border border-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="border border-gray-300 px-4 py-2 text-left text-sm font-semibold text-gray-700">Judul Proposal</th>
                        <th class="border border-gray-300 px-4 py-2 text-left text-sm font-semibold text-gray-700">Jenis</th>
                        <th class="border border-gray-300 px-4 py-2 text-left text-sm font-semibold text-gray-700">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($proposals as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="border border-gray-300 px-4 py-2 text-sm text-gray-800">{{ $item->judul }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-sm text-gray-600">{{ $item->jenis }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-sm">
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium uppercase tracking-wide">
                                    {{ $item->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="border border-gray-300 px-4 py-8 text-center text-gray-500 italic">
                                Belum ada proposal yang diajukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</body>
</html>