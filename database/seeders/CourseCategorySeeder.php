<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $chunkSize = 500;
        $totalRecords = 500000;

        // Tắt indexes và constraints
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('course_category')->truncate();

        $courseIds = range(1, 5000);
        $categoryIds = range(1, 5000);

        $courseCategoryData = [];

        for ($i = 1; $i <= $totalRecords; $i++) {
            $courseCategoryData[] = [
                'course_id' => $this->getRandomId($courseIds),
                'category_id' => $this->getRandomId($categoryIds),
            ];

            if ($i % $chunkSize == 0) {
                // Chunk insert
                DB::table('course_category')->insert($courseCategoryData);
                $courseCategoryData = [];
            }
        }

        if (!empty($courseCategoryData)) {
            // Insert các bản ghi còn lại
            DB::table('course_category')->insert($courseCategoryData);
        }

        // Bật lại indexes và constraints
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    /**
     * Get a random ID from the provided array of IDs.
     *
     * @param array $ids
     * @return int
     */
    private function getRandomId(array $ids)
    {
        return $ids[array_rand($ids)];
    }
}
