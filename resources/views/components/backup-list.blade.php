<div class="@if(count($disks) <= 1) py-8 @endif">
    @if(count($disks) > 1)
        <div class="py-3 flex items-center justify-end">
            <x-filament::input.wrapper>
                <x-filament::input.select wire:model.live="activeDisk">
                    @foreach($disks as $disk)
                        <option wire:key="{{ $disk }}" value="{{ $disk }}">{{ $disk }}</option>
                    @endforeach
                </x-filament::input.select>
            </x-filament::input.wrapper>
        </div>
    @endif
    @if(count($activeDiskBackups) > 0)
        <div class="overflow-hidden overflow-x-auto relative rounded-lg dark:bg-gray-900">
            <table class="fi-ta-ctn overflow-hidden rounded-xl shadow-sm ring-1 ring-gray-950/5 dark:divide-white/10 dark:bg-gray-900 dark:ring-white/10 w-full">
                <thead class="dark:bg-gray-800">
                <tr>
                    <th class="text-left px-2 whitespace-nowrap uppercase text-gray-950 dark:text-white text-sm tracking-wide px-2 py-2">
                        {{ __('Path') }}
                    </th>
                    <th class="text-left px-2 whitespace-nowrap uppercase text-gray-950 dark:text-white text-sm tracking-wide px-2 py-2">
                        {{ __('Created at') }}
                    </th>
                    <th class="text-left px-2 whitespace-nowrap uppercase text-gray-950 dark:text-white text-sm tracking-wide px-2 py-2">
                        {{ __('Size') }}
                    </th>
                    <th></th>
                </tr>
                </thead>
                <tbody class="whitespace-nowrap cursor-pointer dark:divide-white/5">
                @foreach($activeDiskBackups as $backup)
                    <tr wire:key="{{ $backup['path'] }}" class="hover:bg-gray-100">
                        <td class="px-2 py-2 dark:bg-gray-900 group-hover:bg-gray-50 dark:group-hover:bg-gray-900">{{ $backup['path'] }}</td>
                        <td class="px-2 py-2 dark:bg-gray-900 group-hover:bg-gray-50 dark:group-hover:bg-gray-900">{{ $backup['date'] }}</td>
                        <td class="px-2 py-2 dark:bg-gray-900 group-hover:bg-gray-50 dark:group-hover:bg-gray-900">{{ $backup['size'] }}</td>
                        <td class="px-2 py-2 dark:bg-gray-900 group-hover:bg-gray-50 dark:group-hover:bg-gray-900 text-right">
                            {{ ($this->downloadAction)(['disk' => $activeDisk, 'path' => $backup['path']]) }}
                            {{ ($this->deleteAction)(['disk' => $activeDisk, 'path' => $backup['path'], 'date' => $backup['date']]) }}
                        </td>
                    </tr>
                @endforeach
                @if(count($activeDiskBackups) === 0)
                    <tr>
                        <td class="text-center px-2 py-2" colspan="4">
                            {{ __('No backups present') }}
                        </td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    @endif

    <x-filament-actions::modals/>
</div>
