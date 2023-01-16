<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        $keys = [
            'price' => 'p.price',
            'article' => 'p.article',
            'category' => 'c.name',
            'color' => 'cp.color',
            'size' => 'cp.size',
            'material' => 'm.name',
            'description' => 'ep.description',
            'composition' => 'fp.composition',
            'weight' => 'fp.weight',
            'brand' => 'b.name'
        ];

        $params = $request->json()->all();
        $sortBy = $params['sortBy'] ?? 'product';

        if ($params['category'] == 'clothis') {
            if (isset($params['column']) && isset($params['condition'])) {
                $product = DB::table('products AS p')
                    ->leftJoin('categories AS c', 'p.category_id', '=', 'c.id')
                    ->leftJoin('clothis_params AS cp', 'p.id', '=', 'cp.product_id')
                    ->leftJoin('materials AS m', 'm.id', '=', 'cp.material_id')
                    ->select('p.name AS product',
                        'p.price AS price',
                        'p.article AS article',
                        'c.name AS category',
                        'cp.color AS color',
                        'cp.size AS size',
                        'm.name AS material',
                    )
                    ->where($keys['category'], '=', $params['category'])
                    ->where($keys[$params['column']], '=', $params['condition'])
                    ->groupBy('product', 'category', 'price', 'article', 'color', 'size', 'material')
                    ->orderBy($sortBy)
                    ->get();
            } elseif (!isset($params['column']) && !isset($params['condition'])) {
                $product = DB::table('products AS p')
                    ->leftJoin('categories AS c', 'p.category_id', '=', 'c.id')
                    ->leftJoin('clothis_params AS cp', 'p.id', '=', 'cp.product_id')
                    ->leftJoin('materials AS m', 'm.id', '=', 'cp.material_id')
                    ->select('p.name AS product',
                        'p.price AS price',
                        'p.article AS article',
                        'c.name AS category',
                        'cp.color AS color',
                        'cp.size AS size',
                        'm.name AS material',
                    )
                    ->where($keys['category'], '=', $params['category'])
                    ->groupBy('product', 'category', 'price', 'article', 'color', 'size', 'material')
                    ->orderBy($sortBy)
                    ->get();
            } else {
                return response()->json('Ошибка в переданных параметрах');
            }
        }

        if ($params['category'] == 'food') {
            if (isset($params['column']) && isset($params['condition'])) {
                $product = DB::table('products AS p')
                    ->leftJoin('categories AS c', 'p.category_id', '=', 'c.id')
                    ->leftJoin('food_params AS fp', 'p.id', '=', 'fp.product_id')
                    ->select('p.name AS product',
                        'p.price AS price',
                        'p.article AS article',
                        'c.name AS category',
                        'fp.composition AS composition',
                        'fp.weight AS weight',
                    )
                    ->where($keys['category'], '=', $params['category'])
                    ->where($keys[$params['column']], '=', $params['condition'])
                    ->groupBy('product', 'category', 'price', 'article', 'composition', 'weight')
                    ->orderBy($sortBy)
                    ->get();
            } elseif (!isset($params['column']) || !isset($params['condition'])) {
                $product = DB::table('products AS p')
                    ->leftJoin('categories AS c', 'p.category_id', '=', 'c.id')
                    ->leftJoin('food_params AS fp', 'p.id', '=', 'fp.product_id')
                    ->select('p.name AS product',
                        'p.price AS price',
                        'p.article AS article',
                        'c.name AS category',
                        'fp.composition AS composition',
                        'fp.weight AS weight',
                    )
                    ->where($keys['category'], '=', $params['category'])
                    ->groupBy('product', 'category', 'price', 'article', 'composition', 'weight')
                    ->orderBy($sortBy)
                    ->get();
            } else {
                return response()->json('Ошибка в переданных параметрах');
            }
        }

        if ($params['category'] == 'electronic') {
            if (isset($params['column']) && isset($params['condition'])) {
                $product = DB::table('products AS p')
                    ->leftJoin('categories AS c', 'p.category_id', '=', 'c.id')
                    ->leftJoin('electronic_params AS ep', 'p.id', '=', 'ep.product_id')
                    ->leftJoin('brands AS b', 'b.id', '=', 'ep.brand_id')
                    ->select('p.name AS product',
                        'p.price AS price',
                        'p.article AS article',
                        'c.name AS category',
                        'b.name AS brand',
                        'ep.description AS description',
                    )
                    ->where($keys['category'], '=', $params['category'])
                    ->where($keys[$params['column']], '=', $params['condition'])
                    ->groupBy('product', 'category', 'price', 'article', 'brand', 'description')
                    ->orderBy('price')
                    ->get();
            } elseif (!isset($params['column']) || !isset($params['condition'])) {
                $product = DB::table('products AS p')
                    ->leftJoin('categories AS c', 'p.category_id', '=', 'c.id')
                    ->leftJoin('electronic_params AS ep', 'p.id', '=', 'ep.product_id')
                    ->leftJoin('brands AS b', 'b.id', '=', 'ep.brand_id')
                    ->select('p.name AS product',
                        'p.price AS price',
                        'p.article AS article',
                        'c.name AS category',
                        'b.name AS brand',
                        'ep.description AS description',
                    )
                    ->where($keys['category'], '=', $params['category'])
                    ->groupBy('product', 'category', 'price', 'article', 'brand', 'description')
                    ->orderBy('price')
                    ->get();
            } else {
                return response()->json('Ошибка в переданных параметрах');
            }

        }

        return response()->json($product);
    }
}
