<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Harishdurga\LaravelQuiz\Models\Topic;

class CreateTopicCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quiz:create-topic {parentTopicId? : Parent topic ID if this is going to be a sub-topic}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new topic';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $topicName = $this->ask('Enter topic name?');

        $slug = Str::of($topicName)->slug();

        $newTopic = Topic::create([
            'name' => $topicName,
            'slug' => $slug,
        ]);

        if ($parentTopicId = $this->argument('parentTopicId')) {
            $parentTopic = Topic::findOrFail($parentTopicId);

            $parentTopic->children()->save($newTopic);
        }

        $this->comment('Topic created successfully');
    }
}
