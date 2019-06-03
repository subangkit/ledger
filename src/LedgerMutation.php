<?php

namespace Blackit\Ledger;

use Money\Money;
use Money\Currency;
use Illuminate\Database\Eloquent\Model;

class LedgerMutation extends Model
{
    const DEBIT = 'D';
    const CREDIT = 'C';

    protected $fillable = [
        'debcred', 'amount', 'ledger_account_id', 'currency', 'description','date_of_recognition'
    ];

    protected $defaults = array(
        'date_of_recognition' => ''
    );

    public function __construct(array $attributes = array())
    {
        $this->defaults['date_of_recognition'] = date('Y-m-d H:i:s');
        $this->setRawAttributes($this->defaults, true);
        parent::__construct($attributes);
    }

    public function account()
    {
        return $this->belongsTo(LedgerAccount::class, 'ledger_account_id');
    }

    public function transaction()
    {
        return $this->belongsTo(LedgerTransaction::class, 'ledger_transaction_id');
    }

    public function getAmountAttribute($value)
    {
        return new Money($value, new Currency($this->currency));
    }

    public function setAmountAttribute($value)
    {
        // if we get passed in an instance of a Money object, grab the
        // cent value as well as the currency.
        if ($value instanceof Money) {
            $currency = $value->getCurrency()->getCode();
            $value = $value->getAmount();

            $this->attributes['currency'] = $currency;
        }

        $this->attributes['amount'] = $value;
    }
}
