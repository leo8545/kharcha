<?php

require "./vendor/autoload.php";
use App\Form;

$data = new stdClass();
$errors = new stdClass();
$postModel = new \App\Models\Post();
\App\FormBuilder::setData((object) []);
if(@$_GET['btn_sorter']) {
    $orderBy = $_GET['orderBy'] ?? "";
    $order = strtoupper($_GET['order']) ?? "ASC";
    if(!empty($orderBy) && $postModel->select()->orderBy($orderBy)->get() !== null) {
        $posts = $postModel->select()->orderBy($orderBy, $order)->get();
    }
} else {
    $posts = $postModel->all();
}

if(@$_POST['btn_submit']) {
    $fields = [
        'title' => ['required','max:100', 'min:4'],
        'date' => [],
        'price' => ['required'],
        'category' => ['required']
    ];

    $data = (new Form($fields))->validate();
    $errors = $data->errors;

    \App\FormBuilder::setData($data);

    if( count((array)$errors) === 0) {
        $isInserted = $postModel->insert([
            'title' => $data->title,
            'price' => $data->price,
            'category' => $data->category
        ]);
        \App\FormBuilder::setData((object) []);
    }
    $posts = $postModel->all();
}

// Filter form handler
if(@$_GET['btn_filter']) {
    $filterPrice = $_GET['filterPrice'] ?? '';

    if( !empty($filterPrice) && in_array( $_GET['filterPrice'], ['5000-plus', '5000-minus'] ) ) {
        $op = array_search($_GET['filterPrice'], ['5000-plus', '5000-minus']) === 0 ? '>' : '<';
        $posts = $postModel->select()->where('price', $op, '5000')->get();
    }
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="./public/assets/css/main.css">
    <title>Kharcha</title>
</head>
<body>
    <div id="wrapper">
        <div id="container" class="flex">
            <div class="flex-30">
                <?php if(@$isInserted && $isInserted === true) : ?>
                    <div class="success">
                        <p>Great! New item added.</p>
                    </div>
                <?php endif; ?>
                <form method="post">
                    <div class="form-field">
                        <?php \App\FormBuilder::fieldGroup(['label' => 'Title'], [ 'type' => 'text', 'name' => 'title' ] ) ?>
                    </div>
                    <div class="form-field">
                        <?php \App\FormBuilder::fieldGroup(['label' => 'Date'], [ 'type' => 'date', 'name' => 'date' ] ) ?>
                    </div>
                    <div class="form-field">
                        <?php \App\FormBuilder::fieldGroup(['label' => 'Price'], [ 'type' => 'text', 'name' => 'price' ] ) ?>
                    </div>
                    <div class="form-field">
                        <label for="category">Category</label>
                        <?php
                        \App\FormBuilder::select('category', [
                            'general' => 'General',
                            'motorbike' => 'Motorbike',
                            'online_shopping' => 'Online Shopping',
                            'other' => 'Others'
                        ]);
                        ?>
                    </div>
                    <input type="submit" value="submit" name="btn_submit">
                </form>
            </div>
            <div class="flex-70">
                <div>
                    <span>Total amount spent:</span>
                    <span>PKR <?php echo $postModel->getTotalPrice(); ?></span>
                </div>
                <div class="filters">
                    <form method="get" class="filters-form">
                        <div class="form-field">
                            <?php \App\FormBuilder::input('radio', 'filterPrice', '5000-plus', 'filterPrice1') ?>
                            <?php \App\FormBuilder::label('Greater than 5000', 'filterPrice1') ?>
                        </div>
                        <div class="form-field">
                            <?php \App\FormBuilder::input('radio', 'filterPrice', '5000-minus', 'filterPrice2') ?>
                            <?php \App\FormBuilder::label('Smaller than 5000', 'filterPrice2') ?>
                        </div>
                        <input type="submit" value="Filter" name="btn_filter">
                    </form>
                </div>
                <table class="posts table-posts">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Item description</th>
                        <th>
                            <span>Price</span>
                            <?php \App\FormBuilder::sorter('price') ?>
                        </th>
                        <th>
                            <span>Date</span>
                            <?php \App\FormBuilder::sorter('date') ?>
                        </th>

                        <th>
                            <span>Category</span>
                            <?php \App\FormBuilder::sorter('category') ?>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($posts as $index => $post): ?>
                        <tr>
                            <td><?php echo $index+1 ?>.</td>
                            <td><?php echo $post['title'] ?></td>
                            <td><?php echo $post['price'] ?></td>
                            <td><?php echo $post['date'] ?></td>
                            <td><?php echo $post['category'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="public/assets/js/main.js"></script>
</body>
</html>
