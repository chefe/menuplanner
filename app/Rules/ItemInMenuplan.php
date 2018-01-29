<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Menuplan;
use Illuminate\Support\Facades\DB;

class ItemInMenuplan implements Rule
{
    /**
     * Holds the instance of the menuplan which
     * the rule should compare against
     */
    private $menuplan;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(Menuplan $menuplan)
    {
        $this->menuplan = $menuplan;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return DB::table('items')
            ->where('menuplan_id', $this->menuplan->id)
            ->where('id', $value)
            ->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The item does not exist in this menuplan.';
    }
}
