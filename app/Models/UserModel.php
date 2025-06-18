<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{

    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['username', 'email', 'password_hash', 'reset_hash', 'reset_expires', 'verified', 'verify_token', 'verify_expires']; 

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

}
