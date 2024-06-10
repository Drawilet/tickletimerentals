<?php

namespace App\Http\Livewire\Tenant;

use App\Models\Expense;
use App\Models\ExpenseDetail;
use App\Models\Supplier;
use App\Models\SupplierProduct;
use Livewire\Component;

class ExpensesComponent extends Component
{
    public $modals = [
        'add-expense' => false,
    ];

    public $data, $initialData = [
        "details" => [],
        "subtotal" => 0,
        "total" => 0,
    ];

    public $filter, $initialFilter = [
        "supplier_id" => "",
        "product_id" => "",
    ];

    public $suppliers, $products, $expenses;

    public function mount()
    {
        $this->data = $this->initialData;
        $this->filter = $this->initialFilter;

        $this->suppliers = Supplier::select('name', 'id')->get();

        $this->expenses = Expense::all();
    }


    public function render()
    {
        $this->data['subtotal'] = array_sum(array_map(function ($detail) {
            return $detail['quantity'] * $detail['price'];
        }, $this->data['details']));

        $this->data['total'] = array_sum(array_map(function ($detail) {
            return $detail['quantity'] * $detail['price'] * (1 + $detail['iva'] / 100);
        }, $this->data['details']));


        return view('livewire.tenant.expenses-component');
    }

    public function filterProducts()
    {
        $this->products = SupplierProduct::where('supplier_id', $this->filter['supplier_id'])->get();
    }

    public function addDetail()
    {
        if ($this->filter['product_id'] == "")
            return;

        if (count($this->data['details']) > 0) {
            foreach ($this->data['details'] as $key => $detail) {
                if ($detail['product_id'] == $this->filter['product_id']) {
                    $this->data['details'][$key]['quantity'] += 1;
                    return;
                }
            }
        }

        $product = $this->products->where('id', $this->filter['product_id'])->first();

        $this->data['details'][] = [
            'product_id' => $product->id,
            'sku' => $product->sku,
            'concept' => $product->name,
            'unit' => $product->unit,
            'quantity' => 1,
            'price' => $product->price,
            'iva' => $product->iva,
            'supplier_id' => $product->supplier_id,
        ];

        usort($this->data['details'], function ($a, $b) {
            return $a['supplier_id'] <=> $b['supplier_id'];
        });
    }

    public function removeDetail($index)
    {
        unset($this->data['details'][$index]);
    }

    public function saveExpense()
    {
        $this->validate([
            'data.details' => 'required',
        ]);


        $expense = Expense::create([
            'subtotal' => $this->data['subtotal'],
            'total' => $this->data['total'],
        ]);

        foreach ($this->data['details'] as $detail) {
            ExpenseDetail::create([
                'expense_id' => $expense->id,
                'sku' => $detail['sku'],
                'concept' => $detail['concept'],
                'unit' => $detail['unit'],
                'quantity' => $detail['quantity'],
                'price' => $detail['price'],
                'iva' => $detail['iva'],
            ]);
        }

        $this->expenses->push($expense);

        $this->data = $this->initialData;
        $this->filter = $this->initialFilter;

        $this->emit('toast', 'success', __("expense.created"));
        $this->modals['add-expense'] = false;
    }

}
