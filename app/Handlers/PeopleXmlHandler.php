<?php

namespace App\Handlers;

use App\Person;

class PeopleXmlHandler extends Handler
{
    protected int $successCount = 0;

    protected int $errorCount = 0;

    public function handle(): Handler
    {
        foreach ($this->content->children() as $pendingPerson) {
            try {
                $person = Person::create([
                    'id' => $pendingPerson->personid,
                    'name' => $pendingPerson->personname,
                ]);

                foreach ($pendingPerson->phones->children() as $number) {
                    $person->phones()->create(['number' => $number]);
                }

                $this->successCount++;
            } catch (\Exception $e) {
                $this->errorCount++;
            }
        }

        return $this;
    }

    public function getOutput() : array
    {
        $message = 'File processed: '.$this->successCount.' new persons imported and '.$this->errorCount.' persons with error.';
        $level = $this->errorCount > 0 ? 'warning' : 'success';

        return [$message, $level];
    }
}
