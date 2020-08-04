<?php


namespace App;


class FormBuilder
{
    public static object $data;

    public static function setData(object $data)
    {
        self::$data = $data;
    }

    public static function fieldGroup(array $label, array $field)
    {
        $formField = new FormFields();
        $formField->label($label['label'], $label['for'] ?? $field['name']);
        $value = (@self::$data->{$field['name']}) ? self::$data->{$field['name']} : '';
        $formField->input($field['type'], $field['name'], $value);
        $formField->error(@self::$data->errors ?? (object) [], $field['name']);
        return null;
    }

    public static function label(string $label, string $for = "")
    {
        return (new FormFields())->label($label, $for);
    }

    public static function input(string $type, string $name, string $value = '', string $id = '')
    {
        $value = empty($value) && (@self::$data->{$name}) ? self::$data->{$name} : $value;
        return (new FormFields())->input($type, $name, $value, $id);
    }

    public static function select(string $fieldName, array $options)
    {
        return (new FormFields())->select($fieldName, $options);
    }

    public static function sorter(string $orderBy)
    {
        return (new FormFields())->sorter($orderBy);
    }
}