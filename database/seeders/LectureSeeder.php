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
        $jsonData = file_get_contents(database_path('seeders/lectures.json'));

        $data = json_decode($jsonData, true);

        $lecture_ids = [];
        $video_thumbnail = "https://d1bos1fs7g1uv3.cloudfront.net/thumbnails/anh405.png";
        $video_url = "https://d1fi0jbb8q8r9s.cloudfront.net/videos/6620ca93bf03f_React+App+-+Google+Chrome+2023-12-25+14-06-38.mp4";
        $video_duration =13.28;
        $chapter_ids = DB::table('chapters')->pluck('id')->toArray();
        foreach ($data as $index =>$item) {
            $currentLectureIndex = $index % count($chapter_ids);

            $lecture = Lecture::create([
                'chapter_id' => $chapter_ids[$currentLectureIndex],
                'title' => $item['title'],

            ]);
            $lecture->video()->create([
                'thumbnail_url' => $video_thumbnail,
                'url' => $video_url,
                'duration' => $video_duration,
            ]);

            $lecture_ids[] = $lecture->id;
        }

        $resource_data =  json_decode(file_get_contents(database_path('seeders/resources.json')), true);
        foreach ($resource_data as $index => $item) {
            $currentLectureIndex = $index % count($lecture_ids);

            $lecture = Lecture::find($lecture_ids[$currentLectureIndex]);

            $lecture->resources()->create([
                'title' => $item['title'],
                'link' => $item['link'],
            ]);
        }
    }
}
