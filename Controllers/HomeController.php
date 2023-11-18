<?php

namespace App\Http\Controllers;

use App\Models\DonHang;
use App\Models\DonHang_ChiTiet;
use App\Mail\DatHangEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
	
	public function getDatHangDemo() 
    { 
        // Thêm Đơn hàng demo 
        $donhang = new DonHang(); 
        $donhang->user_id = Auth::user()->id; 
        $donhang->tinhtrang_id = 2; // Lưu ý: Bảng tinhtrang phải có dữ liệu có id = 1 
        $donhang->dienthoaigiaohang = '0939011900'; 
        $donhang->diachigiaohang = '18 Ung Văn Khiêm - TP. Long Xuyên - An Giang'; 
        $donhang->save(); 
        
        // Thêm Đơn hàng chi tiết demo 
        $donhang_chitiet = new DonHang_ChiTiet(); 
        $donhang_chitiet->donhang_id = $donhang->id;
        $donhang_chitiet->sanpham_id = 6; // Lưu ý: Bảng sanpham phải có dữ liệu có id = 1 
        $donhang_chitiet->soluongban = 1; 
        $donhang_chitiet->dongiaban = 5990000; 
        $donhang_chitiet->save();

        $donhang_chitiet = new DonHang_ChiTiet(); 
        $donhang_chitiet->donhang_id = $donhang->id; 
        $donhang_chitiet->sanpham_id = 7; // Lưu ý: Bảng sanpham phải có dữ liệu có id = 2 
        $donhang_chitiet->soluongban = 1; 
        $donhang_chitiet->dongiaban = 350000; 
        $donhang_chitiet->save(); 
        
        // Gởi email
        Mail::to(Auth::user()->email)->send(new DatHangEmail($donhang));
        
        return redirect()->route('frontend.dathangdemo');
    }
}
