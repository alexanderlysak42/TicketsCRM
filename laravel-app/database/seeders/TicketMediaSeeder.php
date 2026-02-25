<?php

namespace Database\Seeders;

use App\Models\Ticket;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Random\RandomException;

class TicketMediaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @throws RandomException
     */
    public function run(): void
    {
        Ticket::query()->chunk(100, function ($tickets) {
            foreach ($tickets as $ticket) {
                $count = random_int(0, 3);
                if ($count === 0) continue;

                for ($i = 0; $i < $count; $i++) {
                    $content = "Seed attachment for ticket #{$ticket->id}\n";
                    $fileName = 'ticket-' . $ticket->id . '-' . Str::random(8) . '.txt';

                    $ticket
                        ->addMediaFromString($content)
                        ->usingFileName($fileName)
                        ->usingName("Attachment {$i}")
                        ->toMediaCollection('attachments');
                }
            }
        });
    }
}
