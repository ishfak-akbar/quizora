<?php

namespace App\Console\Commands;

use App\Models\Question;
use App\Models\BankQuestion;
use App\Models\BankOption;
use Illuminate\Console\Command;

class BackfillQuestionBank extends Command
{
    protected $signature = 'bank:backfill';
    protected $description = 'Copy all existing quiz questions into the Question Bank';

    public function handle()
    {
        $questions = Question::with(['options', 'quiz'])->get();
        $count = 0;

        foreach ($questions as $question) {
            $exists = BankQuestion::where('teacher_id', $question->quiz->teacher_id)
                ->where('question_text', $question->question_text)
                ->exists();

            if ($exists) continue;

            $bankQuestion = BankQuestion::create([
                'teacher_id'    => $question->quiz->teacher_id,
                'question_text' => $question->question_text,
                'marks'         => $question->marks,
                'category'      => $question->quiz->category,
                'tags'          => $question->quiz->tags,
            ]);

            foreach ($question->options as $option) {
                BankOption::create([
                    'bank_question_id' => $bankQuestion->id,
                    'option_text'      => $option->option_text,
                    'is_correct'       => $option->is_correct,
                    'order'            => $option->order,
                ]);
            }

            $count++;
        }

        $this->info("Backfilled {$count} questions into the bank.");
    }
}
