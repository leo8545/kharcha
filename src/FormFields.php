<?php


namespace App;


class FormFields
{
    private Request $request;

    public function __construct()
    {
        $this->request = new Request();
    }

    public function error(object $errors, string $fieldName)
    {
        if(is_null($errors) || !@$errors->{$fieldName}) return null;

        $html = '<ul class="errors">';
        foreach($errors->{$fieldName} as $key => $err) {
            $html .= "<li class='single-error error-{$key}'>{$err}</li>";
        }
        $html .= '</ul>';
        echo $html;
        return null;
    }

    public function label($label, $for = "")
    {
        $html = '<label for="'.$for.'">'.$label.'</label>';
        echo $html;
        return null;
    }

    public function input(string $type, string $name, string $value = '', string $id = '')
    {
        $currentValue = $this->request->get($name);
        $checked = (in_array($type, ['checkbox', 'radio']) && $currentValue === $value) ? 'checked' : '';
        $id = (empty($id)) ? $name : $id;
        $html = '<input type="' . $type . '" name="'. $name . '" id="'. $id .'" value="' . $value . '"'. $checked .' />';
        echo $html;
        return null;
    }

    public function select( string $fieldName, array $options)
    {
        $currentValue = $this->request->get($fieldName);
        $html = "<select name='{$fieldName}' id='{$fieldName}'>";
        foreach($options as $value => $label) {
            $html .= "<option value='{$value}'";
            if($currentValue === $value) $html .= " selected";
            $html .= ">{$label}</option>";
        }
        $html .= "</select>";
        echo $html;
        return null;
    }

    public function sorter(string $orderBy)
    {
        if( @$_GET['orderBy'] === $orderBy ) {
            $order = (@$_GET['order'] && $_GET['order'] === 'asc') ? "desc" : "asc";
            $arrow = ($order === 'asc') ? "&uarr;" : "&darr;";
        } else {
            $order = "asc";
            $arrow = "&uarr;";
        }
        $html = '<form method="GET" class="sorter-form">';
        $html .= '<input type="hidden" name="order" value="' . $order . '" >';
        $html .= '<input type="hidden" name="orderBy" value="' . $orderBy . '" >';
        $html .= '<input type="submit" name="btn_sorter" value="' . $arrow . '" />';
        $html .= '</form>';
        echo $html;
        return null;
    }

}