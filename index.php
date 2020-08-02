<?php

require "./vendor/autoload.php";
use App\Form;

$data = new stdClass();
$errors = new stdClass();
if(@$_POST['btn_submit']) {
    $fields = [
        'title' => ['required','max:8', 'min:4'],
        'date' => ['required'],
        'price' => ['required']
    ];

    $data = (new Form($fields))->validate();
    $errors = $data->errors;
}

function old($fieldName)
{
    global $data;
    if(!@$data->$fieldName) return '';
    return (string) $data->$fieldName;
}

function error($fieldName)
{
    global $errors;
    if( !@$errors->$fieldName ) return '';
    $html = '<ul class="errors">';
    foreach( $errors->$fieldName as $key => $err ) {
        $html .= "<li class='single-error error-{$key}'>{$err}</li>";
    }
    $html .= '</ul>';
    return $html;
}

?>

<form method="post">
	<div class="form-field">
        <label for="title">Title</label>
        <input type="text" name="title" id="title" value="<?php echo old('title') ?>">
        <?php echo error('title'); ?>
    </div>
	<div class="form-field">
        <label for="date"></label>
        <input type="date" name="date" id="date" value="<?php echo old('date') ?>">
        <?php echo error('date'); ?>
    </div>
	<div class="form-field">
        <label for="price"></label>
        <input type="text" name="price" id="price" value="<?php echo old('price') ?>">
        <?php echo error('price'); ?>
    </div>
	<input type="submit" value="submit" name="btn_submit">
</form>
