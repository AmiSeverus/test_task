<?php

class Car extends BaseCar
{
    public $passengerSeatsCount;

    public function fillCarType()
    {
        $this->carType = 'car';
    }
    
    public function fillCustomFields($custom_fields)
    {
        $this->passengerSeatsCount = (int) $custom_fields['passengerSeatsCount'];
    }
}