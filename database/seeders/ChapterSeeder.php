<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Chapter;

class ChapterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = file_get_contents(database_path('seeders/chapters.json'));
        $data = json_decode($json);

        foreach ($data as $chapterData) {
            Chapter::create([
                'course_id' => $chapterData->course_id,
                'title' => $chapterData->title,
            ]);
        }
    }
}
