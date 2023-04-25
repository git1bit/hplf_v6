<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /* public function account()
    {
        return $this->hasOne(Account::class);
    } */

    public function company()
    {
        return $this->hasOne(Company::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($user) {

            /* if (request()->isMethod('post')) {
                \Log::info('POST Request Data: ', request()->all());
            } */


            /* $all_request_data = request()->all();
            \Log::info('All Request Data: ', $all_request_data); */

            // フォームから送信されたカテゴリIDを取得します。
            $selected_category_id = request()->input('company.category_id');
            \Log::info('Selected Category ID: ' . $selected_category_id);

            $company = new Company([
                'name' => $user->name,
                'user_id' => $user->id,
                'category_id' => $selected_category_id,
            ]);

            $user->company()->save($company);

            /* $user->company()->create([
                // ここで、デフォルトの会社情報を設定できます。
                'name' => $user->name, // ここでユーザーの名前を会社名に割り当てる
                'user_id' => $user->id,
                'is_published' => 0,
                'category_id' => $selected_category_id,
            ]);
 */
            // 選択されたカテゴリIDを会社に割り当てます。
            /* if ($selected_category_ids) {
                $company->categories()->attach($selected_category_ids);
            } */
        });
    }
}
