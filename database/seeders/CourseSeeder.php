<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;

class CourseSeeder extends Seeder
{
    public function run()
    {
        $coursesData = json_decode(file_get_contents(database_path('seeders/courses.json')), true);

        foreach ($coursesData as $courseData) {
            $course = Course::create([
                'title' => $courseData['title'],
                'description' => $courseData['description'],
                'price' => $courseData['price'],
                'author' => $courseData['author'],
            ]);

            // Attach categories to the course
            $course->categories()->attach($courseData['category_ids']);
        }
    }
}
