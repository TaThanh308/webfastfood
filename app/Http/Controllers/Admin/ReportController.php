<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Carbon\Carbon;
use DB;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }
    public function getRevenueData(Request $request)
    {
        $year = $request->input('year', Carbon::now()->year);
    
        // Lấy doanh thu theo tháng
        $revenueData = Order::selectRaw('MONTH(created_at) as month, SUM(total_price) as revenue')
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    
        // Lấy sản phẩm bán chạy theo từng tháng (sản phẩm có số lượng bán cao nhất)
        $topSellingProducts = DB::table('order_items')
            ->select(
                DB::raw('MONTH(orders.created_at) as month'),
                'products.name as product_name',
                DB::raw('SUM(order_items.quantity) as total_sold')
            )
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->whereYear('orders.created_at', $year)
            ->groupBy(DB::raw('MONTH(orders.created_at)'), 'products.id', 'products.name')
            ->orderBy('total_sold', 'DESC')
            ->get()
            ->groupBy('month') // Nhóm theo tháng
            ->map(function ($items) {
                return $items->first(); // Lấy sản phẩm có số lượng bán nhiều nhất mỗi tháng
            })
            ->values(); // Định dạng lại danh sách JSON
    
        return response()->json([
            'revenueData' => $revenueData,
            'topSellingProducts' => $topSellingProducts
        ]);
    }
    
    
}
