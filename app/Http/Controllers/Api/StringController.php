<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AnalyzeStringRequest;
use App\Models\StringEntry;
use App\Services\StringAnalyzer;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class StringController extends Controller
{
    protected $analyzer;

    public function __construct(StringAnalyzer $analyzer)
    {
        $this->analyzer = $analyzer;
    }

    public function store(AnalyzeStringRequest $request)
    {
        $value = $request->input('value');

        $props = $this->analyzer->analyze($value);
        $id = $props['sha256_hash'];

        $payload = [
            'id' => $id,
            'value' => $value,
            'length' => $props['length'],
            'is_palindrome' => $props['is_palindrome'],
            'unique_characters' => $props['unique_characters'],
            'word_count' => $props['word_count'],
            'properties' => $props,
        ];

        try {
            $entry = StringEntry::create($payload);
        } catch (QueryException $e) {
            if ($this->isDuplicateError($e)) {
                return response()->json(['message' => 'String already exists in the system'], 409);
            }
            throw $e;
        }

        return response()->json([
            'id' => $entry->id,
            'value' => $entry->value,
            'properties' => $entry->properties,
            'created_at' => $entry->created_at->toIso8601String(),
        ], 201);
    }

    protected function isDuplicateError(QueryException $e): bool
    {
        $sqlState = $e->errorInfo[0] ?? null;
        return in_array($sqlState, ['23000', '23505']) || str_contains($e->getMessage(), 'Duplicate');
    }

    public function show($stringValue)
    {
        $entry = StringEntry::where('value', $stringValue)->first();

        if (!$entry) {
            return response()->json(['message' => 'String does not exist in the system'], 404);
        }

        return response()->json([
            'id' => $entry->id,
            'value' => $entry->value,
            'properties' => $entry->properties,
            'created_at' => $entry->created_at->toIso8601String(),
        ], 200);
    }

    public function index(Request $request)
    {
        $q = StringEntry::query();

        if ($request->has('is_palindrome')) {
            $val = filter_var($request->query('is_palindrome'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            if (is_null($val)) return response()->json(['message' => 'Invalid is_palindrome value'], 400);
            $q->where('is_palindrome', (int)$val);
        }

        if ($request->has('min_length')) {
            $min = intval($request->query('min_length'));
            $q->where('length', '>=', $min);
        }
        if ($request->has('max_length')) {
            $max = intval($request->query('max_length'));
            $q->where('length', '<=', $max);
        }
        if ($request->has('word_count')) {
            $wc = intval($request->query('word_count'));
            $q->where('word_count', $wc);
        }
        if ($request->has('contains_character')) {
            $char = $request->query('contains_character');
            if (strlen($char) === 0) return response()->json(['message' => 'Invalid contains_character'], 400);
            $q->where('properties', 'like', '%"'.$char.'"%');
        }

        $perPage = min(100, intval($request->query('per_page', 20)));
        $page = intval($request->query('page', 1));

        $paginator = $q->orderBy('created_at', 'desc')->paginate($perPage, ['*'], 'page', $page);

        $data = $paginator->items();
        $response = [
            'data' => array_map(function($entry){
                return [
                    'id' => $entry->id,
                    'value' => $entry->value,
                    'properties' => $entry->properties,
                    'created_at' => $entry->created_at->toIso8601String(),
                ];
            }, $data),
            'count' => $paginator->total(),
            'filters_applied' => $request->only(['is_palindrome','min_length','max_length','word_count','contains_character']),
        ];

        return response()->json($response, 200);
    }

    public function filterByNaturalLanguage(Request $request)
    {
        $query = $request->query('query', '');
        if (trim($query) === '') {
            return response()->json(['message' => 'query parameter is required'], 400);
        }

        $parser = new \App\Services\NaturalLanguageFilterParser();
        try {
            $filters = $parser->parse($query);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }

        $req = new Request($filters);
        $q = StringEntry::query();

        if (isset($filters['is_palindrome'])) {
            $q->where('is_palindrome', (int)$filters['is_palindrome']);
        }
        if (isset($filters['word_count'])) {
            $q->where('word_count', intval($filters['word_count']));
        }
        if (isset($filters['min_length'])) {
            $q->where('length', '>=', intval($filters['min_length']));
        }
        if (isset($filters['contains_character'])) {
            $char = $filters['contains_character'];
            $q->where('properties', 'like', '%"'.$char.'"%');
        }

        $results = $q->get();

        return response()->json([
            'data' => $results->map(function($entry){
                return [
                    'id' => $entry->id,
                    'value' => $entry->value,
                    'properties' => $entry->properties,
                    'created_at' => $entry->created_at->toIso8601String(),
                ];
            }),
            'count' => $results->count(),
            'interpreted_query' => [
                'original' => $query,
                'parsed_filters' => $filters,
            ],
        ], 200);
    }

    public function destroy($stringValue)
    {
        $entry = StringEntry::where('value', $stringValue)->first();

        if (!$entry) {
            return response()->json(['message' => 'String does not exist in the system'], 404);
        }

        $entry->delete();

        return response(null, 204);
    }
}
