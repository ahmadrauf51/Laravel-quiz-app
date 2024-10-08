<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Harishdurga\LaravelQuiz\Models\Quiz;
use Harishdurga\LaravelQuiz\Models\QuizQuestion;
use Harishdurga\LaravelQuiz\Models\Topic;
use Illuminate\Support\Facades\DB;

class CreateQuizCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quiz:create-quiz';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Quiz';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            DB::beginTransaction();
            $topics = Topic::pluck('name', 'id')->toArray();

            $selectedTopic = $this->choice('Choose topic:', $topics);

            $quizName = $this->ask('What is the quiz name?');
            $quizDescription = $this->ask('What is the quiz description', '');
            $totalMarks = $this->ask('Total marks?');
            $passingMarks = $this->ask('Passing marks?');

            $quiz = Quiz::create([
                'name' => $quizName,
                'description' => $quizDescription,
                'slug' => Str::of($quizName)->slug(),
                'total_marks' => $totalMarks,
                'pass_marks' => $passingMarks,
                'max_attempts' => 1,
                'is_published' => 1,
                'valid_from' => now(),
                'valid_upto' => now()->addDay(5),
            ]);

            $selectedTopicId = array_search($selectedTopic, $topics, true);
            $topic = Topic::findOrFail($selectedTopicId);

            $quiz->topics()->attach($topic->id);
            $questions = $topic->questions()->pluck('name', 'questions.id')->toArray();
            $selectedQuestions = $this->choice('Choose Questions (Separate options by comma (,))', $questions, multiple: true);

            foreach ($selectedQuestions as $index => $questionName) {
                ++$index;
                $questionMarks = $this->ask("Marks for this question: {$questionName}");

                $question = array_search($questionName, $questions);
                $quiz_question =  QuizQuestion::create([
                    'quiz_id' => $quiz->id,
                    'question_id' => $question,
                    'marks' => $questionMarks,
                    'order' => $index,
                    'negative_marks' => 0,
                    'is_optional' => false
                ]);
            }

            
            $this->comment('Quiz created successfully.');
            $this->comment($quiz->name);

            DB::commit();
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
        }
    }
}
