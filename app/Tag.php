<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use SoftDeletes;
    protected $table = 'tags';
    protected $fillable = ['title'];

    public function toArray()
    {
        return [
            'id' => $this->id,
            'title' => $this->title
        ];
    }
}
