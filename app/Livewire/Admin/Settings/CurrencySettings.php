<?php

namespace App\Livewire\Admin\Settings;

use App\Livewire\Admin\Components\BaseComponent;
use App\Models\Currency;

class CurrencySettings extends BaseComponent
{

    public $currency_code;
    public $currency_symbol;
    public $currency;

    protected $rules = [
        'currency_code' => 'required|string|max:10',
        'currency_symbol' => 'required|string|max:5',
    ];

    public function mount()
    {

        $this->currency = Currency::first();

        if ($this->currency) {
            $this->currency_code = $this->currency->code;
            $this->currency_symbol = $this->currency->symbol;
        }
    }

    public function render()
    {
        return view('livewire.admin.settings.currency-settings');
    }

    public function save()
    {
        $this->validate();

        if ($this->currency) {

            $this->currency->update([
                'code' => $this->currency_code,
                'symbol' => $this->currency_symbol,
            ]);
        } else {
            // Create new currency
            $this->currency = Currency::create([
                'code' => $this->currency_code,
                'symbol' => $this->currency_symbol,
            ]);
        }

        $this->toast('Currency updated successfully!', 'success');
    }
}
