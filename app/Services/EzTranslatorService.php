<?php

namespace App\Services;


class EzTranslatorService
{
    public $translation;
    public $default;

    public function __construct($translation = null, $default = null)
    {
        $this->translation = $translation;
        $this->default     = $default;
    }

    public static function create($translation, $default)
    {
        return new static($translation, $default);
    }

    public function translation()
    {
        return (trans("ezmap.{$this->translation}") == "ezmap.{$this->translation}") ?
            $this->default :
            trans('ezmap.' . $this->translation);
    }

    public static function translate($translation, $default = null)
    {
        $default = $default ?? $translation;
        return (new static($translation,$default))->translation();
    }
}
