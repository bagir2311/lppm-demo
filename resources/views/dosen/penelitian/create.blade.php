<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pengajuan Proposal - LPPM Mahardika</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">

    <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Form Pengajuan Proposal</h2>

        <!-- Menampilkan pesan error validasi jika ada -->
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('penelitian.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <!-- Judul Proposal -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Judul Proposal</label>
                <input type="text" name="judul" required 
                    class="w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- Jenis Usulan -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Usulan</label>
                <select name="jenis" required 
                    class="w-full border border-gray-300 rounded-md p-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="Penelitian">Penelitian</option>
                    <option value="Pengabdian">Pengabdian Masyarakat</option>
                </select>
            </div>

            <!-- Upload File -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Unggah File Proposal (PDF, Maks 5MB)</label>
                <input type="file" name="file_proposal" accept=".pdf" required 
                    class="w-full border border-gray-300 rounded-md p-2 bg-gray-50 text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            </div>

            <!-- Tombol Submit -->
            <div class="pt-4">
                <button type="submit" 
                    class="w-full bg-blue-600 text-white font-bold py-2 px-4 rounded-md hover:bg-blue-700 transition duration-300">
                    Ajukan Proposal
                </button>
            </div>
        </form>
    </div>

</body>
</html>