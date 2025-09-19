<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\BulkUpload;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class BulkUserUploadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $bulkUploadId;

    /**
     * Create a new job instance.
     */
    public function __construct($bulkUploadId)
    {
        $this->bulkUploadId = $bulkUploadId;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $bulk = BulkUpload::find($this->bulkUploadId);
        if (!$bulk) return;

        $bulk->update(['status' => 'processing']);

        $file = Storage::get($bulk->file_path);
        $rows = array_map('str_getcsv', explode("\n", $file));
        $header = array_shift($rows);

        $success = 0;
        $fail = 0;
        $errors = [];

        foreach ($rows as $index => $row) {
            if (count($row) < 5) continue;

            try {
                [$name, $email, $password, $gender, $role] = $row;

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $fail++;
                    $errors[] = "Row " . ($index+2) . ": Invalid email - $email";
                    continue;
                }

                User::updateOrCreate(
                    ['email' => $email],
                    [
                        'name'       => $name,
                        'password'   => Hash::make($password),
                        'gender'     => $gender,
                        'role'       => $role,
                        'country_id' => 1,
                        'city_id'    => 1,
                        'terms'      => 1
                    ]
                );
                $success++;
            } catch (\Exception $e) {
                $fail++;
                $errors[] = "Row " . ($index+2) . ": " . $e->getMessage();
            }
        }

        $bulk->update([
            'status'        => 'completed',
            'success_count' => $success,
            'fail_count'    => $fail,
            'error_log'     => implode("\n", $errors),
        ]);
    }
}
