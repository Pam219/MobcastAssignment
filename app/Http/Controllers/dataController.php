<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\Paginator;


class dataController extends Controller
{
    public function index(Request $request)
    {
        $response = Http::get('https://timesofindia.indiatimes.com/rssfeeds/-2128838597.cms?feedtype=json');
        $data = $response->json();
        
        // Process each item to ensure the correct structure
        $items = array_map(function($item) {
            if (isset($item['dc:creator']['#text'])) {
                $item['dc:creator'] = $item['dc:creator']['#text'];
            } else {
                $item['dc:creator'] = 'N/A';
            }
            if (isset($item['description'])) {
                $item['description'] = is_array($item['description']) && isset($item['description']['#text']) ? $item['description']['#text'] : $item['description'];
            } else {
                $item['description'] = 'N/A';
            }
            $item['title'] = $item['title'] ?? 'N/A';
            $item['link'] = $item['link'] ?? 'N/A';
            $item['pubDate'] = $item['pubDate'] ?? 'N/A';
            return $item;
        }, $data['channel']['item']);
        
        // Get the search query and sorting parameters
        $search = $request->input('search', '');
        $sortField = $request->input('sort', 'title');
    
        // Filter items based on search query if it's not empty
        if (!empty($search)) {
            $items = $this->filterItems($items, $search);
        }
    
        // Sort items based on sorting parameters
        $sortedItems = $this->sortItems($items, $sortField);
    
        // Paginate the sorted items
        $sortedItems = $this->sortItems($items, $sortField);

        // Paginate the sorted items
        $perPage = 10; // Number of items per page
        $currentPage = Paginator::resolveCurrentPage() ?: 1;
        $items = new \Illuminate\Pagination\LengthAwarePaginator(
            array_slice($sortedItems, ($currentPage - 1) * $perPage, $perPage),
            count($sortedItems),
            $perPage,
            $currentPage,
            ['path' => Paginator::resolveCurrentPath()]
        );
        return view('index', ['data' => $items, 'search' => $search, 'sort' => $sortField]);
    }

    private function filterItems(array $items, string $search): array
    {
        if (empty($search)) {
            return $items;
        }

        return array_filter($items, function($item) use ($search) {
            return stripos($item['title'], $search) !== false || 
                   stripos($item['description'], $search) !== false || 
                   stripos($item['dc:creator'] ?? '', $search) !== false || 
                   stripos($item['pubDate'], $search) !== false;
        });
    }

    private function sortItems(array $items, string $sortField): array
    {
        usort($items, function($a, $b) use ($sortField) {
            $valueA = $a[$sortField] ?? '';
            $valueB = $b[$sortField] ?? '';

            return strcmp($valueA, $valueB);
        });

        return $items;
    }
}
