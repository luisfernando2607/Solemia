<x-app-layout>
    <div class="max-w-3xl mx-auto space-y-6">
        <div class="card">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-olive-100 flex items-center justify-center">
                        <i class="fas fa-user text-olive-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Información del perfil</h3>
                        <p class="text-sm text-gray-500">Actualiza tu nombre y correo electrónico.</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <livewire:profile.update-profile-information-form />
            </div>
        </div>

        <div class="card">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gold-100 flex items-center justify-center">
                        <i class="fas fa-lock text-gold-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Actualizar contraseña</h3>
                        <p class="text-sm text-gray-500">Cambia tu contraseña de acceso.</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <livewire:profile.update-password-form />
            </div>
        </div>

        <div class="card border-red-200">
            <div class="p-6 border-b border-red-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center">
                        <i class="fas fa-trash text-red-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-red-900">Eliminar cuenta</h3>
                        <p class="text-sm text-red-500">Una vez eliminada, no se puede recuperar.</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <livewire:profile.delete-user-form />
            </div>
        </div>
    </div>
</x-app-layout>
