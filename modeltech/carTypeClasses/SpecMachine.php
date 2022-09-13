<?php

class SpecMachine extends BaseCar
{

    public $extra = 0;

    public function fillCarType()
    {
        $this->carType = 'specMachine';
    }
    
    public function fillCustomFields($custom_fields)
    {
        $this->extra = trim($custom_fields['extra']);
    }
}