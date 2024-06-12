<?php

namespace App\Http\Livewire\Tenant;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\ExpenseDetail;
use App\Models\Supplier;
use Asantibanez\LivewireCharts\Models\AreaChartModel;
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

    public $detail, $initialDetail = [
        "category_id" => "",
        "price" => 0,
        "iva" => 0,
    ];


    public $suppliers, $categories, $expenses;

    public function mount()
    {
        $this->data = $this->initialData;
        $this->detail = $this->initialDetail;

        $this->suppliers = Supplier::select('name', 'id')->get();
        $this->categories = ExpenseCategory::select('name', 'id', 'iva')->get();

        $this->expenses = Expense::all();
    }

    public function updateIva()
    {
        $this->detail['iva'] = $this->categories->where('id', $this->detail["category_id"])->first()->iva;
    }


    public function render()
    {
        $this->data['subtotal'] = 0;
        foreach ($this->data['details'] as $detail) {
            $this->data['subtotal'] += $detail['price'];
        }

        $this->data['total'] = 0;
        foreach ($this->data['details'] as $detail) {
            $this->data['total'] += $detail['price'] * (1 + $detail['iva'] / 100);
        }

        $areaChartModel = new AreaChartModel();
        $areaChartModel->setTitle('Expenses');

        $monthlyExpenses = $this->expenses->groupBy(function ($expense) {
            return $expense->created_at->format('m');
        })->map(function ($expenses) {
            return $expenses->sum('total');
        });

        $monthlyExpenses = $monthlyExpenses->sortKeys();

        foreach ($monthlyExpenses as $month => $total) {
            $areaChartModel->addPoint($month, $total);
        }

        return view('livewire.tenant.expenses-component', [
            'areaChartModel' => $areaChartModel,
        ]);
    }


    public function addDetail()
    {
        if ($this->detail['category_id'] == "") {
            $this->emit('toast', 'error', __("expense.category-required"));
            return;
        }

        if ($this->detail['price'] == 0) {
            $this->emit('toast', 'error', __("expense.price-required"));
            return;
        }

        if ($this->detail['iva'] == 0) {
            $this->emit('toast', 'error', __("expense.iva-required"));
            return;
        }

        $index = collect($this->data['details'])->search(function ($detail) {
            return $detail['category_id'] == $this->detail['category_id'];
        });

        if ($index !== false) {
            $this->data['details'][$index] = $this->detail;
        } else {
            $this->data['details'][] = $this->detail;
        }

        $this->detail = $this->initialDetail;

    }

    public function removeDetail($index)
    {
        unset($this->data['details'][$index]);
    }

    public function saveExpense()
    {
        $this->validate([
            'data.supplier_id' => 'required',
            'data.details' => 'required',
        ]);


        $expense = Expense::create([
            'supplier_id' => $this->data['supplier_id'],
            'subtotal' => $this->data['subtotal'],
            'total' => $this->data['total'],
        ]);

        foreach ($this->data['details'] as $detail) {
            ExpenseDetail::create([
                'expense_id' => $expense->id,
                'expense_category_id' => $detail['category_id'],
                'price' => $detail['price'],
                'iva' => $detail['iva'],
            ]);
        }

        $this->expenses->push($expense);

        $this->data = $this->initialData;

        $this->emit('toast', 'success', __("expense.created"));
        $this->modals['add-expense'] = false;
    }

}
