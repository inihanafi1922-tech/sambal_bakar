<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class FinanceController extends Controller
{
    public function index(Request $request)
    {
        $selectedYear = (int) $request->query('year', now()->year);

        $minDate = Transaction::min('transaction_date');
        $firstYear = $minDate ? Carbon::parse($minDate)->year : now()->year;
        $firstYear = min($firstYear, $selectedYear);

        $yearOptions = range($firstYear, max((int) now()->year, $selectedYear));
        sort($yearOptions);

        $yearTransactions = Transaction::query()
            ->whereYear('transaction_date', $selectedYear)
            ->get();

        $monthlyIncome = array_fill(1, 12, 0);
        $monthlyExpense = array_fill(1, 12, 0);

        foreach ($yearTransactions as $transaction) {
            $month = (int) $transaction->transaction_date->format('n');

            if ($transaction->isIncome()) {
                $monthlyIncome[$month] += $transaction->amount;
            } else {
                $monthlyExpense[$month] += $transaction->amount;
            }
        }

        $yearlyStart = Carbon::create($selectedYear - 4, 1, 1)->startOfDay();
        $yearlyEnd = Carbon::create($selectedYear, 12, 31)->endOfDay();

        $rangeTransactions = Transaction::query()
            ->whereBetween('transaction_date', [$yearlyStart->toDateString(), $yearlyEnd->toDateString()])
            ->get();

        $yearlyLabels = [];
        $yearlyIncome = [];
        $yearlyExpense = [];

        for ($year = $selectedYear - 4; $year <= $selectedYear; $year++) {
            $yearlyLabels[] = (string) $year;
            $yearlyIncome[] = 0;
            $yearlyExpense[] = 0;
        }

        foreach ($rangeTransactions as $transaction) {
            $index = (int) $transaction->transaction_date->format('Y') - ($selectedYear - 4);

            if (! isset($yearlyLabels[$index])) {
                continue;
            }

            if ($transaction->isIncome()) {
                $yearlyIncome[$index] += $transaction->amount;
            } else {
                $yearlyExpense[$index] += $transaction->amount;
            }
        }

        $todayIncome = Transaction::query()
            ->whereDate('transaction_date', today())
            ->where('type', Transaction::TYPE_INCOME)
            ->sum('amount');

        $todayExpense = Transaction::query()
            ->whereDate('transaction_date', today())
            ->where('type', Transaction::TYPE_EXPENSE)
            ->sum('amount');

        $yearIncome = $yearTransactions->where('type', Transaction::TYPE_INCOME)->sum('amount');
        $yearExpense = $yearTransactions->where('type', Transaction::TYPE_EXPENSE)->sum('amount');

        $summary = [
            'today_income' => $todayIncome,
            'today_expense' => $todayExpense,
            'today_balance' => $todayIncome - $todayExpense,
            'year_income' => $yearIncome,
            'year_expense' => $yearExpense,
            'year_balance' => $yearIncome - $yearExpense,
        ];

        $monthly = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            'income' => array_values($monthlyIncome),
            'expense' => array_values($monthlyExpense),
        ];

        $yearly = [
            'labels' => $yearlyLabels,
            'income' => $yearlyIncome,
            'expense' => $yearlyExpense,
        ];

        $transactions = Transaction::query()
            ->whereYear('transaction_date', $selectedYear)
            ->orderByDesc('transaction_date')
            ->orderByDesc('id')
            ->take(100)
            ->get();

        return view('admin.reports', compact(
            'selectedYear',
            'yearOptions',
            'summary',
            'monthly',
            'yearly',
            'transactions'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'type' => ['required', 'in:income,expense'],
            'amount' => ['required', 'integer', 'min:1'],
            'category' => ['nullable', 'string', 'max:60'],
            'description' => ['nullable', 'string', 'max:160'],
            'transaction_date' => ['required', 'date'],
        ]);

        Transaction::create($data);

        return redirect()
            ->route('admin.reports', ['year' => Carbon::parse($data['transaction_date'])->year])
            ->with('status', $data['type'] === Transaction::TYPE_INCOME
                ? 'Pemasukan berhasil dicatat.'
                : 'Pengeluaran berhasil dicatat.');
    }

    public function destroy(Request $request, Transaction $transaction)
    {
        $transaction->delete();

        return redirect()
            ->route('admin.reports', ['year' => $request->query('year', now()->year)])
            ->with('status', 'Transaksi berhasil dihapus.');
    }
}
