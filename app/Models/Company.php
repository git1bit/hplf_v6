<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
        'category_id',
        'catchphrase',
        'image',
        'strength_image1',
        'strength_text1',
        'strength_image2',
        'strength_text2',
        'strength_image3',
        'strength_text3',
        'address',
        'phone_number',
        'fax_number',
        'business_hours',
        'holiday',
        'parking_available',
        'parking_slots',
        'payment_methods',
        'website_url',
        'is_published',
    ];
    protected $casts = [
        'image' => 'array',
        'is_published' => 'boolean',
    ];

    protected $hidden = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function news()
    {
        return $this->hasMany(News::class);
    }

    public function category()
    {
        //return $this->belongsToMany(Category::class, 'company_category');
        return $this->belongsTo(Category::class);
    }
}
