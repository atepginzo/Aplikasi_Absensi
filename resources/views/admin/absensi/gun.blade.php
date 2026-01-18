<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.4em] text-slate-500">Mode Scanner</p>
                <h2 class="mt-1 text-3xl font-semibold text-white">Gun Scanner (Alat)</h2>
                <p class="text-sm text-slate-400">Tempelkan alat scanner ke input di bawah, sistem siap setiap saat.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid gap-6 lg:grid-cols-3">
                <div class="lg:col-span-2 space-y-6">
                    <div class="rounded-3xl border border-slate-800 bg-slate-900/70 p-8 shadow-2xl shadow-black/30">
                        <label for="scanner-input" class="text-sm font-semibold text-slate-300">Input Token QR</label>
                        <div class="mt-3 rounded-2xl border border-slate-800 bg-slate-950/80 p-4 focus-within:border-sky-500/60">
                            <input id="scanner-input" type="text" inputmode="none" autocomplete="off" autofocus placeholder="Fokus otomatis. Tembakkan Gun Scanner di sini" class="w-full bg-transparent text-3xl font-semibold text-slate-50 placeholder-slate-600 focus:outline-none" />
                        </div>
                        <p class="mt-3 text-xs text-slate-500">Scanner bekerja layaknya keyboard. Pastikan kursor tetap berada di kotak input.</p>
                        <div id="scan-toast" class="mt-4 hidden rounded-2xl border px-4 py-3 text-sm font-semibold"></div>
                        <div id="scan-result" class="mt-6 rounded-3xl border border-slate-800 bg-slate-950/70 p-6 space-y-4">
                            <span id="result-badge" class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold tracking-wide text-slate-400 border-slate-700">Menunggu scan...</span>
                            <div>
                                <h3 id="result-name" class="text-2xl font-bold text-white">Belum ada data</h3>
                                <p id="result-class" class="text-sm text-slate-400">Scan QR siswa untuk menampilkan detail.</p>
                            </div>
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                                <div>
                                    <p class="text-xs uppercase tracking-wide text-slate-500">Jam Masuk</p>
                                    <p id="result-time" class="text-lg font-semibold text-slate-100">-</p>
                                </div>
                                <div>
                                    <p class="text-xs uppercase tracking-wide text-slate-500">Tanggal</p>
                                    <p id="result-date" class="text-lg font-semibold text-slate-100">-</p>
                                </div>
                                <div>
                                    <p class="text-xs uppercase tracking-wide text-slate-500">Status</p>
                                    <p id="result-status" class="text-lg font-semibold text-slate-100">Menunggu</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-3xl border border-slate-800 bg-slate-900/70 p-6 shadow-2xl shadow-black/30 flex flex-col">
                    <div class="mb-6">
                        <h3 class="text-xl font-semibold text-white">5 Riwayat Scan Terakhir</h3>
                        <p class="text-sm text-slate-500">Diputakhirkan secara otomatis.</p>
                    </div>
                    <div id="history-list" class="space-y-4 text-sm text-slate-400">
                        <p class="text-slate-500">Belum ada riwayat.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const scannerInput = document.getElementById('scanner-input');
            const toast = document.getElementById('scan-toast');
            const resultName = document.getElementById('result-name');
            const resultClass = document.getElementById('result-class');
            const resultTime = document.getElementById('result-time');
            const resultDate = document.getElementById('result-date');
            const resultStatus = document.getElementById('result-status');
            const resultBadge = document.getElementById('result-badge');
            const historyList = document.getElementById('history-list');
            const fetchUrl = @json(route('admin.absensi.store'));
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const historyLimit = 5;
            let toastTimer = null;
            let isSubmitting = false;
            let cooldownTimer = null;
            const COOLDOWN_DURATION = 3000; // 3 detik cooldown
            const history = [];

            // Wrapper input container
            const inputContainer = scannerInput.parentElement;

            const enforceFocus = () => {
                if (!scannerInput.disabled) {
                    scannerInput.focus();
                }
            };

            const setCooldownState = (active) => {
                if (active) {
                    // Aktifkan cooldown
                    scannerInput.disabled = true;
                    scannerInput.placeholder = 'Tunggu sebentar...';
                    inputContainer.classList.remove('border-slate-800', 'focus-within:border-sky-500/60');
                    inputContainer.classList.add('border-amber-500/60', 'bg-amber-500/5');
                    scannerInput.classList.add('text-amber-400');
                } else {
                    // Nonaktifkan cooldown
                    scannerInput.disabled = false;
                    scannerInput.placeholder = 'Fokus otomatis. Tembakkan Gun Scanner di sini';
                    inputContainer.classList.remove('border-amber-500/60', 'bg-amber-500/5');
                    inputContainer.classList.add('border-slate-800', 'focus-within:border-sky-500/60');
                    scannerInput.classList.remove('text-amber-400');
                    enforceFocus();
                }
            };

            document.addEventListener('click', (event) => {
                if (event.target !== scannerInput && !scannerInput.disabled) {
                    setTimeout(enforceFocus, 60);
                }
            });

            scannerInput.addEventListener('blur', () => {
                if (!scannerInput.disabled) {
                    setTimeout(enforceFocus, 50);
                }
            });

            const playBeep = (tone = 'success') => {
                const ctxClass = window.AudioContext || window.webkitAudioContext;
                if (!ctxClass) {
                    return;
                }
                const ctx = new ctxClass();
                const oscillator = ctx.createOscillator();
                const gainNode = ctx.createGain();
                oscillator.connect(gainNode);
                gainNode.connect(ctx.destination);
                oscillator.type = 'triangle';
                const freqMap = { success: 880, warning: 660, error: 440 };
                oscillator.frequency.value = freqMap[tone] || 660;
                const now = ctx.currentTime;
                gainNode.gain.setValueAtTime(0.25, now);
                gainNode.gain.exponentialRampToValueAtTime(0.0001, now + 0.3);
                oscillator.start(now);
                oscillator.stop(now + 0.3);
            };

            const showToast = (type, message) => {
                const colorMap = {
                    success: 'border-emerald-500/40 bg-emerald-500/10 text-emerald-200',
                    warning: 'border-amber-500/40 bg-amber-500/10 text-amber-200',
                    error: 'border-rose-500/40 bg-rose-500/10 text-rose-200',
                    info: 'border-slate-600 bg-slate-800 text-slate-200'
                };
                toast.className = `mt-4 rounded-2xl border px-4 py-3 text-sm font-semibold ${colorMap[type] || colorMap.info}`;
                toast.textContent = message;
                toast.classList.remove('hidden');
                toast.classList.remove('opacity-0');
                if (toastTimer) {
                    clearTimeout(toastTimer);
                }
                toastTimer = setTimeout(() => {
                    toast.classList.add('opacity-0');
                    setTimeout(() => toast.classList.add('hidden'), 300);
                }, 2200);
            };

            const updateResultCard = (type, payload = {}) => {
                const badgeMap = {
                    success: 'border-emerald-400/50 text-emerald-300 bg-emerald-500/10',
                    warning: 'border-amber-400/50 text-amber-300 bg-amber-500/10',
                    error: 'border-rose-400/50 text-rose-300 bg-rose-500/10',
                    info: 'border-slate-600 text-slate-300 bg-slate-800/60'
                };
                resultBadge.className = `inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold tracking-wide ${badgeMap[type] || badgeMap.info}`;
                resultBadge.textContent = payload.badgeText || 'Menunggu scan...';
                resultName.textContent = payload.nama || 'Belum ada data';
                resultClass.textContent = payload.kelas || 'Scan QR siswa untuk menampilkan detail.';
                resultTime.textContent = payload.jam || '-';
                resultDate.textContent = payload.tanggal || '-';
                resultStatus.textContent = payload.statusLabel || 'Menunggu';
            };

            const renderHistory = () => {
                historyList.innerHTML = '';
                if (history.length === 0) {
                    const emptyState = document.createElement('p');
                    emptyState.className = 'text-slate-500';
                    emptyState.textContent = 'Belum ada riwayat.';
                    historyList.appendChild(emptyState);
                    return;
                }
                history.forEach((item) => {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'rounded-2xl border border-slate-800 bg-slate-950/60 p-4';

                    const topRow = document.createElement('div');
                    topRow.className = 'flex items-center justify-between';

                    const nameEl = document.createElement('p');
                    nameEl.className = 'text-base font-semibold text-slate-100';
                    nameEl.textContent = item.nama;

                    const badge = document.createElement('span');
                    badge.className = `text-xs font-semibold uppercase tracking-wide ${item.statusClass}`;
                    badge.textContent = item.statusLabel;

                    topRow.appendChild(nameEl);
                    topRow.appendChild(badge);

                    const classEl = document.createElement('p');
                    classEl.className = 'text-xs text-slate-400 mt-1';
                    classEl.textContent = item.kelas;

                    const metaEl = document.createElement('p');
                    metaEl.className = 'text-sm text-slate-300 mt-2';
                    metaEl.textContent = `${item.jam} • ${item.tanggal}`;

                    wrapper.appendChild(topRow);
                    wrapper.appendChild(classEl);
                    wrapper.appendChild(metaEl);
                    historyList.appendChild(wrapper);
                });
            };

            const pushHistory = (entry) => {
                history.unshift(entry);
                if (history.length > historyLimit) {
                    history.pop();
                }
                renderHistory();
            };

            const handleResponse = (type, data) => {
                const siswa = data.siswa || {};
                const kelas = siswa.kelas ? siswa.kelas.nama_kelas : 'Kelas tidak tersedia';
                const jam = data.jam_masuk || new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                const tanggal = data.tanggal || new Date().toISOString().split('T')[0];
                const statusLabel = type === 'success' ? 'Hadir' : (type === 'warning' ? 'Duplikat' : 'Gagal');
                const badgeText = type === 'success' ? 'Absensi Tercatat' : (type === 'warning' ? 'Sudah Absen' : 'Scan Bermasalah');
                updateResultCard(type, {
                    badgeText,
                    nama: siswa.nama_siswa || 'Tidak diketahui',
                    kelas,
                    jam,
                    tanggal,
                    statusLabel
                });
                const statusClassMap = {
                    success: 'text-emerald-300',
                    warning: 'text-amber-300',
                    error: 'text-rose-300'
                };
                pushHistory({
                    nama: siswa.nama_siswa || 'Tidak diketahui',
                    kelas,
                    jam,
                    tanggal,
                    statusLabel,
                    statusClass: statusClassMap[type] || 'text-slate-300'
                });
                showToast(type, data.message || 'Proses selesai.');
                playBeep(type);
            };

            const handleSubmit = async () => {
                const token = scannerInput.value.trim();
                if (!token || isSubmitting) {
                    return;
                }
                isSubmitting = true;
                
                // Aktifkan cooldown state segera setelah scan
                setCooldownState(true);
                
                try {
                    const response = await fetch(fetchUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ qrcode_token: token })
                    });
                    let data = {};
                    try {
                        data = await response.json();
                    } catch (error) {
                        data = { message: 'Respon tidak valid.' };
                    }
                    const type = response.ok ? (data.status || 'success') : 'error';
                    handleResponse(type, data);
                } catch (error) {
                    handleResponse('error', { message: 'Jaringan bermasalah. Coba lagi.' });
                } finally {
                    scannerInput.value = '';
                    isSubmitting = false;
                    
                    // Clear existing cooldown timer jika ada
                    if (cooldownTimer) {
                        clearTimeout(cooldownTimer);
                    }
                    
                    // Set cooldown timer - 3 detik sebelum bisa scan lagi
                    cooldownTimer = setTimeout(() => {
                        setCooldownState(false);
                    }, COOLDOWN_DURATION);
                }
            };

            scannerInput.addEventListener('keydown', (event) => {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    handleSubmit();
                }
            });

            enforceFocus();
            renderHistory();
        });
    </script>
</x-app-layout>
