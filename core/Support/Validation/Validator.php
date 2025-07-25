<?php
/**
 * This class is written by @Kashif Sohail
 * Simple Validator class for showing validation messages.
 * Use Validator::validate() to check validation rules.
 */

namespace Core\Support\Validation;

use Core\Support\DB;
use Core\Support\Session;

class Validator
{
    protected static array $messages = [];

    /**
     * @param array $fields
     * @param array $rules
     * @return Validator|string
     */
    public static function validate(array $fields, array $rules): Validator|string
    {
        if (empty($rules)) {
            return "Please provide the validation rules.";
        }

        foreach ($rules as $name => $rule) {
            if (array_key_exists($name, $fields)) {
                self::applyRules($name, $fields[$name], $rule);
            }
        }

        return new self();
    }

    /**
     * @return bool
     */
    public function fails(): bool
    {
        return !empty(self::$messages);
    }

    /**
     * @return string|bool
     */
    public function messages(): string|bool
    {
        return $this->fails() ? implode("<br>", self::$messages) : true;
    }

    /**
     * @return array|bool
     */
    public function errors(): array|bool
    {
        return $this->fails() ? self::$messages : true;
    }

    /**
     * @param string $field
     * @return string|bool
     */
    public function error(string $field): string|bool
    {
        return self::$messages[$field] ?? true;
    }

    /**
     * @return string|bool
     */
    public function first(): string|bool
    {
        return $this->fails() ? reset(self::$messages) : true;
    }

    /**
     * @param string $value
     * @return string
     */
    public static function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    /**
     * @param $field
     * @return bool
     */
    public static function validation($field): bool
    {
        $error = Session::get();
        if (array_key_exists('error_key', $error)) {
            if (isset(Session::get('error_key')[$field])) {
                return true;
            }

            return false;
        }

        return false;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @param string $rules
     * @return void
     */
    private static function applyRules(string $name, mixed $value, string $rules): void
    {
        $rulesArray = explode('|', $rules);

        foreach ($rulesArray as $rule) {

            match (true) {
                $rule === 'required' && $value === '' => self::addMessage($name, 'field is required.'),

                $rule === 'mail' && $value !== '' && !filter_var($value, FILTER_VALIDATE_EMAIL) =>
                self::addMessage($name, 'field is not a valid email.'),

                str_starts_with($rule, 'unique:') && $value !== '' => self::checkUniqueness($name, $value, $rule),

                $rule === 'date' && $value !== '' && !self::validateDate($value) =>
                self::addMessage($name, 'field is not a valid date.'),

                str_starts_with($rule, 'min:') && $value !== '' && strlen($value) < (int)substr($rule, 4) =>
                self::addMessage($name, "minimum length should be " . substr($rule, 4)),

                str_starts_with($rule, 'max:') && $value !== '' && strlen($value) > (int)substr($rule, 4) =>
                self::addMessage($name, "maximum length should be " . substr($rule, 4)),

                $rule === 'numeric' && $value !== '' && !is_numeric($value) =>
                self::addMessage($name, 'field must be numeric.'),

                $rule === 'alphabet' && $value !== '' && !preg_match('/^[\p{L}\s\-]+$/u', $value) =>
                self::addMessage($name, 'field must contain only alphabetic characters.'),

                str_starts_with($rule, 'regex:') && $value !== '' && !preg_match(substr($rule, 6), $value) =>
                self::addMessage($name, 'field format is invalid.'),

                default => null,
            };
        }
    }

    /**
     * @param string $field
     * @param string $message
     * @return void
     */
    private static function addMessage(string $field, string $message): void
    {
        self::$messages[$field] = "The " . self::humanize($field) . " $message";
    }

    /**
     * @param string $date
     * @return bool
     */
    private static function validateDate(string $date): bool
    {
        $d = \DateTime::createFromFormat('d-m-Y', $date);
        return $d && $d->format('d-m-Y') === $date;
    }

    /**
     * @param string $field
     * @return string
     */
    private static function humanize(string $field): string
    {
        return str_replace('_', ' ', $field);
    }

    /**
     * @param string $field
     * @param mixed $value
     * @param string $rule
     * @return void
     */
    private static function checkUniqueness(string $field, mixed $value, string $rule): void
    {
        $parts = explode(',', substr($rule, 7));

        if (count($parts) < 2) {
            self::$messages[$field] = "Invalid unique rule format for $field.";
            return;
        }

        [$table, $column, $exceptId, $idColumn] = array_pad($parts, 4, null);
        $idColumn = $idColumn ?? 'id';

        if (self::recordExists($table, $column, $value, $exceptId, $idColumn)) {
            self::addMessage($field, 'has already been taken.');
        }
    }

    /**
     * @param string $table
     * @param string $column
     * @param mixed $value
     * @param mixed|null $exceptId
     * @param string $idColumn
     * @return bool
     */
    private static function recordExists(string $table, string $column, mixed $value, mixed $exceptId = null, string $idColumn = 'id'): bool
    {
        $query = DB::table($table)->where($column, '=', $value);

        if ($exceptId !== null) {
            $query->where($idColumn, '!=', $exceptId);
        }

        return $query->exists();
    }
}
