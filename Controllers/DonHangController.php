<?php

namespace App\Http\Controllers;

use App\Models\DonHang;
use Illuminate\Http\Request;
use App\Models\DonHang_ChiTiet;
use App\Models\TinhTrang;

class DonHangController extends Controller
{
    public function __construct()
	{
		$this->middleware('auth');
	}

	public function getDanhSach()
	{
		$donhang = DonHang::orderBy('created_at', 'desc')->get();
		return view('donhang.danhsach', compact('donhang'));
	}

	public function getThem()
	{
		// Đặt hàng bên Front-end
	}

	public function postThem(Request $request)
	{
		// Xử lý đặt hàng bên Front-end
	}

	public function getSua($id)
	{
		$donhang = DonHang::find($id);
		$tinhtrang = TinhTrang::all();
		return view('donhang.sua', compact('donhang', 'tinhtrang'));
	}

	public function postSua(Request $request, $id)
	{
		$this->validate($request, [
			'tinhtrang_id' => ['required'],
			'dienthoaigiaohang' => ['required', 'string', 'max:20'],
			'diachigiaohang' => ['required', 'string', 'max:191'],
		]);
		$orm = DonHang::find($id);
		$orm->tinhtrang_id = $request->tinhtrang_id;
		$orm->dienthoaigiaohang = $request->dienthoaigiaohang;
		$orm->diachigiaohang = $request->diachigiaohang;
		$orm->save();
		return redirect()->route('donhang');
	}

	public function getXoa($id)
	{
		$orm = DonHang::find($id);
		$orm->delete();
		$chitiet = DonHang_ChiTiet::where('donhang_id', $orm->id)->first();
		$chitiet->delete();
		return redirect()->route('donhang');
	}
}
