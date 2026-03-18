<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AuditObserver
{
    // untuk mengamati perubahan pada model dan mencatat log audit
    public function created($model)
    {
        $this->logActivity('CREATE', $model);
    }

    // untuk mengamati perubahan pada model dan mencatat log audit
    public function updated($model)
    {
        $this->logActivity('UPDATE', $model, $model->getChanges());
    }

    // untuk mengamati perubahan pada model dan mencatat log audit
    public function deleted($model)
    {
        $this->logActivity('DELETE', $model);
    }

    // untuk mencatat aktivitas audit
    protected function logActivity(string $action, Model $model, array $changes = [])
    {
        // untuk mendapatkan informasi user yang melakukan aksi
        $user = Auth::check() ? Auth::user()->email : 'System';
        $modelname = class_basename($model);
        $modelId = $model->id ?? 'Unknown';

        // untuk menyiapkan detail log audit
        $details = [];
            if ($action === 'UPDATE' && !empty($changes)) {
                $details['changes'] = $changes;
            }

            if ($action === 'CREATE') {
                $details['created'] = $model->toArray();
            }

            if ($action === 'DELETE') {
                $details['deleted'] = $model->toArray();
            }

        // menyimpan log audit ke database
        AuditLog::insert([
            'timestamp'     => now(),                                       // waktu terjadinya aksi
            'user_id'       => $user,                    // ID user yang melakukan aksi
            'action'        => $action,                                     // jenis aksi (Created, Updated, Deleted)
            'resource'      => strtolower($modelname) . ':' . $modelId,     // resource yang diubah
            'status'        => 'Success',                                   // status aksi
            'details'       => json_encode($details),                                    // detail perubahan
        ]);
    }
}
