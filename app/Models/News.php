<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class News extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'details', 'is_published', 'company_id', 'category_id'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function category()
    {
        return $this->belongsTo(category::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($news) {
            // ユーザーに関連付けられた企業のIDを取得
            $company_id = Auth::user()->company->id;
            $category_id = Auth::user()->company->category_id;

            // company_idとcategory_idを設定
            $news->company_id = $company_id;
            $news->category_id = $category_id;
        });
    }
}
