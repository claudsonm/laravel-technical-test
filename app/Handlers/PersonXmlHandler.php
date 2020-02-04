<?php

namespace App\Handlers;

use App\Person;

class PersonXmlHandler extends Handler
{
    protected int $successCount = 0;

    protected int $errorCount = 0;

    public function handle(): Handler
    {
        foreach ($this->content['person'] as $pendingPerson) {
            try {
                $person = Person::create([
                    'id' => $pendingPerson['personid'],
                    'name' => $pendingPerson['personname'],
                ]);
                $numbers = $this->getPhonesFrom($pendingPerson);

                foreach ($numbers as $number) {
                    $person->phones()->create(['number' => $number]);
                }

                $this->successCount++;
            } catch (\Exception $e) {
                $this->errorCount++;
            }
        }

        return $this;
    }

    /**
     * @param string|array $person
     *
     * @return array
     */
    protected function getPhonesFrom($person)
    {
        if (is_array($phones = $person['phones']['phone'])) {
            return $phones;
        }

        return [$phones];
    }

    public function getOutput() : array
    {
        $message = 'File processed: '.$this->successCount.' new persons imported and '.$this->errorCount.' persons with error.';
        $level = $this->errorCount > 0 ? 'warning' : 'success';

        return [$message, $level];
    }
}
