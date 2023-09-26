<div>
    <table class="table-default w-full">
        <thead class="bg-gray-50 dark:bg-gray-800 rounded-t-lg">
        <tr>
            <th class="text-left px-2 whitespace-nowrap uppercase text-gray-950 dark:text-white text-sm tracking-wide px-2 py-2">
                {{ __('Name') }}
            </th>
            <th class="text-left px-2 whitespace-nowrap uppercase text-gray-950 dark:text-white text-sm tracking-wide px-2 py-2">
                {{ __('Disk') }}
            </th>
            <th class="text-left px-2 whitespace-nowrap uppercase text-gray-950 dark:text-white text-sm tracking-wide px-2 py-2">
                {{ __('Healthy') }}
            </th>
            <th class="text-left px-2 whitespace-nowrap uppercase text-gray-950 dark:text-white text-sm tracking-wide px-2 py-2">
                {{ __('Amount of backups') }}
            </th>
            <th class="text-left px-2 whitespace-nowrap uppercase text-gray-950 dark:text-white text-sm tracking-wide px-2 py-2">
                {{ __('Newest backup') }}
            </th>
            <th class="text-left px-2 whitespace-nowrap uppercase text-gray-950 dark:text-white text-sm tracking-wide px-2 py-2">
                {{ __('Used Storage') }}
            </th>
        </tr>
        </thead>
        <tbody class="whitespace-nowrap cursor-pointer dark:divide-white/5">
        @foreach($backupStatuses as $backupStatus)
            <tr wire:key="{{ $backupStatus['disk'] }}">
                <td class="px-2 py-2 dark:border-gray-700 whitespace-nowrap cursor-pointer dark:bg-gray-900 group-hover:bg-gray-50 dark:group-hover:bg-gray-900">{{ __($backupStatus['name']) }}</td>
                <td class="px-2 py-2 dark:border-gray-700 whitespace-nowrap cursor-pointer dark:bg-gray-900 group-hover:bg-gray-50 dark:group-hover:bg-gray-900">{{ __($backupStatus['disk']) }}</td>
                <td class="px-2 py-2 dark:border-gray-700 whitespace-nowrap cursor-pointer dark:bg-gray-900 group-hover:bg-gray-50 dark:group-hover:bg-gray-900">
                    <x-filament::icon
                            icon="heroicon-m-{{ $backupStatus['healthy'] ? 'check-circle' : 'x-circle' }}"
                            @class([
                                'fi-ta-icon-item',
                                'fi-ta-icon-item-size-md h-5 w-5',
                                match ('success') {
                                    'gray' => 'text-gray-400 dark:text-gray-500',
                                    default => 'text-custom-500 dark:text-custom-400',
                                },
                            ])
                            @style([
                                \Filament\Support\get_color_css_variables(
                                    $backupStatus['healthy'] ? 'success' : 'danger',
                                    shades: [400, 500],
                                ),
                            ])
                    />
                </td>
                <td class="px-2 py-2 dark:border-gray-700 whitespace-nowrap cursor-pointer dark:bg-gray-900 group-hover:bg-gray-50 dark:group-hover:bg-gray-900">{{ $backupStatus['amount'] }}</td>
                <td class="px-2 py-2 dark:border-gray-700 whitespace-nowrap cursor-pointer dark:bg-gray-900 group-hover:bg-gray-50 dark:group-hover:bg-gray-900">{{ $backupStatus['newest'] }}</td>
                <td class="px-2 py-2 dark:border-gray-700 whitespace-nowrap cursor-pointer dark:bg-gray-900 group-hover:bg-gray-50 dark:group-hover:bg-gray-900">{{ $backupStatus['usedStorage'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
