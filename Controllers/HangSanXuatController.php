<?php

namespace App\Http\Controllers;

use App\Models\HangSanXuat;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Imports\HangSanXuatImport;
use App\Exports\HangSanXuatExport;
use Excel;


class HangSanXuatController extends Controller
{
    public function getDanhSach()
	{
		$hangsanxuat = HangSanXuat::all();
		return view('hangsanxuat.danhsach', compact('hangsanxuat'));
	}
	public function getThem()
	{
		return view('hangsanxuat.them');
	}
	public function postThem(Request $request)
	{	
		$request->validate([
			'tenhang' => ['required', 'max:255', 'unique:hangsanxuat'],
			'hinhanh' => ['nullable', 'image', 'max:1024']
		]);
		
		$path = '';
		if($request->hasFile('hinhanh'))
		{
		$extension = $request->file('hinhanh')->extension();
		$filename = Str::slug($request->tenhang, '-') . '.' . $extension;
		$path = Storage::putFileAs('hang-san-xuat', $request->file('hinhanh'), $filename);
		}

		$orm = new HangSanXuat();
		$orm->tenhang = $request->tenhang;
		$orm->tenhang_slug = Str::slug($request->tenhang, '-');
		if(!empty($path)) $orm->hinhanh = $path;
		$orm->save();
		return redirect()->route('hangsanxuat');
	}
	public function getSua($id)
	{
		$hangsanxuat = HangSanXuat::find($id);
		return view('hangsanxuat.sua', compact('hangsanxuat'));
	}
	public function postSua(Request $request, $id)
	{
		$request->validate([
			'tenhang' => ['required', 'max:255', 'unique:hangsanxuat,tenhang,'  . $id],
			'hinhanh' => ['nullable', 'image', 'max:1024']
		]);
		
		// Upload hình ảnh
		$path = '';
		if($request->hasFile('hinhanh'))
		{
			// Xóa file cũ
			$orm = HangSanXuat::find($id);
			if(!empty($orm->hinhanh)) Storage::delete($orm->hinhanh);
			// Upload file mới
			$extension = $request->file('hinhanh')->extension();
			$filename = Str::slug($request->tenhang, '-') . '.' . $extension;
			$path = Storage::putFileAs('hang-san-xuat', $request->file('hinhanh'), $filename);
		}
		
		$orm = HangSanXuat::find($id);
		$orm->tenhang = $request->tenhang;
		$orm->tenhang_slug = Str::slug($request->tenhang, '-');
		if(!empty($path)) $orm->hinhanh = $path;
		$orm->save();
		return redirect()->route('hangsanxuat');
	}
	public function getXoa($id)
	{
		$orm = HangSanXuat::find($id);
		$orm->delete();
		if(!empty($orm->hinhanh)) Storage::delete($orm->hinhanh);
		return redirect()->route('hangsanxuat');
	}
	public function postNhap(Request $request)
	{
		Excel::import(new HangSanXuatImport, $request->file('file_excel'));
		return redirect()->route('hangsanxuat');
	}
	// Xuất ra Excel
	public function getXuat()
	{
		return Excel::download(new SanPhamExport, 'danh-sach-san-pham.xlsx');
	}
}
