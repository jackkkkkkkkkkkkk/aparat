<?php

use App\Playlist;
use Illuminate\Database\Seeder;

class PlaylistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Playlist::all()->count()) {
            Playlist::truncate();
        }
        $playlists = [
            'لیست پخش 1',
            'لیست پخش 2',
        ];
        foreach ($playlists as $playlist) {
            Playlist::create([
                'user_id' => 1,
                'title' => $playlist
            ]);
        }
    }
}
