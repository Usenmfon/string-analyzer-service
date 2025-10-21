<?php
namespace App\Services;

class NaturalLanguageFilterParser
{
    public function parse(string $text): array
    {
        $text = mb_strtolower(trim($text), 'UTF-8');

        $filters = [];

        if (preg_match('/\b(single word|one word)\b/', $text)) {
            $filters['word_count'] = 1;
        }

        if (preg_match('/\b(palindromic|palindrome|palindromic strings|palindromes)\b/', $text)) {
            $filters['is_palindrome'] = true;
        }

        if (preg_match('/longer than (\d+)\b/', $text, $m)) {
            $filters['min_length'] = intval($m[1]) + 1;
        }

        if (preg_match('/(longer than or equal to|at least) (\d+)\b/', $text, $m)) {
            $filters['min_length'] = intval($m[2]);
        }

        if (preg_match('/shorter than (\d+)\b/', $text, $m)) {
            $filters['max_length'] = intval($m[1]) - 1;
        }

        if (preg_match('/contain(?:ing)? (?:the )?letter (\w)\b/', $text, $m)) {
            $filters['contains_character'] = $m[1];
        }

        if (!isset($filters['contains_character']) && preg_match('/contains (?:the )?([a-z0-9])\b/', $text, $m)) {
            $filters['contains_character'] = $m[1];
        }

        if (preg_match('/\b(\d+)[ -]?word\b/', $text, $m)) {
            $filters['word_count'] = intval($m[1]);
        } elseif (preg_match('/\b(two[- ]word|three[- ]word|one[- ]word)\b/', $text, $m)) {
            $map = ['one' => 1, 'two' => 2, 'three' => 3];
            foreach ($map as $k => $v) {
                if (strpos($m[0], $k) !== false) {
                    $filters['word_count'] = $v;
                }
            }
        }

        if (empty($filters)) {
            throw new \InvalidArgumentException('Unable to parse natural language query');
        }

        return $filters;
    }
}
