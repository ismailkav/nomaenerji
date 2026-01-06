<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserTable;
use App\Support\OfferLineColumns;
use App\Support\OfferListColumns;
use App\Support\OrderLineColumns;
use App\Support\InvoiceLineColumns;
use App\Support\OrderListColumns;
use App\Support\InvoiceListColumns;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OfferLineColumnController extends Controller
{
    private function definitionsForContext(string $context): array
    {
        return match ($context) {
            'offers_list' => OfferListColumns::definitions(),
            'orders_list' => OrderListColumns::definitions(),
            'invoices_list' => InvoiceListColumns::definitions(),
            'orders' => OrderLineColumns::definitions(),
            'invoices' => InvoiceLineColumns::definitions(),
            default => OfferLineColumns::definitions(),
        };
    }

    private function authUserCode(): string
    {
        return (string) auth()->id();
    }

    private function userCodeFromUser(User $user): string
    {
        return (string) $user->id;
    }

    private function ensureSeeded(string $userCode, string $context): void
    {
        $defs = $this->definitionsForContext($context);
        $existing = UserTable::query()
            ->where('kullanicikod', $userCode)
            ->where('sayfa', $context)
            ->pluck('sutun')
            ->all();

        $existingSet = array_fill_keys($existing, true);
        $toInsert = [];
        $order = 1;

        foreach ($defs as $def) {
            $key = $def['key'];
            if (isset($existingSet[$key])) {
                $order++;
                continue;
            }
            $toInsert[] = [
                'kullanicikod' => $userCode,
                'sayfa' => $context,
                'sutun' => $key,
                'durum' => array_key_exists('default', $def) ? (bool) $def['default'] : true,
                'sirano' => $order,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $order++;
        }

        if ($toInsert) {
            UserTable::query()->insert($toInsert);
        }
    }

    private function indexForContext(Request $request, string $context)
    {
        $userCode = $this->authUserCode();
        $this->ensureSeeded($userCode, $context);

        $rows = UserTable::query()
            ->where('kullanicikod', $userCode)
            ->where('sayfa', $context)
            ->orderBy('sirano')
            ->get()
            ->keyBy('sutun');

        $cols = [];
        foreach ($this->definitionsForContext($context) as $def) {
            $row = $rows->get($def['key']);
            $cols[] = [
                'key' => $def['key'],
                'label' => $def['label'],
                'durum' => $row ? (bool) $row->durum : (array_key_exists('default', $def) ? (bool) $def['default'] : true),
                'sirano' => $row ? (int) $row->sirano : 0,
            ];
        }

        usort($cols, function ($a, $b) {
            return ($a['sirano'] <=> $b['sirano']) ?: strcmp($a['key'], $b['key']);
        });

        return response()->json([
            'ok' => true,
            'columns' => $cols,
        ]);
    }

    private function storeForContext(Request $request, string $context)
    {
        $validated = $request->validate([
            'columns' => ['required', 'array'],
            'columns.*.key' => ['required', 'string'],
            'columns.*.durum' => ['nullable'],
        ]);

        $userCode = $this->authUserCode();
        $this->ensureSeeded($userCode, $context);

        $knownKeys = array_map(fn ($d) => $d['key'], $this->definitionsForContext($context));
        $knownSet = array_fill_keys($knownKeys, true);

        $payload = [];
        foreach (($validated['columns'] ?? []) as $col) {
            $key = (string) ($col['key'] ?? '');
            if ($key === '' || !isset($knownSet[$key])) continue;
            $payload[] = [
                'key' => $key,
                'durum' => (bool) ($col['durum'] ?? false),
            ];
        }

        DB::transaction(function () use ($userCode, $context, $payload, $knownKeys) {
            $order = 1;
            $seen = [];

            foreach ($payload as $col) {
                $seen[$col['key']] = true;
                UserTable::query()->updateOrCreate(
                    ['kullanicikod' => $userCode, 'sayfa' => $context, 'sutun' => $col['key']],
                    ['durum' => $col['durum'], 'sirano' => $order]
                );
                $order++;
            }

            foreach ($knownKeys as $key) {
                if (isset($seen[$key])) continue;
                UserTable::query()->updateOrCreate(
                    ['kullanicikod' => $userCode, 'sayfa' => $context, 'sutun' => $key],
                    ['durum' => true, 'sirano' => $order]
                );
                $order++;
            }
        });

        return $this->indexForContext($request, $context);
    }

    public function offerIndex(Request $request)
    {
        return $this->indexForContext($request, 'offers');
    }

    public function offerStore(Request $request)
    {
        return $this->storeForContext($request, 'offers');
    }

    public function offerListIndex(Request $request)
    {
        return $this->indexForContext($request, 'offers_list');
    }

    public function offerListStore(Request $request)
    {
        return $this->storeForContext($request, 'offers_list');
    }

    public function orderIndex(Request $request)
    {
        return $this->indexForContext($request, 'orders');
    }

    public function orderStore(Request $request)
    {
        return $this->storeForContext($request, 'orders');
    }

    public function orderListIndex(Request $request)
    {
        return $this->indexForContext($request, 'orders_list');
    }

    public function orderListStore(Request $request)
    {
        return $this->storeForContext($request, 'orders_list');
    }

    public function invoiceIndex(Request $request)
    {
        return $this->indexForContext($request, 'invoices');
    }

    public function invoiceStore(Request $request)
    {
        return $this->storeForContext($request, 'invoices');
    }

    public function invoiceListIndex(Request $request)
    {
        return $this->indexForContext($request, 'invoices_list');
    }

    public function invoiceListStore(Request $request)
    {
        return $this->storeForContext($request, 'invoices_list');
    }

    public function seedForUser(User $user)
    {
        $userCode = $this->userCodeFromUser($user);
        $this->ensureSeeded($userCode, 'offers');
        $this->ensureSeeded($userCode, 'offers_list');
        $this->ensureSeeded($userCode, 'orders');
        $this->ensureSeeded($userCode, 'orders_list');
        $this->ensureSeeded($userCode, 'invoices');
        $this->ensureSeeded($userCode, 'invoices_list');

        return response()->json(['ok' => true]);
    }
}
