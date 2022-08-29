<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LanguagesContents extends Model
{
    protected $table = 'languages_contents';
    protected $perPage = 15;
    protected $locale = 'en';

    protected $fillable =['content_title'];

}
