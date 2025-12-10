<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Projects extends Model
{
    use SoftDeletes;

    protected $fillable = [

        // Basic Project Info
        'project_name',
        'company_name',
        'service_type',
        'sub_service',
        'deadline',
        'priority',
        'status',
        'color',

        // Domain Details
        'domain_name',
        'domain_registrar',
        'domain_expiry',

        // Hosting Details
        'hosting_provider',
        'server_type',
        'hosting_expiry',
        'cpanel_url',
        'cpanel_username',
        'cpanel_password',

        // Confidential Credentials
        'project_email',
        'project_email_password',
        'smtp_host',
        'smtp_port',

        'backup_email',
        'admin_url',
        'admin_username',
        'admin_password',

        'other_credentials',
        'lead_id',
    ];

    protected $casts = [
        'deadline'        => 'date',
        'domain_expiry'   => 'date',
        'hosting_expiry'  => 'date',
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class, 'project_id');
    }
}
