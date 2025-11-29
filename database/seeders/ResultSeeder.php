<?php

namespace Database\Seeders;

use App\Models\Result;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ResultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
    {
        $results = [
            [
                'title' => 'Final Exam Results Published',
                'description' => 'The final exam results for all departments have been published.',
                'file' => 'final_exam_results.pdf',
                'createdBy' => 1,
                'updatedBy' => 1,
            ],
            [
                'title' => 'Midterm Results Available',
                'description' => 'Midterm examination results are now available for review.',
                'file' => 'midterm_results.pdf',
                'createdBy' => 1,
                'updatedBy' => 1,
            ],
            [
                'title' => 'Practical Assessment Results',
                'description' => 'Results of the practical assessments conducted last week.',
                'file' => 'practical_assessment.xlsx',
                'createdBy' => 1,
                'updatedBy' => 1,
            ],
            [
                'title' => 'Monthly Performance Report',
                'description' => 'Performance evaluation results for the month have been released.',
                'file' => 'monthly_performance.docx',
                'createdBy' => 1,
                'updatedBy' => 1,
            ],
            [
                'title' => 'Project Evaluation Results',
                'description' => 'Results of the submitted projects are now available.',
                'file' => 'project_evaluation.pdf',
                'createdBy' => 1,
                'updatedBy' => 1,
            ],
        ];

        foreach ($results as $result) {
            Result::firstOrCreate(
                ['slug' => Str::slug($result['title'])],
                [
                    'title'       => $result['title'],
                    'description' => $result['description'],
                    'file'        => $result['file'],
                    'createdBy'   => $result['createdBy'],
                    'updatedBy'   => $result['updatedBy'],
                ]
            );
        }
    }
}
