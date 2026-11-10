<?php

namespace App\Http\Controllers;

use App\Models\StaffSchedule;
use App\Models\User;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function index()
    {
        $totalStaff = User::query()
            ->whereIn('role', [User::ROLE_ADMIN, User::ROLE_KASIR])
            ->count();

        $todaySchedules = StaffSchedule::with('user')
            ->whereDate('duty_date', today())
            ->orderBy('shift')
            ->get();

        $upcoming = StaffSchedule::with('user')
            ->whereDate('duty_date', '>', today())
            ->whereDate('duty_date', '<=', today()->addDays(30))
            ->orderBy('duty_date')
            ->orderBy('shift')
            ->get()
            ->groupBy(fn (StaffSchedule $schedule) => $schedule->duty_date->toDateString());

        $stats = [
            'today' => $todaySchedules->count(),
            'upcoming' => $upcoming->flatten()->count(),
            'total_staff' => $totalStaff,
        ];

        return view('admin.staff.index', compact('todaySchedules', 'upcoming', 'stats'));
    }

    public function cashierIndex()
    {
        $todaySchedules = StaffSchedule::with('user')
            ->whereDate('duty_date', today())
            ->orderBy('shift')
            ->get();

        $upcoming = StaffSchedule::with('user')
            ->whereDate('duty_date', '>', today())
            ->whereDate('duty_date', '<=', today()->addDays(30))
            ->orderBy('duty_date')
            ->orderBy('shift')
            ->get()
            ->groupBy(fn (StaffSchedule $schedule) => $schedule->duty_date->toDateString());

        return view('kasir.staff', compact('todaySchedules', 'upcoming'));
    }

    public function cashierStore(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'duty_date' => ['required', 'date'],
            'shift' => ['required', 'string', 'max:30'],
        ]);

        $exists = StaffSchedule::query()
            ->whereDate('duty_date', $data['duty_date'])
            ->where('shift', $data['shift'])
            ->where(function ($query) use ($data, $request) {
                $query->where('name', $data['name'])
                    ->orWhere('user_id', $request->user()->id)
                    ->orWhereHas('user', fn ($user) => $user->where('name', $data['name']));
            })
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors(['name' => 'Nama ini sudah dijadwalkan pada shift tersebut. Hapus jadwal berikutnya jika ingin mengganti penjaga.']);
        }

        StaffSchedule::create([
            'user_id' => $request->user()->id,
            'name' => $data['name'],
            'duty_date' => $data['duty_date'],
            'shift' => $data['shift'],
        ]);

        return redirect()
            ->route('kasir.staff')
            ->with('status', 'Nama penjaga kasir berhasil disimpan dan masuk ke data admin.');
    }

    public function cashierDestroy(StaffSchedule $schedule)
    {
        if ($schedule->duty_date->isToday()) {
            return back()->withErrors(['duty_date' => 'Jadwal bertugas hari ini tidak bisa dihapus.']);
        }

        if (! $schedule->duty_date->isFuture()) {
            return back()->withErrors(['duty_date' => 'Hanya jadwal berikutnya yang bisa dihapus.']);
        }

        $schedule->delete();

        return redirect()
            ->route('kasir.staff')
            ->with('status', 'Jadwal berhasil dihapus. Silakan isi nama penjaga pengganti.');
    }
}
