<?php

declare(strict_types=1);

?>
<x-filament-widgets::widget>
    <x-filament::section>
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    {{ __('employee::work_hours.weekly_summary') }}
                </h2>
                <span class="text-sm text-gray-500">
                    {{ $startOfWeek }} - {{ $endOfWeek }}
                </span>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <!-- Total Hours Card -->
                <div class="rounded-lg bg-white p-4 shadow dark:bg-gray-800">
                    <div class="flex items-center">
                        <div class="rounded-md bg-primary-100 p-3 dark:bg-primary-900">
                            <x-heroicon-o-clock class="h-6 w-6 text-primary-600 dark:text-primary-400" />
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                {{ __('employee::work_hours.total_hours') }}
                            </p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                {{ number_format($totalHours, 2) }} {{ __('employee::work_hours.hours') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Days Worked Card -->
                <div class="rounded-lg bg-white p-4 shadow dark:bg-gray-800">
                    <div class="flex items-center">
                        <div class="rounded-md bg-green-100 p-3 dark:bg-green-900">
                            <x-heroicon-o-calendar class="h-6 w-6 text-green-600 dark:text-green-400" />
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                {{ __('employee::work_hours.days_worked') }}
                            </p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                {{ $daysWorked }} {{ __('employee::work_hours.days') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Average Hours Per Day Card -->
                <div class="rounded-lg bg-white p-4 shadow dark:bg-gray-800">
                    <div class="flex items-center">
                        <div class="rounded-md bg-blue-100 p-3 dark:bg-blue-900">
                            <x-heroicon-o-scale class="h-6 w-6 text-blue-600 dark:text-blue-400" />
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                {{ __('employee::work_hours.avg_hours_per_day') }}
                            </p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                {{ number_format($averageHoursPerDay, 2) }} {{ __('employee::work_hours.hours') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daily Breakdown -->
            <div class="mt-6">
                <h3 class="mb-3 text-base font-medium text-gray-900 dark:text-gray-100">
                    {{ __('employee::work_hours.daily_breakdown') }}
                </h3>
                <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow dark:border-gray-700 dark:bg-gray-800">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                    {{ __('employee::work_hours.date') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                    {{ __('employee::work_hours.hours_worked') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                    {{ __('employee::work_hours.sessions') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                            @forelse($workHours as $date => $sessions)
                                <tr>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                        {{ \Carbon\Carbon::parse($date)->isoFormat('dddd, MMMM D') }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm text-gray-500 dark:text-gray-300">
                                        {{ number_format($sessions->sum('hours_worked'), 2) }} {{ __('employee::work_hours.hours') }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm text-gray-500 dark:text-gray-300">
                                        {{ $sessions->count() }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                        {{ __('employee::work_hours.no_records_found') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
