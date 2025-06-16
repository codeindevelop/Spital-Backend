<?php

namespace Modules\Settings\App\Services\System;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;
use Modules\Settings\App\Repositories\System\GeneralSettingRepository;

class GeneralSettingService
{
    protected GeneralSettingRepository $repository;

    public function __construct(GeneralSettingRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get general settings
     */
    public function getSettings(): Model|Builder|null
    {
        return $this->repository->getFirst();
    }

    /**
     * Update general settings
     */
    public function updateSettings(array $data)
    {
        $validatedData = $this->validateData($data);
        return $this->repository->updateOrCreate($validatedData);
    }

    /**
     * Validate input data
     * @throws ValidationException
     */
    protected function validateData(array $data): array
    {
        return validator($data, [
            'timezone_id' => 'required|uuid|exists:time_zones,id',
            'language_id' => 'required|uuid|exists:languages,id',
            'country_id' => 'required|uuid|exists:countries,id',
            'site_name' => 'nullable|string|max:255',
            'site_desc' => 'nullable|string|max:500',
            'maintenance_mode' => 'boolean',
            'user_panel_url' => 'nullable|url|max:255',
        ])->validate();
    }
}
