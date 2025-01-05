<?php

class Person
{
    // Constructor promotion:
    public function __construct(
        public string $firstName,
        public string $lastName,
        public int $age,
        public float $salary
    ) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->age = $age;
        $this->salary = $salary;
    }

    public function greet(): string
    {
        return sprintf(
            "%s %s is %d years old and is paid \$%g per month.",
            $this->firstName,
            $this->lastName,
            $this->age,
            $this->salary
        );
    }
}
