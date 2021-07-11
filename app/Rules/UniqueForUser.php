<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UniqueForUser implements Rule
{
    /**
     * @var string
     */
    private $tableName;
    /**
     * @var string
     */
    private $userId;
    /**
     * @var string
     */
    private $columnName;

    /**
     * Create a new rule instance.
     *
     * @param string $tableName
     * @param string $userId
     * @param string $columnName
     */
    public function __construct(string $tableName, string $userId = null, string $columnName = null)
    {
        //
        $this->tableName = $tableName;
        $this->userId = $userId;
        $this->columnName = $columnName;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $field = $this->columnName ? $this->columnName : $attribute;
        $this->columnName = $field;
        return !DB::table($this->tableName)
            ->where($field, $value)
            ->where('user_id', $this->userId)
            ->count();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "تکراری می باشد{$this->columnName} فیلد ";
    }
}
