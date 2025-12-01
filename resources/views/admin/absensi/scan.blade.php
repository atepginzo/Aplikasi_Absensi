<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-slate-100 leading-tight">
                    {{ __('Scanner Absensi') }}
                </h2>
                <p class="mt-1 text-sm text-slate-400">Scan QR Code siswa untuk absensi</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- KIRI: Area Scanner dengan Native App Look --}}
                <div class="lg:col-span-2">
                    <div class="bg-slate-900/80 backdrop-blur-sm rounded-2xl border border-slate-800 overflow-hidden shadow-xl">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-lg font-bold text-white">Kamera Scanner</h3>
                                <div class="flex items-center space-x-2">
                                    <div class="w-3 h-3 rounded-full bg-red-500 animate-pulse"></div>
                                    <span class="text-xs font-medium text-slate-400">Live</span>
                                </div>
                            </div>
                            
                            {{-- Scanner Container dengan Native App Styling --}}
                            <div class="relative bg-black rounded-2xl overflow-hidden shadow-2xl" style="aspect-ratio: 1;">
                                {{-- Overlay dengan Scanning Frame --}}
                                <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                    {{-- Scanning Frame --}}
                                    <div class="relative w-64 h-64">
                                        {{-- Corner Borders --}}
                                        <div class="absolute top-0 left-0 w-12 h-12 border-t-4 border-l-4 border-blue-400 rounded-tl-2xl"></div>
                                        <div class="absolute top-0 right-0 w-12 h-12 border-t-4 border-r-4 border-blue-400 rounded-tr-2xl"></div>
                                        <div class="absolute bottom-0 left-0 w-12 h-12 border-b-4 border-l-4 border-blue-400 rounded-bl-2xl"></div>
                                        <div class="absolute bottom-0 right-0 w-12 h-12 border-b-4 border-r-4 border-blue-400 rounded-br-2xl"></div>
                                        
                                        {{-- Scanning Line Animation --}}
                                        <div class="absolute inset-0 overflow-hidden">
                                            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-transparent via-blue-400 to-transparent animate-pulse" style="animation: scan 2s linear infinite;"></div>
                                        </div>
                                    </div>
                                    
                                    {{-- Overlay Dark Areas --}}
                                    <div class="absolute inset-0 bg-black bg-opacity-40">
                                        <svg class="w-full h-full">
                                            <defs>
                                                <mask id="scanner-mask">
                                                    <rect width="100%" height="100%" fill="black"/>
                                                    <rect x="50%" y="50%" width="256" height="256" fill="white" rx="16" transform="translate(-128, -128)"/>
                                                </mask>
                                            </defs>
                                            <rect width="100%" height="100%" fill="black" opacity="0.5" mask="url(#scanner-mask)"/>
                                        </svg>
                                    </div>
                                </div>
                                
                                {{-- Actual Scanner --}}
                                <div id="reader" class="w-full h-full"></div>
                                
                                {{-- Instructions Overlay --}}
                                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-6">
                                    <p class="text-white text-sm text-center font-medium">
                                        Arahkan QR Code siswa ke dalam frame
                                    </p>
                                </div>
                            </div>

                            {{-- Loading Indicator --}}
                            <div id="loading-indicator" class="hidden mt-4">
                                <div class="flex items-center justify-center space-x-2 p-4 bg-blue-500/20 border border-blue-500/30 rounded-xl backdrop-blur-sm">
                                    <svg class="animate-spin h-5 w-5 text-blue-400" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span class="text-sm font-semibold text-blue-300">Memproses scan...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- KANAN: Log Hasil Scan --}}
                <div class="lg:col-span-1">
                    <div class="bg-slate-900/80 backdrop-blur-sm rounded-2xl border border-slate-800 overflow-hidden shadow-xl sticky top-6">
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-white mb-4 pb-4 border-b border-slate-800">Hasil Scan Terakhir</h3>
                            
                            {{-- Result Container --}}
                            <div id="result-container" class="hidden mb-6">
                                <div id="result-card" class="p-5 rounded-xl border-2 transition-all duration-300">
                                    <div class="flex items-center mb-3">
                                        <div id="result-icon" class="w-10 h-10 rounded-full flex items-center justify-center mr-3">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                        <h4 id="result-title" class="text-lg font-bold"></h4>
                                    </div>
                                    <p id="result-message" class="text-sm mb-2"></p>
                                    <p id="result-time" class="text-xs opacity-75"></p>
                                </div>
                            </div>

                            {{-- Empty State --}}
                            <div id="empty-state" class="text-center py-8">
                                <svg class="w-16 h-16 text-slate-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                                </svg>
                                <p class="text-sm text-slate-400">Belum ada hasil scan</p>
                            </div>

                            {{-- Tips Section --}}
                            <div class="mt-8 p-4 bg-slate-800/50 rounded-xl border border-slate-700 backdrop-blur-sm">
                                <h4 class="text-sm font-semibold text-white mb-3 flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Tips Scanning
                                </h4>
                                <ul class="text-xs text-slate-300 space-y-2">
                                    <li class="flex items-start">
                                        <span class="text-blue-400 mr-2">•</span>
                                        Pastikan pencahayaan cukup
                                    </li>
                                    <li class="flex items-start">
                                        <span class="text-blue-400 mr-2">•</span>
                                        Jaga jarak QR Code 10-20 cm dari kamera
                                    </li>
                                    <li class="flex items-start">
                                        <span class="text-blue-400 mr-2">•</span>
                                        Gunakan browser Chrome/Firefox/Edge terbaru
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    {{-- AUDIO: Suara Beep saat sukses --}}
    <audio id="scan-sound" src="https://assets.mixkit.co/active_storage/sfx/2578/2578-preview.m4a"></audio>

    {{-- SCRIPT SCANNER --}}
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <style>
        @keyframes scan {
            0% { transform: translateY(0); opacity: 1; }
            50% { opacity: 0.5; }
            100% { transform: translateY(256px); opacity: 1; }
        }
    </style>

    <script>
        // Konfigurasi CSRF Token untuk AJAX Request
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const scanSound = document.getElementById('scan-sound');
        
        // Variabel untuk mencegah scan beruntun (debounce)
        let isProcessing = false;

        function onScanSuccess(decodedText, decodedResult) {
            // Jika sedang memproses, abaikan scan baru
            if (isProcessing) return;

            isProcessing = true;
            document.getElementById('loading-indicator').classList.remove('hidden');
            document.getElementById('empty-state').classList.add('hidden');
            
            // Mainkan suara beep
            scanSound.play().catch(e => console.log('Audio play failed:', e));

            console.log(`Scan result: ${decodedText}`);

            // KIRIM DATA KE SERVER VIA FETCH API
            fetch("{{ route('admin.absensi.store') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken
                },
                body: JSON.stringify({
                    qrcode_token: decodedText
                })
            })
            .then(response => response.json())
            .then(data => {
                showResult(data);
            })
            .catch(error => {
                console.error('Error:', error);
                showResult({
                    status: 'error',
                    message: 'Terjadi kesalahan sistem.'
                });
            })
            .finally(() => {
                // Beri jeda 2 detik sebelum bisa scan lagi
                setTimeout(() => {
                    isProcessing = false;
                    document.getElementById('loading-indicator').classList.add('hidden');
                }, 2000);
            });
        }

        function onScanFailure(error) {
            // handle scan failure, usually better to ignore and keep scanning.
            // console.warn(`Code scan error = ${error}`);
        }

        function showResult(data) {
            const container = document.getElementById('result-container');
            const card = document.getElementById('result-card');
            const icon = document.getElementById('result-icon');
            const title = document.getElementById('result-title');
            const message = document.getElementById('result-message');
            const time = document.getElementById('result-time');

            container.classList.remove('hidden');
            
            // Reset classes
            card.className = 'p-5 rounded-xl border-2 transition-all duration-300';
            icon.className = 'w-10 h-10 rounded-full flex items-center justify-center mr-3';

            if (data.status === 'success') {
                card.classList.add('bg-emerald-500/20', 'border-emerald-500/30', 'backdrop-blur-sm');
                icon.classList.add('bg-emerald-500', 'text-white');
                icon.innerHTML = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
                title.textContent = 'BERHASIL!';
                title.className = 'text-lg font-bold text-emerald-300';
                message.textContent = data.message;
                message.className = 'text-sm mb-2 text-emerald-200';
            } else if (data.status === 'warning') {
                card.classList.add('bg-amber-500/20', 'border-amber-500/30', 'backdrop-blur-sm');
                icon.classList.add('bg-amber-500', 'text-white');
                icon.innerHTML = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>';
                title.textContent = 'SUDAH ABSEN';
                title.className = 'text-lg font-bold text-amber-300';
                message.textContent = data.message;
                message.className = 'text-sm mb-2 text-amber-200';
            } else {
                card.classList.add('bg-red-500/20', 'border-red-500/30', 'backdrop-blur-sm');
                icon.classList.add('bg-red-500', 'text-white');
                icon.innerHTML = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
                title.textContent = 'GAGAL!';
                title.className = 'text-lg font-bold text-red-300';
                message.textContent = data.message;
                message.className = 'text-sm mb-2 text-red-200';
            }

            time.textContent = new Date().toLocaleTimeString('id-ID');
            time.className = 'text-xs opacity-75 text-slate-400';
        }

        // INISIALISASI SCANNER
        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", 
            { 
                fps: 10, 
                qrbox: { width: 250, height: 250 },
                aspectRatio: 1.0,
                supportedScanTypes: [Html5QrcodeScanType.SCAN_TYPE_CAMERA]
            },
            /* verbose= */ false
        );
        
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    </script>
</x-app-layout>
