<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Perusahaan;
use Illuminate\Support\Str;

class generate_unique_code extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate_unique_code';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $perusahaans = Perusahaan::all();

        foreach ($perusahaans as $perusahaan) {
            // Generate random kode_perusahaan
            $randomKode = Str::random(9);
            // $randomKode = strtoupper(str_random(9)); // Generate a random string of 10 characters
            $perusahaan->update(['kode_perusahaan' => $randomKode]);
        }

        $this->info('kode_perusahaan has been randomized for all Perusahaan records.');
    }
}
