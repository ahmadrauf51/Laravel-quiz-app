<?php

namespace App\Console\Commands;

use App\Models\User;
use Harishdurga\LaravelQuiz\Models\QuestionOption;
use Harishdurga\LaravelQuiz\Models\Quiz;
use Harishdurga\LaravelQuiz\Models\QuizAttempt;
use Harishdurga\LaravelQuiz\Models\QuizAttemptAnswer;
use Illuminate\Console\Command;

class AttemptQuiz extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quiz:attempt {quiz : Quiz id to attempt}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Attempt Quiz';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $quiz = Quiz::find( $this->argument('quiz'));

        $userId = $this->ask('Enter user id');

        $participant = User::find($userId);

        $quiz_attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'participant_id' => $participant->id,
            'participant_type' => get_class($participant)
        ]);

        if($quiz)
        {
            foreach($quiz->questions as $quizQuestion)
            {
                if($quizQuestion->question)
                {
                    $options = $quizQuestion->question->options()->pluck('name', 'id')->toArray();
                    $selectedOption = $this->choice($quizQuestion->question->name,$options);

                    QuizAttemptAnswer::create(
                        [
                            'quiz_attempt_id' => $quiz_attempt->id,
                            'quiz_question_id' => $quizQuestion->question->id,
                            'question_option_id' => array_search($selectedOption, $options,true),
                        ]
                    );
                }
            }

            $this->comment('Quiz total marks are: '. $quiz->total_marks);
            $this->comment('Your marks are: '. $quiz_attempt->calculate_score());
            
        } else {
            $this->error("No Quiz found");
        }
    }
}
