<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLoanRequest;
use App\Http\Requests\UpdateLoanRequest;
use App\Models\Loan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $loans = Loan::with(['book', 'user'])
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json($loans);
    }

    public function store(StoreLoanRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;
        $data['status'] = $data['status'] ?? 'Dipinjam';

        $loan = Loan::create($data);

        return response()->json($loan->load(['book', 'user']), 201);
    }

    public function show(Request $request, string $id): JsonResponse
    {
        $loan = $this->findLoan($id);
        $this->authorizeOwner($request, $loan);

        return response()->json($loan->load(['book', 'user']));
    }

    public function update(UpdateLoanRequest $request, string $id): JsonResponse
    {
        $loan = $this->findLoan($id);
        $this->authorizeOwner($request, $loan);

        $loan->update($request->validated());

        return response()->json($loan->load(['book', 'user']));
    }

    public function destroy(Request $request, string $id): JsonResponse
    {
        $loan = $this->findLoan($id);
        $this->authorizeOwner($request, $loan);

        $loan->delete();

        return response()->json([
            'message' => 'Peminjaman berhasil dihapus',
        ]);
    }

    private function findLoan(string $id): Loan
    {
        $loan = Loan::find($id);

        if (! $loan) {
            abort(404, 'Peminjaman tidak ditemukan');
        }

        return $loan;
    }

    private function authorizeOwner(Request $request, Loan $loan): void
    {
        if ($loan->user_id !== $request->user()->id) {
            abort(403, 'Anda tidak berhak mengakses peminjaman milik pengguna lain');
        }
    }
}
