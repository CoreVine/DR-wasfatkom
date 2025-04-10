<?php

namespace App\Traits;

use App\Models\ProvinceTranslate;

trait ModelTranslateTrait
{


    public $className = self::class;



    public function from_camel_case($input)
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        return implode('_', $ret);
    }

    private function get_class_name_use()
    {
        $namespaceclass = explode("\\", $this->className);
        $name = end($namespaceclass);
        $name = $this->from_camel_case($name);

        return strtolower($name);
    }

    public function all_translate()
    {


        return $this->hasMany($this->className . "Translate", $this->get_class_name_use() . '_id', 'id');
    }


    public function locale_translate()
    {

        return $this->hasOne($this->className . "Translate")
            ->whereLang(app()->getLocale());
    }

    public function first_translate()
    {
        return $this->hasOne($this->className . "Translate");
    }

    public function getNameAttribute()
    {
        if ($this->locale_translate) {
            return $this->locale_translate->name;
        } else {
            return $this->first_translate->name ?? null;
        }
    }


    public function getTitleAttribute()
    {
        if ($this->locale_translate) {
            return $this->locale_translate->title;
        } else {
            return $this->first_translate->title ?? null;
        }
    }

    public function getRequirementsAttribute()
    {
        if ($this->locale_translate) {
            return $this->locale_translate->requirements;
        } else {
            return $this->first_translate->requirements;
        }
    }


    public function getDescriptionAttribute()
    {
        if ($this->locale_translate) {
            return $this->locale_translate->description;
        } else {
            return $this->first_translate?->description ?? null;
        }
    }


    public function getShortDescriptionAttribute()
    {
        if ($this->locale_translate) {
            return $this->locale_translate->short_description;
        } else {
            return $this->first_translate?->short_description ?? null;
        }
    }




    public function getDetailsAttribute()
    {
        if ($this->locale_translate) {
            return $this->locale_translate->details;
        } else {
            return $this->first_translate->details ?? null;
        }
    }

    public function getContentAttribute()
    {
        if ($this->locale_translate) {
            return $this->locale_translate->content;
        } else {
            return $this->first_translate->content ?? null;
        }
    }

    public function getLearnAttribute()
    {
        if ($this->locale_translate) {
            return $this->locale_translate->learn;
        } else {
            return $this->first_translate->learn ?? null;
        }
    }

    public function getPresenterAttribute()
    {
        if ($this->locale_translate) {
            return $this->locale_translate->presenter;
        } else {
            return $this->first_translate->presenter ?? null;
        }
    }


    public function getSlugAttribute()
    {
        if ($this->locale_translate) {
            return $this->locale_translate->slug;
        } else {
            return $this->first_translate->slug ?? null;
        }
    }



    public function getMetaDescriptionAttribute()
    {
        if ($this->locale_translate) {
            return $this->locale_translate->meta_description;
        } else {
            return $this->first_translate->meta_description ?? null;
        }
    }
}
