<?php

abstract class BaseCar
{

    abstract public function fillCustomFields($custom_fields);

    abstract public function fillCarType();

    protected $carType;

    protected $photoFileName;

    protected $brand;

    protected $carrying;

    public function __construct($photoFileName, $brand, $carrying, $custom_fields)
    {
        $this->photoFileName = trim($photoFileName);
        $this->brand = trim($brand);
        $this->carrying = (float) $carrying;
        $this->fillCarType();
        $this->fillCustomFields($custom_fields);
    }

    public function getPhotoFileExt()
    {
        $arr = explode('.', $this->photoFileName);
        if (count($arr) > 1)
        {
            return end($arr);
        }
    }
}