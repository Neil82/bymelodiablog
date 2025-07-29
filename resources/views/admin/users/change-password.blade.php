@extends('layouts.admin')

@section('title', __('admin.users.change_password'))

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ __('admin.users.change_password') }}</h2>
        </div>

        <form action="{{ route('admin.users.update-password') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <!-- Current Password -->
            <div>
                <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('admin.users.current_password') }} *
                </label>
                <input type="password" 
                       id="current_password" 
                       name="current_password" 
                       required
                       autocomplete="current-password"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                @error('current_password')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- New Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('admin.users.new_password') }} *
                </label>
                <input type="password" 
                       id="password" 
                       name="password" 
                       required
                       autocomplete="new-password"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                @error('password')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm New Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('admin.users.password_confirmation') }} *
                </label>
                <input type="password" 
                       id="password_confirmation" 
                       name="password_confirmation" 
                       required
                       autocomplete="new-password"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                @error('password_confirmation')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Requirements -->
            <div class="bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-md p-4">
                <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Requisitos de contraseña:</h4>
                <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                    <li>• Mínimo 8 caracteres</li>
                    <li>• Al menos una letra mayúscula</li>
                    <li>• Al menos una letra minúscula</li>
                    <li>• Al menos un número</li>
                    <li>• Al menos un carácter especial</li>
                </ul>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('admin.dashboard') }}" 
                   class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition-colors">
                    {{ __('admin.general.cancel') }}
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                    {{ __('admin.users.change_password') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection