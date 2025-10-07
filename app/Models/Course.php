<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    public function lecturer()
{
    return $this->belongsTo(User::class, 'lecturer_id');
}

public function students()
{
    return $this->belongsToMany(User::class, 'course_user');
}

public function materials()
{
    return $this->hasMany(Material::class);
}

public function assignments()
{
    return $this->hasMany(Assignment::class);
}

public function discussions()
{
    return $this->hasMany(Discussion::class);
}
}
