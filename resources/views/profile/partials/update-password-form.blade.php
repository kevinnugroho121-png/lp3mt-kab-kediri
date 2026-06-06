<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')




        
        <div>
            <label for="current_password" class="block text-sm font-bold text-gray-700">Password Saat Ini</label>
            <div class="relative mt-1">
                <input id="current_password" name="current_password" type="password" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 pr-10" autocomplete="current-password" />
                <button type="button" onclick="togglePassword('current_password', 'eye_current')" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-400 hover:text-blue-600 transition focus:outline-none">
                    <svg id="eye_current" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 text-red-600 font-bold text-sm" />
        </div>

        <div>
            <label for="password" class="block text-sm font-bold text-gray-700">Password Baru</label>
            <div class="relative mt-1">
                <input id="password" name="password" type="password" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 pr-10" autocomplete="new-password" />
                <button type="button" onclick="togglePassword('password', 'eye_new')" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-400 hover:text-blue-600 transition focus:outline-none">
                    <svg id="eye_new" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-red-600 font-bold text-sm" />
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-bold text-gray-700">Konfirmasi Password Baru</label>
            <div class="relative mt-1">
                <input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 pr-10" autocomplete="new-password" />
                <button type="button" onclick="togglePassword('password_confirmation', 'eye_confirm')" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-400 hover:text-blue-600 transition focus:outline-none">
                    <svg id="eye_confirm" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 text-red-600 font-bold text-sm" />
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-lg transition shadow-sm">
                Simpan Password Baru
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-sm font-bold text-green-600 bg-green-100 border border-green-200 px-3 py-1.5 rounded-md">
                    ✓ Berhasil Diperbarui
                </p>
            @endif
        </div>

    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            
            if (input.type === 'password') {
                input.type = 'text';
                // Ganti SVG menjadi ikon mata dicoret (eye-off)
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.29 3.29m0 0l3.29 3.29m0 0l-3.29-3.29m0 0L3 3m18 18l-3.29-3.29m0 0l-3.29-3.29m0 0l3.29 3.29m0 0L21 21"></path>';
            } else {
                input.type = 'password';
                // Ganti SVG menjadi ikon mata terbuka (eye)
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
            }
        }
    </script>




    </form>
</section>
