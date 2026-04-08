<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        DB::table('agendamentos')->truncate();
        DB::table('barbeiros')->truncate();
        DB::table('servicos')->truncate();
        DB::table('users')->truncate();

        Schema::enableForeignKeyConstraints();

        User::create([
            'name' => 'Administrador BarberPro',
            'email' => 'admin@barberpro.test',
            'password' => 'password',
            'email_verified_at' => Carbon::now(),
            'created_at' => Carbon::now()->subMonths(18),
            'updated_at' => Carbon::now()->subDay(),
        ]);

        $this->call([
            BarbeiroSeeder::class,
            ServicoSeeder::class,
            AgendamentoHistoricoSeeder::class,
        ]);
    }
}
