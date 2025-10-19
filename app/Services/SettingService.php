<?php

namespace App\Services;


use App\Models\SiteSetting;
use App\Repositories\SettingRepository;


class SettingService
{
  protected $repository;

  public function __construct(SettingRepository $repository)
  {
    $this->repository = $repository;
  }


  /**
   * Save site settings
   *
   * @param array $data
   * @return SiteSetting
   */
  public function saveSiteSettings(array $data): SiteSetting
  {

    return $this->repository->saveSiteSettings($data);
  }
}
