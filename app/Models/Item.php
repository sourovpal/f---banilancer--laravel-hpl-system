<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    // Define the table associated with the model
    protected $table = 'items';

    // Specify the primary key if it's not 'id'
    protected $primaryKey = 'id';

    // Enable or disable automatic timestamp maintenance
    public $timestamps = true;

    // Fields that can be mass assigned
    protected $fillable = [
        'code', 'name', 'specification', 'unit', 'pack', 'category_id',
        'price', 'cost', 'gl', 'stock', 'min', 'ordered', 'location',
        'image', 'status', 'remark'
    ];

    // If you have custom date fields, you might need to specify them
    protected $dates = ['created_at', 'updated_at'];

    /**
     * Relationship with the Category model
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    // Add more relationships here if necessary
}
