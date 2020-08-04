<?php


namespace App\Models;


class Post extends Model
{
    protected $tableName = 'posts';
    protected $fillables = ['title', 'price', 'category'];

    public function getTotalPrice()
    {
        $totalPrice = 0;
        $arr = $this->select("price")->get();
        foreach($arr as $single) {
            $totalPrice += (int) $single['price'];
        }
        return $totalPrice;
    }
}