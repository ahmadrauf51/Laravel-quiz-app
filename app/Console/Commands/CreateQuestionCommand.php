<?php

namespace App\Console\Commands;

use Harishdurga\LaravelQuiz\Models\Question;
use Harishdurga\LaravelQuiz\Models\QuestionOption;
use Harishdurga\LaravelQuiz\Models\QuestionType;
use Harishdurga\LaravelQuiz\Models\Topic;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateQuestionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quiz:create-question {topic : The topic which will be assigned to these questions}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new Questions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            DB::beginTransaction();
            
            $topic = Topic::findOrFail($this->argument('topic'));

            $question = $this->ask('What is the question?');

            if (!$question) {
                $this->error('Please enter a correct question.');
                return;
            }
            // Get question type value
            $questionTypes = QuestionType::pluck('name', 'id')->toArray();
            $questionType = $this->choice('Select question type:', $questionTypes, 1);

            $selectedQuestionType = array_search($questionType, $questionTypes, true);

            // create question
            $question = Question::create([
                'name' => $question,
                'question_type_id' => $selectedQuestionType,
                'is_active' => true,
            ]);

            $question->topics()->attach($topic->id);

            $nextOption = 'Yes';
            $index = 1;

            while($nextOption == 'Yes') {
                $optionValue = $this->ask("Option# {$index}:");
                $isCorrectOption = $this->choice('Is this the correct option?', ['No', 'Yes'], 0);

                QuestionOption::create([
                    'question_id' => $question->id,
                    'name' => $optionValue,
                    'is_correct' => $isCorrectOption == 'Yes' ? true : false,
                ]);

                $nextOption = $this->choice('Want to add new option?', ['Yes', 'No'], 0);

                $index++;
            }
            
            $this->comment("Question created successfully.");

            DB::commit();
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();

            $this->error('Something went wrong.' . $th->getMessage());
        }
        
    }
}
