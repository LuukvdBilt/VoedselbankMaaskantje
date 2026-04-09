<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'Category'; // let op: lowercase naam zoals in je DB
    protected $primaryKey = 'Id';  // hoofdletter Id zoals in je DB

    protected $fillable = ['Name', 'note', 'is_active']; // kolomnamen exact zoals in DB
    public $timestamps = true; // je hebt created_at en updated_at
}