<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Perusahaan;
use Illuminate\Support\Str;

class RandomizeKodePerusahaanJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        $perusahaans = Perusahaan::all();

        foreach ($perusahaans as $perusahaan) {
            // Generate random kode_perusahaan
            $randomKode = Str::random(9);
            // $randomKode = strtoupper(str_random(9)); // Generate a random string of 10 characters
            $perusahaan->update(['kode_perusahaan' => $randomKode]);
        }
    }
}
