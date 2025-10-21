<?php

namespace App\Services;
class StringAnalyzer
{
    public function analyze(string $value): array
    {
        $normalized = mb_strtolower($value, 'UTF-8');

        $length = mb_strlen($value, 'UTF-8');

        $isPalindrome = $this->isPalindrome($normalized);

        $uniqueCharacters = $this->countUniqueCharacters($value);

        $wordCount = $this->countWords($value);

        $sha256 = hash('sha256', $value);

        $freq = $this->characterFrequencyMap($value);

        return [
            'length' => $length,
            'is_palindrome' => $isPalindrome,
            'unique_characters' => $uniqueCharacters,
            'word_count' => $wordCount,
            'sha256_hash' => $sha256,
            'character_frequency_map' => $freq,
        ];
    }

    protected function isPalindrome(string $s): bool
    {
        $reversed = $this->mbStrRev($s);
        return $s === $reversed;
    }

    protected function mbStrRev(string $str): string
    {
        $len = mb_strlen($str, 'UTF-8');
        $rev = '';
        for ($i = $len - 1; $i >= 0; $i--) {
            $rev .= mb_substr($str, $i, 1, 'UTF-8');
        }
        return $rev;
    }

    protected function countUniqueCharacters(string $s): int
    {
        $chars = [];
        $len = mb_strlen($s, 'UTF-8');
        for ($i = 0; $i < $len; $i++) {
            $c = mb_substr($s, $i, 1, 'UTF-8');
            $chars[$c] = true;
        }
        return count($chars);
    }

    protected function countWords(string $s): int
    {
        $trimmed = trim($s);
        if ($trimmed === '') return 0;
        $parts = preg_split('/\s+/u', $trimmed, -1, PREG_SPLIT_NO_EMPTY);
        return is_array($parts) ? count($parts) : 0;
    }

    protected function characterFrequencyMap(string $s): array
    {
        $map = [];
        $len = mb_strlen($s, 'UTF-8');
        for ($i = 0; $i < $len; $i++) {
            $c = mb_substr($s, $i, 1, 'UTF-8');
            if (!isset($map[$c])) $map[$c] = 0;
            $map[$c]++;
        }
        return $map;
    }
}
