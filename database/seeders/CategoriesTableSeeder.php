<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Topic;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run()
    {
        $categoriesData = [
            [
                'name' => 'Development',
                'subcategories' => [
                    [
                        'name' => 'Web Development',
                        'topics' => ['HTML', 'CSS', 'JavaScript', 'PHP', 'Python', 'ReactJs', 'Laravel', 'VueJs'],
                    ],
                    [
                        'name' => 'Mobile Apps',
                        'topics' => ['IOS Development', 'Android Development', 'React Native'],
                    ],
                    [
                        'name' => 'Data Science',
                        'topics' => ['Python', 'Machine Learning', 'Deep Learning'],
                    ],
                    [
                        'name' => 'Game Development',
                        'topics' => ['Unity', 'Unreal Engine', 'Godot'],
                    ]
                ],
            ],
            [
                'name' => 'Business',
                'subcategories' => [
                    [
                        'name' => 'Entrepreneurship',
                        'topics' => ['Startup', 'Business Planning', 'Marketing'],
                    ],
                    [
                        'name' => 'Finance',
                        'topics' => ['Financial Planning', 'Investing', 'Personal Finance'],
                    ],
                    [
                        'name' => 'Communication',
                        'topics' => ['Leadership', 'Product Management', 'Presentation Skills'],
                    ],
                ],
            ],
            [
                'name' => 'Marketing',
                'subcategories' => [
                    [
                        'name' => 'Digital Marketing',
                        'topics' => ['SEO', 'Social Media Marketing', 'Content Marketing'],
                    ],
                    [
                        'name' => 'Advertising',
                        'topics' => ['Online Advertising', 'Print Advertising', 'Ad Campaigns'],
                    ],
                ],
            ],
            [
                'name' => 'Photography & Video',
                'subcategories' => [
                    [
                        'name' => 'Photography Basics',
                        'topics' => ['Camera Settings', 'Composition', 'Lighting'],
                    ],
                    [
                        'name' => 'Video Editing',
                        'topics' => ['Editing Software', 'Color Correction', 'Special Effects'],
                    ],
                ],
            ],
            [
                'name' => 'Health & Fitness',
                'subcategories' => [
                    [
                        'name' => 'Fitness Training',
                        'topics' => ['Cardio Workouts', 'Strength Training', 'Yoga'],
                    ],
                    [
                        'name' => 'Nutrition',
                        'topics' => ['Healthy Eating', 'Meal Planning', 'Supplements'],
                    ],
                ],
            ],
            [
                'name' => 'Music',
                'subcategories' => [
                    [
                        'name' => 'Music Theory',
                        'topics' => ['Notes and Scales', 'Chords', 'Composition'],
                    ],
                    [
                        'name' => 'Instrumental Techniques',
                        'topics' => ['Guitar', 'Piano', 'Drums'],
                    ],
                    [
                        'name' => 'Music Production',
                        'topics' => ['Recording', 'Mixing', 'Mastering'],
                    ],
                ],
            ],
        ];

        foreach ($categoriesData as $categoryData) {
            $this->createCategory($categoryData);
        }
    }

    private function createCategory(array $data, ?Category $parentCategory = null)
    {
        $category = new Category(['name' => $data['name']]);

        if ($parentCategory) {
            $category->parent_category_id = $parentCategory->id;
        }

        $category->save();

        if (isset($data['subcategories'])) {
            foreach ($data['subcategories'] as $subcategoryData) {
                $this->createCategory($subcategoryData, $category);
            }
        }

        // Add topics directly to the category
        if (isset($data['topics'])) {
            foreach ($data['topics'] as $topicName) {
                $topic = $category->topics()->create(['name' => $topicName]);
            }
        }
    }
}
