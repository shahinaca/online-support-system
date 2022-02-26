<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnquiryResponse extends Model
{
    use HasFactory;

      /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'enquiry_id',
        'is_read',
        'reply',
        'user_type',
        'notification_email',
        'created_by'
    ];

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
    public function enquiry()
    {
        return $this->hasOne(Enquiry::class,'id', 'enquiry_id');
    }

}
