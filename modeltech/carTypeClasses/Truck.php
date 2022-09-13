<?php

class Truck extends BaseCar
{

    public $bodyWidth = 0;
    public $bodyHeight = 0;
    public $bodyLength = 0;

    public function fillCarType()
    {
        $this->carType = 'truck';
    }
    
    public function fillCustomFields($custom_fields)
    {
        $this->bodyWidth = (float) $custom_fields['bodyWidth'];
        $this->bodyHeight = (float) $custom_fields['bodyHeight'];
        $this->bodyLength = (float) $custom_fields['bodyLength'];
    }

    public function getBodyVolume()
    {
        return $this->bodyWidth * $this->bodyHeight * $this->bodyLength;
    }
}