<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    use HasFactory;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'enquiry_code',
        'question',
        'is_read',
        'status',
        'closed_by',
        'closed_at',
        'created_by'
    ];

 /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function searchableAs()
    {
        return 'enquiry_code';
    }

     /**
     * Get the replies for the question.
     */
    public function replies()
    {
        return $this->hasMany(EnquiryResponse::class,'enquiry_id', 'id');
    }


     /**
     * Get the replies for the question.
     */
    public function lastAnswer()
    {
        return $this->hasOne(EnquiryResponse::class,'enquiry_id', 'id')->where('user_type', 'admin')->latest();
    }

    /**
     * Get the replies for the question.
     */
    public function createdUser()
    {
        return $this->hasOne(User::class,'id', 'created_by');
    }


    /**
     * Get the replies for the question.
     */
    public function closedUser()
    {
        return $this->hasOne(User::class,'id', 'closed_by');
    }

}
