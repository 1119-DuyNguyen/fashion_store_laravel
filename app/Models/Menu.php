<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Menu
 *
 * @property int $id
 * @property string $name
 * @property int $parent_id
 * @property string $description
 * @property string $content
 * @property string $slug
 * @property int $active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Menu newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Menu newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Menu query()
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Menu extends Model
{
    use HasFactory; // not to link to the database., factory pattern nên khi cần seed => User::factory()->create();
    //https://laravel.com/docs/9.x/eloquent#generating-model-classes
    // table & primary key nếu không khai báo cũng sẽ tự render ra như dưới

    protected $table = 'menus';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'parent_id',
        'description',
        'content',
        'active',
        'slug'

    ];
    //????
    public function products()
    {
        return $this->hasMany(Product::class, 'menu_id', 'id');
    }
}
