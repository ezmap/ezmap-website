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

        return (new static($translation, $default))->translation();
    }

    public function helpTranslation()
    {
        return (trans("ezmaphelp.{$this->translation}") == "ezmaphelp.{$this->translation}") ?
            $this->default :
            trans('ezmaphelp.' . $this->translation);
    }

    public static function help($translation, $default = null)
    {
        $default = $default ?? $translation;

        return (new static($translation, $default))->helpTranslation();
    }

    public static function language($locale)
    {
        //trans($id = null, $parameters = [], $domain = 'messages', $locale = null)
        return trans('ezmap.language',[],'messages', $locale);
    }

}
