<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Simple rule-based validator.
 * Rules: required, email, numeric, int, min:n, max:n, in:a,b,c, url, date, boolean, slug
 * Usage:
 *   $v = new Validator($data, ['name' => 'required|max:120', 'email' => 'required|email']);
 *   if ($v->fails()) { $errors = $v->errors(); }
 */
final class Validator
{
    private array $data;
    private array $rules;
    private array $errors = [];
    private array $labels;

    public function __construct(array $data, array $rules, array $labels = [])
    {
        $this->data = $data;
        $this->rules = $rules;
        $this->labels = $labels;
        $this->run();
    }

    private function label(string $field): string
    {
        return $this->labels[$field] ?? ucfirst(str_replace('_', ' ', $field));
    }

    private function run(): void
    {
        foreach ($this->rules as $field => $ruleString) {
            $value = $this->data[$field] ?? null;
            $rules = explode('|', $ruleString);
            $required = in_array('required', $rules, true);

            if (!$required && ($value === null || $value === '')) {
                continue; // optional & empty → skip
            }

            // A field is treated as numeric only when it declares int/numeric.
            // Otherwise min/max apply to STRING LENGTH — so a digit-only value
            // like a phone number is measured by character count, not value.
            $numericField = false;
            foreach ($rules as $r) {
                $rname = explode(':', $r, 2)[0];
                if ($rname === 'int' || $rname === 'numeric') {
                    $numericField = true;
                    break;
                }
            }

            foreach ($rules as $rule) {
                [$name, $param] = array_pad(explode(':', $rule, 2), 2, null);
                $this->applyRule($field, $value, $name, $param, $numericField);
            }
        }
    }

    private function applyRule(string $field, $value, string $rule, ?string $param, bool $numericField = false): void
    {
        $label = $this->label($field);
        switch ($rule) {
            case 'required':
                if ($value === null || trim((string) $value) === '') {
                    $this->addError($field, "{$label} is required.");
                }
                break;
            case 'email':
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($field, "{$label} must be a valid email address.");
                }
                break;
            case 'numeric':
                if (!is_numeric($value)) {
                    $this->addError($field, "{$label} must be a number.");
                }
                break;
            case 'int':
                if (filter_var($value, FILTER_VALIDATE_INT) === false) {
                    $this->addError($field, "{$label} must be a whole number.");
                }
                break;
            case 'min':
                if ($numericField) {
                    if ((float) $value < (float) $param) {
                        $this->addError($field, "{$label} must be at least {$param}.");
                    }
                } elseif (mb_strlen((string) $value) < (int) $param) {
                    $this->addError($field, "{$label} must be at least {$param} characters.");
                }
                break;
            case 'max':
                if ($numericField) {
                    if ((float) $value > (float) $param) {
                        $this->addError($field, "{$label} must not exceed {$param}.");
                    }
                } elseif (mb_strlen((string) $value) > (int) $param) {
                    $this->addError($field, "{$label} must not exceed {$param} characters.");
                }
                break;
            case 'in':
                $allowed = explode(',', (string) $param);
                if (!in_array((string) $value, $allowed, true)) {
                    $this->addError($field, "{$label} is invalid.");
                }
                break;
            case 'url':
                if (!filter_var($value, FILTER_VALIDATE_URL)) {
                    $this->addError($field, "{$label} must be a valid URL.");
                }
                break;
            case 'date':
                if (strtotime((string) $value) === false) {
                    $this->addError($field, "{$label} must be a valid date.");
                }
                break;
            case 'boolean':
                if (!in_array((string) $value, ['0', '1', 'true', 'false', 'on', ''], true)) {
                    $this->addError($field, "{$label} must be true or false.");
                }
                break;
            case 'slug':
                if (!preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', (string) $value)) {
                    $this->addError($field, "{$label} may only contain lowercase letters, numbers and hyphens.");
                }
                break;
        }
    }

    private function addError(string $field, string $message): void
    {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = $message;
        }
    }

    public function fails(): bool
    {
        return !empty($this->errors);
    }

    public function passes(): bool
    {
        return empty($this->errors);
    }

    public function errors(): array
    {
        return $this->errors;
    }
}
