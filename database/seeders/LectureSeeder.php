<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Lecture;
use Illuminate\Support\Facades\DB;

class LectureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('lectures')->truncate();
        $jsonData = file_get_contents(database_path('seeders/lectures.json'));

        $data = json_decode($jsonData, true);

        $lecture_ids = [];
        $video_thumbnail ="https://cdn.study.salyr.online/videos/65844bb54f26e_fire_background_loop2_videvo2.mov";
        $video_url = "https://cdn.study.salyr.online/videos/thumbnails/thumbnail-1703168951.jpg";
        $video_duration =13.28;

        foreach ($data as $item) {
            $lecture = Lecture::create([
                'chapter_id' => $item['chapter_id'],
                'title' => $item['title'],

            ]);
            $lecture->video()->create([
                'thumbnail_url' => $video_thumbnail,
                'url' => $video_url,
                'duration' => $video_duration,
            ]);

            $lecture_ids[] = $lecture->id;
        }

        $resource_data =  json_decode(file_get_contents(database_path('seeds/resources.json')), true);
        foreach ($resource_data as $index => $item) {
            $currentLectureIndex = $index % $totalLectures;

            $lecture = Lecture::find($lecture_ids[$currentLectureIndex]);

            $lecture->resources()->create([
                'title' => $item['title'],
                'link' => $item['link'],
            ]);
        }
    }
}