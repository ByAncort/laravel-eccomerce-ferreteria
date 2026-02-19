<x-app-layout>
    <div class="bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10 lg:py-12">

            {{-- Header --}}
            <div class="mb-6 sm:mb-8 flex items-center gap-4">
                <a href="{{ route('vendors.index') }}"
                   class="w-9 h-9 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 flex items-center justify-center text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 12H5M12 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl sm:text-4xl text-gray-900 dark:text-white">Editar Proveedor</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ $vendor->name }}</p>
                </div>
            </div>

            {{-- Alerts --}}
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg text-green-700 dark:text-green-400 text-sm font-medium flex items-center gap-2">
                    <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M20 6L9 17l-5-5"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg text-red-700 dark:text-red-400 text-sm font-medium flex items-center gap-2">
                    <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="12"/>
                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    Por favor corrige los errores en el formulario.
                </div>
            @endif

            {{-- Form Card --}}
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">

                {{-- Card Header with vendor avatar --}}
                <div class="p-5 sm:p-6 border-b border-gray-200 dark:border-gray-700 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-200 to-blue-300 dark:from-blue-800 dark:to-blue-600 flex items-center justify-center font-bold text-sm text-blue-700 dark:text-blue-200 uppercase flex-shrink-0">
                        {{ substr($vendor->name, 0, 2) }}
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Proveedor #{{ $vendor->id }}</p>
                        <p class="text-sm text-gray-700 dark:text-gray-300 font-medium mt-0.5">Creado {{ $vendor->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="ml-auto">
                        @if($vendor->status === 'active')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-600 dark:bg-green-400"></span>
                                Activo
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-400">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-600 dark:bg-red-400"></span>
                                Inactivo
                            </span>
                        @endif
                    </div>
                </div>

                <form action="{{ route('vendors.update', $vendor->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="p-5 sm:p-6 space-y-5">

                        {{-- Nombre --}}
                        <div>
                            <label for="name" class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">
                                Nombre completo *
                            </label>
                            <input type="text" name="name" id="name" required
                                value="{{ old('name', $vendor->name) }}"
                                placeholder="Ej. María González"
                                class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 outline-none transition {{ $errors->has('name') ? 'border-red-500 dark:border-red-500' : '' }}">
                            @error('name')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- RUN --}}
                        <div>
                            <label for="run" class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">
                                RUN *
                            </label>
                            <input type="text" name="run" id="run" required
                                value="{{ old('run', $vendor->run) }}"
                                placeholder="Ej. 12.345.678-9"
                                class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 outline-none transition {{ $errors->has('run') ? 'border-red-500 dark:border-red-500' : '' }}">
                            @error('run')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email y Teléfono --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="email" class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">
                                    Email *
                                </label>
                                <input type="email" name="email" id="email" required
                                    value="{{ old('email', $vendor->email) }}"
                                    placeholder="correo@ejemplo.com"
                                    class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 outline-none transition {{ $errors->has('email') ? 'border-red-500 dark:border-red-500' : '' }}">
                                @error('email')
                                    <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="phone" class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">
                                    Teléfono
                                </label>
                                <input type="text" name="phone" id="phone"
                                    value="{{ old('phone', $vendor->phone) }}"
                                    placeholder="+56 9 1234 5678"
                                    class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 outline-none transition {{ $errors->has('phone') ? 'border-red-500 dark:border-red-500' : '' }}">
                                @error('phone')
                                    <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Estado --}}
                        <div>
                            <label for="status" class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">
                                Estado *
                            </label>
                            <select name="status" id="status" required
                                class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 outline-none transition">
                                <option value="active" {{ old('status', $vendor->status) === 'active' ? 'selected' : '' }}>Activo</option>
                                <option value="inactive" {{ old('status', $vendor->status) === 'inactive' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Dirección --}}
                        <div>
                            <label for="address" class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">
                                Dirección
                            </label>
                            <input type="text" name="address" id="address"
                                value="{{ old('address', $vendor->address) }}"
                                placeholder="Av. Providencia 123"
                                class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 outline-none transition">
                            @error('address')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Ciudad y País --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="city" class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">
                                    Ciudad
                                </label>
                                <input type="text" name="city" id="city"
                                    value="{{ old('city', $vendor->city) }}"
                                    placeholder="Santiago"
                                    class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 outline-none transition">
                                @error('city')
                                    <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="country" class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">
                                    País
                                </label>
                                <input type="text" name="country" id="country"
                                    value="{{ old('country', $vendor->country) }}"
                                    placeholder="Chile"
                                    class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 outline-none transition">
                                @error('country')
                                    <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                    </div>

                    {{-- Footer --}}
                    <div class="flex items-center justify-between gap-3 px-5 sm:px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/30">
                        <a href="{{ route('vendors.index') }}"
                           class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            Cancelar
                        </a>
                        <button type="submit"
                            class="px-5 py-2 bg-gray-900 dark:bg-white hover:bg-gray-800 dark:hover:bg-gray-100 text-white dark:text-gray-900 text-sm font-semibold rounded-lg transition active:scale-[0.99]">
                            Guardar cambios
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>