<?php

require_once './carTypeClasses/BaseCar.php';
require_once './carTypeClasses/Car.php';
require_once './carTypeClasses/Truck.php';
require_once './carTypeClasses/SpecMachine.php';

function getCarList()
{

    $cars = [];

    $fieldsIndexes = [
        'carType' => false,
        'photoFileName' => false,
        'brand' => false,
        'carrying' => false,
        'passengerSeatsCount'=>false,
        'bodyWhole' => false,
        'extra'=>false
    ];

    $carTypeCustomFields = 
    [
        'car' => ['passengerSeatsCount'],
        'truck' => ['bodyWhole'],
        'specMachine' => ['extra']
    ];
    
    $countFields = count($fieldsIndexes);

    $cars = [];

    $handle = fopen("data.csv", "r");

    $cnt = 0;

    while (($data = fgetcsv($handle, NULL, ';')) !== false)
    {
        $cnt++;
        if($cnt === 1)
        {
            foreach($data as $key=>$field)
            {
                switch ($field)
                {
                    case 'car_type':
                        $fieldsIndexes['carType'] = $key;
                        break;
                    case 'brand':
                        $fieldsIndexes['brand'] = $key;
                        break;        
                    case 'passenger_seats_count':
                        $fieldsIndexes['passengerSeatsCount'] = $key;
                        break;
                    case 'photo_file_name':
                        $fieldsIndexes['photoFileName'] = $key;
                        break;
                    case 'body_whl':
                        $fieldsIndexes['bodyWhole'] = $key;
                        break;
                    case 'carrying':
                        $fieldsIndexes['carrying'] = $key;
                        break;
                    case 'extra':
                        $fieldsIndexes['extra'] = $key;
                        break;
                }
            }
        } else {
            if (count($data) !== $countFields)
            {
                continue;
            };
            if(!checkMandatoryFields($data, $fieldsIndexes))
            {
                continue;
            };
            switch ($data[$fieldsIndexes['carType']])
            {
                case 'car':
                    $newCar = createCar($data, $fieldsIndexes ,$carTypeCustomFields);
                    break;
                case 'truck':
                    $newCar = createTruck($data, $fieldsIndexes ,$carTypeCustomFields);
                    break;
                case 'spec_machine':
                    $newCar = createSpecMachine($data, $fieldsIndexes ,$carTypeCustomFields);
                    break;
            }
            if ($newCar)
            {
                $cars[] = $newCar;
            }
        };
    };

    return $cars;
}

function createCar($fields, $fieldsIndexes, $carTypeCustomFields)
{
    if(!checkCustomFields($fields, $fieldsIndexes, $carTypeCustomFields['car']))
    {
        return false;
    }

    return new Car(
        $fields[$fieldsIndexes['photoFileName']], 
        $fields[$fieldsIndexes['brand']], 
        $fields[$fieldsIndexes['carrying']],
        getCustomFields($fields, $fieldsIndexes, $carTypeCustomFields['car'])
    );
}

function createTruck($fields, $fieldsIndexes, $carTypeCustomFields)
{
    if(!checkCustomFields($fields, $fieldsIndexes, $carTypeCustomFields['truck']))
    {
        return false;
    }

    return new Truck(
        $fields[$fieldsIndexes['photoFileName']], 
        $fields[$fieldsIndexes['brand']], 
        $fields[$fieldsIndexes['carrying']],
        getCustomFields($fields, $fieldsIndexes, $carTypeCustomFields['truck'])
    );
}

function createSpecMachine($fields, $fieldsIndexes, $carTypeCustomFields)
{
    if(!checkCustomFields($fields, $fieldsIndexes, $carTypeCustomFields['specMachine']))
    {
        return false;
    }
    return new SpecMachine(
        $fields[$fieldsIndexes['photoFileName']], 
        $fields[$fieldsIndexes['brand']], 
        $fields[$fieldsIndexes['carrying']],
        getCustomFields($fields, $fieldsIndexes, $carTypeCustomFields['specMachine'])
    );
}

function checkMandatoryFields($fieldsToCheck, $fieldsIndexes)
{
    foreach($fieldsToCheck as $key=>$fieldToCheck)
    {
        switch ($key)
        {
            case $fieldsIndexes['photoFileName']:
                $file = new SplFileInfo($fieldToCheck);
                if (!$file->getExtension())
                {
                    return false;
                };
                break;
            case $fieldsIndexes['brand']:
                if(!$fieldToCheck)
                {
                    return false;
                };
                break;
            case $fieldsIndexes['carrying']:
                if ((float) $fieldToCheck <= 0)
                {
                    return false;
                };
                break;
        }          
    }
    return true;
}

function checkCustomFields($fieldsToCheck, $fieldsIndexes, $customFields)
{
    foreach($customFields as $field)
    {
        switch($field)
        {
            case 'passengerSeatsCount':
                if((int) $fieldsToCheck[$fieldsIndexes[$field]] < 1)
                {
                    return false;
                } 
                break;
            case 'bodyWhole':
                $arr = explode('x', $fieldsToCheck[$fieldsIndexes[$field]]);
                if(count($arr) !== 3)
                {
                    return false;
                };
                foreach($arr as $val)
                {
                    if((float) $val <= 0)
                    {
                        return false;
                    }
                }
                break;
            case 'extra':
                if(!trim($fieldsToCheck[$fieldsIndexes[$field]]))
                {
                    return false;
                }
                break;
        }
        return true;
    }
}

function getCustomFields($fields, $fieldsIndexes, $customFields)
{
    $customArr = [];
    foreach($customFields as $customField)
    {
        if($customField === 'bodyWhole')
        {
            $arr = explode('x',$fields[$fieldsIndexes[$customField]]);
            $fields = ['bodyWidth','bodyHeight','bodyLength'];
            foreach($fields as $key=>$val)
            {
                $customArr[$val] = $arr[$key];
            }
        } else {
            $customArr[$customField] = $fields[$fieldsIndexes[$customField]];
        }
    }
    return $customArr;
}

print_r(getCarList());