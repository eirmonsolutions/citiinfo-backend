<?php

namespace App\Services;

use App\Models\BusinessListing;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class BusinessHoursService
{
    public const DEFAULT_TIMEZONE = 'Australia/Sydney';

    private const STATE_TIMEZONES = [
        'australian capital territory' => 'Australia/Sydney',
        'act'                          => 'Australia/Sydney',
        'new south wales'              => 'Australia/Sydney',
        'nsw'                          => 'Australia/Sydney',
        'victoria'                     => 'Australia/Melbourne',
        'vic'                          => 'Australia/Melbourne',
        'queensland'                   => 'Australia/Brisbane',
        'qld'                          => 'Australia/Brisbane',
        'south australia'              => 'Australia/Adelaide',
        'sa'                           => 'Australia/Adelaide',
        'western australia'            => 'Australia/Perth',
        'wa'                           => 'Australia/Perth',
        'northern territory'           => 'Australia/Darwin',
        'nt'                           => 'Australia/Darwin',
        'tasmania'                     => 'Australia/Hobart',
        'tas'                          => 'Australia/Hobart',
    ];

    public function resolveTimezone(BusinessListing $listing): string
    {
        if (! empty($listing->cityRel?->timezone)) {
            return $listing->cityRel->timezone;
        }

        $stateName = strtolower(trim($listing->stateRel?->name ?? ''));

        if ($stateName !== '' && isset(self::STATE_TIMEZONES[$stateName])) {
            return self::STATE_TIMEZONES[$stateName];
        }

        return self::DEFAULT_TIMEZONE;
    }

    public function status(BusinessListing $listing): array
    {
        /** @var Collection<int, \App\Models\BusinessHour> $hours */
        $hours = $listing->relationLoaded('hours')
            ? $listing->hours
            : $listing->hours()->get();

        $timezone = $this->resolveTimezone($listing);
        $now      = Carbon::now($timezone);
        $todayKey = strtolower($now->format('l'));
        $nowTime  = $now->format('H:i:s');

        $default = [
            'is_open'      => false,
            'is_lunch'     => false,
            'text'         => 'Closed Now',
            'class'        => 'closed',
            'detail_label' => 'Closed',
            'timezone'     => $timezone,
            'day'          => $todayKey,
        ];

        if ($hours->isEmpty()) {
            return $default;
        }

        $today = $hours->first(
            fn ($hour) => strtolower((string) $hour->day_of_week) === $todayKey
        );

        if (! $today || (int) $today->is_closed === 1) {
            return $default;
        }

        $open       = $this->normalizeTime($today->open_time);
        $close      = $this->normalizeTime($today->close_time);
        $breakStart = $this->normalizeTime($today->break_start);
        $breakEnd   = $this->normalizeTime($today->break_end);

        if (! $open || ! $close || ! $this->inRange($nowTime, $open, $close)) {
            return $default;
        }

        if ($breakStart && $breakEnd && $this->inRange($nowTime, $breakStart, $breakEnd)) {
            return [
                'is_open'      => false,
                'is_lunch'     => true,
                'text'         => 'Lunch Time',
                'class'        => 'lunch',
                'detail_label' => 'Lunch Time',
                'timezone'     => $timezone,
                'day'          => $todayKey,
            ];
        }

        $closeLabel = Carbon::createFromFormat('H:i:s', $close, $timezone)->format('g:i a');

        return [
            'is_open'      => true,
            'is_lunch'     => false,
            'text'         => 'Open Now',
            'class'        => 'open',
            'detail_label' => "Open · Closes {$closeLabel}",
            'timezone'     => $timezone,
            'day'          => $todayKey,
        ];
    }

    public function normalizeTime(mixed $time): ?string
    {
        if ($time === null || $time === '') {
            return null;
        }

        $value = trim((string) $time);

        if (preg_match('/^\d{2}:\d{2}$/', $value)) {
            return $value . ':00';
        }

        if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $value)) {
            return $value;
        }

        return null;
    }

    public function inRange(string $now, string $start, string $end): bool
    {
        if ($start <= $end) {
            return $now >= $start && $now <= $end;
        }

        return $now >= $start || $now <= $end;
    }
}
