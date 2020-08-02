<?php

namespace App;

class ValidationMethods
{
    private static Request $request;

    public static object $validationData;

    public function __construct()
    {
        self::$request = new Request();
        self::$validationData = (object) [];
        self::$validationData->errors = (object) [];
    }

    /**
     * Validates maximum length of the field
     * @param $fieldName string Name attribute of the form element
     * @param $limit int Number of characters
     * @return object
     */
    public static function validate_maxLength($fieldName, $limit)
    {
        $isValid = strlen(self::$request->get($fieldName)) <= $limit;
        if( !$isValid ) {
            self::$validationData->errors->$fieldName['max_length'] = "{$fieldName} should be maximum of {$limit} characters";
        }
        self::$validationData->$fieldName = self::$request->get($fieldName);
        return self::$validationData;
    }

    /**
     * Validates minimum length of the field
     * @param $fieldName string Name attribute of the form element
     * @param $limit int Number of characters
     * @return object
     */
    public static function validate_minLength($fieldName, $limit)
    {
        $isValid = strlen(self::$request->get($fieldName)) >= $limit;
        if( !$isValid ) {
            self::$validationData->errors->$fieldName['min_length'] = "{$fieldName} should be minimum of {$limit} characters";
        }
        self::$validationData->$fieldName = self::$request->get($fieldName);
        return self::$validationData;
    }

    /**
     * Validates required
     * @param $fieldName string Name attribute of the form element
     * @return object
     */
    public static function validate_required($fieldName)
    {
        $isValid = (self::$request->get($fieldName) === "" ? false : true);
        if( !$isValid ) {
            self::$validationData->errors->$fieldName['required'] = "{$fieldName} is required";
        }
        self::$validationData->$fieldName = self::$request->get($fieldName);
        return self::$validationData;
    }
}