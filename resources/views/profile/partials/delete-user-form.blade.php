
    <button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="px-4 py-2 text-sm font-medium text-red-500 hover:text-red-400 transition-colors border border-red-500 hover:border-red-400 rounded-full"
    >
        {{ __('Hapus Akun') }}
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 bg-slate-900 rounded-2xl">
            @csrf
            @method('delete')

            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-500/10">
                    <svg class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                </div>
                <h2 class="mt-3 text-lg font-semibold text-slate-50">
                    {{ __('Apakah Anda yakin ingin menghapus akun?') }}
                </h2>

                <p class="mt-2 text-sm text-slate-400">
                    {{ __('Setelah akun Anda dihapus, semua data akan dihapus secara permanen. Masukkan password Anda untuk mengonfirmasi penghapusan akun.') }}
                </p>
            </div>

            <div class="mt-6">
                <x-input-label for="password" :value="__('Password')" class="text-slate-300" />
                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-full bg-slate-900 border-slate-700 text-slate-50 placeholder-slate-500 focus:ring-2 focus:ring-sky-600 focus:border-sky-600"
                    placeholder="••••••••"
                />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-1 text-sm text-red-500" />
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <button 
                    type="button" 
                    x-on:click="$dispatch('close')"
                    class="px-4 py-2 text-sm font-medium text-slate-300 bg-slate-800 border border-slate-700 rounded-full hover:bg-slate-700 transition-colors"
                >
                    {{ __('Batal') }}
                </button>

                <button 
                    type="submit"
                    class="px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-red-600 to-red-500 rounded-full hover:from-red-500 hover:to-red-400 transition-colors"
                >
                    {{ __('Ya, Hapus Akun') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>
