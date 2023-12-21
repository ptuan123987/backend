<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\CourseReview;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class CourseReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Assuming you have users in your database
        $userIds = User::pluck('id')->toArray();

        $reviewsData = json_decode(File::get(database_path('seeders/course_reviews.json')), true);

        // Seed the reviews
        foreach ($reviewsData as $review) {
            $review['user_id'] = $this->getRandomUserId($userIds);
            CourseReview::create($review);
        }
    }

    /**
     * Get a random user ID from the provided array of user IDs.
     *
     * @param array $userIds
     * @return int
     */
    private function getRandomUserId(array $userIds)
    {
        return $userIds[array_rand($userIds)];
    }
}
