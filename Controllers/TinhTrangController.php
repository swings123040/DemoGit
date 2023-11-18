<?php

namespace App\Http\Controllers;

use App\Models\TinhTrang;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TinhTrangController extends Controller
{
    public function getDanhSach()
	{
		$tinhtrang = TinhTrang::all();
		return view('tinhtrang.danhsach', compact('tinhtrang'));
	}
	public function getThem()
	{
		return view('tinhtrang.them');
	}
	public function postThem(Request $request)
	{	
		$request->validate([
			'tinhtrang' => ['required', 'max:255', 'unique:tinhtrang'],
		]);
		
		$orm = new TinhTrang();
		$orm->tinhtrang = $request->tinhtrang;
		$orm->save();
		return redirect()->route('tinhtrang');
	}
	public function getSua($id)
	{
		$tinhtrang = TinhTrang::find($id);
		return view('tinhtrang.sua', compact('tinhtrang'));
	}
	public function postSua(Request $request, $id)
	{
		$request->validate([
			'tinhtrang' => ['required', 'max:255', 'unique:tinhtrang'],
		]);
		
		$orm = TinhTrang::find($id);
		$orm->tinhtrang = $request->tinhtrang;
		$orm->save();
		return redirect()->route('tinhtrang');
	}
	public function getXoa($id)
	{
		$orm = TinhTrang::find($id);
		$orm->delete();
		return redirect()->route('tinhtrang');
	}
}
